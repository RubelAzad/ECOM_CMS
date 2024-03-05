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
    Route::get('vendor', 'VendorController@index')->name('vendor');
    Route::group(['prefix' => 'vendor', 'as'=>'vendor.'], function () {
        Route::post('datatable-data', 'VendorController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'VendorController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'VendorController@edit')->name('edit');
        Route::post('view', 'VendorController@view')->name('view');
        Route::post('delete', 'VendorController@delete')->name('delete');
        Route::post('bulk-delete', 'VendorController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'VendorController@change_status')->name('change.status');
    });
});
