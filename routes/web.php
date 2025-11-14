<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController; // <-- Tambahkan ini

/*
|--------------------------------------------------------------------------
| REDIRECT ROOT KE LOGIN ADMIN
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/admin/login');

/*
|--------------------------------------------------------------------------
| ROUTE LOGIN & LOGOUT ADMIN
|--------------------------------------------------------------------------
*/

// Form Login
Route::get('/admin/login', [AuthController::class, 'loginForm'])
    ->name('admin.login')
    ->middleware('guest');

// Proses Login
Route::post('/admin/login', [AuthController::class, 'login'])
    ->name('admin.login.submit')
    ->middleware('guest');

// Proses Logout
Route::post('/admin/logout', [AuthController::class, 'logout'])
    ->name('admin.logout')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| ROUTE ADMIN (LOGIN WAJIB)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard.index');

    // Update Avatar
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])
        ->name('profile.updateAvatar');

    /*
    |--------------------------------------------------------------------------
    | CRUD PENGGUNA (ADMIN → Data Master → Pengguna)
    |--------------------------------------------------------------------------
    */
    Route::resource('pengguna', UserController::class);
});