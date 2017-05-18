<?php

header('Content-Type: application/json');

use Ewetasker\Manager\AdministrationManager;
use Ewetasker\Manager\RuleManager;
use Ewetasker\Manager\UserManager;
use Ewetasker\Performer\ChromecastPerformer;
use Ewetasker\Performer\TelegramPerformer;

include_once('administrationManager.php');
include_once('ruleManager.php');
include_once('../performers/chromecastPerformer.php');
include_once('../performers/telegramPerformer.php');
include_once('userManager.php');

$admin_manager = new AdministrationManager();
$rule_manager = new RuleManager();
$user_manager = new UserManager();

$input_event = $_POST['inputEvent'];
$input_event = preg_replace("/\.(\s+)/", ".\n", $input_event);
$user = $_POST['user'];

$admin_manager->userRuns($user);
unset($admin_manager);

$imported_rules = $user_manager->getImportedRules('username', $user);
$rules = '';
foreach ($imported_rules as $rule_title) {
    $rule = $rule_manager->getRule($rule_title);
    $rules .= $rule['rule'] . "\n";
}

$rules = isset($_POST['rules']) ? $_POST['rules'] : $rules;

$response = evaluateEvent($input_event, $rules);

//echo $response . PHP_EOL . PHP_EOL . PHP_EOL;

$responseJSON = parseResponse($input_event, $response, $user);

echo json_encode($responseJSON);

function actionTrigger($channel, $action, $parameter, $user)
{
    switch ($channel) {
        case 'Telegram':
            $telegram = new TelegramPerformer();
            switch ($action) {
                case 'SendMessage':
                    $telegram->sendMessage($parameter, $user);
                    break;
                
                default:
                    # code...
                    break;
            }
            unset($telegram);
            break;

        case 'Chromecast':
            $chromecast = new ChromecastPerformer();
            switch ($action) {
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

function parseResponse($input, $response, $user){
    
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
        $parameters[$channel] = $parameter;
    }
    foreach ($lines_with_actions as $line) {
        $response = preg_split("/[\s,]+/", trim($line));
        $channel = str_replace(':', '', strstr($response[0], ':'));
        $action['channel'] = preg_replace('/\d+$/', '', $channel);
        $action['action'] = str_replace([':', '.'], '', strstr($response[2], ':'));
        $action['parameter'] = '';
        if (array_key_exists($channel, $parameters)) {
            $action['parameter'] = $parameters[$channel];
        }
        array_push($actionsJson['actions'], $action);
        $admin_manager = new AdministrationManager();
        $admin_manager->runAction($action['channel'], $action['action']);
        actionTrigger($action['channel'], $action['action'], $action['parameter'], $user);
        unset($admin_manager);
    }

    return $actionsJson;
}