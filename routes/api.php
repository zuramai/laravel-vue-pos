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

Route::prefix('v1')->group(function()  {
    Route::middleware('cors')->group(function() {
        Route::post('/auth/login', 'API\AuthController@login');
        Route::post('/auth/register', 'API\AuthController@register');
        Route::post('/auth/refresh', 'API\AuthController@refresh');
        
        
        Route::middleware('auth:api')->group(function(){
            
            Route::get('/auth/user', 'API\AuthController@user');
            Route::post('/auth/logout','API\AuthController@logout');

            Route::get('/admin/home', 'API\HomeController@index');
            
            Route::resource('category', 'API\CategoryController'); 
            Route::post('category/{id}','API\CategoryController@update');
            
            Route::resource('user', 'API\UsersController');
            Route::post('user/{id}', 'API\UsersController@update');

            Route::get('customer/all','API\CustomerController@all');
            Route::resource('customer', 'API\CustomerController');
            Route::post('customer/{id}', 'API\CustomerController@update');
            
            Route::get('product/search', 'API\ProductController@search');
            Route::resource('product', 'API\ProductController'); 
            Route::post('product/{id}', 'API\ProductController@update');
        
            Route::post('transaction/new', 'API\TransactionController@newTransaction');
            Route::get('transaction/history', 'API\TransactionController@history');
            Route::get('transaction/chart', 'API\TransactionController@chart');
            Route::get('transaction/invoice/{invoice_id}', 'API\TransactionController@invoice');
            Route::resource('transaction', 'API\TransactionController'); 

            Route::resource('payment-method', 'API\PaymentMethodController');
            Route::get('/report', 'API\HomeController@report');
        });
    });
});


Route::get('siswa','API\SiswaController@index');
Route::post('siswa','API\SiswaController@create');
Route::put('siswa/{id}','API\SiswaController@update');
Route::delete('siswa/{id}','API\SiswaController@delete');