<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/game/store', 'GameController@store');
Route::post('/game/get', 'GameController@index');
Route::post('/game/reset', 'GameController@destroy');

Route::post('/match/store', 'MatchController@store');
Route::post('/match/get', 'MatchController@index');
