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

Route::group(['prefix' => 'v1'], function () {
    Route::resource('theaters', 'TheaterController');
    Route::resource('events', 'EventController');

    Route::group(['prefix' => 'auth'], function () {
    	Route::post('login', 'AuthController@login');
    	Route::post('signup', 'AuthController@signup');
    	Route::post('resetPassword', 'AuthController@resetPassword');
    	Route::post('requestPassword', 'AuthController@requestPassword');
    	//Route::post('signup', 'RegisterController@create');
    	//Route::post('resetPassword', 'ResetPasswordController');
    	//Route::post('requestPassword', 'ForgotPasswordController');
	});
});
