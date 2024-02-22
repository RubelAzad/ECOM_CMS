<?php

use Codeboxr\EcourierCourier\Facade\Ecourier;
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


Auth::routes(['register'=>false]);

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index');
    Route::get('dashboard-data/{start_date}/{end_date}', 'HomeController@dashboard_data');
    Route::get('unauthorized', 'HomeController@unauthorized')->name('unauthorized');
    Route::get('my-profile', 'MyProfileController@index')->name('my.profile');
    Route::post('update-profile', 'MyProfileController@updateProfile')->name('update.profile');
    Route::post('update-password', 'MyProfileController@updatePassword')->name('update.password');

    //Menu Routes
    Route::get('menu', 'MenuController@index')->name('menu');
    Route::group(['prefix' => 'menu', 'as'=>'menu.'], function () {
        Route::post('datatable-data', 'MenuController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'MenuController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'MenuController@edit')->name('edit');
        Route::post('delete', 'MenuController@delete')->name('delete');
        Route::post('bulk-delete', 'MenuController@bulk_delete')->name('bulk.delete');
        Route::post('order/{menu}','MenuController@orderItem')->name('order');

        //Module Routes
        Route::get('builder/{id}','ModuleController@index')->name('builder');
        Route::group(['prefix' => 'module', 'as'=>'module.'], function () {
            Route::get('create/{menu}','ModuleController@create')->name('create');
            Route::post('store-or-update','ModuleController@storeOrUpdate')->name('store.or.update');
            Route::get('{menu}/edit/{module}','ModuleController@edit')->name('edit');
            Route::delete('delete/{module}','ModuleController@destroy')->name('delete');
	    Route::get('{statusid}/status/{module}', 'ModuleController@change_status')->name('status');

            //Permission Routes
            Route::get('permission', 'PermissionController@index')->name('permission');
            Route::group(['prefix' => 'menu', 'as'=>'permission.'], function () {
                Route::post('datatable-data', 'PermissionController@get_datatable_data')->name('datatable.data');
                Route::post('store', 'PermissionController@store')->name('store');
                Route::post('edit', 'PermissionController@edit')->name('edit');
                Route::post('update', 'PermissionController@update')->name('update');
                Route::post('delete', 'PermissionController@delete')->name('delete');
                Route::post('bulk-delete', 'PermissionController@bulk_delete')->name('bulk.delete');
            });

        });
    });

    //Role Routes
    Route::get('role', 'RoleController@index')->name('role');
    Route::group(['prefix' => 'role', 'as'=>'role.'], function () {
        Route::get('create', 'RoleController@create')->name('create');
        Route::post('datatable-data', 'RoleController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RoleController@store_or_update_data')->name('store.or.update');
        Route::get('edit/{id}', 'RoleController@edit')->name('edit');
        Route::get('view/{id}', 'RoleController@show')->name('view');
        Route::post('delete', 'RoleController@delete')->name('delete');
        Route::post('bulk-delete', 'RoleController@bulk_delete')->name('bulk.delete');
    });

    //User Routes
    Route::get('user','UserController@index')->name('menu');
    Route::group(['prefix' => 'user', 'as'=>'user.'], function () {
        Route::post('datatable-data', 'UserController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'UserController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'UserController@edit')->name('edit');
        Route::post('show', 'UserController@show')->name('show');
        Route::post('delete', 'UserController@delete')->name('delete');
        Route::post('bulk-delete', 'UserController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'UserController@change_status')->name('change.status');
    });


    Route::get('setting','SettingController@index')->name('setting');
    Route::get('get-upazilla','SettingController@get_upazillas')->name('get-upazillas');

    Route::get('ecourier', 'SettingController@index')->name('ecourier');
    Route::group(['prefix' => 'ecourier', 'as'=>'ecourier.'], function () {
        Route::post('datatable-data', 'SettingController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'SettingController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'SettingController@edit')->name('edit');
        Route::post('delete', 'SettingController@delete')->name('delete');
        Route::post('bulk-delete', 'SettingController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'SettingController@change_status')->name('change.status');
    });



    Route::post('general-setting','SettingController@general_seting')->name('general.setting');
    Route::post('mail-setting','SettingController@mail_setting')->name('mail.setting');


    Route::get('file','LocationImoportExportController@importExportView')->name('file');
    Route::group(['prefix' => 'file', 'as'=>'file.'], function () {
        Route::get('export', 'LocationImoportExportController@export')->name('export');
        Route::post('import', 'LocationImoportExportController@import')->name('import');
    });

    //ecourier routes
    Route::get('cities', function(){
        return Ecourier::area()->city(); //city is similar with district
    });
    Route::get('/upzilla', function () {
        return Ecourier::area()->thana("Bagherhat"); //upazila is similar with thana
    });
    Route::get('/postcode', function () {
        return Ecourier::area()->postcode("Dhaka", "Bangshal");
    });
    Route::get('/areaList', function () {
        return Ecourier::area()->areaList("1100");
    });
    Route::get('/branch', function () {
        return Ecourier::area()->branch();
    });
    Route::get('/packageList', function () {
        return Ecourier::order()->packageList();
    });

    Route::get('/createOrder', function () {
        return Ecourier::order()->create([
            "ep_name" => "IFAD", // eCommerce Partner (EP) Name
            "pick_contact_person" => "MD. Rubel", // Contact Person of provided ep
            "pick_district" => "Dhaka", // Pickup district name
            "pick_thana" => "Sutrapur", // Pickup thana name
            "pick_hub" => "18488", // Pickup branch id
            "pick_union" => "Nawabpur", // Pickup union
            "pick_address" => "Nobendro Nath Bosak Lane", // Pickup address
            "pick_mobile" => "01676717945", // Pickup person contact number


            "recipient_name" => "MD. Nazmul", // Parcel receiver’s name
            "recipient_mobile" => "01743524989", // Parcel receiver’s mobile number
            "recipient_district"   => "Dhaka", // Parcel receiver’s district name
            "recipient_city" => "Dhaka", // Parcel receiver’s city name
            "recipient_thana" => "Dhaka", // Parcel receiver’s thana name
            "recipient_area" => "Dhanmondi 32", // Parcel receiver’s area name
            "recipient_union" => "Shonkor", // Parcel receiver’s union name
            "recipient_address" => "House#34,Road#4,Block#4 O", // Parcel receiver’s full address


            "package_code" => "#2416", // Package code find in package API
            // "parcel_detail"        => "", // Parcel product or documents details
            // "number_of_item"       => "", // Total quantity
            "product_price" => "1200", // Receive amount from parcel receiver’s
            "payment_method" => "Card Payment – CCRD", // Cash On Delivery – COD,Point of Sale – POS, Mobile Payment – MPAY, Card Payment – CCRD
            "ep_id" => "2556", // Invoice Id
            // "actual_product_price" => "" // Parcel product actual price
        ]);
    });
    Route::get('/tracking', function () {
        return Ecourier::order()->tracking("ECR57358627130923");
    });
});
