<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use Ewetasker\Manager\UserManager;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;

/**
 * Callback query command
 */
class CallbackqueryCommand extends SystemCommand
{
    protected $name = 'callbackquery';
    protected $description = 'Reply to callback query';
    protected $version = '1.1.0';

    public function execute()
    {
        $update            = $this->getUpdate();
        $callback_query    = $update->getCallbackQuery();
        $callback_query_id = $callback_query->getId();
        $callback_data     = $callback_query->getData();
        $rule_title = explode('<-->', $callback_data)[0];
        $chat_id = explode('<-->', $callback_data)[1];
        $action = explode('<-->', $callback_data)[2];

        $text = 'error';
        if ($action === 'import') {
            $text = 'Rule previously imported.';
            if ($this->importRule($chat_id, $rule_title)) {
                $text = 'Rule imported.';
            }
        } elseif ($action === 'remove') {
            $text = 'You don\'t have that rule imported.';
            if ($this->removeRule($chat_id, $rule_title)) {
                $text = 'Rule remove.';
            }
        }

        

        $data = [
            'callback_query_id' => $callback_query_id,
            'text'              => $text,
            'show_alert'        => $callback_data === 'thumb up',
            'cache_time'        => 5,
        ];

        return Request::answerCallbackQuery($data);
    }

    private function importRule($chat_id, $rule_title)
    {
        include_once('../controllers/userManager.php');
        $user_manager = new UserManager([]);
        $username = $user_manager->getUsernameByChatId($chat_id);
        return $user_manager->importRule($rule_title, $username);
    }

    private function removeRule($chat_id, $rule_title)
    {
        include_once('../controllers/userManager.php');
        $user_manager = new UserManager([]);
        $username = $user_manager->getUsernameByChatId($chat_id);
        return $user_manager->removeRule($rule_title, $username);
    }
}