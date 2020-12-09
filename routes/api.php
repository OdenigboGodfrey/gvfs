<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/laravel-user', function (Request $request) {
    return $request->user();
});

Route::namespace('API')->group(function(){
    Route::group(['prefix' => 'item'], function(){
        Route::namespace('Item')->group(function(){
            Route::post('/create', 'ItemController@create_item')->name('api.item.create');
        });
    });
});
