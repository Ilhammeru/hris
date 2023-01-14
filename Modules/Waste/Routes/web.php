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
use Modules\Waste\Http\Controllers\WasteController;

Route::prefix('waste')->group(function() {
    Route::get('/', 'WasteController@index');
});

Route::get('waste/ajax/in', [WasteController::class, 'ajaxIn'])->name('waste.ajax.in');
Route::resource('waste', WasteController::class);
