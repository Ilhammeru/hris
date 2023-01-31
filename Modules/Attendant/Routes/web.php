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

Route::prefix('attendant')->group(function() {
    Route::get('/', 'AttendantController@index')->name('attendant.index');
    Route::post('/', 'AttendantController@store')->name('attendant.store');
    Route::delete('/{id}', 'AttendantController@destroy')->name('attendant.destroy');
    Route::get('/ajax', 'AttendantController@ajax')->name('attendant.ajax');
    Route::post('/import', 'AttendantController@import')->name('attendant.import');
});
