<?php

header('Content-Type: application/json');

use Ewetasker\Manager\RuleManager;
use Ewetasker\Manager\UserManager;

include_once('ruleManager.php');
include_once('userManager.php');

$rule_manager = new RuleManager([]);
$user_manager = new UserManager([]);

$input_event = '@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .
@prefix ewe-presence: <http://gsi.dit.upm.es/ontologies/ewe-connected-home-presence/ns/#> .
@prefix ewe: <http://gsi.dit.upm.es/ontologies/ewe/ns/#> .
@prefix ewe-presence: <http://gsi.dit.upm.es/ontologies/ewe-connected-home-presence/ns/#> .

ewe-presence:PresenceSensor rdf:type ewe-presence:PresenceDetectedAtDistance.
ewe-presence:PresenceSensor ewe:sensorID "1a2b3c".
ewe-presence:PresenceSensor ewe:distance 0.';// $_POST['inputEvent'];
$user = 'admin';//$_POST['user'];

$imported_rules = $user_manager->getImportedRules('username', $user);
$rules = '';
foreach ($imported_rules as $rule_title) {
    $rule = $rule_manager->getRule($rule_title);
    $rules .= $rule['rule'] . "\n";
}

$rules = '@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .
@prefix string: <http://www.w3.org/2000/10/swap/string#>.
@prefix math: <http://www.w3.org/2000/10/swap/math#>.
@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .
@prefix ewe: <http://gsi.dit.upm.es/ontologies/ewe/ns/#> .
@prefix ewe-presence: <http://gsi.dit.upm.es/ontologies/ewe-connected-home-presence/ns/#> .
@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .
@prefix ewe-wifi: <http://gsi.dit.upm.es/ontologies/ewe-wifi/ns/#> .
@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .
@prefix ewe-twitter: <http://gsi.dit.upm.es/ontologies/ewe-twitter/ns/#> .
@prefix ov: <http://vocab.org/open/#> .
{
?event rdf:type ewe-presence:PresenceDetectedAtDistance.
?event ewe:sensorID ?sensorID.
?sensorID string:equalIgnoringCase "1a2b3c".
?event!ewe:distance math:lessThan "2". 
}
=>
{
ewe-wifi:Wifi rdf:type ewe-wifi:ON .
ewe-twitter:Twitter rdf:type ewe-twitter:PostTweet;
ov:message "hola, muy buenas".
}.';

$response = evaluateEvent($input_event, $rules);

echo $response . PHP_EOL . PHP_EOL . PHP_EOL;

$responseJSON = parseResponse($input_event, $response);

echo json_encode($responseJSON) . PHP_EOL;

function deleteAllBetween($beginning, $end, $string)
{
    $beginning_pos = strpos($string, $beginning);
    $end_pos = strpos($string, $end);
    if ($beginning_pos === false || $end_pos === false) {
        return $string;
    }
    $text_to_delete = substr($string, $beginning_pos, $end_pos + strlen($end) - $beginning_pos);
    return str_replace($text_to_delete, '', $string);
}

function evaluateEvent($input, $rules)
{
        
    $data = array(
        'data' => array($rules, $input),
        'query' => '{ ?a ?b ?c. } => { ?a ?b ?c. }.'
    );

    $url = 'http://eye.restdesc.org/';

    $ch = curl_init($url);

    $postString = http_build_query($data, '', '&');

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

function deleteInputFromResponse($input, $response)
{
    foreach ($input as $input_line) {
        $input_line = trim(substr($input_line, 0, strlen($input_line) - 1));
        foreach ($response as $key => $response_line) {
            $response_line = trim(substr($response_line, 0, strlen($response_line) - 1));
            if ($input_line === $response_line) {
                unset($response[$key]);
            }
        }
    }
    return $response;
}

function parseResponse($input, $response){
    
    // REMOVE PREFIXES.
    while(strpos($response, 'PREFIX') !== false){
        $response = deleteAllBetween('PREFIX', '>', $response);
    }

    while(strpos($input, '@prefix') !== false){
        $input = deleteAllBetween('@prefix', '> .', $input);
        $input = deleteAllBetween('@prefix', '>.', $input);
    }

    // REMOVE COMMENTS.
    while(strpos($input, '#C') !== false){
        $input = deleteAllBetween('#C', 'C#', $input);
    }

    // CHANGE RDF:TYPE BY A
    $input = str_replace('rdf:type', 'a', $input);

    // REMOVE BLANK SPACES AND BREAKPOINTS
    $input = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $input);
    $response = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $response);

    // SPLIT IN SENTENCES
    $splittedInput = array_filter(explode("\n", trim($input)));
    $splittedResponse = array_filter(explode("\n", trim($response)));

    $splittedResponse = deleteInputFromResponse($splittedInput, $splittedResponse);

    $splittedResponse = array_values($splittedResponse);
    $splittedResponse = array_filter($splittedResponse);

    // SPLIT ACTIONS AND PARAMETERS.
    $lines_with_parameters = array();
    $lines_with_actions = array();
    foreach ($splittedResponse as $line) {
        if (strpos($line, 'ov:')) {
            array_push($lines_with_parameters, $line);
            continue;
        }
        array_push($lines_with_actions, $line);
    }

    // SPLIT ACTIONS.
    $actionsJson = array('success' => 1);
    $actionsJson['actions'] = array();
    $parameters = array();
    foreach ($lines_with_parameters as $line) {
        $response = preg_split("/[\s]+/", trim($line));
        $channel = str_replace(':', '', strstr($response[0], ':'));
        $parameter = '';
        for ($i = 2; $i < count($response); $i++) { 
            $parameter .= $response[$i] . ' ';  # It is neccesary if the parameter is a string with spaces.
        }
        $parameter = trim($parameter);
        $parameter = str_replace(array('".', '"'), '', strstr($parameter, '"'));
        if (!array_key_exists($channel, $parameters)) {
            $parameters[$channel] = array();
        }
        array_push($parameters[$channel], $parameter);
    }
    foreach ($lines_with_actions as $line) {
        $response = preg_split("/[\s,]+/", trim($line));
        $action['channel'] = str_replace(':', '', strstr($response[0], ':'));
        $action['action'] = str_replace([':', '.'], '', strstr($response[2], ':'));
        $action['parameter'] = '';
        if (array_key_exists($action['channel'], $parameters)) {
            foreach ($parameters[$action['channel']] as $parameter) {
                $action['parameter'] = $parameter;
                array_push($actionsJson['actions'], $action);
            }
            unset($parameters[$action['channel']]);
        } else {
            array_push($actionsJson['actions'], $action);
        }
    }

    return $actionsJson;
}