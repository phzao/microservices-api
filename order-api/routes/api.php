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
        Route::get('orders/{id}', 'OrderController@show');
        Route::get('orders', 'OrderController@index');
        Route::get('orders/user/{id}', 'OrderController@indexByUser');
        Route::put('orders/{id}', 'OrderController@update');
        Route::post('orders', 'OrderController@store');
        Route::delete('orders/{id}', 'OrderController@destroy');

        if (env('APP_ENV') == 'testing') {
            Route::get('orders-test/{id}', 'OrderController@showTest');
            Route::post('orders-test', 'OrderController@storeTest');
        }
    });
});
