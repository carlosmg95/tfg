<?php

require_once('ruleManager.php');
$config = [];
$rule_manager = new RuleManager($config);

$rule_title = htmlspecialchars($_POST['Rule-title']);
$rule_description = htmlspecialchars($_POST['Rule-description']);
$rule_place = htmlspecialchars($_POST['Rule-place']);
$author = $_POST['Author'];
$action_channels = $_POST['Action-channels'];
$action_titles = $_POST['Actions'];
$event_channels = $_POST['Event-channels'];
$event_titles = $_POST['Events'];

$success = $rule_manager->createNewRule(
    $rule_title,
    $rule_description,
    $rule_place,
    $author,
    $action_channels,
    $action_titles,
    $event_channels,
    $event_titles
);

if (!$succes) {
    header('Location: ../index.php');
}

?>