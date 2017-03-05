<?php

session_start();
require_once('./userManager.php');
$config = "ol";
$manager = new UserManager($config);

$username = $_POST["username"];
$password = $_POST["password"];

if($manager->createNewUser($username, $password)) {
    $_SESSION["user"] = $username;
    header("Location: ../user.php");
}

?>