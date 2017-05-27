<?php

use Ewetasker\Manager\ChannelManager;
use Ewetasker\Manager\RuleManager;

include_once('./controllers/channelManager.php');
include_once('./controllers/ruleManager.php');

$command = $_POST['command'];
$channel_manager = new ChannelManager();

switch ($command) {

    case 'getChannels':
        
        $result = $channel_manager->getChannelsList('JSON');

        echo json_encode($result, JSON_UNESCAPED_SLASHES);
        break;
    
    case 'createRule':

        $rule_title = $_POST['rule_title'];
        $channel_event = $_POST['rule_channel_one'];
        $channel_action = $_POST['rule_channel_two'];
        $event_title = $_POST['rule_event_title'];
        $action_title = $_POST['rule_action_title'];
        $rule_description = $_POST['rule_description'];
        $rule_creator = $_POST['rule_creator'];
        $rule_place = $_POST['rule_place'];
        $rule = $_POST['rule'];
        
        $rule = str_replace("\n\n+", "\n", $rule);
        $rule = str_replace("\r", "", $rule);
        $rule = preg_replace("/\s+@/", "\n@", $rule);
        $rule = preg_replace("/}\./", "\n}.", $rule);
        $rule = preg_replace("/{\?/", "\n{\n?", $rule);
        $rule = preg_replace("/\.}/", ".\n}\n", $rule);
        $rule = preg_replace("/{e/", "\n{\ne", $rule);        
        $rule = str_replace("\n\n+", "\n", $rule);
        $rule = str_replace("\r", "", $rule);
        
        $rules_manager = new RuleManager();
        $rules_manager->createNewRule(
            $rule_title,
            $rule_description,
            $rule_place,
            $rule_creator,
            array($channel_action),
            array($action_title),
            array($channel_event),
            array($event_title),
            $rule
        );

        break;

    case 'createRuleParams':

        $rule_title = $_POST['rule_title'];
        $channel_event = $_POST['rule_channel_one'];
        $channel_action = $_POST['rule_channel_two'];
        $event_title = $_POST['rule_event_title'];
        $action_title = $_POST['rule_action_title'];
        $rule_description = $_POST['rule_description'];
        $rule_creator = $_POST['rule_creator'];
        $rule_place = $_POST['rule_place'];
        $event_params = array();
        $action_params = array();
        foreach ($_POST as $key => $value) {
            if (fnmatch('event_param_*', $key)) {
                $i = str_replace('event_param_', '', $key);
                $event_params[$i] = $value;
            } else if (fnmatch('action_param_*', $key)) {
                $i = str_replace('action_param_', '', $key);
                $action_params[$i] = $value;
            }
        }
        ksort($event_params);
        ksort($action_params);

        $action_rules = '';
        $action_prefixes = '';
        $info = $channel_manager->getRulesAndPrefix($channel_action);
        $action_rule = $info['actions'][$action_title]['rule'];
        if ($action_params !== []) {
            $action_rule = preg_replace('/\s/', $channel_manager->getN($channel_action) . ' ', $action_rule, 1);
            foreach ($action_params as $parameter) {
                $action_rule = preg_replace('/(#+\w+#)/', '"' . $parameter . '"', $action_rule, 1);
            }
        }
        $action_rules .= $action_rule . PHP_EOL;
        $action_prefixes .= $info['actions'][$action_title]['prefix'] . PHP_EOL;

        $event_rules = '';
        $event_prefixes = '';
        $info = $channel_manager->getRulesAndPrefix($channel_event);
        $event_rule = $info['events'][$event_title]['rule'];
        preg_match_all('/\?\w+/', $event_rule, $variables);
        $variables = array_unique($variables[0]);
        foreach ($variables as $variable) {
            $event_rule = preg_replace('/\\' . $variable . '/', $variable . '0', $event_rule);
        }
        if ($event_params !== '') {
            foreach ($event_params as $parameter) {
                $event_rule = preg_replace('/(#+\w+#)/', '"' . $parameter . '"', $event_rule, 1);
            }
        }
        $event_rules .= $event_rule . PHP_EOL;
        $event_prefixes .= $info['events'][$event_title]['prefix'] . PHP_EOL;

        $rule = $event_prefixes . $action_prefixes . "{\n" . $event_rules . "}\n=>\n{\n" . $action_rules . '}.';
        $rule = str_replace("\n\n", "\n", $rule);
        $rule = str_replace("\r", "", $rule);
        
        $rules_manager = new RuleManager();
        $rules_manager->createNewRule(
            $rule_title,
            $rule_description,
            $rule_place,
            $rule_creator,
            array($channel_action),
            array($action_title),
            array($channel_event),
            array($event_title),
            $rule
        );

        break; 

    default:
        # code...
        break;
}