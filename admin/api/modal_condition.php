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

try {
    $dbh = new PDO('mysql:host='.$HOST.';dbname='.$DBNAME.';charset=utf8', $USERNAME, $PWD,
        array(PDO::ATTR_EMULATE_PREPARES => false));
} catch (PDOException $e) {
    exit('データベース接続失敗。'.$e->getMessage());
}

try{
    $type = $_POST['type'];

    if ($type == 'load') {
        $sql = "SELECT * FROM setting WHERE id = 0";
        $sth = $dbh->prepare($sql);
        $sth->execute();

        $returnData = array();
        while($row = $sth->fetch(PDO::FETCH_ASSOC)){
            $returnData[]=array(
                'created_from'=>$row['created_from'],
                'created_to'=>$row['created_to']
            );
        }
        //json出力
        header('Content-type: application/json');
        echo json_encode($returnData);

    } elseif ($type = 'update') {
        $created_from   = $_POST['created_from'];
        $created_to     = $_POST['created_to'];

        //DB更新
        $sql = "UPDATE setting SET created_from = :created_from, created_to = :created_to WHERE id = 0";
        $sth = $dbh->prepare($sql);
        $sth->execute(array(
                ':created_from' => $created_from,
                ':created_to'   => $created_to)
        );

        return true;
    }

} catch (Exception $e) {
    error_log($e->getMessage());
}

?>