<?php

require_once('ruleManager.php');
$config = [];
$rule_manager = new RuleManager($config);

$rule_title = htmlspecialchars($_POST['Rule-title']);
$rule_description = htmlspecialchars($_POST['Rule-description']);
$rule_place = htmlspecialchars($_POST['Rule-place']);
$author = htmlspecialchars($_POST['Author']);
$action_channel = htmlspecialchars($_POST['Action-channel']);
$action_title = htmlspecialchars($_POST['Action']);
$event_channel = htmlspecialchars($_POST['Event-channel']);
$event_title = htmlspecialchars($_POST['Event']);

$success = $rule_manager->createNewRule(
    $rule_title,
    $rule_description,
    $rule_place,
    $author,
    $action_channel,
    $action_title,
    $event_channel,
    $event_title
);

if (!$succes) {
    header('Location: ../index.php');
}

?>