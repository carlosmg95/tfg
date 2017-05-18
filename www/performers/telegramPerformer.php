<?php

namespace Ewetasker\Performer;

use Ewetasker\Manager\UserManager;

include_once('userManager.php');

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
        $url = 'http://localhost:8888/telegram-bot/hook.php';
        $data = array(
            'update_id' => 273066490,
            'message' => array(
                'message_id' => 1439,
                'from' => array(
                    'id' => $user['chat_id'],
                    'first_name' => 'Carlos',
                    'username' => 'carlosmg95',
                    'language_code' => 'en-US'
                ),
                'chat' => array(
                    'id' => $user['chat_id'],
                    'first_name' => 'Carlos',
                    'username' => 'carlosmg95',
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
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array('Content-type: application/json'));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        curl_exec($curl);
        curl_close($curl);
    }
}