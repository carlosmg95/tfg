<?php

use Ewetasker\Manager\ChannelManager;

include_once('./controllers/channelManager.php');
include_once('./controllers/ruleManager.php');

$command = $_POST['command'];

switch ($command) {

    case 'getChannels':
        
        $channel_manager = new ChannelManager();
        $result = $channel_manager->getChannelsList('JSON');

        echo json_encode($result, JSON_UNESCAPED_SLASHES);
        break;
    
    case 'createRule':

        $ruleTitle = $_POST['rule_title'];
        $channelOne = $_POST['rule_channel_one'];
        $channelTwo = $_POST['rule_channel_two'];
        $eventTitle = $_POST['rule_event_title'];
        $actionTitle = $_POST['rule_action_title'];
        $ruleDescription = $_POST['rule_description'];
        $ruleCreator = $_POST['rule_creator'];
        $rulePlace = $_POST['rule_place'];
        $rule = $_POST['rule'];
        $rule = str_replace("\n", "", $rule);
        $rule = str_replace("\r", "", $rule);
        $rule = stripslashes($rule);
        
        $rulesManager = new RulesManager($config);
        $rulesManager->createRule($ruleTitle, $channelOne, $channelTwo, $eventTitle, $actionTitle, $ruleDescription, $ruleCreator, $rulePlace, $rule);

        break;  

    default:
        # code...
        break;
}