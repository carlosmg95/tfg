<?php

namespace Ewetasker\Performer;

header('Content-Type: application/json');

use Abraham\TwitterOAuth\TwitterOAuth;
use Ewetasker\Manager\UserManager;

include_once('../controllers/userManager.php');
require('../vendor/twitteroauth/autoload.php');

/**
* 
*/
class TwitterPerformer
{
    private $twitter_perfomer;
    
    function __construct()
    {
        return $this->twitter_perfomer;
    }

    function postTweet($message, $username)
    {
        $user_manager = new UserManager();

        $user = $user_manager->getUser($username);

        // The TwitterOAuth instance
        $connection = new TwitterOAuth('pxMR6sbn6wQPjU406yBqO8zdC', '5LCBELROUBJCn1gFNM3xKd4HTnyIRWdgr9LhLljhbrgYr0gdxA', $user['twitteraccesstoken'], $user['twittersecrettoken']);

        $statues = $connection->post('statuses/update', ['status' => $message]);

        unset($user_manager);
    }
}