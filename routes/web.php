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

//Auth::routes();

// Authentication Routes...

/*
Route::group(['middleware' => ['web']], function() {

// Login Routes...
    Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
    Route::post('login', ['as' => 'login.post', 'uses' => 'Auth\LoginController@login']);
    Route::post('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

// Registration Routes...
    Route::get('register', ['as' => 'register', 'uses' => 'Auth\RegisterController@showRegistrationForm']);
    Route::post('register', ['as' => 'register.post', 'uses' => 'Auth\RegisterController@register']);

// Password Reset Routes...
    Route::get('password/reset', ['as' => 'password.reset', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
    Route::post('password/email', ['as' => 'password.email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);
    Route::get('password/reset/{token}', ['as' => 'password.reset.token', 'uses' => 'Auth\ResetPasswordController@showResetForm']);
    Route::post('password/reset', ['as' => 'password.reset.post', 'uses' => 'Auth\ResetPasswordController@reset']);
});
*/

Route::get('/login','Auth\LoginController@showLoginForm');


Route::get('/home', 'HomeController@index');

Route::get('/clock', 'ClockController@clock');

Route::get('/history', 'HistoryController@display');
Route::post('/history', 'HistoryController@display');

Route::post('/history/modify', 'HistoryController@modify');

Route::get('/history/delete/{id}', 'HistoryController@delete');
Route::post('/history/delete/{id}', 'HistoryController@delete');

Route::get('/hours', 'HourController@display');
Route::post('/hours', 'HourController@display');

Route::get('/checkIfInOut', 'ClockController@checkIfInOut');

Route::get('/punchNow/{punchType}', 'HistoryController@punchNow');

Route::get('/test', 'testController@test');
