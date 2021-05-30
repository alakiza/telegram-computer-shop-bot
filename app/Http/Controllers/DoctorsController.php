<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Http\Controllers\DBHelper;

class DoctorsController extends Controller
{
    public function GetDoctors(Request $request)
    {
        $doctors = DB::table('doctors')->get();

        $telegram_users = DB::table('telegram_users')->get()->toArray();

        $tmp_users = [];
        foreach ($telegram_users as $telegram_user) {
            $tmp_users[$telegram_user->id] = $telegram_user->user_id;
        }

        foreach ($doctors as $doctor) {
            if (isset($tmp_users[$doctor->telegram_user])) {
                $doctor->telegram_user = $tmp_users[$doctor->telegram_user];
            } else {
                $doctor->telegram_user = "";
            }
        }

        return $doctors;
    }

    public function AddDoctors(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'surname' => ['required', 'max:255'],
        'name' => ['required', 'max:255'],
        'patronymic' => ['required', 'max:255'],
        'spetialization' => ['required', 'max:255'],
        'telegram_user' => ['required', 'integer']
        ]);

        if ($validator->fails()) {
            return response($validator->errors()->all(), 500);
        }

        $data = $request->json()->all();

        DB::beginTransaction();
        $telegram_users = DB::table('telegram_users')->where('user_id', '=', $data["telegram_user"])->get()->toArray();

        $telegram_user_id = 0;

        if(empty($telegram_users)) {
            $telegram_user_id = DB::table('telegram_users')->insertGetId(['user_id' => $data["telegram_user"], 
                                                                         "dialog_path" => "",
                                                                         "dialog_params" => ""]);
        } else {
            $telegram_user_id = $telegram_users[0]->id;
        }

        DB::table('doctors')->insert(['name' => $data['name'], 
                                     'surname' => $data['surname'],
                                     'patronymic' => $data['patronymic'],
                                     'spetialization' => $data['spetialization'],
                                     'telegram_user' => $telegram_user_id]);

        DB::commit();

        return response(200);
    }

    public function UpdateDoctors(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer'],
            'new.surname' => ['required', 'max:255'],
            'new.name' => ['required', 'max:255'],
            'new.patronymic' => ['required', 'max:255'],
            'new.spetialization' => ['required', 'max:255'],
            'new.telegram_user' => ['required', 'integer']
        ]);

        if ($validator->fails()) {
        return response($validator->errors()->all(), 500);
        }

        $data = $request->json()->all();

        DB::beginTransaction();
        $telegram_users = DB::table('telegram_users')->where('user_id', '=', $data["new"]["telegram_user"])->get()->toArray();

        $telegram_user_id = 0;

        if(empty($telegram_users)) {
            $telegram_user_id = DB::table('telegram_users')->insertGetId(['user_id' => $data["new"]["telegram_user"], 
                                                                          "dialog_path" => "",
                                                                          "dialog_params" => ""]);
        } else {
            $telegram_user_id = $telegram_users[0]->id;
        }

        unset($data['new']['id']);
        unset($data['new']['created_at']);
        unset($data['new']['updated_at']);
        $data['new']['telegram_user'] = $telegram_user_id;
        
        DB::table('doctors')->where('id', '=', $data['id'])->update($data['new']);

        DB::commit();

        return response(200);
    }

    public function DeleteDoctors(Request $request)
    {
        DBHelper::DeleteByID('doctors', $request);
    }
}
