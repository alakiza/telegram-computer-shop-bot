<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use TelegramBot\Api\Types\ReplyKeyboardMarkup; // использование ReplyKeyboardMarkup (основное меню)
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup; // использование InlineKeyboardMarkup (кнопки под сообщением)

use App\Http\Controllers\BaseBotController;

class CategoriesController extends BaseBotController
{
    public function processCmd($controller_config_path, $message) 
    {
        $self_config = config($controller_config_path);

        $cid = $message->getChat()->getId();

        $answer = 'Выберите интересующую категорию';

        $buttons = [];

        $categories = DB::table('categories')->get()->toArray();
        foreach ($categories as $category) {
            array_push($buttons, [$category->category_name]);
        }

        $keyboard = $this->generateKeyboardFromButtons($controller_config_path, $buttons, $message);

        $this->bot->sendMessage($message->getChat()->getId(), $answer, 'HTML', true, null, $keyboard);

        return;
    }
}
