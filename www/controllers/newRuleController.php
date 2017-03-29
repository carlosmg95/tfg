<?php

use Ewetasker\Manager\ChannelManager;
use Ewetasker\Manager\RuleManager;
include_once('channelManager.php');
include_once('ruleManager.php');

$config = [];
$channel_manager = new ChannelManager($config);
$rule_manager = new RuleManager($config);

$rule_title = htmlspecialchars($_POST['Rule-title']);
$rule_description = htmlspecialchars($_POST['Rule-description']);
$rule_place = htmlspecialchars($_POST['Rule-place']);
$author = $_POST['Author'];
$action_channels = $_POST['Action-channels'];
$action_titles = $_POST['Actions'];
$actions_parameters = $_POST['Parameters-actions'];
$event_channels = $_POST['Event-channels'];
$event_titles = $_POST['Events'];
$events_parameters = $_POST['Parameters-events'];

$i = 0;
$action_rules = '';
$action_prefixes = '';
foreach ($action_channels as $channel_title) {
    $info = $channel_manager->getRulesAndPrefix($channel_title);
    $action_rule = $info['actions'][$action_titles[$i]]['rule'];
    if ($actions_parameters[$i] !== '') {
        $action_rule = preg_replace('/(#+\w+#)/', '"' . $actions_parameters[$i] . '"', $action_rule);
    }
    $action_rules .= $action_rule . PHP_EOL;
    $action_prefixes .= $info['actions'][$action_titles[$i]]['prefix'] . PHP_EOL;
    $i++;
}

$i = 0;
$event_rules = '';
$event_prefixes = '';
foreach ($event_channels as $channel_title) {
    $info = $channel_manager->getRulesAndPrefix($channel_title);
    $event_rule = $info['events'][$event_titles[$i]]['rule'];
    if ($events_parameters[$i] !== '') {
        $event_rule = preg_replace('/(#+\w+#)/', '"' . $events_parameters[$i] . '"', $event_rule);
    }
    $event_rules .= $event_rule . PHP_EOL;
    $event_prefixes .= $info['events'][$event_titles[$i]]['prefix']. PHP_EOL;
    $i++;
}

$rule = $event_prefixes . $action_prefixes . "{\n" . $event_rules . "}\n=>\n{\n" . $action_rules . '}.';
$rule = str_replace("\n\n", "\n", $rule);
$rule = str_replace("\r", "", $rule);

$success = $rule_manager->createNewRule(
    $rule_title,
    $rule_description,
    $rule_place,
    $author,
    $action_channels,
    $action_titles,
    $event_channels,
    $event_titles,
    $rule
);

if (!$succes) {
    header('Location: ../index.php');
}

?>