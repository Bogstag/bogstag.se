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

Route::group(['prefix' => '/api/v1', 'middleware' => 'web'], function () {
    Route::get('{model}/{id}', 'ApiDataPreviewController@show');
    Route::get('{model}', 'ApiDataPreviewController@index');
});

Route::group(['middleware' => 'web', 'throttle'], function () {
    Route::redirect('home', '/');

    Route::view('/', 'pages.home');

    Route::view('/about', 'pages.about');

    Route::get('activity/steps', 'StepCharts@getStepCharts');
    Route::get('server/email', 'EmailCharts@getEmailCharts');
    Route::resource('game/steam', 'SteamGameController');
    Route::get('movie/watched/all', 'MovieWatchedController@indexAll');
    Route::get('movie/watched/cinema', 'MovieWatchedController@indexCinema');
    Route::resource('movie/stats', 'MovieTicketStatsController');
    Route::resource('movie', 'MovieController');

    Auth::routes();
    Route::redirect('register', '/');
    Route::get('/logout', 'Auth\LoginController@logout');
});

//Admin routs
Route::group(['prefix' => 'admin', 'middleware' => 'web'], function () {
    // Admin Dashboard
    Auth::routes();
    Route::get('dashboard', 'Admin\DashboardController@index');
    Route::get('emaildrop/getEmailDropsData', 'Admin\EmailDropController@getEmailDropsData');
    Route::get('emaildrop/setAdressToOkMailGun/{recipient}', 'Admin\EmailDropController@setAdressToOkMailGun');
    Route::resource('emaildrop', 'Admin\EmailDropController');
    Route::resource('profile', 'Admin\ProfileController');
    Route::get('settings', 'Admin\SettingsController@index');
    Route::get('settings/resetapitoken', 'Admin\SettingsController@resetapitoken');
    Route::resource('externalapilimit', 'Admin\ExternalApiLimitAdminController');
    Route::resource('oauth2credential', 'Admin\Oauth2CredentialAdminController');
    Route::resource('movietickets', 'Admin\MovieTicketsAdminController');
    Route::resource('addmovietickets', 'Admin\MovieTicketsAddAdminController');
});
