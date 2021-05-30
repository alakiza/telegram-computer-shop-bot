<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'bot', 'middleware' => 'web'], function () {
    Route::post('webhook', 'BotController@index');
});

Route::post('/auth/login', 'AuthController@login');

Route::middleware(['auth:api'])->group(function() {
    Route::post('/auth/register', 'AuthController@register');
    Route::post('/auth/logout', 'AuthController@logout');
    Route::post('/auth/refresh', 'AuthController@refresh');
    Route::post('/auth/me', 'AuthController@me');
});

Route::get('/{any}', 'SpaController@index')->where('any', '.*');
