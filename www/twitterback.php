<?php

use Abraham\TwitterOAuth\TwitterOAuth;
use Ewetasker\Manager\UserManager;

require_once('./controllers/userManager.php');
require('vendor/twitteroauth/autoload.php');

    
session_start();

$user_manager = new UserManager();

// The TwitterOAuth instance
$connection = new TwitterOAuth('pxMR6sbn6wQPjU406yBqO8zdC', '5LCBELROUBJCn1gFNM3xKd4HTnyIRWdgr9LhLljhbrgYr0gdxA');

//GETTING ALL THE TOKEN NEEDED
$oauth_verifier = $_GET['oauth_verifier'];
$token_secret = $_COOKIE['token_secret'];
$oauth_token = $_COOKIE['oauth_token'];

//EXCHANGING THE TOKENS FOR OAUTH TOKEN AND TOKEN SECRET
$connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $token_secret);
$access_token = $connection->oauth('oauth/access_token', array('oauth_verifier' => $oauth_verifier));

$accessToken=$access_token['oauth_token'];
$secretToken=$access_token['oauth_token_secret'];

$user_manager->updateTwitter($_SESSION['user'], $accessToken, $secretToken);
//DISPLAY THE TOKENS
header('Location: ./user.php');