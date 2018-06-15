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
Route::post('addUser', 'AdminController@createUser')->middleware('kuayu');
Route::post('upload', 'AdminController@upload')->middleware('kuayu');
Route::get('adminList', 'AdminController@adminList')->middleware('kuayu');
Route::get('getUser', 'AdminController@getUser')->middleware('kuayu');
Route::post('editUser', 'AdminController@editUser')->middleware('kuayu');
