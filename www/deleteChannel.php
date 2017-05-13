<?php

session_start();

use Ewetasker\Manager\ChannelManager;
include_once('controllers/channelManager.php');

$channel_manager = new ChannelManager();

$channel_title = htmlspecialchars($_GET['channelTitle']);

if (isset($_SESSION['user']) && $_SESSION['user'] === 'admin' && $channel_manager->deleteChannel($channel_title)) {
    header('Location: ../channels.php');
} else {
    header('Location: ../index.php');
}