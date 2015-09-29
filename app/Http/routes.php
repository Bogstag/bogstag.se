<?php

//Api Routes
Route::group([
    'domain'     => env('API_DOMAIN', false),
    'prefix'     => 'v1',
    'middleware' => 'APIAuth'
], function () {
    Route::resource('date', 'Api\DateTime\DateController');
    Route::resource('step', 'Api\Activity\StepController');
    Route::resource('emailstat', 'Api\Email\StatController');
    Route::resource('emaildrop', 'Api\Email\DropController');
});

//Website Routs
Route::group(array('prefix' => '/api/v1'), function () {
    Route::get('{model}/{id}', 'ApiDataPreviewController@show');
    Route::get('{model}', 'ApiDataPreviewController@index');
});
Route::get('/', function () {
    return view('pages.home');
});
Route::get('about', function () {
    return view('pages.about');
});
Route::get('home', function () {
    return redirect('/');
});

Route::resource('activity/steps', 'StepCharts@getStepCharts');
Route::resource('server/email', 'EmailCharts@getEmailCharts');

// Auth Routes
// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
//No registrations Route::get('auth/register', 'Auth\AuthController@getRegister');
//No registrations Route::post('auth/register', 'Auth\AuthController@postRegister');

//Admin routs
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    # Admin Dashboard
    Route::get('dashboard', 'Admin\DashboardController@index');
    Route::resource('emaildrop', 'Admin\EmailDropController');
});
