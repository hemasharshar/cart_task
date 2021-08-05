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

Route::group(['prefix' => 'v1'], function () {
    Route::group(['middleware' => 'jwt.auth'], function () {

    });

    // Products routes api
    Route::get('/products/all', 'ProductAPIController@index');
    Route::get('/products/show/{product_id}', 'ProductAPIController@show');

    // Cart routes api
    Route::get('cart', 'CartAPIController@cart');
    Route::post('add-to-cart/{product_id}', 'CartAPIController@addToCart');
    Route::post('update-cart', 'CartAPIController@updateCart');

    // Auth routes api
    Route::post('/auth/login', 'AuthAPIController@login');
});
