<?php

use Ewetasker\Manager\AdministrationManager;
use Ewetasker\Performer\ChromecastPerformer;
use Ewetasker\Performer\HueLightPerformer;
use Ewetasker\Performer\TelegramPerformer;
use Ewetasker\Performer\TwitterPerformer;

include_once('administrationManager.php');
include_once('../performers/chromecastPerformer.php');
include_once('../performers/hueLightPerformer.php');
include_once('../performers/telegramPerformer.php');
include_once('../performers/twitterPerformer.php');

$actions = isset($_POST['actions']) ? $_POST['actions'] : [];

if (isset($_POST['channel']) && isset($_POST['action'])) {
    $action = array();
    $action['channel'] = $_POST['channel'];
    $action['action'] = $_POST['action'];
    $action['parameter'] = isset($_POST['parameter']) ? $_POST['parameter'] : "";
    array_push($actions, $action);
}

foreach ($actions as $action) {
    $admin_manager = new AdministrationManager();
    $admin_manager->runAction($action['channel'], $action['action']);
    $admin_manager->userRuns($_POST['user']);
    unset($admin_manager); 
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
                    $chromecast->playVideo($action['parameter']);
                    break;
                
                default:
                    # code...
                    break;
            }
            unset($chromecast);
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

        case 'HueLight':
            $huelight = new HueLightPerformer();
            switch ($action['action']) {
                case 'TurnOn':
                    $huelight->turnOn();
                    break;
                case 'TurnOff':
                    $huelight->turnOff();
                    break;
                case 'SetBrightness':
                    $huelight->setBrightness($action['parameter']);
                    break;
                
                default:
                    # code...
                    break;
            }
            unset($huelight);
            break;
        
        default:
            # code...
            break;
    }
}