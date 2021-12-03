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
 * Route for User view d
 */
// get method
Route::get('profile', 'PanelController@profile');
Route::get('loan-aggrement', 'PanelController@loanAggrement');
Route::get('loan-aggrement/{el}', 'PanelController@pickAggrement');
Route::get('member-loans', 'TsLoansController@index');
Route::get('get-loans', 'TsLoansController@getLoans');
Route::get('loan-detail/{el}', 'TsLoansController@loanDetail');

// post method
Route::post('detail-approved', 'TsLoansController@detailApproved');

