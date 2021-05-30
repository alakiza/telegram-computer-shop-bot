<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use TelegramBot\Api\Types\ReplyKeyboardMarkup; // использование ReplyKeyboardMarkup (основное меню)
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup; // использование InlineKeyboardMarkup (кнопки под сообщением)

use App\Http\Controllers\BaseBotController;

class GetPatientDataController extends BaseBotController
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
            $answer = "Ваши пациенты:\n\n";

            $patients = DB::table('patients')
                            ->join('chambers', 'chambers.id', '=', 'patients.chamber')
                            ->where('id_doctors', '=', $doctor->id)
                            ->select('patients.*', 'chambers.chamber_num')
                            ->get()->toArray();

            foreach($patients as $patient) {
                $answer = $answer.$patient->surname." ".$patient->name." ".$patient->patronymic."\n  Поступил ".$patient->receipt_date."\n  Палата ".$patient->chamber_num."\n\n";
            }

            $keyboard = $this->generateKeyboard($controller_config_path, $message);

            $this->bot->sendMessage($message->getChat()->getId(), $answer, 'HTML', true, null, $keyboard);
        }
    }
}
