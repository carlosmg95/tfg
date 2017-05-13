<?php

session_start();

use Ewetasker\Manager\ChannelManager;
include_once('channelManager.php');

$manager = new ChannelManager();

$title = htmlspecialchars($_POST['title']);
$description = htmlspecialchars($_POST['description']);
$nicename = htmlspecialchars($_POST['nicename']);

$file = 'image';
$dest = '../img/';

if (is_valid($file) && dir_exists($dest)) {
    $moved_file = $dest . $_FILES[$file]['name'];
    if (file_exists($moved_file)) {
        header('Location: ../newchannel.php?error=fileExists');
        return;
    } else {
        move_file($_FILES[$file]['tmp_name'], $moved_file);
    }
} else if (!empty($_FILES[$file]['name']) && !is_valid($file)) {
    header('Location: ../newchannel.php?error=wrongFile');
    return;
} else {
    $moved_file = $dest . 'channel.png';
}

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
    header('Location: ../newchannel.php?error=neitherActionNorEvent');
} elseif ($manager->createNewChannel($title, $description, $nicename, $moved_file, $events, $actions)) {
    header('Location: ../channels.php');
} else {
    header('Location: ../newchannel.php?error=channelExists');
}


// Functions

function dir_exists($destination) {
    return file_exists($destination) && is_dir($destination);
}

function move_file($source, $destination) {
    move_uploaded_file($source, $destination);
}

function is_valid($file) {
    $extValid = array('gif', 'jpeg', 'jpg', 'png');
    $temp = explode('.', $_FILES[$file]['name']);
    $extension = end($temp);
    $type = $_FILES[$file]['type'];
    $validTypes = array('image/jpeg', 'image/jpg', 'image/pjpeg', 'image/x-png', 'image/png');
    $maxSize = 100000;

    return in_array($extension, $extValid) && in_array($type, $validTypes) && $_FILES[$file]['size'] < $maxSize;
}