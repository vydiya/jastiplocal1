<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\JastiperController;

// controllers untuk area jastiper
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
| AUTH ADMIN (login/logout)
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AuthController::class, 'loginForm'])
    ->name('admin.login')
    ->middleware('guest');

Route::post('/admin/login', [AuthController::class, 'login'])
    ->name('admin.login.submit')
    ->middleware('guest');

Route::post('/admin/logout', [AuthController::class, 'logout'])
    ->name('admin.logout')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| ROUTE ADMIN (prefix admin, name admin.)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.updateAvatar');

    // resource admin
    Route::resource('pengguna', UserController::class);
    Route::resource('jastiper', JastiperController::class);
});

/*
|--------------------------------------------------------------------------
| ROUTE JASTIPER (prefix jastiper, name jastiper.)
|--------------------------------------------------------------------------
| Controller berada di App\Http\Controllers\Jastiper\*
*/
Route::middleware(['auth'])->prefix('jastiper')->name('jastiper.')->group(function () {
    Route::resource('pesanan', PesananController::class);
    Route::resource('detail-pesanan', DetailPesananController::class);
    Route::resource('barang', BarangController::class);
});
