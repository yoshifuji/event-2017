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

ini_set('log_errors', 'On');
ini_set('error_log', '../error.log');

try {
    $dbh = new PDO('mysql:host='.$HOST.';dbname='.$DBNAME.';charset=utf8', $USERNAME, $PWD,
        array(PDO::ATTR_EMULATE_PREPARES => false));
} catch (PDOException $e) {
    exit('データベース接続失敗。'.$e->getMessage());
}

/*
 * DB更新
 */
try{
    //パラメータ取得
    $arrChecks = json_decode($_POST['checks']);

//    ob_start();
//    print_r($arrChecks);
//    $out = ob_get_contents();
//    ob_end_clean();
//    file_put_contents("../hoge.txt", $out, FILE_APPEND);

    //DB更新
    $sql = "UPDATE instagenic SET is_enable = :is_enable WHERE id = :id";
    $sth = $dbh->prepare($sql);

    foreach ($arrChecks as $val) {
        $sth->execute(array(
                ':is_enable' => 0,
                ':id' => $val)
        );
    }
} catch (Exception $e) {
    error_log($e->getMessage());
}

/*
 * データ再出力
 */
try{
    $sth = $dbh->prepare("SELECT * FROM instagenic WHERE is_enable = 1 LIMIT 10");
    $sth->execute();

    $return_str = "";
    $cnt = 0;
    foreach ($sth as $row) {
        $return_str .= ($cnt % 2 == 0) ? '<tr role="row" class="even">' : '<tr role="row" class="odd">';
        $return_str .= '<td><div><label><input id='.htmlspecialchars($row['id']).' type="checkbox"></label></div></td>';
        $return_str .= '<td>'.htmlspecialchars($row['id']).'</td>';
        $return_str .= '<td><img class="img-thumbnail" src="img/'.($cnt+1).'.jpg" width="100" height="100"></td>';
        $return_str .= '<td>'.htmlspecialchars($row['image_name']).'</td>';
        $return_str .= '<td>'.htmlspecialchars($row['user_id']).'</td>';
        $return_str .= '<td>'.htmlspecialchars($row['user_name']).'</td>';
        $return_str .= '<td>'.htmlspecialchars($row['score']).'</td>';
        $return_str .= '<td>'.htmlspecialchars($row['category']).'</td>';
        $return_str .= '<td>'.htmlspecialchars($row['sub_category']).'</td>';
        $return_str .= '<td>'.htmlspecialchars($row['is_enable']).'</td>';
        $return_str .= '<td>'.htmlspecialchars($row['created_at']).'</td>';
        $return_str .= '<td>'.htmlspecialchars($row['updated_at']).'</td>';
        $return_str .= '</tr>';
        $cnt++;
    }
    echo $return_str;

} catch (Exception $e) {
    error_log($e->getMessage());
}

?>