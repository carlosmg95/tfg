<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Ewetasker\Manager\UserManager;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
/**
 * User "/getimportedrules" command
 */
class GetImportedRulesCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'getImportedRules';
    protected $description = 'A command that show your imported rules';
    protected $usage = '/getimportedrules';
    protected $version = '1.0.0';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $rules_list = $this->getImportedRules($chat_id);
        $text = '';
        foreach ($rules_list as $value) {
            $text .= $value . PHP_EOL;
        }
        $text = $text ? $text : 'You don\'t have imported rules.';

        $data = [
            'chat_id' => $chat_id,
            'text' => $text,
        ];

        // Send the imported rules.
        return Request::sendMessage($data);
    }

    private function getImportedRules($chat_id)
    {
        include_once('../controllers/userManager.php');
        $user_manager = new UserManager([]);
        $rules_list = $user_manager->getImportedRules('chat_id', (string) $chat_id);

        return $rules_list;
    }
}