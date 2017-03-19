<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

//use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
/**
 * User "/image" command
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
        $text    = 'hola';

        $data = [
            'chat_id' => $chat_id,
            'text' => $text,
        ];

        //Return a random picture from the telegram->getUploadPath().
        return Request::sendMessage($data);
    }
}