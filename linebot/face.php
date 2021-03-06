<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/aws-autoloader.php';

use Aws\Credentials\CredentialProvider;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;

// 0xを抜いた数字の部分
$codeHappy = '100001';
$codeSoHappy = '100002';
$codeShock = '100009';
$codeSad = '100016';
$codeTeeth = '10000B';
$codeKira = '10002D';
$codeGood = '100033';
$codeCake = '100056';
$codeGomen = '100017';
$codeWish = '10000A';
$codeFight = '10002A';
$codeGuift = '100075';
// 16進エンコードされたバイナリ文字列をデコード
$binHappy = hex2bin(str_repeat('0', 8 - strlen($codeHappy)) . $codeHappy);
$binSoHappy = hex2bin(str_repeat('0', 8 - strlen($codeSoHappy)) . $codeSoHappy);
$binShock = hex2bin(str_repeat('0', 8 - strlen($codeShock)) . $codeShock);
$binSad = hex2bin(str_repeat('0', 8 - strlen($codeSad)) . $codeSad);
$binTeeth = hex2bin(str_repeat('0', 8 - strlen($codeTeeth)) . $codeTeeth);
$binKira = hex2bin(str_repeat('0', 8 - strlen($codeKira)) . $codeKira);
$binGood = hex2bin(str_repeat('0', 8 - strlen($codeGood)) . $codeGood);
$binCake = hex2bin(str_repeat('0', 8 - strlen($codeCake)) . $codeCake);
$binGomen = hex2bin(str_repeat('0', 8 - strlen($codeGomen)) . $codeGomen);
$binWish = hex2bin(str_repeat('0', 8 - strlen($codeWish)) . $codeWish);
$binFight = hex2bin(str_repeat('0', 8 - strlen($codeFight)) . $codeFight);
$binGuift = hex2bin(str_repeat('0', 8 - strlen($codeGuift)) . $codeGuift);
// UTF8へエンコード
$iconHappy =  mb_convert_encoding($binHappy, 'UTF-8', 'UTF-32BE');
$iconSoHappy =  mb_convert_encoding($binSoHappy, 'UTF-8', 'UTF-32BE');
$iconShock =  mb_convert_encoding($binShock, 'UTF-8', 'UTF-32BE');
$iconSad =  mb_convert_encoding($binSad, 'UTF-8', 'UTF-32BE');
$iconTeeth =  mb_convert_encoding($binTeeth, 'UTF-8', 'UTF-32BE');
$iconKira =  mb_convert_encoding($binKira, 'UTF-8', 'UTF-32BE');
$iconGood =  mb_convert_encoding($binGood, 'UTF-8', 'UTF-32BE');
$iconCake =  mb_convert_encoding($binCake, 'UTF-8', 'UTF-32BE');
$iconGomen =  mb_convert_encoding($binGomen, 'UTF-8', 'UTF-32BE');
$iconWish =  mb_convert_encoding($binWish, 'UTF-8', 'UTF-32BE');
$iconFight =  mb_convert_encoding($binFight, 'UTF-8', 'UTF-32BE');
$iconGuift =  mb_convert_encoding($binGuift, 'UTF-8', 'UTF-32BE');

// //配列などに格納して使う
// $text[] =  array("type" => "text","text" => $emoticon);



$CAT='CqQQmXaPBT5iFiLNnI6vP3/V92DBgWNGFU/TZUo6XNrKcIRS9mSPiMXd5JUH5d/BrgRcB9JHOtJCDxDlmH3O5UCyMMiqHe8SITQCZNxn7nz1Tvk7BqIQWJFlbmJHNFMqEmcNm0WfYVNVmSZcVIFJ+gdB04t89/1O/w1cDnyilFU=';
$CS='677c5e2eb0b27147c58ea0dc24462ca9';
putenv("CHANNEL_ACCESS_TOKEN=$CAT");
putenv("CHANNEL_SECRET=$CS");

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);

$signature = $_SERVER["HTTP_" . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
try {
  $events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);
  $json_string = file_get_contents('php://input');
} catch(\LINE\LINEBot\Exception\InvalidSignatureException $e) {
  error_log("parseEventRequest failed. InvalidSignatureException => ".var_export($e, true));
} catch(\LINE\LINEBot\Exception\UnknownEventTypeException $e) {
  error_log("parseEventRequest failed. UnknownEventTypeException => ".var_export($e, true));
} catch(\LINE\LINEBot\Exception\UnknownMessageTypeException $e) {
  error_log("parseEventRequest failed. UnknownMessageTypeException => ".var_export($e, true));
} catch(\LINE\LINEBot\Exception\InvalidEventRequestException $e) {
  error_log("parseEventRequest failed. InvalidEventRequestException => ".var_export($e, true));
}
try {
	$json_object = json_decode($json_string);
  $message_text =   $json_object->{"events"}[0]->{"message"}->{"text"};
  foreach ($events as $event) {
	  if (!($event instanceof \LINE\LINEBot\Event\MessageEvent)) {
	    error_log('Non message event has come');
	    continue;
	  }
	  $profile = $bot->getProfile($event->getUserId())->getJSONDecodedBody();
    $userId = $profile['userId'];
	  if ($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage) {
                  if (preg_match('/help|ヘルプ|へるぷ|やりかた|やり方|こまんど|コマンド|command|comand/i', $message_text)) {
                      // $message = "インスタ映えだと思う画像をここに送ると、AI(IBM Watson)がインスタ映え度を採点してくれるよ！\n冬フェス会場で撮影した写真をここにアップロードしてね！";
                      // $bot->replyText($event->getReplyToken(), $message);

                      $message_1 = "ルール：あなたの写真をここに送ると、AI(IBM Watson)があなたの顔から将来出世しそうかどうか判定してくれるよ" . $iconTeeth;
                      $message_2 = "でもムービーは対象外なんだ、ごめんなさい" . $iconGomen;
                      $message_3 = "コマンド１：あなたの「順位」を聞いてくれれば、全体の中でどのくらいにいるか教えてあげるよ！" . $iconSoHappy;
                      $message_4 = "コマンド２：入賞「商品」を聞いてくれれば、少しだけ教えてあげるよ" . $iconGuift;
                      $message_5 = "その他にも色んなコマンドがあるから試してみてね" . $iconShock;
                      replyMultiMessage($bot, $event->getReplyToken(),
                        new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message_1),
                        new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message_2),
                        new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message_3),
                        new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message_4),
                        new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message_5)
                        );
                  } else if (preg_match('/rank|ランク|らんく|順位/i', $message_text)) {
                      $latestScore = searchScore($userId);
                      $latestRank = searchmyRank($userId);
                      $headCount = searchHeadCount();
                      $rating = 1.0 - ($latestRank / $headCount);
  //デバッグ
  // file_put_contents("log.txt", $latestScore,FILE_APPEND);
  // file_put_contents("log.txt", $latestRank,FILE_APPEND);
  // file_put_contents("log.txt", $headCount,FILE_APPEND);
  // file_put_contents("log.txt", $rating,FILE_APPEND);

                      $message = "今までのあなたの最高スコアは" . kirisute($latestScore*100) . "点だよ。";

                      //点数によって返答メッセージを分岐
                      if ( $latestScore >= 0.8 ) {
                        $message = $message . "かなり大物になる予感・・・？" . $iconShock . "";
                      } else if ( ($latestScore < 0.8) && ($latestScore >= 0.6) ) {
                        $message = $message . "まずまずの点数だね！" . $iconShock . "";
                      } else if ( ($latestScore < 0.6) && ($latestScore >= 0.3) ) {
                        $message = $message . "ドンマイ" . $iconShock . "でも人間顔だけやないで！気にすんな" . $iconTeeth;
                      } else if ( ($latestScore < 0.3) && ($latestScore > 0.00) ) {
                        $message = $message . "ありゃ、なんかゴメンナサイ。。" . $iconGomen;
                      } else {
                        $message = "インスタ映えだと思う画像をここに送ると、AI(IBM Watson)がインスタ映え度を採点してくれるよ" . $iconKira . $iconKira;
                      }

                      // 順位によってはメッセージを追記
                      if ( ($rating < 1.0) && ($rating >= 0.8) ) {
                        $message = $message . "\nちなみにあなたの写真、上位20%以内にランクインしてるみたい。景品ゲットできるかも？？" . $iconTeeth;
                      } else if ( ($rating < 0.8) && ($rating >= 0.5) ) {
                        $message = $message . "\nちなみにあなたの写真、上位50%以内にランクインしてるみたい。この調子で頑張れ！" . $iconSoHappy;
                      } else if ( ($rating < 0.5) && ($rating >= 0.0) ) {
                        $message = $message . "\nちなみにあなたの写真は今、全体の50%以下みたい。もっと頑張れ！" . $iconFight . $iconFight;
                      } else {
                      }

                      $bot->replyText($event->getReplyToken(), $message);
                  // } else if (preg_match('/ASO部|asobu/i', $message_text)) {
                  //     //実装中
                  //     $message = "----ASO部の紹介----";
                  //     $bot->replyText($event->getReplyToken(), $message);

                  } else if (preg_match('/メリークリスマス|Merry Xmas|MerryXmas|MerryChristmas|Merry Christmas/i', $message_text)) {
                      $message = "クリスマスはちょっと早いぞ！でもメリークリスマス、楽しい冬になるといいね" . $iconTeeth . $iconTeeth . $iconTeeth;
                      $bot->replyText($event->getReplyToken(), $message);
                  } else if (preg_match('/商品|景品|なにがもらえる/i', $message_text)) {
                      $message_1 = "インスタ映え度判定ではティファニーのカップやGoogle homeなんかを用意しているよ" . $iconHappy;
                      replyMultiMessage($bot, $event->getReplyToken(),
                        new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message_1),
                        new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder("https://" . $_SERVER["HTTP_HOST"] . "/linebot/tiffany.png", "https://" . $_SERVER["HTTP_HOST"] . "/linebot/tiffany.png"),
                        new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder("https://" . $_SERVER["HTTP_HOST"] . "/linebot/googlehome.jpg", "https://" . $_SERVER["HTTP_HOST"] . "/linebot/googlehome.jpg")
                      );
                  } else if (preg_match('/豆知識|まめちしき|雑学|ざつがく/i', $message_text)) {
                      $message = "ケンタッキーフライドチキンの味付けのレシピを知っている人物は世界中にたった２人しかいない。クリスマスバーレルを食べるときはぜひ自慢してみて！" . $iconTeeth;
                      $bot->replyText($event->getReplyToken(), $message);
                  } else if (preg_match('/出世の秘訣/i', $message_text)) {
                      $message = "まずこう・・・手もみの仕方から覚えよう";
                      $bot->replyText($event->getReplyToken(), $message);
                  } else if (preg_match('/ギャグ/i', $message_text)) {
                      $message = "アルミ缶の上にあるミカn・・・って何させてるんですか！！";
                      $bot->replyText($event->getReplyToken(), $message);
                  } else if (preg_match('/迷子|はぐれた|はぐれました/i', $message_text)) {
                      $message = "とりあえず深呼吸をして、一番おいしそうなにおいがした方へ歩いてみよう" . $iconCake;
                      $bot->replyText($event->getReplyToken(), $message);
                  // } else if (preg_match('/マップ|まっぷ|地図|map/i', $message_text)) {
                  //     $message = "ほいっ！";
                  //     replyMultiMessage($bot, $event->getReplyToken(),
                  //       new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message),
                  //       new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder("https://" . $_SERVER["HTTP_HOST"] . "/linebot/5a0c1a06738f0.jpg", "https://" . $_SERVER["HTTP_HOST"] . "/linebot/5a0c1a06738f0.jpg")
                  //     );
                  } else if (preg_match('/結果確認|結果かくにん|けっか確認|けっかかくにん/i', $message_text)) {
                      //22:30を超えているか？
                      // file_put_contents("log.txt", date('H:i:s'),FILE_APPEND);
                      // file_put_contents("log.txt", '06:30:00',FILE_APPEND);
                      // if (strtotime(date('H:i:s')) < strtotime('06:30:00')) {
                      //   $message = "ちょっとまってね！";
                      //   $bot->replyText($event->getReplyToken(), $message);
                      // } else {

                        $winPoint = myFinalRank($userId);
                      file_put_contents("log.txt", $winPoint,FILE_APPEND);
                        if ( $winPoint > 0 ){
                          $message = "おめでとうございます" . $iconTeeth . "あなたの投稿した画像は見事入選しました！！" . "商品授与をしたいのでメインステージ横までお越しください" . $iconHappy . $iconKira . $iconKira;
                        } else {
                          $message = "残念ながらあなたの投稿した画像は入賞しませんでした" . $iconGomen . "これからメインステージで表彰式をやるのでもしよかったらお越しください" . $iconWish;
                        }
                        $bot->replyText($event->getReplyToken(), $message);

                      // }


                  } else {
                      $message = $profile["displayName"] . "さん、画像をアップロードしてね！" . $iconSoHappy;
                      $bot->replyText($event->getReplyToken(), $message);
                  }
	  } elseif ($event instanceof \LINE\LINEBot\Event\MessageEvent\ImageMessage) {

	    $response = $bot->getMessageContent($event->getMessageId());
      //tmpフォルダに一時的にjpgファイルを作成
	    $filename = saveImageToTmp($response->getRawBody());

      $directory_path = "tmp";
      $imagePath = $directory_path. "/" . $filename;

      //Watsonにて画像判定
      $vr_result = VR_Post($imagePath);
      $json_data = json_encode($vr_result,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
      error_log($json_data);
         
      $userName = $profile['displayName']; 
      $class = $vr_result["images"][0]["classifiers"][0];
      $highestScore = 0.0;
      $className = "";
      $classScore = 0.0;
      for($i=1;$i>=0;$i--){
        if ($class["classes"][$i]["score"] > $highestScore ) {
          $highestScore = $class["classes"][$i]["score"];
          $className = $class["classes"][$i]["class"];
          $classScore = $class["classes"][$i]["score"];
        }
      }

      //MD顔の補正
      if ( $className === 'md' ) {
        $classScore = $classScore*1.1;
        if ( $classScore > 100.0 ) {
          $classScore = 100.0;
        }
      }


      $seqFileId = saveToDB($userId, $userName, $className, $classScore);

		  $message = "すてきな写真をありがとう！あなたの顔は出世顔判定結果" . kirisute($classScore*100) . "点だよ！";
      if ( $classScore >= 0.8 ) {
          $message = $message . "かなり大物になる予感・・・？" . $iconShock . "";
      } else if ( ($classScore < 0.8) && ($classScore >= 0.6) ) {
          $message = $message . "まずまずの点数だね！" . $iconShock . "";
      } else if ( ($classScore < 0.6) && ($classScore >= 0.3) ) {
          $message = $message . "ドンマイ" . $iconShock . "でも人間顔だけやないで！気にすんな" . $iconTeeth;
      } else {
          //家でぬるぬるしてな！
          $message = $message . "ありゃ、なんかゴメンナサイ。。" . $iconGomen;
      }
  		$bot->replyText($event->getReplyToken(), $message);
      $ini = './credentials.ini';
      $iniProvider = CredentialProvider::ini('fuyufes-s3', $ini);
      $iniProvider = CredentialProvider::memoize($iniProvider);

      $client = new Aws\S3\S3Client([
          'region'   => 'ap-northeast-1',
          'version'  => '2006-03-01',
          'credentials' => $iniProvider
      ]);

      //S3のバケット名
      $bucketName = 'prd-fuyufes2017';
      $keyName = 'img/std/' . $seqFileId . '.jpg';
      $srcFile = $imagePath;

      $client->putObject([
          'Bucket' => $bucketName,
          'Key' => $keyName,
          'SourceFile' => $srcFile,
          'ContentType'=> mime_content_type($srcFile)
      ]);
      unlink($imagePath);

    } elseif ($event instanceof \LINE\LINEBot\Event\MessageEvent\StickerMessage) {
                      $bot->replyMessage($event->getReplyToken(), new \LINE\LINEBot\MessageBuilder\StickerMessageBuilder(1, 1));
    } else {
                      $message = $profile["displayName"] . "さん、画像をアップロードしてね！";
                      $bot->replyText($event->getReplyToken(), $message);
    }

	 }
} catch (Exception $e) {
	error_log('Exception has come');	
}


function saveImageToTmp($rawBody) {
  $im = imagecreatefromstring($rawBody);
  $resultString = "";
  //デバッグ
  if ($im !== false) {
      $filename = uniqid();
      $filename = $filename . ".jpg";
      $directory_path = "tmp";
      if(!file_exists($directory_path)) {
        if(mkdir($directory_path, 0777, true)) {
            chmod($directory_path, 0777);
        }
      }
      
      $path = $directory_path. "/" . $filename;
      imagejpeg($im, $path, 75);

      return $filename;
  } else {
      error_log("fail to create image.");
  }
}

function VR_Post($jpg){
  $classifyId = "face_1709463283";
  // $classifyId = "date_default_timezone_get(oid)t";
//  $classifyId = "family2_1072224594";
  //デバッグ
  // file_put_contents("log.txt", "aaa",FILE_APPEND);
 try {
    #変数宣言
    $url = 'https://gateway-a.watsonplatform.net/visual-recognition/api/v3/classify'
          .'?api_key=ed08ebac4443187db1643d1d582ce43c9afc7168&version=2017-12-09&threshold=0.0';
    $curl = curl_init();
    $data = array("images_file" => new CURLFile($jpg,mime_content_type($jpg),basename($jpg)),
                    "classifier_ids" => $classifyId);
    curl_setopt($curl, CURLOPT_URL, $url);
    //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
    curl_setopt($curl, CURLOPT_POST, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    $vr_exec = curl_exec($curl);
    curl_close($curl);
    $re = json_decode($vr_exec,true);
    return $re;
  } catch(Exception $e){
    echo $e->getMessage();
  }
}

function saveToDB($userId, $displayName, $className, $classScore) {
  $ini_array  = parse_ini_file("config.ini");
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

  $is_enable = 1;
  $created_at = new DateTime();
  $created_at = $created_at->format('Y-m-d H:i:s');
  $updated_at = new DateTime();
  $updated_at = $updated_at->format('Y-m-d H:i:s');
  $category = 'face';


  $sth = $dbh->prepare("INSERT INTO instagenic (user_id, user_name, score, category, sub_category, is_enable, created_at, updated_at) VALUES (:user_id, :user_name, :score, :category, :sub_category, :is_enable, :created_at, :updated_at)");
  $dbh->beginTransaction();
  $sth->bindParam(':user_id', $userId, PDO::PARAM_STR);
  $sth->bindParam(':user_name', $displayName, PDO::PARAM_STR);
  $sth->bindParam(':score', $classScore, PDO::PARAM_STR);
  $sth->bindParam(':category', $category, PDO::PARAM_STR);
  $sth->bindParam(':sub_category', $className, PDO::PARAM_STR);
  $sth->bindParam(':is_enable', $is_enable, PDO::PARAM_INT);
  $sth->bindParam(':created_at', $created_at, PDO::PARAM_STR);
  $sth->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);
  $sth->execute();

  // INSERTされたデータのIDを取得
  $id = $dbh->lastInsertId('id');
  $dbh->commit();
  return $id;

}

function searchScore($userId){
  $ini_array  = parse_ini_file("config.ini");
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
  $category = 'face';

  $sth = $dbh->prepare("SELECT MAX(score) FROM instagenic where user_id = :user_id and category = :category");
  $sth->bindParam(':user_id', $userId, PDO::PARAM_STR);
  $sth->bindParam(':category', $category, PDO::PARAM_STR);
  $sth->execute();
  $select_data = $sth->fetch();
  return $select_data[0];
}

function searchmyRank($userId){
  $ini_array  = parse_ini_file("config.ini");
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
  $category = 'face';

  $sth_set = $dbh->prepare("set @c:=0;");
  $sth_set->execute();
  $sth = $dbh->prepare("SELECT ranking.* from (select @c:=@c+1, scorelist.* from (SELECT MAX(score), user_id FROM instagenic WHERE category = :category GROUP BY user_id ORDER BY MAX(score) DESC) scorelist) ranking where ranking.user_id = :user_id");
  $sth->bindParam(':user_id', $userId, PDO::PARAM_STR);
  $sth->bindParam(':category', $category, PDO::PARAM_STR);
  $sth->execute();
  $select_data = $sth->fetch();
  return $select_data[0];
}

function searchHeadCount(){
  $ini_array  = parse_ini_file("config.ini");
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
  $category = 'face';

  $sth = $dbh->prepare("SELECT count(distinct(user_id)) from instagenic where category = :category");
  $sth->bindParam(':category', $category, PDO::PARAM_STR);
  $sth->execute();
  $select_data = $sth->fetch();
  // //デバッグ
  // file_put_contents("log.txt", $select_data[0],FILE_APPEND);
  return $select_data[0];
}

function replyMultiMessage($bot, $replyToken, ...$msgs) {
  $builder = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
  foreach($msgs as $value) {
    $builder->add($value);
  }
  $response = $bot->replyMessage($replyToken, $builder);
  if (!$response->isSucceeded()) {
    error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
  }
}

function kirisute($num) {
  $n = 2 ;
  $kirisute = floor( $num * pow( 10 , $n ) ) / pow( 10 , $n ) ;
  return $kirisute;
}



function myFinalRank($userId) {
  $ini_array  = parse_ini_file("config.ini");
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

  $is_elected = 1;
  $category = 'face';


  $sth = $dbh->prepare("SELECT MAX(score), user_id, category, sub_category FROM instagenic WHERE user_id = :user_id and is_elected = :is_elected and category = :category");
  $dbh->beginTransaction();
  $sth->bindParam(':user_id', $userId, PDO::PARAM_STR);
  $sth->bindParam(':category', $category, PDO::PARAM_STR);
  $sth->bindParam(':is_elected', $is_elected, PDO::PARAM_INT);
  $sth->execute();
  $select_data = $sth->fetch();
  return $select_data[0];

}

 ?>
