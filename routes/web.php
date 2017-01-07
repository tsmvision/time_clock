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

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

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

//Route::get('/login','Auth\LoginController@showLoginForm');


//Route::get('/home', 'HomeController@index')->middleware('auth');
Route::get('/', 'ClockController@clock')->middleware('auth');
Route::get('/clock', 'ClockController@clock')->middleware('auth');

Route::get('/history', 'HistoryController@showList')->middleware('auth');
Route::post('/history', 'HistoryController@showList')->middleware('auth');


Route::get('/history/add', 'HistoryController@add')->middleware('auth');
Route::post('/history/add', 'HistoryController@add')->middleware('auth');

Route::get('/history/delete/{id}', 'HistoryController@delete')->middleware('auth');
Route::post('/history/delete/{id}', 'HistoryController@delete')->middleware('auth');

Route::get('/history/update', 'HistoryController@update')->middleware('auth');
Route::post('/history/update', 'HistoryController@update')->middleware('auth');

Route::get('/workingHours', 'WorkingHourController@showList')->middleware('auth');
Route::post('/workingHours', 'WorkingHourController@showList')->middleware('auth');

Route::get('/admin', 'HistoryController@showList')->middleware('auth');
Route::post('/admin', 'HistoryController@showList')->middleware('auth');

Route::get('/admin/history', 'HistoryController@showList')->middleware('auth');
Route::post('/admin/history', 'HistoryController@showList')->middleware('auth');

Route::get('/admin/history/add', 'HistoryController@add')->middleware('auth');
Route::post('/admin/history/add', 'HistoryController@add')->middleware('auth');

Route::get('/admin/history/delete/{id}', 'HistoryController@delete')->middleware('auth');
Route::post('/admin/history/delete/{id}', 'HistoryController@delete')->middleware('auth');

Route::get('/admin/history/update', 'HistoryController@update')->middleware('auth');
Route::post('/admin/history/update', 'HistoryController@update')->middleware('auth');

Route::get('/admin/workingHours', 'WorkingHourController@adminShowList')->middleware('auth');
Route::post('/admin/workingHours', 'WorkingHourController@adminShowList')->middleware('auth');

Route::get('/admin/workingHours/delete/{id}', 'HistoryController@delete')->middleware('auth');
Route::post('/admin/workingHours/delete/{id}', 'HistoryController@delete')->middleware('auth');

Route::get('/admin/users', 'UserController@showList')->middleware('auth');
Route::post('/admin/users', 'UserController@showList')->middleware('auth');

Route::get('/punchNow/{punchType}', 'HistoryController@punchNow')->middleware('auth');

Route::get('/punchNow02', 'HistoryController@punchNow02')->middleware('auth');
Route::post('/punchNow02', 'HistoryController@punchNow02')->middleware('auth');

Route::get('/test', 'WorkingHourController@showListTest');

Route::get('/logout', 'Auth\LogoutController@logout');

