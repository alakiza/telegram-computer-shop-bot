<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Http\Controllers\DBHelper;

class ChambersController extends Controller
{
    public function GetChambers(Request $request)
    {
        $patients = DB::table('chambers')->get();

        return $patients;
    }
}
