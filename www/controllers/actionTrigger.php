<?php

use Ewetasker\Performer\TelegramPerformer;
use Ewetasker\Performer\TwitterPerformer;

include_once('../performers/telegramPerformer.php');
include_once('../performers/twitterPerformer.php');

$actions = isset($_POST['actions']) ? $_POST['actions'] : [];

if (isset($_POST['channel']) && isset($_POST['action'])) {
    $action = array();
    $action['channel'] = $_POST['channel'];
    $action['action'] = $_POST['action'];
    $action['parameter'] = isset($_POST['parameter']) ? $_POST['parameter'] : '';
    array_push($actions, $action);
}

foreach ($actions as $action) {
    switch ($action['channel']) {
        case 'Telegram':
            $telegram = new TelegramPerformer();
            switch ($action['action']) {
                case 'SendMessage':
                    $telegram->sendMessage($action['parameter'], $_POST['user']);
                    break;
                case 'ImportRules':
                    $telegram->importRules($action['parameter'], $_POST['user']);
                    break;
                
                default:
                    # code...
                    break;
            }
            unset($telegram);
            break;

        case 'Twitter':
            $twitter = new TwitterPerformer();
            switch ($action['action']) {
                case 'PostTweet':
                    $twitter->postTweet($action['parameter'], $_POST['user']);
                    break;
                
                default:
                    # code...
                    break;
            }
            unset($twitter);
            break;
        
        default:
            # code...
            break;
    }
}