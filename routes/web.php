<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Shared Auth Controller
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
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\LaporanKeuntunganController;
use App\Http\Controllers\Admin\PembayaranAdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RekeningController;
use App\Http\Controllers\Admin\LogAktivitasController;
use App\Http\Controllers\Admin\UlasanController as AdminUlasanController;
use App\Http\Controllers\Admin\NotifikasiController;

/*
|--------------------------------------------------------------------------
| Controllers - User
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\User\KeranjangController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\JastiperRegistrationController;
use App\Http\Controllers\User\PesananController as UserPesananController;
use App\Http\Controllers\User\UlasanController;

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
use App\Http\Controllers\Jastiper\LaporanKeuntunganJastiperController;
use App\Http\Controllers\Jastiper\ProfileController as JastiperProfileController;
use App\Http\Controllers\Jastiper\DashboardJastiperController;

/*
|--------------------------------------------------------------------------
| ROOT ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [AuthController::class, 'landing'])->name('home');

Route::get('/produk/{id}', [AuthController::class, 'showProductDetail'])->name('produk.detail');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/


Route::get('/login',    [AuthController::class, 'loginForm'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout',  [AuthController::class, 'logout'])
    ->name('logout')->middleware('auth');

Route::post('/notifikasi/{notification}/read', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.markAsRead');

Route::post('/notifikasi/read-all', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.markAllAsRead');

Route::delete('/notifikasi/{notification}', [NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');

Route::get('/tatacara-belanja', function () {
    return view('user.cara-belanja.index');
})->name('cara-belanja');
Route::get('/tentang-kami', function () {
    return view('user.tentang-kami.index');
})->name('tentang-kami');
/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Keranjang

    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/{barang_id}', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::put('/keranjang/update/{productId}', [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/hapus/{productId}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');

    // Riwayat Pesanan
    Route::get('/pesanan/riwayat', [UserPesananController::class, 'riwayat'])->name('pesanan.riwayat');
    Route::put('/pesanan/{id}/complete', [UserPesananController::class, 'completeOrder'])->name('pesanan.complete');

    // Ulasan
    Route::post('/ulasan/{pesanan}', [UlasanController::class, 'store'])->name('ulasan.store');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/prepare', [CheckoutController::class, 'prepareCheckout'])->name('checkout.prepare');
    Route::post('/checkout/next', [CheckoutController::class, 'processStep'])->name('checkout.process');
    Route::get('/checkout/previous', [CheckoutController::class, 'previousStep'])->name('checkout.previous');
    Route::get('/finish', [CheckoutController::class, 'finalizeCheckout'])->name('checkout.finish');

    // Jastiper Registration
    Route::get('/jastiper/daftar', [JastiperRegistrationController::class, 'create'])->name('jastiper.register.create');
    Route::post('/jastiper/daftar', [JastiperRegistrationController::class, 'store'])->name('jastiper.register.store');
});

/*
|--------------------------------------------------------------------------
| ROUTE ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

        // Profile
        Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.updateAvatar');

        // Ulasan
        Route::resource('ulasans', AdminUlasanController::class)->only(['index', 'show', 'destroy']);

        // Log Aktivitas
        Route::get('log-aktivitas', [LogAktivitasController::class, 'index'])->name('log-aktivitas.index');

        // Notifikasi
        Route::resource('notifikasi', NotifikasiController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // Data User
        Route::resource('pengguna', UserController::class);

        // Jastiper
        Route::resource('jastiper', JastiperController::class);

        // Kategori
        Route::resource('kategori', KategoriController::class);

        // Rekening
        Route::resource('rekening', RekeningController::class);

        // Laporan Keuntungan
        Route::get('laporan-keuntungan', [LaporanKeuntunganController::class, 'index'])
            ->name('laporan.keuntungan');

        /*
        |--------------------------------------------------------------------------
        | Kelola Dana - Konfirmasi Pembayaran
        |--------------------------------------------------------------------------
        */
        Route::get('konfirmasi-pembayaran', [PembayaranAdminController::class, 'daftarKonfirmasiPembayaran'])
            ->name('konfirmasi-pembayaran.index');

        Route::post('konfirmasi-pembayaran/{pesanan}', [PembayaranAdminController::class, 'konfirmasiPembayaran'])
            ->name('konfirmasi-pembayaran');

        Route::post('/tolak-pembayaran/{pesanan}', [PembayaranAdminController::class, 'tolakPembayaran'])->name('tolak-pembayaran');

        /*
        |--------------------------------------------------------------------------
        | Kelola Dana - Pelepasan Dana
        |--------------------------------------------------------------------------
        */
        Route::get('pelepasan-dana', [PembayaranAdminController::class, 'daftarPelepasanDana'])
            ->name('pelepasan-dana.index');

        Route::post('pelepasan-dana/{pesanan}', [PembayaranAdminController::class, 'lepasDanaKeJastiper'])
            ->name('lepas-dana-jastiper');
    });

/*
|--------------------------------------------------------------------------
| ROUTE JASTIPER
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:jastiper'])
    ->prefix('jastiper')->name('jastiper.')
    ->group(function () {


        Route::get('/bantuan-jastiper', function () {
            return view('jastiper.bantuan.index');
        })->name('bantuan-jastiper');

        // Dashboard
        Route::get('/dashboard', [DashboardJastiperController::class, 'index'])
            ->name('dashboard.index');

        // Pesanan
        Route::resource('pesanan', PesananController::class);
        Route::put(
            'pesanan/{pesanan}/siap-kirim',
            [PesananController::class, 'updateStatusToSiapDikirim']
        )->name('pesanan.update.siap.kirim');

        Route::get(
            'pesanan/{pesanan}/show-data',
            [PesananController::class, 'showData']
        )->name('pesanan.show.data');

        // Laporan
        Route::get('/laporan-penjualan', [LaporanKeuntunganJastiperController::class, 'index'])
            ->name('laporan-keuntungan.index');

        // Profile
        Route::get('profile', [JastiperProfileController::class, 'index'])->name('profile.index');
        Route::get('profile/edit', [JastiperProfileController::class, 'edit'])->name('profile.edit');
        Route::post('profile/update', [JastiperProfileController::class, 'update'])->name('profile.update');

        // Detail Pesanan
        Route::resource('detail-pesanan', DetailPesananController::class);

        // Barang
        Route::resource('barang', BarangController::class);

        // Ulasan
        Route::resource('ulasans', JastiperUlasanController::class)->only(['index', 'show']);
    });
