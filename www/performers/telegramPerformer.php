<?php

namespace Ewetasker\Performer;

use Ewetasker\Manager\UserManager;

include_once('../controllers/userManager.php');

/**
* 
*/
class TelegramPerformer
{
    private $telegram_perfomer;
    
    function __construct()
    {
        return $this->telegram_perfomer;
    }

    function sendMessage($message, $user)
    {
        $user_manager = new UserManager();
        $user = $user_manager->getUser($user);
        $url = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/telegram-bot/hook.php';
        $data = array(
            'update_id' => 273066490,
            'message' => array(
                'message_id' => 1439,
                'from' => array(
                    'id' => $user['chat_id'],
                    'first_name' => $user['username'],
                    'username' => $user['username'],
                    'language_code' => 'en-US'
                ),
                'chat' => array(
                    'id' => $user['chat_id'],
                    'first_name' => $user['username'],
                    'username' => $user['username'],
                    'type' => 'private'
                ),
                'date' => date('U'),
                'text' => '/sendmessage ' . $message,
                'entities' => [
                    array(
                        'type' => 'bot_command',
                        'offset' => 0,
                        'length' => 11
                    )
                ]
            )
        );
        
        $content = json_encode($data);
        unset($user_manager);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array('Content-type: application/json'));
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        curl_exec($curl);
        curl_close($curl);
    }

    function importRules($place, $user)
    {
        $user_manager = new UserManager();
        $user = $user_manager->getUser($user);
        $url = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/telegram-bot/hook.php';
        $data = array(
            'update_id' => 273066490,
            'message' => array(
                'message_id' => 1439,
                'from' => array(
                    'id' => $user['chat_id'],
                    'first_name' => $user['username'],
                    'username' => $user['username'],
                    'language_code' => 'en-US'
                ),
                'chat' => array(
                    'id' => $user['chat_id'],
                    'first_name' => $user['username'],
                    'username' => $user['username'],
                    'type' => 'private'
                ),
                'date' => date('U'),
                'text' => '/importrules ' . $place,
                'entities' => [
                    array(
                        'type' => 'bot_command',
                        'offset' => 0,
                        'length' => 12
                    )
                ]
            )
        );
        
        $content = json_encode($data);
        unset($user_manager);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array('Content-type: application/json'));
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        curl_exec($curl);
        curl_close($curl);
    }
}