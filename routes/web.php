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

Route::get('/history', 'ClockController@history');
Route::put('/history', 'ClockController@history');

Route::get('/checkIfInOut', 'ClockController@checkIfInOut');

Route::get('/punchNow', 'ClockController@punchNow');

Route::get('/test', 'ClockController@test');
