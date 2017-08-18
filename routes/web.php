<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::group(['namespace'=>'Home', 'middleware' => ['web']], function() {
    Route::get('/', 'HomeController@index');
    Route::get('/education/level', 'EducationController@level');
    Route::get('/education/cart', 'EducationController@cart');
    Route::get('/sms/send', 'SmsController@send');
    Route::get('/help/about', 'HelpController@about');
});

Route::group(['namespace'=>'Home', 'middleware' => ['web','user.auth']], function() {
    Route::get('/order/pay', 'OrderController@pay');
    Route::get('/order/list', 'OrderController@list');
    Route::put('/order/cancel', 'OrderController@cancel');
    Route::put('/order/uncancel', 'OrderController@uncancel');
    Route::put('/order/delete', 'OrderController@delete');
});


//Auth::routes();
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::get('auth/forget', 'Auth\AuthController@forget');
Route::get('auth/resetpass', 'Auth\AuthController@resetpass');

