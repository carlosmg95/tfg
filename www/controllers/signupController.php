<?php

session_start();
require_once('userManager.php');
$config = [];
$manager = new UserManager($config);

$username = strtolower(htmlspecialchars($_POST["username"]));
$password = $_POST["password"];

if(!empty($username) && !is_null($password) && $manager->createNewUser($username, $password)) {
    $_SESSION["user"] = $username;
    header("Location: ../user.php");
} else {
    header("Location: ../user.php?error=userExists");
}

?>