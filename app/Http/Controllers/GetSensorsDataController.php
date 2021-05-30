<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use TelegramBot\Api\Types\ReplyKeyboardMarkup; // использование ReplyKeyboardMarkup (основное меню)
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup; // использование InlineKeyboardMarkup (кнопки под сообщением)

use InfluxDB2\Client;
use InfluxDB2\Model\WritePrecision;
use InfluxDB2\Point;

use App\Http\Controllers\BaseBotController;

class GetSensorsDataController extends BaseBotController
{
    public function processCmd($controller_config_path, $message) 
    {
        $self_config = config($controller_config_path);

        $time=config('telegram.default_time_range');
        $measurement = config('telegram.influx_measurement');
        $fields = config('telegram.influx_fields');

        $token = '4VyKdmPUf51S612tQX5Eu08_BtQBbErh_2kzhHwVRQCWQ8fhnagzg-jU_s-qmwkbCQggHdswV_iO7DF0vVJVzw==';
        $org = 'Dizayka Org';
        $bucket = 'SensorsData';

        $client = new Client([
            "url" => "http://192.168.0.106:8086",
            "token" => $token,
        ]);

        $cid = $message->getChat()->getId();

        $doctors = DB::table('doctors')
                       ->join('telegram_users','doctors.telegram_user', '=', 'telegram_users.id')
                       ->where('telegram_users.user_id', '=', $cid)
                       ->select('doctors.*', 'telegram_users.user_id')
                       ->get()->toArray();

        if (! empty($doctors)) {

            $doctor = $doctors[0];

            $patients = DB::table('patients')
                           ->where('patients.id_doctors', '=', $doctor->id) 
                           ->join('sensors', 'patients.id', '=', 'sensors.id_patients')
                           ->select('patients.*', 'sensors.name as sensor_name', 'sensors.ip as sensor_ip')
                           ->get()->toArray();

            $answer = "Данные с датчиков:\n";

            foreach ($patients as $patient) {
                $answer = $answer."\n$patient->surname $patient->name $patient->patronymic\n";
                foreach($fields as $field => $field_config) {
                    $answer = $answer."  $field:\n";

                    $query = "from(bucket: \"{$bucket}\")
                    |> range(start: -$time"."s)
                    |> filter(fn: (r) => r[\"_measurement\"]  == \"$measurement\")
                    |> filter(fn: (r) => r[\"_field\"]        == \"$field\")
                    |> filter(fn: (r) => r[\"host\"] == \"$patient->sensor_ip\")";
        
                    $tables = $client->createQueryApi()->query($query, $org);

                    if (!empty($tables)) {
                        $table = $tables[0];

                        $data = [];
                        foreach($table->records as $record) {
                            array_push($data, $record->values['_value']);
                        }

                        $max_data = max($data);
                        $min_data = min($data);
                        
                        $medium = array_sum($data)/count($data);

                        $last_data = end($data);

                        $answer = $answer."    Последние данные: $last_data\n";

                        $minutes = $time/60;
                        $answer = $answer."    Данные за $minutes минут:\n";
                        $answer = $answer."    Максимум: $max_data\n";
                        $answer = $answer."    Минимум: $min_data\n";
                        $answer = $answer."    Среднее: $medium\n";
                    } else {
                        $answer = $answer."    Нет данных\n";
                    }
                }
            }
    
            $this->bot->sendMessage($message->getChat()->getId(), $answer, 'HTML');
        }
        
    }
}
