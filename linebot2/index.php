<?php
date_default_timezone_set('Asia/Tokyo');
$time = date('Y/m/d H:i:s');
require_once __DIR__ . '/vendor/autoload.php';
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);

$signature = $_SERVER["HTTP_" . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
try {
  $events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);
} catch(\LINE\LINEBot\Exception\InvalidSignatureException $e) {
  error_log("parseEventRequest failed. InvalidSignatureException => ".var_export($e, true));
} catch(\LINE\LINEBot\Exception\UnknownEventTypeException $e) {
  error_log("parseEventRequest failed. UnknownEventTypeException => ".var_export($e, true));
} catch(\LINE\LINEBot\Exception\UnknownMessageTypeException $e) {
  error_log("parseEventRequest failed. UnknownMessageTypeException => ".var_export($e, true));
} catch(\LINE\LINEBot\Exception\InvalidEventRequestException $e) {
  error_log("parseEventRequest failed. InvalidEventRequestException => ".var_export($e, true));
}
foreach ($events as $event) {
  if (!($event instanceof \LINE\LINEBot\Event\MessageEvent)) {
    error_log('Non message event has come');
    continue;
  }
  if (!($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage)) {
    error_log('Non text message has come');
    continue;
  }
  #$bot->replyText($event->getReplyToken(), $event->getText());
 $profile = $bot->getProfile($event->getUserId())->getJSONDecodedBody();
  #$message = $profile["displayName"] . "さん、おはようございます！今日も頑張りましょう！";
  #$message2 = "今は".$time."今日の天気は雨です。傘を持っていきましょう！";
 $message = "講師、アシスタント業務お疲れ様でした。\n"."アシスタント業務担当の方は以下のシートにFacebook宣伝用の写真及びコメントを\n"."\n".
 "講師の方は授業の振り返りを以下のFacebookグループにシェアお願いします。\n".
 "また出勤届けの方も記入よろしくお願いします。";
 $message2 ="アシスタント業務→"."URL";
 $message3 = "講師の方→"."URL";

  $bot->replyMessage($event->getReplyToken(),
    (new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder())
      ->add(new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message))
      ->add(new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message2))
       ->add(new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message3))
  );
}
 ?>