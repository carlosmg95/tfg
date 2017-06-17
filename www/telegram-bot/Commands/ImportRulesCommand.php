<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Ewetasker\Manager\RuleManager;
use Ewetasker\Manager\UserManager;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Entities\InlineKeyboard;
/**
 * User "/importrules" command
 */
class ImportRulesCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'importRules';
    protected $description = 'Allow to import rules';
    protected $usage = '/importrules or /importrules <place>';
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
            $keyboard[$line_index][] = ['text' => $item, 'callback_data' => $item . '<-->' . $chat_id . '<-->import'];
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
        $place = trim($message->getText(true));
        $rules_list = $this->getNoImportedRules($chat_id, $place);

        if (empty($rules_list)) {
            $data = [
                'chat_id' => $chat_id,
                'text' => 'There aren\'t rules to import.'
            ];
        } else {
            $keyboard = $this->createKeyboard($rules_list, 1, $chat_id);
            $inline_keyboard = new InlineKeyboard(...$keyboard);

            $text = (bool) $place ? 'These rules are the availabe rules of ' . $place : 'Choose a rule';
            $text .= PHP_EOL . PHP_EOL . '⬇⬇⬇⬇⬇⬇⬇⬇⬇⬇⬇⬇⬇️';

            $data = [
                'chat_id' => $chat_id,
                'text' => $text,
                'reply_markup' => $inline_keyboard
            ];
        }
        
        // Send the imported rules.
        return Request::sendMessage($data);
    }

    private function getNoImportedRules($chat_id, $place)
    {
        include_once('../controllers/ruleManager.php');
        include_once('../controllers/userManager.php');
        $user_manager = new UserManager();
        $rules_manager = new RuleManager();
        $rules_list = $rules_manager->getRulesList();
        $no_rules_list = array();
        $username = $user_manager->getUsernameByChatId((string) $chat_id);

        foreach ($rules_list as $rule_title) {
            $rule = $rules_manager->getRule($rule_title);
            $description = $rule['description'];
            $rule_place = $rule['place'];
            if ((bool) $place) {
                if (!$user_manager->ruleImported($rule_title, $username) && $description !== 'ADMIN RULE' && $place === $rule_place) {
                    array_push($no_rules_list, $rule_title);
                }
            } else {
                if (!$user_manager->ruleImported($rule_title, $username) && $description !== 'ADMIN RULE') {
                    array_push($no_rules_list, $rule_title);
                }
            }
        }

        return $no_rules_list;
    }
}
