<?php
/**
 * Created by PhpStorm.
 * User: YoshitakaFujisawa
 * Date: 17/11/12
 * Time: 午後7:04
 */
$ini_array  = parse_ini_file("config.ini");
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

$sql = "SELECT inst.* FROM instagenic inst";
$sql .= " INNER JOIN (SELECT user_id, MAX(score) AS maxscore FROM instagenic GROUP BY user_id) groupscore";
$sql .= " ON inst.user_id = groupscore.user_id AND inst.score = groupscore.maxscore";
$sql .= " WHERE 1 AND is_enable = 1 ORDER BY score DESC LIMIT 30";

$sth = $dbh->prepare($sql);
$sth->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Fuyufes Admin</title>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>

    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css"/>

    <!-- customize -->
    <link rel="stylesheet" href="css/ranking.css"/>
    <script>
        $(document).ready(function() {
            $('#ranking').DataTable({
                paging: false,
                searching: false
            });
        })
    </script>
    <!-- modal window -->
    <script src="js/modal_setting.js"></script>
    <script src="js/table_data.js"></script>
<!--    <script src="js/retrieve_img.js"></script>-->
</head>
<body>
<div id="ttl">
    <h1> Fuyu Fes 2017 </h1>
</div>

<!-- 検索条件指定 -->
<nav class="navbar navbar-default navbar-light bg-faded rounded navbar-toggleable-md">
    <div class="container-fluid">
        <h4>検索条件</h4>
        <div class="collapse navbar-collapse" id="containerSearch">
            <form id="searchForm" class="form-inline">
                <div class="search-elem">
                    <span id="chkLineid">
                        <label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
                            <input type="checkbox" class="custom-control-input" checked>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">LineIDを重複しない(ユーザーの最高点の画像のみ表示)</span>
                        </label>
                    </span>
                    <span id="chkInactive">
                        <label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
                            <input type="checkbox" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">非アクティブな(無効とした)レコードも表示する</span>
                        </label>
                    </span>
                </div>
                <div class="search-elem">
                    <span id="txtDateFrom">
                        <label for="validationCustom04">投稿日時(開始)</label>
                        <input type="text" class="form-control" id="txt-date-from" placeholder="yyyy/MM/dd hh:mm:ss">
                    </span>
                    　〜　
                    <span id="txtDateTo">
                        <label for="validationCustom04">投稿日時(終了)</label>
                        <input type="text" class="form-control" id="txt-date-to" placeholder="yyyy/MM/dd hh:mm:ss">
                    </span>
                    <caption>　※投稿日時は yyyy/MM/dd の表記でも検索できます</caption>
                </div>
                <div class="search-elem">
                    <span id="category">
                        <label class="mr-sm-2" for="inlineFormCustomSelectPref">カテゴリ</label>
                        <select class="custom-select mb-2 mr-sm-2 mb-sm-0" id="inlineFormCustomSelectPref">
                            <option value="1">all</option>
                            <option value="2">insta</option>
                            <option value="3">face</option>
                        </select>
                    </span>
                    <span id="subcategory">
                        <label class="mr-sm-2" for="inlineFormCustomSelectPref">サブカテゴリ</label>
                        <select class="custom-select mb-2 mr-sm-2 mb-sm-0" id="inlineFormCustomSelectPref">
                            <option value="1">all</option>
                            <option value="2">food</option>
                            <option value="3">human</option>
                            <option value="3">facility</option>
                            <option value="3">smgr</option>
                            <option value="3">md</option>
                        </select>
                    </span>
                </div>
                <div class="search-elem">
                    <span id="txtRecordNumber">
                        <input type="text" class="form-control" id="txt-record-number" placeholder="30">
                        <label for="validationCustom04"> 件 </label>
                    </span>
                </div>
                <div class="search-elem">
                    <button type="button" id="btnSearch" class="btn btn-primary">
                        検索
                    </button>
                    <!-- モーダル表示 -->
                    <button id="btn-setting" class="btn btn-default" data-toggle="modal" data-target="#modal-example">
                        設定画面
                    </button>
		    <!-- 当選チェック  -->
                    <button id="btnElection" class="btn btn-default">
                        当選
                    </button>
                </div>
            </form>
        </div>
    </div>
</nav>

<div class="page-header">
    <h3>集計ランキング</h3>
</div>

<!--
0 checkbox to enable
1 id (varchar)
2 user_name (varchar)
3 image_name (varchar)
4 score (double)
5 category (varchar)
6 sub_category (varchar)
7 is_enable (int)
8 created_at (DATETIME)
9 updated_at (DATETIME)
10 Image
-->
<div class="row">
    <div class="col-md-10">
        <table id="ranking" class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>チェック</th>
                <th>#</th>
                <th>画像</th>
                <th>ユーザーID</th>
                <th>ユーザー名</th>
                <th>スコア</th>
                <th>カテゴリ</th>
                <th>サブカテゴリ</th>
                <th>isEnable</th>
                <th>登録日時</th>
                <th>更新日時</th>
            </tr>
            </thead>
            <tbody>
                <?php
                //初回呼び出し分
                $imgPrefix = preg_match('/prd/', $_SERVER['SERVER_NAME'])
                    ? "https://s3-ap-northeast-1.amazonaws.com/prd-fuyufes2017/img/std/" : "https://s3-ap-northeast-1.amazonaws.com/fuyufes2017/img/std/";

                $cnt = 0;
                foreach ($sth as $row) {
                    echo '<tr>';
                    echo '<td><div><label><input id='.htmlspecialchars($row['id']).' type="checkbox"></label></div></td>';
                    echo '<td>'.htmlspecialchars($row['id']).'</td>';
		    echo '<td><a href="'.$imgPrefix.$row['id'].'.jpg" target="_blank">';
		    echo '<img class="img-thumbnail" src="'.$imgPrefix.$row['id'].'-thumbnail.jpeg">';
		    echo '</a></td>';
                    echo '<td>'.htmlspecialchars($row['user_id']).'</td>';
                    echo '<td>'.htmlspecialchars($row['user_name']).'</td>';
                    echo '<td>'.htmlspecialchars($row['score']).'</td>';
                    echo '<td>'.htmlspecialchars($row['category']).'</td>';
                    echo '<td>'.htmlspecialchars($row['sub_category']).'</td>';
                    echo '<td>'.htmlspecialchars($row['is_enable']).'</td>';
                    echo '<td>'.htmlspecialchars($row['created_at']).'</td>';
                    echo '<td>'.htmlspecialchars($row['updated_at']).'</td>';
                    echo '</tr>';
                    $cnt++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="btn_action">
    <p>
<!--        <button type="button" class="btn btn-default" id="btnReset">Reset</button>-->
        <button type="button" class="btn btn-danger" id="btnDisable">Disable</button>
    </p>
</div>

<!-- replace test
<div>
    <span class="name00"></span>
    <span class="score00"></span>
    <section class="img00" data-background="https://goo.gl/k4tMpj">
    </section>

    <span class="name01"></span>
    <span class="score01"></span>
    <section class="img01" data-background="https://goo.gl/k4tMpj">
    </section>

    <span class="name02"></span>
    <span class="score02"></span>
    <section class="img02" data-background="https://goo.gl/k4tMpj">
    </section>
</div>
-->

<!-- モーダル画面 -->
<div class="modal" id="modal-example" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modal-label">ダイアログ</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="modal-date-from" class="col-form-label">投稿日時(開始):</label>
                        <input type="text" class="form-control" id="txt-modal-date-from" placeholder="yyyy/MM/dd hh:mm:ss">
                    </div>
                    <div class="form-group">
                        <label for="modal-date-to" class="col-form-label">投稿日時(終了):</label>
                        <input type="text" class="form-control" id="txt-modal-date-to" placeholder="yyyy/MM/dd hh:mm:ss">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                <button type="button" class="btn btn-primary" id="btnSave">保存</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
