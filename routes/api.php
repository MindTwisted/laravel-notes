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

Route::prefix('auth')->group(function () {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

Route::prefix('user')->group(function () {

    Route::post('register', 'UserController@register');
    Route::post('change-name', 'UserController@changeName');
    Route::post('change-email', 'UserController@changeEmail');
    Route::post('change-password', 'UserController@changePassword');

});
