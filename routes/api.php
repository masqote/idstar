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

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');

Route::group(['middleware' => 'auth:api'], function(){
    
    Route::post('details', 'API\UserController@details');

    Route::get('news', 'API\NewsController@index');
    Route::get('news/{id}', 'API\NewsController@edit');
    Route::get('news/detail/{id}', 'API\NewsController@newsDetail');

    Route::get('news/{id}/comment', 'API\NewsController@comment');

    	// Hak Akses Create,Update,Delete News
    	Route::group(['middleware' => 'hak_akses'], function(){
    		Route::post('news/create', 'API\NewsController@store');
    		Route::put('news/{id}', 'API\NewsController@update');
   			Route::delete('news/{id}', 'API\NewsController@delete');
    	});

});
