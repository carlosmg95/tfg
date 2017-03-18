<?php

session_start();
require_once('controllers/userManager.php');
$config = [];
$user_manager = new UserManager($config);

$rule_title = $_GET['ruleTitle'];

$user_manager->removeRule($rule_title, $_SESSION['user']);
header('Location: ' . $_SERVER["HTTP_REFERER"]);

?>