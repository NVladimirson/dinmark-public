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
Route::get('ticket','API\NotificationController@ticket');
Route::get('news','API\NotificationController@news');
Route::get('order','API\NotificationController@order');
Route::get('implementation','API\NotificationController@implementation');
Route::get('reclamation','API\NotificationController@reclamation');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
