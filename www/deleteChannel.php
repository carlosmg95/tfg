<?php

session_start();
require_once('controllers/channelManager.php');
$config = [];
$channel_manager = new ChannelManager($config);

$channel_title = htmlspecialchars($_GET['channelTitle']);

if (isset($_SESSION['user']) && $_SESSION['user'] === 'admin' && $channel_manager->deleteChannel($channel_title)) {
    header('Location: ../channels.php');
} else {
    header('Location: ../index.php');
}

?>