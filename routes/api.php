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

Route::prefix('v1')->group(function () {
    Route::post('/register', 'UserController@register');
    Route::post('/login', 'UserController@login');
    
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('/profile', 'UserController@profile');
        Route::post('/logout', 'UserController@logout');

        Route::get('/ad', 'AdController@index');
        Route::post('/ad', 'AdController@store');
        Route::get('/ad/{ad}', 'AdController@show');
        
        Route::post('/order', 'OrderController@store');
        Route::get('/order', 'OrderController@index');

        Route::get('/temp_order_items', 'TempOrderItemController@index');
        Route::post('/temp_order_items', 'TempOrderItemController@store');
        Route::post('/temp_order_items/delete', 'TempOrderItemController@destroy');
    });
});