<?php

session_start();

use Ewetasker\Manager\UserManager;
include_once('./userManager.php');

$username = $_SESSION['user'];
$telegram_id = $_POST['telegram-id'];
$user_manager = new UserManager();

if ($user_manager->setTelegramId($username, $telegram_id)) {
    header('Location: ../user.php');
} else {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}