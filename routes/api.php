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

Route::post('login', 'Api\UserController@login');
Route::post('register', 'Api\UserController@store');
Route::group(['middleware' => ['auth:api']], function(){
    Route::post('details', 'Api\UserController@details');
    Route::post('update', 'Api\UserController@update');
    Route::post('orders', 'Api\OrderController@order');
    Route::get('getkey', 'Api\UserController@getkey');

    //historyorders
    Route::post('listorder', 'Api\OrderController@historyorder');
    Route::get('orderdetail/{id}', 'Api\OrderController@historyorderdetails');

    //notification
    Route::get('list-notification', 'Api\NotificationController@index');
    Route::post('check-read', 'Api\NotificationController@check_read');

    // history rechage
    Route::get('list-rechage', 'Api\UserController@rechage');

    //logout
    Route::get('logout', 'Api\UserController@logout');
    Route::post('feedback', 'Api\UserController@feedback');
});
Route::get('products/{id}', 'Api\ProductController@GetProductByCategory');
Route::get('categories', 'Api\CategoryController@index');
Route::get('product/{id}', 'Api\ProductController@GetProductById');
Route::get('getconfig', 'Api\SettingController@getconfig');
Route::post('array', 'Api\ProductController@jsondecode');
