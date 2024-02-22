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
    Route::get('usergroup', 'UserGroupController@index')->name('usergroup');
    Route::group(['prefix' => 'usergroup', 'as'=>'usergroup.'], function () {
        Route::post('datatable-data', 'UserGroupController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'UserGroupController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'UserGroupController@edit')->name('edit');
        Route::post('show', 'UserGroupController@show')->name('show');
        Route::post('view', 'UserGroupController@view')->name('view');
        Route::post('delete', 'UserGroupController@delete')->name('delete');
        Route::post('bulk-delete', 'UserGroupController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'UserGroupController@change_status')->name('change.status');
    });
});