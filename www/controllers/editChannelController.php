<?php

session_start();
require_once('channelManager.php');
$config = [];
$manager = new ChannelManager($config);

$old_title = htmlspecialchars($_POST["old-title"]);
$title = htmlspecialchars($_POST["title"]);
$description = htmlspecialchars($_POST["description"]);
$nicename = htmlspecialchars($_POST["nicename"]);

$events = array();
$actions = array();

foreach ($_POST as $key => $value) {
    if (fnmatch('event*', $key)) {
        $events[$key] = $value;
    } elseif (fnmatch('action*', $key)) {
        $actions[$key] = $value;
    }
}

if(empty($events) && empty($actions)) {
    header("Location: ../editchannel.php?error=neitherActionNorEvent");
} elseif ($manager->editChannel($old_title, $title, $description, $nicename, $events, $actions)) {
    header("Location: ../channels.php");
} else {
    header("Location: ../editchannel.php?error=channelExists");
}

?>