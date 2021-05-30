<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use TelegramBot\Api\Types\ReplyKeyboardMarkup; // использование ReplyKeyboardMarkup (основное меню)
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup; // использование InlineKeyboardMarkup (кнопки под сообщением)

use App\Http\Controllers\BaseBotController;

class GetDoctorDataController extends BaseBotController
{
    public function processCmd($controller_config_path, $message) 
    {
        $self_config = config($controller_config_path);

        $cid = $message->getChat()->getId();

        $doctors = DB::table('doctors')
                       ->join('telegram_users','doctors.telegram_user', '=', 'telegram_users.id')
                       ->where('telegram_users.user_id', '=', $cid)
                       ->select('doctors.*', 'telegram_users.user_id')
                       ->get()->toArray();

        if (! empty($doctors)) {
            $doctor = $doctors[0];
            
            $answer = "$doctor->surname $doctor->name $doctor->patronymic\n";
            $answer = $answer."Специальность - $doctor->spetialization\n";
            $answer = $answer."telegram id - $doctor->user_id\n";

            $this->bot->sendMessage($message->getChat()->getId(), $answer, 'HTML');
        } else {
            $answer = "Не удалось найти данные о Вас";
            $this->bot->sendMessage($message->getChat()->getId(), $answer, 'HTML');
        }
    }
}
