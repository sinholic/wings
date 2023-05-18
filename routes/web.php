<?php

use Illuminate\Support\Facades\Route;

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
Auth::routes();
Route::group(["middleware" => "auth"], function () {
        Route::resource('products', ProductController::class);
        Route::resource('transactions', TransactionController::class)->only(['index', 'store']);
        Route::get('/', 'DashboardController@sales')->name('index');
    Route::group([
        "prefix" => "dashboard"
    ], function () {
        Route::get('/sales', 'DashboardController@sales')->name('sales');
    });
});
