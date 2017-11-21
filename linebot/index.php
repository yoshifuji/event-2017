<?php

require_once __DIR__ . '/vendor/autoload.php';

$CAT='sGxLbx6bmS3VMdUDkr/r8twBYJKOTJ607EVgWwnJ8lUjguCVsHMF67Z1u+7fTfdYe5hHmyPrOD5x6d7wJWjqCmhn3/DFErKiMQfQl6kcFEihZLU/aCoZNaK79EtPComX/7S1wURVDyoC5NeaAvEIsgdB04t89/1O/w1cDnyilFU=';
$CS='6c617d405958a57a284e3b2816020263';
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
        $message_text = $json_object->{"events"}[0]->{"message"}->{"text"};
        foreach ($events as $event) {
	  if (!($event instanceof \LINE\LINEBot\Event\MessageEvent)) {
	    error_log('Non message event has come');
	    continue;
	  }
	  $profile = $bot->getProfile($event->getUserId())->getJSONDecodedBody();
	  if ($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage) {
                  if ($message_text == "help") {
                      $message = "あなたのスマートフォンで撮影した写真をここにアップロードしてね！";
                      $bot->replyText($event->getReplyToken(), $message);
                  } else if ($message_text == "ヘルプ") {
                      $message = "あなたのスマートフォンで撮影した写真をここにアップロードしてね！";
                      $bot->replyText($event->getReplyToken(), $message);
                  } else if ($message_text == "へるぷ") {
                      $message = "あなたのスマートフォンで撮影した写真をここにアップロードしてね！";
                      $bot->replyText($event->getReplyToken(), $message);
                  } else {
		      $message = $profile["displayName"] . "さん。画像をアップロードしてください。";
		      $bot->replyText($event->getReplyToken(), $message);
                  }
	  } elseif ($event instanceof \LINE\LINEBot\Event\MessageEvent\ImageMessage) {
	      $response = $bot->getMessageContent($event->getMessageId());
	      	      
	      $imagePath = saveImageToTmp($response->getRawBody());
          $vr_result = VR_Post($imagePath);
          $json_data = json_encode($vr_result,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
          error_log($json_data);
         
          $userId = $profile['userId'];
          $userName = $profile['displayName']; 
          $isJiro = false;
          $class = $vr_result["images"][0]["classifiers"][0]["classes"][0];
          $className = $class["class"];
          $classScore = $class["score"];
          saveToDB($userId, $userName, $className, $classScore, $imagePath);
          $watsonMessage = "";
          $watsonMessage = $watsonMessage . "Class: " . $className . "=";
          $watsonMessage = $watsonMessage . "Score: " . $classScore . ";";
          	
       	  if ($class["class"] == "jiron") {
              if ($class["score"] >= 0.75) {
          			$isJiro = true;
         		}
        	}
		  $message = $userName . "さんの画像は正常にアップロードできました。Watsonからのメッセージ:" . $watsonMessage;
		  if ($isJiro) {
		  	$message = $message . "--- そして、あなたはじろうです。ジークじろーん！";
		  }
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
      $directory_path = "tmp";
      if(!file_exists($directory_path)) {
        if(mkdir($directory_path, 0777, true)) {
            chmod($directory_path, 0777);
        }
      }
      
      $path = $directory_path. "/" . $filename . ".jpg";
      
      imagejpeg($im, $path, 75);
      return $path;
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
          .'?api_key=ff36e516fdeed5651ee1ca9659ba391b34441ac3&version=2017-10-22';
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

function saveToDB($userId, $displayName, $className, $classScore, $filePath) {
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
           //ishikawa debug
          file_put_contents("log.txt", $userId,FILE_APPEND);
          file_put_contents("log.txt", $displayName,FILE_APPEND);
          file_put_contents("log.txt", $className,FILE_APPEND);
          file_put_contents("log.txt", $classScore,FILE_APPEND);


$is_enable = 1;
$created_at = new DateTime();
$created_at = $created_at->format('Y-m-d H:i:s');
$updated_at = new DateTime();
$updated_at = $updated_at->format('Y-m-d H:i:s');


$sth = $dbh->prepare("INSERT INTO instagenic (user_name, image_name, score, category, sub_category, is_enable, created_at, updated_at) VALUES (:user_name, :image_name, :score, :category, :sub_category, :is_enable, :created_at, :updated_at)");
$sth->bindParam(':user_name', $displayName, PDO::PARAM_STR);
$sth->bindParam(':image_name', $filePath, PDO::PARAM_STR);
$sth->bindParam(':score', $classScore, PDO::PARAM_STR);
$sth->bindParam(':category', $className, PDO::PARAM_STR);
$sth->bindParam(':sub_category', $className, PDO::PARAM_STR);
$sth->bindParam(':is_enable', $is_enable, PDO::PARAM_INT);
$sth->bindParam(':created_at', $created_at, PDO::PARAM_STR);
$sth->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);
$sth->execute();
}

function saveFile($sequenceNo, $imagePath) {

}


 ?>
