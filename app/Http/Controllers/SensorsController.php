<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Http\Controllers\DBHelper;

class SensorsController extends Controller
{
    public function GetSensors(Request $request)
    {
        $patients = DB::table('sensors')->get();

        return $patients;
    }

    public function AddSensors(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'ip' => ['required', 'ipv4'],
            'id_patients' => ['required', 'integer'],
            ]);

        if ($validator->fails()) {
            return response($validator->errors()->all(), 500);
        }
    
        $data = $request->json()->all();

        DB::table('sensors')->insert(["name" => $data["name"],
                                      "ip" => $data["ip"],
                                      "id_patients" => $data["id_patients"]]);

        return response(200);
    }

    public function DeleteSensors(Request $request)
    {
        DBHelper::DeleteByID('sensors', $request);
    }

    public function UpdateSensors(Request $request)
    {
        DBHelper::Update('sensors', $request);
    }
}
