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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('home', function() {
	return redirect('/');
});

Route::get('/', 'HomeController@index')->name('home');
Route::get('select', 'HomeController@select')->name('select')->middleware('auth');
Route::get('export/{provinsi}', 'HomeController@export')->name('export');
Route::get('lapor', 'HomeController@lapor')->name('lapor');
Route::post('donasi', 'HomeController@donasiPost')->name('donasiPost');
Route::get('donasi', 'HomeController@donasi')->name('donasi');
Route::post('lapor', 'HomeController@laporPost')->name('laporPost');
Route::post('create', 'HomeController@create')->name('create');
Route::post('store', 'HomeController@store')->name('store');
Route::get('report', 'HomeController@report')->name('report');
Route::get('total', 'HomeController@total')->name('total');
Route::get('progress', 'HomeController@progress')->name('progress');
Route::get('selesai', 'HomeController@selesai')->name('selesai');
Route::get('file', 'HomeController@allfile')->name('allfile');
Route::get('file/{provinsi}', 'HomeController@file')->name('file');
Route::get('check', 'HomeController@check')->name('check');
Route::get('pecahan', 'HomeController@pecahan')->name('pecahan');
