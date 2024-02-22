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

    Route::get('get-upazillas/{district_id}','ConditionalDiscountController@GetUpazillas')->name('get-upazillas');

    Route::get('conditionaldiscount', 'ConditionalDiscountController@index')->name('conditionaldiscount');
    Route::group(['prefix' => 'conditionaldiscount', 'as'=>'conditionaldiscount.'], function () {
        Route::post('datatable-data', 'ConditionalDiscountController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'ConditionalDiscountController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'ConditionalDiscountController@edit')->name('edit');
        Route::post('show', 'ConditionalDiscountController@show')->name('show');
        Route::post('view', 'ConditionalDiscountController@view')->name('view');
        Route::post('delete', 'ConditionalDiscountController@delete')->name('delete');
        Route::post('bulk-delete', 'ConditionalDiscountController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'ConditionalDiscountController@change_status')->name('change.status');
    });

});