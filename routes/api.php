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


Route::group(['prefix' => '', 'namespace' => 'API'], function () {
    
    Route::get('get_room_data', 'PlannerController@getRoomData');
    Route::post('set_room_data', 'PlannerController@setRoomData');
    
    Route::any('{any}', 'PlannerController@show404')->where(['any'=>'.*']);    
});
