<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Ewetasker\Manager\RuleManager;
use Ewetasker\Manager\UserManager;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ForceReply;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Entities\Keyboard;
/**
 * User "/importrules" command
 */
class ImportRulesCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'importRules';
    protected $description = 'A command that allow import rules';
    protected $usage = '/importrules';
    protected $version = '0.5.0';
    /**#@-*/

    private function createKeyboard($items, $layout)
    {
        $keyboard = array();
        $line_index = 0;

        foreach ($items as $item) {
            if(empty($keyboard[$line_index])) {
                $keyboard[$line_index] = [];
            }
            array_push($keyboard[$line_index], $item);
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
        $rules_list = $this->getNoImportedRules($chat_id);

        if (empty($rules_list)) {
            $data = [
                'chat_id' => $chat_id,
                'text' => 'There aren\'t rules to import.'
            ];
        } else {
            $keyboard = $this->createKeyboard($rules_list, 2);
            $keyboard = new Keyboard(...$keyboard);
            $keyboard = $keyboard->setResizeKeyboard(true)->setOneTimeKeyboard(false)->setSelective(false);

            $data = [
                'chat_id' => $chat_id,
                'text' => 'Choose a rule.',
                'reply_markup' => $keyboard
            ];
        }
        
        // Send the imported rules.
        return Request::sendMessage($data);
    }

    private function getNoImportedRules($chat_id)
    {
        include_once('../controllers/ruleManager.php');
        include_once('../controllers/userManager.php');
        $user_manager = new UserManager([]);
        $rules_manager = new RuleManager([]);
        $rules_list = $rules_manager->getRulesList();
        $no_rules_list = array();
        $username = $user_manager->getUsernameByChatId((string) $chat_id);

        foreach ($rules_list as $rule) {
            if (!$user_manager->ruleImported($rule, $username)) {
                array_push($no_rules_list, $rule);
            }
        }

        return $no_rules_list;
    }
}