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
    });
});
