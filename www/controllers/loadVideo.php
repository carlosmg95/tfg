<?php

use Ewetasker\Manager\RuleManager;
include_once('ruleManager.php');

$rule_manager = new RuleManager();

$key = $_POST['key'];
$url_video = $_POST['url'];
$format = $_POST['format'];
$place = isset($_POST['place']) ? $_POST['place'] : '';

$urls = array();
if ((bool) $place) {
    $url = preg_replace('/\w+\.php/', '', $rule_manager->getURLPlace($place));
    $url .= 'addVideo.php';
    array_push($urls, $url);
} else {
    foreach ($rule_manager->getPlaces() as $place) {
        $url = preg_replace('/\w+\.php/', '', $rule_manager->getURLPlace($place));
        $url .= 'addVideo.php';
        array_push($urls, $url);
    }
}

$postString = 'key=' . $key . '&url=' . $url_video . '&format=' . $format;

foreach ($urls as $url) {
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_exec($ch);
    curl_close($ch);
}

header('Location: ../user.php');