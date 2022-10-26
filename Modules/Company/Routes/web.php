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

Route::prefix('company')->middleware('auth')->group(function() {
    Route::get('/', 'CompanyController@index')->name('company');

    // structure organization
    Route::get('/company/structure', 'CompanyController@structure')->name('company.structure');

    // department
    Route::get('/department/ajax', 'DepartmentController@ajax')->name('company.department.ajax');
    Route::resource('department', 'DepartmentController', [
        'names' => [
            'index' => 'company.department.index',
            'create' => 'company.department.create',
            'store' => 'company.department.store',
            'edit' => 'company.department.edit',
            'update' => 'company.department.update',
            'destroy' => 'company.department.delete',
        ]
    ]);
    
    // division
    Route::get('/division/ajax', 'DivisionController@ajax')->name('company.division.ajax');
    Route::resource('division', 'DivisionController', [
        'names' => [
            'index' => 'company.division.index',
            'create' => 'company.division.create',
            'store' => 'company.division.store',
            'edit' => 'company.division.edit',
            'update' => 'company.division.update',
            'destroy' => 'company.division.delete',
        ]
    ]);
});
