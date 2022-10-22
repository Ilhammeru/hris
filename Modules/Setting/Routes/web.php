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

Route::prefix('setting')->middleware('auth')->group(function() {
    Route::get('/menu', 'SettingController@index')->name('setting.menu');
    Route::get('/menu/create', 'SettingController@createMenu')->name('setting.menu.create');
    Route::get('/menu/ajax', 'SettingController@menuAjax')->name('setting.menu.ajax');
    Route::post('/menu/store', 'SettingController@menuStore')->name('setting.menu.store');
    Route::patch('/menu/update/{id}', 'SettingController@updateMenu')->name('setting.menu.update');
    Route::delete('/menu/{id}', 'SettingController@deleteMenu')->name('setting.menu.delete');
    Route::get('/menu/{id}', 'SettingController@editMenu')->name('setting.menu.edit');
});
