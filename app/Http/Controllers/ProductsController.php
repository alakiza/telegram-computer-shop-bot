<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use TelegramBot\Api\Types\ReplyKeyboardMarkup; // использование ReplyKeyboardMarkup (основное меню)
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup; // использование InlineKeyboardMarkup (кнопки под сообщением)

use App\Http\Controllers\BaseBotController;

class ProductsController extends BaseBotController
{
    public function processCmd($controller_config_path, $message) 
    {
        $self_config = config($controller_config_path);

        $cid = $message->getChat()->getId();
        $category = $message->getText();

        $answer = 'Выберите товар';

        $buttons = [];

        $users = DB::table('telegram_users')->where("user_id", "=", $cid)->get()->toArray();
        if (!empty($users)) {            
            $user_params = json_decode($users[0]->dialog_params, $associative=true);
            $categories = DB::table('categories')->where("category_name", "=", $category)->get()->toArray();
            if (!empty($categories)) {
                $products = DB::table('products')->where("category_id", "=", $categories[0]->id)->get()->toArray();
                foreach ($products as $product) {
                    array_push($buttons, [$product->name]);
                }

                $keyboard = $this->generateKeyboardFromButtons($controller_config_path, $buttons, $message);

                $this->bot->sendMessage($message->getChat()->getId(), $answer, 'HTML', true, null, $keyboard);

                $user_params["selectedCategory"] = $category;

                Log::info($category);
                DB::table('telegram_users')->where("user_id", "=", $cid)->update(["dialog_params" => json_encode($user_params)]);

                return true;
            } else {
                $categories = DB::table('categories')->where("category_name", "=", $user_params["selectedCategory"])->get()->toArray();
                if (!empty($categories)) {
                    $products = DB::table('products')->where("category_id", "=", $categories[0]->id)->get()->toArray();
                    foreach ($products as $product) {
                        array_push($buttons, [$product->name]);
                    }

                    $keyboard = $this->generateKeyboardFromButtons($controller_config_path, $buttons, $message);

                    $this->bot->sendMessage($message->getChat()->getId(), $answer, 'HTML', true, null, $keyboard);

                    DB::table('telegram_users')->where("user_id", "=", $cid)->update(["dialog_params" => json_encode($user_params)]);

                    return true;
                }
            }
        }

        return false;
    }
}
