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
use Modules\User\Http\Controllers\AuthController;
use Modules\User\Http\Controllers\RoleController;
use Modules\User\Http\Controllers\UserController;

Route::get('/template/profile', function() {
    $pageTitle = "Template Profile";
    return view('profile', compact('pageTitle'));
})->name('template.profile');

Route::prefix('auth')->group(function() {
    Route::get('/login', function() {
        return view('auth.login');
    })->name('login');
});

Route::prefix('user')->middleware('auth')->group(function() {
    Route::get('/role/ajax', 'RoleController@ajax')->name('user.role.ajax');
    Route::resource('role', 'RoleController', [
        'names' => [
            'index' => 'user.role',
            'create' => 'user.role.create',
            'store' => 'user.role.store',
            'edit' => 'user.role.edit',
            'update' => 'user.role.update',
            'destroy' => 'user.role.delete'
        ]
    ]);
    Route::get('/permission/ajax', 'PermissionController@ajax')->name('user.permission.ajax');
    Route::get('/permission-group', 'PermissionController@indexGroup')->name('user.permission-group');
    Route::get('/permission-group/list', 'PermissionController@listGroup')->name('user.permission-group.list');
    Route::get('/permission-group/{id}', 'PermissionController@editGroup')->name('user.permission-group.edit');
    Route::patch('/permission-group/{id}', 'PermissionController@updateGroup')->name('user.permission-group.update');
    Route::post('/permission-group', 'PermissionController@storeGroup')->name('user.permission-group.store');
    Route::delete('/permission-group/{id}', 'PermissionController@destroyGroup')->name('user.permission-group.delete');
    Route::resource('permission', 'PermissionController', [
        'names' => [
            'index' => 'user.permission',
            'create' => 'user.permission.create',
            'store' => 'user.permission.store',
            'edit' => 'user.permission.edit',
            'update' => 'user.permission.update',
            'destroy' => 'user.permission.delete'
        ]
    ]);

    Route::get('/list', 'UserController@index')->name('user.list');
    Route::get('/list/ajax', 'UserController@ajax')->name('user.ajax');
    Route::get('/create', 'UserController@create')->name('user.create');
    Route::post('/store', 'UserController@store')->name('user.store');
    Route::get('/{id}', 'UserController@edit')->name('user.edit');
    Route::patch('/{id}', 'UserController@update')->name('user.update');
    Route::delete('/{id}', 'UserController@destroy')->name('user.delete');
});
