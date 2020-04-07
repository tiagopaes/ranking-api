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

Route::post('/auth/register', 'AuthController@register');
Route::post('/auth/login', 'AuthController@login');

Route::middleware('auth:api')->group( function () {
    Route::apiResource('ranking', 'RankingController');
    Route::apiResource('ranking.player', 'PlayerController');
    Route::get('/user', 'AuthController@getUser');
});
