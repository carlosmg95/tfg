<?php

// MongoDB
$dbhost = $_ENV['MONGO_HOST'];
$dbname = $_ENV['MONGO_DB'];
$port = $_ENV['MONGO_PORT'];
$username = $_ENV['MONGO_USER'];
$password = $_ENV['MONGO_PASS'];

$config = array(
    'username' => $username,
    'password' => $password,
    'dbname'   => $dbname,
    'connection_string'=> sprintf('mongodb://%s:%d', $dbhost, $port)
);

?>