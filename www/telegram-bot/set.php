<?php
//Composer Loader
$dir = realpath(__DIR__.'/..');
$loader = require $dir.'/vendor/autoload.php';

$API_KEY = '298993971:AAE5u4Std_5l6aEZDpuVjo-QO3IsszBOiFY';
$BOT_NAME = 'ewetasker_bot';
$link = 'https://8cd014e9.ngrok.io/telegram-bot/hook.php';
try {
    // create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($API_KEY, $BOT_NAME);
    // set webhook
    $result = $telegram->setWebHook($link);
    //Uncomment to use certificate
    //$result = $telegram->setWebHook($link, $path_certificate);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e;
}
