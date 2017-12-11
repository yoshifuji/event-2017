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
    $dbh = new PDO('mysql:host='.$HOST.';port=3306;dbname='.$DBNAME.';charset=utf8', $USERNAME, $PWD,
        array(PDO::ATTR_EMULATE_PREPARES => false));
} catch (PDOException $e) {
    exit('データベース接続失敗。'.$e->getMessage());
}
try{
    $callback = "callbackFunc";
    if(isset($_GET['callback'])){
        $callback=$_GET['callback'];
    }
    //$arrayCategory = array('insta'=>array('food','human','facility'), 'face'=>array('smgr','md'));
    $cat = $_GET['category'];
    $sub = $_GET['subcategory'];
    $returnData = getImageArray($dbh, $cat, $sub);
//    //logging
//    ob_start();
//    print_r($returnData);
//    $out = ob_get_contents();
//    ob_end_clean();
//    file_put_contents("../php.log", $out, FILE_APPEND);
    //json出力
    //https://qiita.com/stkdev/items/f3e6cae58ab73faee502
    $json = json_encode($returnData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
header("Content-type: application/x-javascript");
print <<<END
$callback($json);
END;
} catch (Exception $e) {
    error_log($e->getMessage());
}
function getImageArray($dbh, $category, $subCategory){
    try{
        $tmpArray = array();
        $sql = "SELECT MAX(i1.score) as score, i1.category, i1.sub_category, i1.user_id, i1.user_name,i1.image_name FROM (SELECT * FROM instagenic where is_enable = true and category=:category and sub_category=:sub_category) as i1 GROUP BY i1.user_id, i1.category, i1.sub_category ORDER BY MAX(i1.score) DESC LIMIT 3";
        $sth = $dbh->prepare($sql);
        $sth->bindParam(':category', $category, PDO::PARAM_STR);
        $sth->bindParam(':sub_category', $subCategory, PDO::PARAM_STR);
        $sth->execute();
        while($row = $sth->fetch(PDO::FETCH_ASSOC)){
            array_push($tmpArray, array(
                'score'=>$row['score'],
                'category'=>$row['category'],
                'sub_category'=>$row['sub_category'],
                'user_id'=>$row['user_id'],
                'user_name'=>$row['user_name'],
                'image_name'=>$row['image_name']
            ));
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
    return $tmpArray;
}
?>
