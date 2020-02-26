<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');
Auth::routes();
Route::get('logincustomer', 'HomeController@login');
Route::get('/json', 'HomeController@json');
Route::get('/testqrcode', function(){
    return view('frontend.test');
});
Route::post('submitlogin', 'FE\CustomerController@login')->name('customerlogin');
Route::get('gettable', 'FE\CustomerController@gettable');
Route::get('privacy', 'FE\PrivacyController@index');
Route::group(['middleware' => ['auth', 'is_admin']], function () {

    //For Nhân viên
    Route::get('order/index', 'OrderController@index')->name('order.byday');
    Route::get('order/details/{id}', 'OrderController@orderdetails')->name('order.details');
    Route::get('order/print/{id}', 'OrderController@printBill')->name('printbill');
    Route::get('order/revenue', 'OrderController@revenue')->name('order.revenue');
    Route::get('order/success/{id}', 'OrderController@success')->name('order.success');
    Route::get('order/next/{id}', 'OrderController@next')->name('order.next');
    Route::get('order/error/{id}', 'OrderController@error')->name('order.error');
    Route::get('order/view/{id}', 'OrderController@vieworder')->name('order.view');
    Route::get('order/product/{id}', 'OrderController@printproduct')->name('order.product');
    Route::get('doanh-thu-theo-ngay', 'StaticController@doanhthutheongay')->name('doanh.thu.theo.ngay');
    Route::get('lay-doanh-thu-theo-ngay', 'StaticController@laydoanhthutheongay');
    
    //For Sếp
    Route::group(['middleware' => ['check_role']], function () {
        Route::get('doanh-thu-theo-thang', 'StaticController@doanhthutheothang')->name('doanh.thu.theo.thang');
        Route::get('lay-doanh-thu-theo-thang', 'StaticController@laydoanhthutheothang');
        //settings
        Route::get('setting/index', 'SettingController@index')->name('setting.index');
        Route::post('setting/update/{id}', 'SettingController@update')->name('setting.update');
        // stores
        Route::get('store/index', 'StoreController@index')->name('store.index');
        // admins
        Route::get('admin/index', 'AdminController@index')->name('admin.index'); //$url = route('profile', ['id' => 1]);
        Route::post('admin/add', 'AdminController@store')->name('admin.add');
        Route::get('admin/delete/{id}', 'AdminController@delete')->name('admin.delete');
        Route::post('admin/update/{id}', 'AdminController@update')->name('admin.update');
        //Categoríe
        Route::get('category/index', 'CategoryController@index')->name('category.index');
        Route::post('category/add', 'CategoryController@add')->name('category.add');
        Route::post('category/update/{id}', 'CategoryController@update')->name('category.update');
        Route::get('category/delete/{id}', 'CategoryController@delete')->name('category.delete');

        //Products
        Route::get('product/index', 'ProductController@index')->name('product.index');
        Route::post('product/add', 'ProductController@add')->name('product.add');
        Route::post('product/update/{id}', 'ProductController@update')->name('product.update');
        Route::get('product/delete/{id}', 'ProductController@delete')->name('product.delete');
        Route::get('product/viewupdate/{id}', 'ProductController@viewupdate')->name('product.viewupdate');
        Route::get('search-product', 'ProductController@search');
        Route::get('product/category/{id}', 'ProductController@getAllByCategory')->name('product.bycateogry');
        Route::get('customer/view/store', 'UserController@viewstore')->name('customer.view.store');
        Route::post('customer/store', 'UserController@store')->name('customer.store');

        //feedback
        Route::get('feedback/index', 'AdminController@feedback')->name('feedback.index');

        //recipes
        Route::get('recipe/index', 'ProductController@indexRecipe')->name('recipe.index');
        Route::get('recipe/store', 'ProductController@storeRecipe')->name('recipe.store');
        Route::post('recipe/store', 'ProductController@postStoreRecipe')->name('recipe.store');

        // export 
        Route::post('export', 'StaticController@exportMoth')->name('export.revenue');
    });

        //Customer
        Route::get('customer/index', 'UserController@index')->name('customer.index');
        Route::get('customer/info/{id}', 'UserController@info')->name('customer.info');
        Route::get('search-customer', 'UserController@search');
        Route::post('/naptien/{id}', 'UserController@naptien')->name('naptien');

        //Rechage of day
        Route::get('rechage/index', 'OrderController@indexRechage')->name('rechage');
        Route::post('store/pricebox', 'OrderController@storePriceBox')->name('store.pricebox');
        Route::post('shiftwork', 'OrderController@shiftwork')->name('shiftwork');
});

//front-end
Route::get('product/{id}', 'FE\CustomerController@detailsProduct')->name('frontend.product.details');
Route::post('cart/add', 'FE\CustomerController@addProduct')->name('cart.add');
Route::get('cart/show', 'FE\CustomerController@ShowCart')->name('cart.show');
Route::get('cart/delete/{id}', 'FE\CustomerController@DeleteCart')->name('cart.delete');
Route::post('cart/checkout', 'FE\CustomerController@checkout')->name('cart.checkout');
Route::get('order/here', 'FE\CustomerController@orderhere')->name('ordernow');

//Notification
Route::get('notification', 'FE\CustomerController@notification')->name('notification');
Route::get('profiler', 'FE\CustomerController@profiler')->name('profiler');
Route::get('logout', 'FE\CustomerController@logout')->name('logout');
Route::post('register', 'FE\CustomerController@register')->name('register');
Route::get('test/{id}', 'OrderController@tesst');
