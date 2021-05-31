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
        $max_items = config("telegram.itemsOnPage");

        $pageUp = config("telegram.pageUp");
        $pageDown = config("telegram.pageDown");

        $cid = $message->getChat()->getId();
        $category = $message->getText();
        $msg = $message->getText();

        $answer = 'Выберите товар';

        $buttons = [];

        $users = DB::table('telegram_users')->where("user_id", "=", $cid)->get()->toArray();
        if (!empty($users)) {            
            $user_params = json_decode($users[0]->dialog_params, $associative=true);

            if (mb_stripos($msg, config("telegram.goBack")) !== false) {
                $category =  $user_params["selected_category"];
            }

            if (mb_stripos($msg, $pageUp) !== false) {
                $category =  $user_params["selected_category"];
            }

            if (mb_stripos($msg, $pageDown) !== false) {
                $category =  $user_params["selected_category"];
            }

            $categories = DB::table('categories')->where("category_name", "=", $category)->get()->toArray();
            if (empty($categories)) {
                $categories = DB::table('categories')->where("category_name", "=", $user_params["selected_category"])->get()->toArray();
            }
            if (!empty($categories)) {
                $products = DB::table('products')->where("category_id", "=", $categories[0]->id)->get()->toArray();

                $product_page = 0;
                if (isset($user_params["product_page"])) {
                    $product_page = $user_params["product_page"];
                }

                if (mb_stripos($msg, $pageUp) !== false) {
                    $product_page -= 1;
                }

                if (mb_stripos($msg, $pageDown) !== false) {
                    $product_page += 1;
                }

                if ($product_page < 0) {
                    $product_page = 0;
                }
                
                if ($product_page * $max_items > count($products)) {
                    $product_page -= 1;
                }

                $user_params["product_page"] = $product_page;

                DB::table('telegram_users')->where("user_id", "=", $cid)->update(["dialog_params" => json_encode($user_params)]);

                $buttons = [];

                Log::info($product_page);
                Log::info($max_items);
                Log::info(count($products));
                if(count($products) > $max_items) {
                    $from_item =  $product_page * $max_items;

                    if($from_item > 0 && $from_item < count($products) - $max_items) {
                        
                        array_push($buttons, [$pageUp]);

                        $product_show = array_slice($products, $from_item, $max_items);
                        foreach ($product_show as $product) {
                            array_push($buttons, [$product->name]);
                        }

                        array_push($buttons, [$pageDown]);

                    } else if($from_item < count($products) - $max_items) {

                        $product_show = array_slice($products, $from_item, $max_items);
                        foreach ($product_show as $product) {
                            array_push($buttons, [$product->name]);
                        }

                        array_push($buttons, [$pageDown]);

                    } else {

                        array_push($buttons, [$pageUp]);

                        $product_show = array_slice($products, $from_item, $max_items);
                        foreach ($product_show as $product) {
                            array_push($buttons, [$product->name]);
                        }

                    }
                } else {
                    foreach ($products as $product) {
                        array_push($buttons, [$product->name]);
                    }
                }

                $keyboard = $this->generateKeyboardFromButtons($controller_config_path, $buttons, $message);

                $this->bot->sendMessage($message->getChat()->getId(), $answer, 'HTML', true, null, $keyboard);

                $user_params["selected_category"] = $category;

                Log::info($category);
                DB::table('telegram_users')->where("user_id", "=", $cid)->update(["dialog_params" => json_encode($user_params)]);

                return true;
            }
            //  else {
               
            //     if (!empty($categories)) {
            //         $products = DB::table('products')->where("category_id", "=", $categories[0]->id)->get()->toArray();
            //         foreach ($products as $product) {
            //             array_push($buttons, [$product->name]);
            //         }

            //         $keyboard = $this->generateKeyboardFromButtons($controller_config_path, $buttons, $message);

            //         $this->bot->sendMessage($message->getChat()->getId(), $answer, 'HTML', true, null, $keyboard);

            //         DB::table('telegram_users')->where("user_id", "=", $cid)->update(["dialog_params" => json_encode($user_params)]);

            //         return true;
            //     }
            // }
        }

        return false;
    }
}
