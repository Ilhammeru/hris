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
    Route::prefix('event')->group(function() {
        Route::get('/', 'EventController@index')->name('event.index');
        Route::post('/', 'EventController@store')->name('event.store');
        Route::get('/ajax', 'EventController@ajax')->name('event.ajax');
        Route::get('/ajax/{id}', 'EventController@ajaxAttendees')->name('event.ajax');
        Route::get('/export/{id}', 'EventController@exportAttendees')->name('event.export-attendees');
        Route::get('/{id}', 'EventController@show')->name('event.show');
        Route::get('/{id}/edit', 'EventController@edit')->name('event.edit');
        Route::post('/check_in', 'EventController@check_in')->name('event.check_in');
        Route::get('/guestbook/{slug}', 'EventController@guestbook')->name('event.guestbook');
        Route::post('/get/guestbook/list', 'EventController@guestbook_list')->name('event.guestbook_list');
    });
});
