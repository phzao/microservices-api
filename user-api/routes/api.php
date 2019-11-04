<?php

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

Route::middleware(['cors', 'api'])->group(function () {
    Route::group(['prefix'=>'v1'], function (){
        Route::get('users/{id}', 'UserController@showElastic');
        Route::get('users', 'UserController@index');
        Route::get('users-secure/{id}', 'UserController@showSecure');
        Route::put('users/{id}', 'UserController@update');
        Route::post('users', 'UserController@store');
        Route::delete('users/{id}', 'UserController@destroy');

        if (env('APP_ENV') == 'testing') {
            Route::get('users-test', 'UserController@indexTest');
            Route::get('users-test/{id}', 'UserController@show');
        }
    });
});
