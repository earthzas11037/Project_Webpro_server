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

Route::group(['middleware' => ['auth.jwt', 'auth_manager']], function () {
    Route::get('checkTokenManager',function () {
        return response()->json(['message' => true ]);
    });
    Route::get('alluser', 'App\Http\Controllers\Api\UserController@getAllUsers');
    Route::post('updateUser', 'App\Http\Controllers\Api\UserController@update');

    Route::get('getdataforregister', 'App\Http\Controllers\Api\Auth\RegisterController@getdataforRegister');
    Route::post('register', 'App\Http\Controllers\Api\Auth\RegisterController@register');

    Route::post('record/update', 'App\Http\Controllers\Api\RecordController@update');
    Route::get('record/All', 'App\Http\Controllers\Api\RecordController@getAllRecord');
    Route::get('record/AllByDate/{start}/{end}', 'App\Http\Controllers\Api\RecordController@getAllRecordByDate');

    Route::post('leaveRecord/approve', 'App\Http\Controllers\Api\LeaveRecordController@approve');
    Route::post('leaveRecord/disapprove', 'App\Http\Controllers\Api\LeaveRecordController@disapprove');
    Route::get('leaveRecord/AllWaitForApproval', 'App\Http\Controllers\Api\LeaveRecordController@getAllWaitForApproval');
    
    Route::post('report/insert', 'App\Http\Controllers\Api\ReportController@insert');
    Route::get('report/All/{year}/{month}', 'App\Http\Controllers\Api\ReportController@getAllReport');
});

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('checkToken',function () {
        return response()->json(['message' => true ]);
    });
    Route::get('logout', 'App\Http\Controllers\Api\Auth\LoginController@logout');
    Route::get('user/{id}', 'App\Http\Controllers\Api\UserController@getUserById');

    Route::post('record/comeIn', 'App\Http\Controllers\Api\RecordController@comeIn');
    Route::post('record/getOut', 'App\Http\Controllers\Api\RecordController@getOut');
    Route::get('record/getAll/{id}', 'App\Http\Controllers\Api\RecordController@getAllById');

    Route::post('leaveRecord/insert', 'App\Http\Controllers\Api\LeaveRecordController@insert');
    Route::get('leaveRecord/All', 'App\Http\Controllers\Api\LeaveRecordController@getAllLeaveRecord');
    Route::get('leaveRecord/AllById/{id}', 'App\Http\Controllers\Api\LeaveRecordController@getAllById');

    Route::post('calendar/insert', 'App\Http\Controllers\Api\CalendarController@insert');
    Route::get('calendar/getById/{id}', 'App\Http\Controllers\Api\CalendarController@getAllById');
    Route::get('calendar/All', 'App\Http\Controllers\Api\CalendarController@getAllCalendar');
    
});

Route::group(['middleware' => ['auth.jwt', 'auth_admin']], function () {
    Route::get('checkTokenAdmin',function () {
        return response()->json(['message' => true ]);
    });
    Route::post('record/byId', 'App\Http\Controllers\Api\RecordController@recordTime');
});


Route::post('login', 'App\Http\Controllers\Api\Auth\LoginController@login');
