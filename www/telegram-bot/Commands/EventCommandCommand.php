<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Ewetasker\Manager\UserManager;
use Longman\TelegramBot\Commands\UserCommand;
/**
 * User "/eventcommnad" command
 */
class EventCommandCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'eventCommand';
    protected $description = 'Send a command as a event';
    protected $usage = '/eventcommand <command>';
    protected $version = '1.0.0';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $text = trim($message->getText(true));
        include_once('../controllers/userManager.php');
        $user_manager = new UserManager();
        $url = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/controllers/eventsManager.php';
        $data = array(
            'user' => $user_manager->getUsernameByChatId((string) $chat_id),
            'inputEvent' => '@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>. @prefix ewe-telegram: <http://gsi.dit.upm.es/ontologies/ewe-telegram/ns/#>. @prefix string: <http://www.w3.org/2000/10/swap/string#>. @prefix ewe: <http://gsi.dit.upm.es/ontologies/ewe/ns/#>. ewe-telegram:Telegram rdf:type ewe-telegram:EventCommand. ewe-telegram:Telegram ewe:eventCommand "' . $text . '".'
        );
        unset($user_manager);

        $ch = curl_init($url);

        $postString = http_build_query($data, '', '&');

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);        
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_exec($ch);
        curl_close($ch);        
    }
}