<?php

session_start();
require_once('controllers/channelManager.php');
$config = [];
$manager = new ChannelManager($config);

$channelTitle = htmlspecialchars($_GET["channelTitle"]);

if (isset($_SESSION["user"]) && $_SESSION["user"] === "admin" && $manager->removeChannelByTitle($channelTitle)) {
    header("Location: ../channels.php");
} else {
    header("Location: ../index.php");
}

?>