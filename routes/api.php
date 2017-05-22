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
    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::resource('theaters', 'TheaterController');
        Route::resource('events', 'EventController');
        Route::resource('categories', 'CategoryController');


    });

    Route::group(['prefix' => 'events/{event_id}'], function () {
        Route::resource('reviews', 'ReviewController');
    });

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', ['as' => 'auth.login', 'uses' => 'AuthController@login']);
        Route::post('signup', [ 'as' => 'auth.signup', 'uses' => 'AuthController@signup']);
        Route::get('me', [ 'as' => 'auth.me', 'uses' => 'AuthController@getUser'] )->middleware('jwt.auth');
    });

});
