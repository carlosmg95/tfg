<?php

require('twitteroauth/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

session_start();


// The TwitterOAuth instance
$connection = new TwitterOAuth('pxMR6sbn6wQPjU406yBqO8zdC', '5LCBELROUBJCn1gFNM3xKd4HTnyIRWdgr9LhLljhbrgYr0gdxA');
$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/twitterback.php'));
//callback is set to where the rest of the script is

//TAKING THE OAUTH TOKEN AND THE TOKEN SECRET AND PUTTING THEM IN COOKIES (NEEDED IN THE NEXT SCRIPT)
$oauth_token=$request_token['oauth_token'];
$token_secret=$request_token['oauth_token_secret'];
setcookie('token_secret', ' ', time()-3600);
setcookie('token_secret', $token_secret, time()+60*10);
setcookie('oauth_token', ' ', time()-3600);
setcookie('oauth_token', $oauth_token, time()+60*10);

//GETTING THE URL FOR ASKING TWITTER TO AUTHORIZE THE APP WITH THE OAUTH TOKEN
$url = $connection->url('oauth/authorize', array('oauth_token' => $oauth_token));

//REDIRECTING TO THE URL
header('Location: ' . $url);
