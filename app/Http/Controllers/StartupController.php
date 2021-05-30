<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use TelegramBot\Api\Types\ReplyKeyboardMarkup; // использование ReplyKeyboardMarkup (основное меню)
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup; // использование InlineKeyboardMarkup (кнопки под сообщением)

use App\Http\Controllers\BaseBotController;

class StartupController extends BaseBotController
{
    public function processCmd($controller_config_path, $message) 
    {
        $self_config = config($controller_config_path);

        $cid = $message->getChat()->getId();
        $users = DB::table('telegram_users')->where("user_id", "=", $cid)->get()->toArray();
        if (! empty($users)) {
            $doctors = DB::table('doctors')->where("telegram_user", "=", $users[0]->id)->get()->toArray();
            if (! empty($doctors)) {
                $doctor = $doctors[0];
                $answer = 'Добро пожаловать, '.$doctor->surname." ".$doctor->name." ".$doctor->patronymic;

                $keyboard = $this->generateKeyboard($controller_config_path, $message);

                $this->bot->sendMessage($message->getChat()->getId(), $answer, 'HTML', true, null, $keyboard);
            }
        }

        return;
    }
}
