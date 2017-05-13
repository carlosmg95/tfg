<?php

session_start();

use Ewetasker\Manager\UserManager;
include_once('userManager.php');

$manager = new UserManager();

$username = strtolower(htmlspecialchars($_POST['username']));
$password = $_POST['password'];

if(!empty($username) && !is_null($password) && $manager->createNewUser($username, $password)) {
    $_SESSION['user'] = $username;
    header('Location: ../user.php');
} else {
    header('Location: ../user.php?error=userExists');
}

?>