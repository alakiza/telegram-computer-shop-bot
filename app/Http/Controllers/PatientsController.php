<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Http\Controllers\DBHelper;

class PatientsController extends Controller
{
    public function GetPatients(Request $request)
    {
        $patients = DB::table('patients')->get();

        return $patients;
    }

    public function AddPatients(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'surname' => ['required', 'max:255'],
        'name' => ['required', 'max:255'],
        'patronymic' => ['required', 'max:255'],
        'card_number' => ['required', 'integer'],
        'chamber' => ['required', 'integer'],
        'id_doctors' => ['required', 'integer']
        ]);

        if ($validator->fails()) {
        return response($validator->errors()->all(), 500);
        }

        $data = $request->json()->all();

        DB::table('patients')->insert(['name' => $data['name'], 
                                     'surname' => $data['surname'],
                                     'patronymic' => $data['patronymic'],
                                     'card_number' => $data['card_number'],
                                     'chamber' => $data['chamber'],
                                     'id_doctors' => $data['id_doctors'],
                                     'receipt_date' => now()]);

        return response(200);
    }

    public function UpdatePatients(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'new.surname' => ['required', 'max:255'],
        'new.name' => ['required', 'max:255'],
        'new.patronymic' => ['required', 'max:255'],
        'new.card_number' => ['required', 'integer'],
        'new.chamber' => ['required', 'integer'],
        'new.id_doctors' => ['required', 'integer']
        ]);

        if ($validator->fails()) {
        return response($validator->errors()->all(), 500);
        }

        $data = $request->json()->all();

        DBHelper::Update('patients', $request);

        return response(200);
    }

    public function DeletePatients(Request $request)
    {
        DBHelper::DeleteByID('patients', $request);
    }
}
