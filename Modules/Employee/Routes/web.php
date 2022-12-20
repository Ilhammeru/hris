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

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function() {
    Route::prefix('employee')->group(function() {
        Route::get('/', 'EmployeeController@index')->name('hrd.dashboard');
        Route::get('/list', 'EmployeeController@index')->name('employee.index');
        Route::get('/create', 'EmployeeController@create')->name('employee.create');
        Route::post('/store', 'EmployeeController@store')->name('employee.store');
        Route::get('/list/ajax', 'EmployeeController@ajax')->name('employee.index.ajax');
        Route::get('/show/{id}', 'EmployeeController@show')->name('employee.detail.profile');
        Route::post('/get-city', 'EmployeeController@getCity')->name("employee.get-city");
        Route::post('/employee.get-district', 'EmployeeController@getDistrict')->name("employee.get-district");
        Route::post('/employee.get-village', 'EmployeeController@getVillage')->name("employee.get-village");
        Route::post('/employee.get-division', 'EmployeeController@getDivision')->name('employee.get-division');
    });
});
