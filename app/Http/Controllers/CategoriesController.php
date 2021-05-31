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
        $max_items = config("telegram.itemsOnPage");

        $pageUp = config("telegram.pageUp");
        $pageDown = config("telegram.pageDown");

        $cid = $message->getChat()->getId();
        $msg = $message->getText();

        $categories = DB::table('categories')->get()->toArray();
        
        $users = DB::table('telegram_users')->where("user_id", "=", $cid)->get()->toArray();
        
        if (!empty($users)) {   
            $answer = 'Выберите интересующую категорию';
            $user_params = json_decode($users[0]->dialog_params, $associative=true);

            $category_page = 0;
            if (isset($user_params["category_page"])) {
                $category_page = $user_params["category_page"];
            }

            if (mb_stripos($msg, $pageUp) !== false) {
                $category_page -= 1;
            }

            if (mb_stripos($msg, $pageDown) !== false) {
                $category_page += 1;
            }

            if ($category_page < 0) {
                $category_page = 0;
            }
            
            if ($category_page * $max_items > count($categories)) {
                $category_page -= 1;
            }

            $user_params["category_page"] = $category_page;

            DB::table('telegram_users')->where("user_id", "=", $cid)->update(["dialog_params" => json_encode($user_params)]);

            $buttons = [];

            if(count($categories) > $max_items) {
                $from_item =  $category_page * $max_items;

                if($from_item > 0 && $from_item < count($categories) - $max_items) {
                    
                    array_push($buttons, [$pageUp]);

                    $category_show = array_slice($categories, $from_item, $max_items);
                    foreach ($category_show as $category) {
                        array_push($buttons, [$category->category_name]);
                    }

                    array_push($buttons, [$pageDown]);

                } else if($from_item < count($categories) - $max_items) {

                    $category_show = array_slice($categories, $from_item, $max_items);
                    foreach ($category_show as $category) {
                        array_push($buttons, [$category->category_name]);
                    }

                    array_push($buttons, [$pageDown]);

                } else {

                    array_push($buttons, [$pageUp]);

                    $category_show = array_slice($categories, $from_item, $max_items);
                    foreach ($category_show as $category) {
                        array_push($buttons, [$category->category_name]);
                    }

                }
            } else {
                foreach ($categories as $category) {
                    array_push($buttons, [$category->category_name]);
                }
            }
            

            $keyboard = $this->generateKeyboardFromButtons($controller_config_path, $buttons, $message);

            $this->bot->sendMessage($message->getChat()->getId(), $answer, 'HTML', true, null, $keyboard);

            return true;
        }

        return false;
    }
}
