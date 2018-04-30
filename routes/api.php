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
    Route::put('change-name', 'UserController@changeName');
    Route::put('change-email', 'UserController@changeEmail');
    Route::put('change-password', 'UserController@changePassword');
    Route::delete('delete-user', 'UserController@deleteUser');

});

Route::middleware('auth:api')->group(function () {

    Route::resource('notes', 'NoteController')->except([
        'create',
        'edit',
    ]);

});