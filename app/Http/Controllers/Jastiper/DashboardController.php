<?php

namespace App\Http\Controllers\Jastiper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard jastiper
     */
    public function index()
    {
        // Contoh: ambil beberapa statistik khusus jastiper jika diperlukan.
        // Jika kamu ingin menampilkan data nyata, ambil dari model:
        // $user = Auth::user();
        // $totalPesanan = \App\Models\Pesanan::where('jastiper_id', $user->id)->count();
        // $pendapatan = ...;
        //
        // return view('jastiper.dashboard.index', compact('totalPesanan','pendapatan'));

        return view('jastiper.dashboard.index');
    }
}
