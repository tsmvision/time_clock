<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/clock', 'ClockController@clock');

Route::get('/history', 'HistoryController@display');
Route::post('/history', 'HistoryController@display');

Route::post('/modify', 'HistoryController@modify');
Route::post('/delete', 'HistoryController@delete');

Route::get('/checkIfInOut', 'ClockController@checkIfInOut');

Route::get('/punchNow', 'HistoryController@punchNow');

Route::get('/test', 'ClockController@test');
