<?php

use Ewetasker\Manager\ChannelManager;
use Ewetasker\Manager\RuleManager;
use Ewetasker\Manager\UserManager;
include_once('channelManager.php');
include_once('ruleManager.php');
include_once('userManager.php');

$channel_manager = new ChannelManager();
$rule_manager = new RuleManager();
$user_manager = new UserManager();

$rule_title = htmlspecialchars($_POST['Rule-title']);
$rule_description = htmlspecialchars($_POST['Rule-description']);
$rule_place = htmlspecialchars($_POST['Rule-place']);
$new_place = $_POST['New-place'];
$author = $_POST['Author'];
$action_channels = $_POST['Action-channels'];
$action_titles = $_POST['Actions'];
$actions_parameters = isset($_POST['Parameters-actions']) ? $_POST['Parameters-actions'] : [];
$event_channels = $_POST['Event-channels'];
$event_titles = $_POST['Events'];
$events_parameters = isset($_POST['Parameters-events']) ? $_POST['Parameters-events'] : [];

$i = 0;
$action_rules = '';
$action_prefixes = '';
foreach ($action_channels as $channel_title) {
    $info = $channel_manager->getRulesAndPrefix($channel_title);
    $action_rule = $info['actions'][$action_titles[$i]]['rule'];
    if ($actions_parameters[$i] !== []) {
        $action_rule = preg_replace('/\s/', $channel_manager->getN($channel_title) . ' ', $action_rule, 1);
        foreach ($actions_parameters[$i] as $parameter) {
            $action_rule = preg_replace('/(#+\w+#)/', '"' . $parameter . '"', $action_rule, 1);
        }
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
    preg_match_all('/\?\w+/', $event_rule, $variables);
    $variables = array_unique($variables[0]);
    foreach ($variables as $variable) {
        $event_rule = preg_replace('/\\' . $variable . '/', $variable . $i, $event_rule);
    }
    if ($events_parameters[$i] !== '') {
        foreach ($events_parameters[$i] as $parameter) {
            $event_rule = preg_replace('/(#+\w+#)/', '"' . $parameter . '"', $event_rule, 1);
        }
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

if ((bool) $new_place) {
    $action_channels = ['twitter'];
    $action_titles = ['Post a tweet'];
    $event_channels = ['presence'];
    $event_titles = ['Presence Detected At Distance Less Than'];
    $rule = "@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .\n@prefix string: <http://www.w3.org/2000/10/swap/string#>.\n@prefix math: <http://www.w3.org/2000/10/swap/math#>.\n@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .\n@prefix ewe: <http://gsi.dit.upm.es/ontologies/ewe/ns/#> .\n@prefix ewe-presence: <http://gsi.dit.upm.es/ontologies/ewe-connected-home-presence/ns/#> .@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .\n@prefix ewe-twitter: <http://gsi.dit.upm.es/ontologies/ewe-twitter/ns/#> .\n@prefix ov: <http://vocab.org/open/#> .\n{\n\t?event rdf:type ewe-presence:PresenceDetectedAtDistance.\n\t?event ewe:sensorID ?sensorID.\n\t?sensorID string:equalIgnoringCase \"" . $new_place . "\".\n\t?event!ewe:distance math:lessThan \"3\".\n}\n=>\n{\n\tewe-twitter:Twitter" . $channel_manager->getN($action_channels[0]) . " rdf:type ewe-twitter:PostTweet;\nov:message \"You are in " . $rule_place . "\".\n}.";

    $rule_manager->createNewRule(
        'Import rules ' . $rule_place,
        'ADMIN RULE',
        $rule_place,
        'admin',
        $action_channels,
        $action_titles,
        $event_channels,
        $event_titles,
        $rule
    );
    $user_manager->importRuleToAll('Import rules ' . $rule_place);
}

if (!$success) {
    header('Location: ../index.php');
}