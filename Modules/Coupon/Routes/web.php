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


Route::group(['middleware' => ['auth']], function () {
    Route::get('coupon', 'CouponController@index')->name('coupon');
    Route::group(['prefix' => 'coupon', 'as'=>'coupon.'], function () {
        Route::post('datatable-data', 'CouponController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'CouponController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'CouponController@edit')->name('edit');
        Route::post('show', 'CouponController@show')->name('show');
        Route::post('view', 'CouponController@view')->name('view');
        Route::post('delete', 'CouponController@delete')->name('delete');
        Route::post('bulk-delete', 'CouponController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'CouponController@change_status')->name('change.status');
    });
});
