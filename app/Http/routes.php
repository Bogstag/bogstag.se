<?php

//Api Routes
Route::group([
    'domain'     => env('API_DOMAIN', false),
    'prefix'     => 'v1',
    'middleware' => ['auth:api', 'throttle'],
], function () {
    Auth::guard('api')->user();
    Route::resource('date', 'Api\DateTime\DateController');
    Route::resource('step', 'Api\Activity\StepController');
    Route::resource('emailstat', 'Api\Email\StatController');
    Route::resource('emaildrop', 'Api\Email\DropController');
});

//Website Routs
Route::group(['prefix' => '/api/v1', 'middleware' => 'web'], function () {
    Route::get('{model}/{id}', 'ApiDataPreviewController@show');
    Route::get('{model}', 'ApiDataPreviewController@index');
});

Route::group(['middleware' => 'web', 'throttle'], function () {
    Route::any('home', function () {
        return redirect('/');
    });

    Route::get('/', function () {
        return view('pages.home');
    });
    Route::get('about', function () {
        return view('pages.about');
    });

    Route::get('test', 'test@index');

    Route::resource('activity/steps', 'StepCharts@getStepCharts');
    Route::resource('server/email', 'EmailCharts@getEmailCharts');
    Route::resource('game/steam', 'SteamGameController');
    Route::resource('movie/watched', 'MovieController');
    Route::resource('movie', 'MovieController');

    Route::auth();
    Route::any('register', function () {
        return redirect('/');
    });
});

//Admin routs
Route::group(['prefix' => 'admin', 'middleware' => 'web'], function () {
    // Admin Dashboard
    Route::auth();
    Route::get('dashboard', 'Admin\DashboardController@index');
    Route::get('emaildrop/getEmailDropsData', 'Admin\EmailDropController@getEmailDropsData');
    Route::get('emaildrop/setAdressToOkMailGun/{recipient}', 'Admin\EmailDropController@setAdressToOkMailGun');
    Route::resource('emaildrop', 'Admin\EmailDropController');
    Route::resource('profile', 'Admin\ProfileController');
    Route::get('settings', 'Admin\SettingsController@index');
    Route::get('settings/resetapitoken', 'Admin\SettingsController@resetapitoken');
});
