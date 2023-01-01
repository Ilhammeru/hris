<?php

use App\Http\Controllers\AuthController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use Modules\Employee\Http\Controllers\LeavePermissionController;

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

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// delete REDIS
Route::get('/delete-redis/{key}', function($key) {
    Redis::del($key);
});
Route::get('/view-redis/{key}', function($key) {
    return Redis::get($key);
});
Route::get('flush-redis', function() {
    Redis::del('user_chat_theme');
    Redis::del('last_step_action');
    Redis::del('user_theme_action');
});

Route::get('/user', function() {
    $pageTitle = "Template User";
    return view('user', compact('pageTitle'));
})->name('template.user');
Route::get('/template/profile', function() {
    $pageTitle = "Template Profile";
    return view('profile', compact('pageTitle'));
})->name('template.profile');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', function() {
    $pageTitle = "Dashboard";
    return view('dashboard', compact('pageTitle'));
})->name('dashboard');

Route::get('/register', function() {
    return view('register');
})->name('register');

Route::get('/password-reset', function() {
    return 'password reset';
})->name('password.reset');
Route::get('/password-email', function() {
    return 'password email';
})->name('password.email');
Route::get('/password-request', function() {
    return 'password request';
})->name('password.request');

// Print leave permission
Route::get('/print/leave-permission', [LeavePermissionController::class, 'print'])->name('print.leave-permission');