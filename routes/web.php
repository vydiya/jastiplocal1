<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Shared auth controller
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Controllers - Admin
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JastiperController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KelolaDanaController;
use App\Http\Controllers\Admin\LogAktivitasController;
use App\Http\Controllers\Admin\UlasanController as AdminUlasanController;
use App\Http\Controllers\Admin\ValidasiProdukController;

/*
|--------------------------------------------------------------------------
| Controllers - Jastiper
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Jastiper\BarangController;
use App\Http\Controllers\Jastiper\ControllerKategoriBarang;
use App\Http\Controllers\Jastiper\ControllerRekening;
use App\Http\Controllers\Jastiper\DetailPesananController;
use App\Http\Controllers\Jastiper\PesananController;
use App\Http\Controllers\Jastiper\UlasanController as JastiperUlasanController;
use App\Http\Controllers\Jastiper\LaporanController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'loginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');

Route::get('/admin/login', [AuthController::class, 'loginForm'])->name('admin.login')->middleware('guest');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit')->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| ROUTE ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')->name('admin.')->group(function ()  {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.updateAvatar');

    Route::resource('ulasans', AdminUlasanController::class)->only(['index','show','destroy']);
    Route::get('log-aktivitas', [LogAktivitasController::class, 'index'])->name('log-aktivitas.index');

    Route::resource('notifikasi', App\Http\Controllers\Admin\NotifikasiController::class)
        ->only(['index','create','store','edit','update','destroy']);

    Route::resource('pengguna', UserController::class);
    Route::resource('jastiper', JastiperController::class);
    Route::resource('pembayaran', PembayaranController::class);
    Route::resource('kelola-dana', KelolaDanaController::class);

    /*
    |--------------------------------------------------------------------------
    | VALIDASI PRODUK (DIUBAH SESUAI PERMINTAAN)
    |--------------------------------------------------------------------------
    |
    | Hanya 2 route:
    | - index (menampilkan tabel ✔ / ✖)
    | - action (AJAX setujui/tolak)
    |
    */
    Route::get('validasi-produk', 
        [ValidasiProdukController::class, 'index']
    )->name('validasi-produk.index');

    Route::post('validasi-produk/{validasi}/action', 
        [ValidasiProdukController::class, 'action']
    )->name('validasi-produk.action');
});

/*
|--------------------------------------------------------------------------
| ROUTE JASTIPER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:jastiper'])
    ->prefix('jastiper')->name('jastiper.')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\Jastiper\DashboardController::class, 'index'])
        ->name('dashboard.index');

    Route::resource('pesanan', PesananController::class);
    Route::resource('detail-pesanan', DetailPesananController::class);
    Route::resource('barang', BarangController::class);
    Route::resource('kategori-barang', ControllerKategoriBarang::class);
    Route::resource('rekening', ControllerRekening::class);
    Route::resource('laporan', LaporanController::class);

    Route::resource('ulasans', JastiperUlasanController::class)->only(['index','show']);

    Route::resource('notifikasi', App\Http\Controllers\Jastiper\NotifikasiController::class)
        ->only(['index','create','store','edit','update','destroy']);
});
