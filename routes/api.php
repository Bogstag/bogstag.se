<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

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
