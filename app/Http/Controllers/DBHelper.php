<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use InfluxDB2\Client;
use InfluxDB2\Model\WritePrecision;
use InfluxDB2\Point;

class DBHelper
{
  public static function DeleteByID(String $table, Request $request)
  {
    $validator = Validator::make($request->all(), [
      'id' => ['required', 'integer']
    ]);

    if ($validator->fails()) {
      return response($validator->errors()->all(), 500);
    }

    $data = $request->json()->all();
    
    DB::table($table)->where('id', '=', $data['id'])->delete();

    return response(200);
  }

  public static function Update(String $table, Request $request)
  {
    $validator = Validator::make($request->all(), [
      'id' => ['required', 'integer']
    ]);

    if ($validator->fails()) {
      return response($validator->errors()->all(), 500);
    }

    $data = $request->json()->all();

    unset($data['new']['id']);
    unset($data['new']['created_at']);
    unset($data['new']['updated_at']);
    
    DB::table($table)->where('id', '=', $data['id'])->update($data['new']);

    return response(200);
  }
}