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
Route::post('register', 'api/ApiController@register');

Route::group(['namespace' => 'API', 'as' => 'api'], function () {
    includeRouteFiles(__DIR__.'/api/');
});
