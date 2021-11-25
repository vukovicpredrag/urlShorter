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

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');

    Route::post('/saveLink', 'LinkController@save')->name('link.save');
    Route::delete('/deleteLink{link}', 'LinkController@delete')->name('link.delete');
    Rote::get('/count/{link}', 'LinkController@count')->name('link.count');
});

