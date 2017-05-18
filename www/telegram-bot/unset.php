<?php

session_start();
if(!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin')
    header('Location: ../index.php');
    
//Composer Loader
$loader = require __DIR__.'/../vendor/autoload.php';

$API_KEY = '298993971:AAE5u4Std_5l6aEZDpuVjo-QO3IsszBOiFY';
$BOT_NAME = 'ewetasker_bot';
try {
    // create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($API_KEY, $BOT_NAME);
    // set webhook
    $result = $telegram->unsetWebHook();
    //Uncomment to use certificate
    //$result = $telegram->setWebHook($link, $path_certificate);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e;
}
