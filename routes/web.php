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

Route::any('/', [
	'as' => 'main', 'uses' => 'MainController@index',
]);

Route::any('/rank',  [
	'as' => 'rank', 'uses' => 'MainController@rank',
]);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('https');
