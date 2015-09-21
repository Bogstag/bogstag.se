<?php

//Api Routes
Route::group(['domain'     => env('API_DOMAIN', false),
              'prefix'     => 'v1',
              'middleware' => 'APIAuth'
], function () {
    Route::resource('date', 'Api\DateTime\DateController');
    Route::resource('step', 'Api\Activity\StepController');
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

Route::resource('graph', 'PageChartController@getStepCharts');

//Test Routes
//Route::get('test', 'Integration\Google\GoogleFit@getStepData');

// Auth Routes
Route::controllers([
    'auth'     => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

// Redirect Routes
Route::get('home', function () {
    return redirect('/');
});
Route::get('auth/register', function () {
    return redirect('/');
});



