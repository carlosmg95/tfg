<?php

session_start();

use Ewetasker\Manager\UserManager;
include_once('controllers/userManager.php');

$user_manager = new UserManager();

$rule_title = $_GET['ruleTitle'];

$user_manager->removeRule($rule_title, $_SESSION['user']);
header('Location: ' . $_SERVER['HTTP_REFERER']);