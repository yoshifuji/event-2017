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
    $dbh = new PDO('mysql:host='.$HOST.';port=3306;dbname='.$DBNAME.';charset=utf8', $USERNAME, $PWD,
        array(PDO::ATTR_EMULATE_PREPARES => false));
} catch (PDOException $e) {
    exit('データベース接続失敗。'.$e->getMessage());
}

try{
    $isIncludedLineidDuplicated = isset($_POST['chkIncludedLineid']) ? $_POST['chkIncludedLineid'] : null;
    $isIncludedInactive         = isset($_POST['chkIncludedInactive']) ? $_POST['chkIncludedInactive'] : null;
    $dateFrom                   = $_POST['txtDateFrom']; //"2017/11/20";
    $dateTo                     = $_POST['txtDateTo']; //"2017/11/25";
    $category                   = $_POST['slctCategory']; //"test";
    $subcategory                = $_POST['slctSubCategory']; //"test";
    $recordNumber               = !empty($_POST['txtRecordNumber']) ? $_POST['txtRecordNumber'] : 30; //"30";

    //sql生成
    $sql = "SELECT * FROM instagenic WHERE 1";
    if ($isIncludedLineidDuplicated){
        $sql = "SELECT inst.* FROM instagenic inst INNER JOIN";
        $sql .= " (SELECT user_id, MAX(score) AS maxscore FROM instagenic ";
        if (($category != "all") && ($subcategory != "all"))       $sql .= " where category = '" .$category. "' and sub_category = '" .$subcategory. "'";
        if (($category === "all") && ($subcategory != "all"))        $sql .= " where sub_category = '" .$subcategory. "'";
        if (($category != "all") && ($subcategory === "all"))        $sql .= " where category = '" .$category. "'";
   $sql .= " GROUP BY user_id) groupscore ";
        $sql .= " ON inst.user_id = groupscore.user_id AND inst.score = groupscore.maxscore";
        $sql .= " WHERE 1";
    }
    if (!$isIncludedInactive)                   $sql .= " AND is_enable = 1";
    if ($dateFrom)                              $sql .= " AND created_at >= '".$dateFrom."'";
    if ($dateTo)                                $sql .= " AND created_at <= '".$dateTo."'";
    if ($category && $category != "all")        $sql .= " AND category = '".$category."'";
    if ($subcategory && $subcategory != "all")  $sql .= " AND sub_category = '".$subcategory."'";
    $sql .= " ORDER BY score DESC LIMIT ".$recordNumber;

    $sth = $dbh->prepare($sql);
    $sth->execute();

    /*
     * データ再出力
     */
    $return_str = "";
    $imgPrefix = preg_match('/prd/', $_SERVER['SERVER_NAME'])
        ? "https://s3-ap-northeast-1.amazonaws.com/prd-fuyufes2017/img/std/" : "https://s3-ap-northeast-1.amazonaws.com/fuyufes2017/img/std/";

    $cnt = 0;
    foreach ($sth as $row) {
        $return_str .= ($cnt % 2 == 0) ? '<tr role="row" class="even">' : '<tr role="row" class="odd">';
        $return_str .= '<td><div><label><input id='.htmlspecialchars($row['id']).' type="checkbox"></label></div></td>';
        $return_str .= '<td>'.htmlspecialchars($row['id']).'</td>';
	$return_str .= '<td><a href="'.$imgPrefix.$row['id'].'.jpg" target="_blank">';
	$return_str .= '<img class="img-thumbnail" src="'.$imgPrefix.$row['id'].'-thumbnail.jpeg">';
	$return_str .= '</a></td>';
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
