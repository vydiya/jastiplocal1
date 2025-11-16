<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\JastiperController;

// controllers untuk area jastiper (frontend jastiper / panel jastiper)
use App\Http\Controllers\Jastiper\PesananController;
use App\Http\Controllers\Jastiper\DetailPesananController;
use App\Http\Controllers\Jastiper\BarangController;

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

    // CRUD Pengguna
    Route::resource('pengguna', UserController::class);

    // CRUD Jastiper (admin mengelola jastipers)
    Route::resource('jastiper', JastiperController::class);
});

/*
|--------------------------------------------------------------------------
| ROUTE JASTIPER (LOGIN WAJIB)
| grouped under /jastiper with name prefix jastiper.*
|--------------------------------------------------------------------------
|
| These controllers live in App\Http\Controllers\Jastiper\
*/
Route::middleware(['auth'])->prefix('jastiper')->name('jastiper.')->group(function () {
    // CRUD Pesanan (jastiper sees/manage pesanan)
    Route::resource('pesanan', PesananController::class);

    // CRUD Detail Pesanan (jika butuh CRUD terpisah)
    Route::resource('detail-pesanan', DetailPesananController::class);

    // CRUD Barang (barang milik jastiper)
    Route::resource('barang', BarangController::class);
});
