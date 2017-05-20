<?php

use Ewetasker\Performer\ChromecastPerformer;
use Ewetasker\Performer\TelegramPerformer;

include_once('../performers/chromecastPerformer.php');
include_once('../performers/telegramPerformer.php');

$actions = isset($_POST['actions']) ? $_POST['actions'] : [];

foreach ($actions as $action) {    
    switch ($action['channel']) {
        case 'Telegram':
            $telegram = new TelegramPerformer();
            switch ($action['action']) {
                case 'SendMessage':
                    $telegram->sendMessage($action['parameter'], $_POST['user']);
                    break;
                
                default:
                    # code...
                    break;
            }
            unset($telegram);
            break;

        case 'Chromecast':
            $chromecast = new ChromecastPerformer();
            switch ($action['action']) {
                case 'PlayVideo':
                    $chromecast->playVideo();
                    break;
                
                default:
                    # code...
                    break;
            }
            unset($chromecast);
            break;
        
        default:
            # code...
            break;
    }
}