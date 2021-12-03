<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| View Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

/**
 * Route for User update data
 */

Route::post('update-profile', 'PanelController@updateProfile');
Route::post('update-profile-photo', 'PanelController@updateProfilePhoto');
Route::post('update-staff', 'PanelController@updateStaff');
Route::post('update-loan', 'TsLoansController@updateLoan');
Route::post('revisi-loan', 'TsLoansController@revisiLoan');
Route::post('update-revisi-loan', 'TsLoansController@updateRevisiLoan');
Route::post('agree-revisi-loan', 'TsLoansController@agreeRevisiLoan');

Route::post('change-status', 'TsLoansController@changeStatus');
Route::post('update-status', 'TsLoansController@updateStatus');
