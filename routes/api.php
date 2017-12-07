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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/v1/insert','AngularController@postFormData');
Route::get('/v1/display','AngularController@display');
Route::post('/v1/edit','AngularController@edit');
Route::post('/v1/previewUpdate','AngularController@preview_edit');
Route::post('/v1/delete','AngularController@delete');