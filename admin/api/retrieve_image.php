<?php
/**
 * Created by PhpStorm.
 * User: YoshitakaFujisawa
 * Date: 17/11/25
 * Time: 午前1:23
 */

$ini_array  = parse_ini_file("../config.ini");
$HOST       = $ini_array['HOST'];
$DBNAME     = $ini_array['DBNAME'];
$USERNAME   = $ini_array['USERNAME'];
$PWD        = $ini_array['PWD'];

//return obj
$returnData = array();

try {
    $dbh = new PDO('mysql:host='.$HOST.';dbname='.$DBNAME.';charset=utf8', $USERNAME, $PWD,
        array(PDO::ATTR_EMULATE_PREPARES => false));
} catch (PDOException $e) {
    exit('データベース接続失敗。'.$e->getMessage());
}

try{
    $arrayCategory = array(
        'insta'=>'food', 'insta'=>'human', 'insta'=>'facility',
        'face'=>'smgr', 'face'=>'md'
    );

    foreach ($arrayCategory as $key => $value){
        $returnData += appendImageArray($dbh, $key, $value, $returnData);
    }

    //json出力
    header('Content-type: application/json');
    echo json_encode($returnData);

} catch (Exception $e) {
    error_log($e->getMessage());
}

function appendImageArray($dbh, $category, $subCategory){
    try{
        $tmpArray = array();
        $sth = $dbh->prepare("SELECT MAX(i1.score) as score, i1.category, i1.sub_category, i1.user_id, i1.user_name,i1.image_name FROM (SELECT * FROM instagenic where is_enable = true and category=:category and sub_category=:sub_category) as i1 GROUP BY i1.user_id, i1.category, i1.sub_category ORDER BY MAX(i1.score) DESC LIMIT 3");
        $sth->bindParam(':category', $category, PDO::PARAM_STR);
        $sth->bindParam(':sub_category', $subCategory, PDO::PARAM_STR);
        $sth->execute();

        while($row = $sth->fetch(PDO::FETCH_ASSOC)){
            $tmpArray += array(
                'score'=>$row['score'],
                'category'=>$row['category'],
                'sub_category'=>$row['sub_category'],
                'user_id'=>$row['user_id'],
                'user_name'=>$row['user_name'],
                'image_name'=>$row['image_name']
            );
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
    }

    return $tmpArray;
}

?>