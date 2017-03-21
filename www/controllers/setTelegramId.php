<?php

session_start();

$username = $_SESSION['user'];
$telegram_id = $_POST['telegram-id'];

var_dump($username);
var_dump($telegram_id);