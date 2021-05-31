<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use TelegramBot\Api\Types\ReplyKeyboardMarkup; // использование ReplyKeyboardMarkup (основное меню)
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup; // использование InlineKeyboardMarkup (кнопки под сообщением)

class BaseBotController
{
    protected $bot;

    function __construct($mainBot)
    {
        $this->bot = $mainBot;
    }

    public function generateKeyboard($controller_config_path, $message)
    {
        $self_config = config($controller_config_path);

        $commands = $self_config['commands'];
        $buttons = [];

        foreach ($commands as $cmd => $cmd_config) {
            array_push($buttons, [$cmd_config['text']]);
        }

        if (count(explode(".commands.", $controller_config_path)) > 2) {
            array_push($buttons, [config("telegram.goBack")]);
        }

        $keyboard = new ReplyKeyboardMarkup($buttons, false, true);

        return $keyboard;
    }

    public function generateKeyboardFromButtons($controller_config_path, $buttons, $message, $goBack=true)
    {
        $self_config = config($controller_config_path);

        // $commands = $self_config['commands'];
        // $buttons = [];

        // foreach ($commands as $cmd => $cmd_config) {
        //     array_push($buttons, [$cmd_config['text']]);
        // }

        if($goBack) {
            if (count(explode(".commands.", $controller_config_path)) > 2) {
                array_push($buttons, [config("telegram.goBack")]);
            }
        }

        $keyboard = new ReplyKeyboardMarkup($buttons, false, true);

        return $keyboard;
    }

    public function processCmd($controller_config_path, $message) 
    {
        
    }
}
