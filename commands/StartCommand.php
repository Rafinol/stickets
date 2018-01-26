<?php
/**
 * Created by PhpStorm.
 * User: Web2
 * Date: 26.01.2018
 * Time: 17:40
 */
use Longman\TelegramBot\Request;
class StartCommand extends \Longman\TelegramBot\Commands\SystemCommands\StartCommand
{

    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $user_id = $message->getFrom()->getId();

        return Request::sendMessage(["text"=>$chat_id]);
    }
}