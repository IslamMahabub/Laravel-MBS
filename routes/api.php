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

Route::post('login', 'Api\ApiAuthController@login');
Route::post('register', 'Api\ApiAuthController@register');
 
Route::middleware('auth:api')->group(function () {
    Route::get('user', 'Api\ApiAuthController@details');
 
    Route::resource('account', 'Api\AccountController');
    Route::post('currentbalance', 'Api\AccountController@getBalance');

    Route::post('transaction', 'Api\TransactionController@index');
    Route::post('deposit', 'Api\TransactionController@depost');
    Route::post('withdraw', 'Api\TransactionController@withdraw');
    Route::post('transfer', 'Api\TransactionController@transfer');
    Route::post('transferhistory', 'Api\TransactionController@transferHistory');

});