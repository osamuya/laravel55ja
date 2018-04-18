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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/* Mail authentication */
Route::post('/regist_confirm', 'Auth\RegisterController@registConfirm');
Route::post('/regist_store', 'Auth\RegisterController@registStore');
Route::get('/regist_mail_authenticate_user/{accesshash}', 'Auth\RegisterController@mailAuthenticate');
