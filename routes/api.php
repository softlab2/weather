<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/

Route::prefix('api/weather')->namespace('Softlab\Weather')->middleware(['web'])->group(function () {
	Route::get('/get', 'WeatherController@get')->name('api.weather.get');
});