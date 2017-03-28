<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Ewetasker\Manager\UserManager;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Entities\InlineKeyboard;
/**
 * User "/removerules" command
 */
class RemoveRulesCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'removerules';
    protected $description = 'A command that allow remove rules';
    protected $usage = '/removerules';
    protected $version = '1.0.0';
    /**#@-*/

    private function createKeyboard($items, $layout, $chat_id)
    {
        $keyboard = [];
        $line_index = 0;

        foreach ($items as $item) {
            if(empty($keyboard[$line_index])) {
                $keyboard[$line_index] = [];
            }
            $keyboard[$line_index][] = ['text' => $item, 'callback_data' => $item . '<-->' . $chat_id . '<-->remove'];
            if (count($keyboard[$line_index]) === $layout) {
                $line_index++;
            }
        }

        return $keyboard;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $rules_list = $this->getImportedRules($chat_id);

        if (empty($rules_list)) {
            $data = [
                'chat_id' => $chat_id,
                'text' => 'You don\'t have imported rules.'
            ];
        } else {
            $keyboard = $this->createKeyboard($rules_list, 1, $chat_id);
            $inline_keyboard = new InlineKeyboard(...$keyboard);

            $data = [
                'chat_id' => $chat_id,
                'text' => 'Choose a rule to remove' . PHP_EOL . PHP_EOL . '⬇⬇⬇⬇⬇⬇⬇⬇⬇⬇⬇⬇⬇️',
                'reply_markup' => $inline_keyboard
            ];
        }
        
        // Send the imported rules.
        return Request::sendMessage($data);
    }

    private function getImportedRules($chat_id)
    {;
        include_once('../controllers/userManager.php');
        $user_manager = new UserManager([]);
        $imported_rules_list = $user_manager->getImportedRules('chat_id', (string) $chat_id);

        return $imported_rules_list;
    }
}