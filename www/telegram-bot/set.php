<?php

session_start();
if(!isset($_SESSION['user']) || $_SESSION['user'] !== 'admin')
    header('Location: ../index.php');

//Composer Loader
$dir = realpath(__DIR__.'/..');
$loader = require $dir.'/vendor/autoload.php';

$API_KEY = '298993971:AAE5u4Std_5l6aEZDpuVjo-QO3IsszBOiFY';
$BOT_NAME = 'ewetasker_bot';
$link = $_GET['host'] . '/telegram-bot/hook.php';
try {
    // create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($API_KEY, $BOT_NAME);
    // set webhook
    $result = $telegram->setWebHook($link);
    //Uncomment to use certificate
    //$result = $telegram->setWebHook($link, $path_certificate);
    if ($result->isOk()) {
        echo $result->getDescription() . PHP_EOL;
        echo 'Host: ' . $_GET['host'] . PHP_EOL;
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e;
}
