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

Route::get('/messages','HomeController@index');
Route::get('/users','HomeController@users');
Route::get('/getMessages/{id}','HomeController@getMessages');
Route::post('/sendMessage/{id}','HomeController@SendMessage');
Route::post('/markAsRead/{id}','HomeController@markAsRead');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
