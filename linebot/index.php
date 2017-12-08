<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/aws-autoloader.php';

use Aws\Credentials\CredentialProvider;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;

$CAT='sGxLbx6bmS3VMdUDkr/r8twBYJKOTJ607EVgWwnJ8lUjguCVsHMF67Z1u+7fTfdYe5hHmyPrOD5x6d7wJWjqCmhn3/DFErKiMQfQl6kcFEihZLU/aCoZNaK79EtPComX/7S1wURVDyoC5NeaAvEIsgdB04t89/1O/w1cDnyilFU=';
$CS='6c617d405958a57a284e3b2816020263';
putenv("CHANNEL_ACCESS_TOKEN=$CAT");
putenv("CHANNEL_SECRET=$CS");

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);
/////////////////////////////////////////////////////////////
// $channelId_photogenic='';
// $channelId_promotion='';
// $json_string1 = file_get_contents('php://input');
// $json_object1 = json_decode($json_string1);

// for(var i = 0 ; i < $json_object1.length ; i++){
//     file_put_contents("log.txt", $json_object1[i],FILE_APPEND);
// }


// $message_token1 = $json_object1->{"events"}[0]->{"replyToken"};
// file_put_contents("log.txt", "\nmessage token is" . $message_token1,FILE_APPEND);



/////////////////////////////////////////////////////////////

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
// for(var i = 0 ; i < $json_object.length ; i++){
//     file_put_contents("log.txt", $json_object[i],FILE_APPEND);
// }
$message_token1 = $json_object->{"events"}[0]->{"replyToken"};
file_put_contents("log.txt", "\nmessage token is" . $message_token1,FILE_APPEND);
  $message_text =   $json_object->{"events"}[0]->{"message"}->{"text"};
  foreach ($events as $event) {
	  if (!($event instanceof \LINE\LINEBot\Event\MessageEvent)) {
	    error_log('Non message event has come');
	    continue;
	  }
	  $profile = $bot->getProfile($event->getUserId())->getJSONDecodedBody();
    $userId = $profile['userId'];
	  if ($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage) {
                  if (preg_match('/help|ヘルプ|へるぷ/i', $message_text)) {
                      $message = "インスタ映えだと思う画像をここに送ると、AI(IBM Watson)がインスタ映え度を採点してくれるよ！\n冬フェス会場で撮影した写真をここにアップロードしてね！";
                      $bot->replyText($event->getReplyToken(), $message);
                  } else if (preg_match('/rank|ランク|らんく|順位/i', $message_text)) {
                      $latestScore = searchScore($userId);
                      $latestRank = searchRank($userId);

                      $message = $latestScore;

                      //点数によって返答メッセージを分岐
                      if ( $latestScore >= 0.8 ) {
                        $message = "今までにあなたが送ってくれた画像の最高スコアは" . $message . "だよ。あと一息！";
                      } else if ( $latestScore >= 0.65 ) {
                        $message = "今までにあなたが送ってくれた画像の最高スコアは" . $message . "だよ。センスいい！";
                      } else {
                        $message = "今までにあなたが送ってくれた画像の最高スコアは" . $message . "だよ。がんばれ！";
                      }

                      //上位ランカーにはメッセージを追記
                      if ( empty($latestRank) ) {
                      } else {
                        $message = $message . "\nしかもあなたの写真、上位5位以上にランクインしてるみたい。この調子で頑張れ！";
                      }

                      $bot->replyText($event->getReplyToken(), $message);
                  } else if (preg_match('/ASO部|asobu/i', $message_text)) {
                      //実装中
                      $message = "----ASO部の紹介----";
                      $bot->replyText($event->getReplyToken(), $message);
                  } else if (preg_match('/メリークリスマス|Merry Xmas|MerryXmas|MerryChristmas|Merry Christmas/i', $message_text)) {
                      $message = "クリスマスはちょっと早いぞ！でもメリークリスマス、楽しい冬になるといいね☆";
                      $bot->replyText($event->getReplyToken(), $message);
                  } else if (preg_match('/商品|景品|なにがもらえる/i', $message_text)) {
                      $message = "ティファニーのコップやGoogle Homeなどがあるよ。詳細は授与式で。お楽しみに！";
                      replyMultiMessage($bot, $event->getReplyToken(),
                        new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message),
                        new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder("https://" . $_SERVER["HTTP_HOST"] . "/linebot/5a0c1a06738f0.jpg", "https://" . $_SERVER["HTTP_HOST"] . "/linebot/5a0c1a06738f0.jpg")
                      );
                  } else if (preg_match('/豆知識|まめちしき|雑学|ざつがく/i', $message_text)) {
                      $message = "ケンタッキーフライドチキンの味付けのレシピを知っている人物は世界中にたった２人しかいない。クリスマスバーレルを食べるときはぜひ自慢してみて！";
                      $bot->replyText($event->getReplyToken(), $message);
                  } else if (preg_match('/出世の秘訣/i', $message_text)) {
                      $message = "まずこう・・・手もみの仕方から覚えよう";
                      $bot->replyText($event->getReplyToken(), $message);
                  } else if (preg_match('/ギャグ/i', $message_text)) {
                      $message = "アルミ缶の上にあるミカn・・・って何させてるんですか！";
                      $bot->replyText($event->getReplyToken(), $message);
                  } else if (preg_match('/迷子|はぐれた|はぐれました/i', $message_text)) {
                      $message = "とりあえず深呼吸をして、一番おいしそうなにおいがした方へ歩いてみよう";
                      $bot->replyText($event->getReplyToken(), $message);
                  } else if (preg_match('/マップ|まっぷ|地図|map/i', $message_text)) {
                      $message = "ほいっ！";
                      replyMultiMessage($bot, $event->getReplyToken(),
                        new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message),
                        new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder("https://" . $_SERVER["HTTP_HOST"] . "/linebot/5a0c1a06738f0.jpg", "https://" . $_SERVER["HTTP_HOST"] . "/linebot/5a0c1a06738f0.jpg")
                      );
                  } else {
            		      $message = $profile["displayName"] . "さん、画像をアップロードしてね！";
		                  $bot->replyText($event->getReplyToken(), $message);
                  }
	  } elseif ($event instanceof \LINE\LINEBot\Event\MessageEvent\ImageMessage) {
      //$message = "すてきな写真をありがとう！";
      $bot->replyText($event->getReplyToken(), $message);

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
      $class = $vr_result["images"][0]["classifiers"][0]["classes"][0];
      $className = $class["class"];
      $classScore = $class["score"];
      $seqFileId = saveToDB($userId, $userName, $className, $classScore);




      // $watsonMessage = "";
      // $watsonMessage = $watsonMessage . "Class: " . $className . "=";
      // $watsonMessage = $watsonMessage . "Score: " . $classScore . ";";
          	
		  $message = "すてきな写真をありがとう！IBM Watsonの判定結果によるとあなたの写真はインスタ映え度" . $classScore*100 . "点！";
      if ( $classScore >= 0.8 ) {
          $message = $message . "あと一息！";
      } else if ( $classScore >= 0.65 ) {
          $message = $message . "センスいい！";
      } else {
          $message = $message . "もっとがんばれ！";
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
      $bucketName = 'fuyufes2017';
      // //class毎に格納先のフォルダを定義
      // if ( $className == 'Irish stew' ) {
      //   $keyName = 'img/' . $seqFileId . '.jpg';
      // } else if (  $className == 'memorial' ) {
      //   $keyName = 'img2/' . $seqFileId . '.jpg';
      // } else {
      //   $keyName = $seqFileId . '.jpg';
      // }
      $keyName = 'img/std/' . $seqFileId . '.jpg';
      $srcFile = $imagePath;

      $client->putObject([
          'Bucket' => $bucketName,
          'Key' => $keyName,
          'SourceFile' => $srcFile,
          'ContentType'=> mime_content_type($srcFile)
      ]);
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
  $classifyId = "default";
//  $classifyId = "family2_1072224594";
 try {
    #変数宣言
    $url = 'https://gateway-a.watsonplatform.net/visual-recognition/api/v3/classify'
          .'?api_key=d9b2d25031403cba343b7fcfdd80d908f7630990&version=2017-10-22';
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
      //デバッグ
      file_put_contents("log.txt", "ポスト時" . $userId,FILE_APPEND);
      file_put_contents("log.txt", "\n",FILE_APPEND);


  $sth = $dbh->prepare("INSERT INTO instagenic (user_id, user_name, score, category, sub_category, is_enable, created_at, updated_at) VALUES (:user_id, :user_name, :score, :category, :sub_category, :is_enable, :created_at, :updated_at)");
  $dbh->beginTransaction();
  $sth->bindParam(':user_id', $userId, PDO::PARAM_STR);
  $sth->bindParam(':user_name', $displayName, PDO::PARAM_STR);
  $sth->bindParam(':score', $classScore, PDO::PARAM_STR);
  $sth->bindParam(':category', $className, PDO::PARAM_STR);
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

function saveToS3($sequenceNo, $imagePath) {
  // //デバッグ
  // file_put_contents("log.txt", $sequenceNo,FILE_APPEND);
  // file_put_contents("log.txt", $imagePath,FILE_APPEND);

  // require './lib/aws-autoloader.php';
  // use Aws\Credentials\CredentialProvider;
  // $ini = './credentials.ini';
  // $iniProvider = CredentialProvider::ini('fuyufes-s3', $ini);
  // $iniProvider = CredentialProvider::memoize($iniProvider);

  // $client = new Aws\S3\S3Client([
  //     'region'   => 'ap-northeast-1',
  //     'version'  => '2006-03-01',
  //     'credentials' => $iniProvider
  // ]);

  // //画像ファイルをバケットに入れる。
  // $bucketName = 'fuyufes2017';
  // $keyName = 'ishikawa3.jpg';
  // //$keyName = $sequenceNo . '.jpg';
  // $srcFile = 'tmp/5a182e277c36e.jpg';

  // $client->putObject([
  //     'Bucket' => $bucketName,
  //     'Key' => $keyName,
  //     'SourceFile' => $srcFile,
  //     'ContentType'=> mime_content_type($srcFile)
  // ]);

}

function searchScore($userId){
      //デバッグ
      file_put_contents("log.txt", "確認時" . $userId,FILE_APPEND);
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

  $sth = $dbh->prepare("SELECT MAX(score) FROM instagenic where user_id = :user_id");
  $sth->bindParam(':user_id', $userId, PDO::PARAM_STR);
  $sth->execute();
  $select_data = $sth->fetch();
  //デバッグ
  //file_put_contents("log.txt", $select_data[0],FILE_APPEND);
  //file_put_contents("log.txt", \n,FILE_APPEND);
  return $select_data[0];
}

function searchRank($userId){
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

  $sth = $dbh->prepare("SELECT * FROM (SELECT MAX(score), user_id FROM instagenic GROUP BY user_id ORDER BY MAX(score) DESC LIMIT 5) AS top5 WHERE top5.user_id = :user_id");
  $sth->bindParam(':user_id', $userId, PDO::PARAM_STR);
  $sth->execute();
  $select_data = $sth->fetch();
  //デバッグ
  //file_put_contents("log.txt", $select_data[0],FILE_APPEND);
  //file_put_contents("log.txt", \n,FILE_APPEND);
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


 ?>
