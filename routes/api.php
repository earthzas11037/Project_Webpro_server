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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', 'App\Http\Controllers\Api\Auth\LoginController@logout');
    Route::get('alluser', 'App\Http\Controllers\Api\UserController@getAllUsers');
    Route::get('user/{id}', 'App\Http\Controllers\Api\UserController@getUserById');
    Route::get('checkToken', 'App\Http\Controllers\Api\UserController@checkToken');

    Route::post('record/byId', 'App\Http\Controllers\Api\RecordController@recordTime');
    Route::post('record/comeIn', 'App\Http\Controllers\Api\RecordController@comeIn');
    Route::post('record/getOut', 'App\Http\Controllers\Api\RecordController@getOut');
    Route::get('record/All', 'App\Http\Controllers\Api\RecordController@getAllRecord');

    Route::post('leaveRecord/insert', 'App\Http\Controllers\Api\LeaveRecordController@insert');
    Route::post('leaveRecord/approve', 'App\Http\Controllers\Api\LeaveRecordController@approve');
    Route::post('leaveRecord/disapprove', 'App\Http\Controllers\Api\LeaveRecordController@disapprove');
    Route::get('leaveRecord/All', 'App\Http\Controllers\Api\LeaveRecordController@getAllLeaveRecord');
});

Route::post('login', 'App\Http\Controllers\Api\Auth\LoginController@login');
Route::post('register', 'App\Http\Controllers\Api\Auth\RegisterController@register');
