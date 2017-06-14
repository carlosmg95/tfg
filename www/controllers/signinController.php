<?php

session_start();

use Ewetasker\Manager\UserManager;
include_once('userManager.php');

$manager = new UserManager();

$username = strtolower(htmlspecialchars($_POST['username']));
$password = hash('sha1', $_POST['password']);

if($manager->login($username, $password)) {
    $_SESSION['user'] = $username;
    header('Location: ../user.php');
} else {
    header('Location: ../user.php?error=userIncorrect');
}

?>