<?php

// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;


// redirect root ke admin dashboard (cara cepat & aman)
Route::redirect('/', '/admin/dashboard');

// --- route admin tetap seperti sebelumnya ---
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('dashboard', DashboardController::class);
});

Route::prefix('admin')->name('admin.')->group(function () {
    // route lain...
    Route::post('profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.updateAvatar');
});