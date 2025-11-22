<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use App\Models\LogAktivitas;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // OPSIONAL: Auto log aktivitas setiap user membuka halaman admin
        if (Auth::check()) {
            LogAktivitas::create([
                'user_id'   => Auth::id(),
                'aksi'      => 'Akses Halaman',
                'deskripsi' => Request::path(),
            ]);
        }
    }
}
