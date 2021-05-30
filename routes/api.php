<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:api'])->group(function() {
    Route::prefix('patients')->group(function () {
        Route::get('/get', 'PatientsController@GetPatients');
        Route::post('/add', 'PatientsController@AddPatients');
        Route::post('/delete', 'PatientsController@DeletePatients');
        Route::put('/update', 'PatientsController@UpdatePatients');
    });

    Route::prefix('doctors')->group(function () {
        Route::get('/get', 'DoctorsController@GetDoctors');
        Route::post('/add', 'DoctorsController@AddDoctors');
        Route::post('/delete', 'DoctorsController@DeleteDoctors');
        Route::put('/update', 'DoctorsController@UpdateDoctors');
    });

    Route::prefix('chambers')->group(function () {
        Route::get('/get', 'ChambersController@GetChambers');
        Route::post('/delete', 'ChambersController@DeleteChambers');
        Route::put('/update', 'ChambersController@UpdateChambers');
    });

    Route::prefix('sensors')->group(function () {
        Route::get('/get', 'SensorsController@GetSensors');
        Route::post('/add', 'SensorsController@AddSensors');
        Route::post('/delete', 'SensorsController@DeleteSensors');
        Route::put('/update', 'SensorsController@UpdateSensors');
    });
});
