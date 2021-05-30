<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use TelegramBot\Api\Client; // подключение библиотеки Telegram API
use TelegramBot\Api\Types\ReplyKeyboardMarkup; // использование ReplyKeyboardMarkup (основное меню)
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup; // использование InlineKeyboardMarkup (кнопки под сообщением)

use App\Http\Controllers\StartupController;

class BotController extends Controller
{
    private $bot = null;
    private $controllers = null;

    public function __construct()
    {
        $this->bot = new \TelegramBot\Api\Client(config('telegram.token'));

        $controllersClass = config('telegram.controllers');
        foreach($controllersClass as $controllerClass)
        {
            $controller = new $controllerClass($this->bot);
            
            $this->controllers[$controllerClass] = $controller;
        }
    }

    function executeCommand($command_config_path, $message)
    {
        Log::info($command_config_path);
        $controller_config_path = $command_config_path.".controller";

        $controllerClass = config($controller_config_path);

        $this->controllers[$controllerClass]->processCmd($command_config_path, $message);

        return;
    }

    function generateConfigPathByDialogPath(Array $dialog_path)
    {
        $config_path = "telegram";
        foreach($dialog_path as $cmd) {
            $config_path = $config_path.".commands.".$cmd;
        }
        return $config_path;
    }

    function sendSorry($chatId) {
        $answer = "Доступ запрещён.\nВас нет в списке пользователей.\nОбратитесь к администратору.";

        $this->bot->sendMessage($chatId, $answer, 'HTML', true, null, null);
    }

    function contextSwitcher($Update) {
        $message = $Update->getMessage();
        if ($message !== null) {
            $mtext = $message->getText();
            $cid = $message->getChat()->getId();

            $users = DB::table('telegram_users')->where("user_id", "=", $cid)->get()->toArray();

            $dialog_path = [];

            if (empty($users)) {
                $this->sendSorry($cid);
                return;
            } else {
                $user_dialog_path = $users[0]->dialog_path;
                if (empty($user_dialog_path))
                {
                    $dialog_path = [];
                } else {
                    $dialog_path = explode(".", $user_dialog_path);
                }
            }

            $config_path = $this->generateConfigPathByDialogPath($dialog_path);
            
            $context_commands = config($config_path.".commands");
            foreach($context_commands as $command_name => $command_config) {
                if (mb_stripos($mtext, $command_config['text']) !== false) {
                    $this->executeCommand($config_path.".commands.".$command_name, $message);

                    if (! $command_config['is_stub']) {
                        array_push($dialog_path, $command_name);

                        DB::table('telegram_users')->where("user_id", "=", $cid)->update(["dialog_path" => implode(".", $dialog_path)]);
                    }
                    return;
                }
            }
            if (mb_stripos($mtext, config("telegram.goBack")) !== false) {

                if (count($dialog_path) > 1) {
                    unset($dialog_path[array_key_last($dialog_path)]);

                    $config_path = $this->generateConfigPathByDialogPath($dialog_path);   
                    $this->executeCommand($config_path, $message);

                    DB::table('telegram_users')->where("user_id", "=", $cid)->update(["dialog_path" => implode(".", $dialog_path)]); 
                };
            }
        }
        
    }

    public function index()
    {
        $this->bot->on(\Closure::fromCallable([$this, 'contextSwitcher']), 
        function ($message) {
            return true;
        });

        if (!empty($this->bot->getRawBody())) {
            try {
                $this->bot->run();
            } catch (\Exception $e) {
                Log::info($e);
            }
        }
    }
}
