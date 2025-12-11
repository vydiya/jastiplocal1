<?php

namespace App\Http\Controllers\Jastiper;

use App\Http\Controllers\Controller;
use App\Models\AlurDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jastiper; 
use App\Models\Pesanan; 

class LaporanKeuntunganJastiperController extends Controller
{
    /**
     * Helper method untuk mendapatkan ID Jastiper yang sedang login.
     * @return int
     */
    protected function getJastiperId()
    {
        if (!Auth::check()) {
            abort(403, 'Anda harus login.');
        }
        
        $jastiperModel = Auth::user()->jastiper;
        
        if (!$jastiperModel) {
            abort(403, 'Akses tidak diizinkan. Anda bukan terdaftar sebagai Jastiper.');
        }
        
        return $jastiperModel->id;
    }

    public function index(Request $request)
    {
        $jastiperId = $this->getJastiperId();

        $q = $request->input('q');

        $query = AlurDana::where('jenis_transaksi', 'PELEPASAN_DANA')
            ->where('status_konfirmasi', 'DIKONFIRMASI')
            ->whereHas('pesanan', function ($q_pesanan) use ($jastiperId) {
                $q_pesanan->where('jastiper_id', $jastiperId); 
            })
            ->with(['pesanan']); 

        if ($q) {
            $query->where(function ($subQuery) use ($q) {
                $subQuery->where('id', 'like', "%{$q}%") 
                         ->orWhere('pesanan_id', 'like', "%{$q}%");
            });
        }

        $transaksis = $query->orderBy('created_at', 'desc')
                            ->paginate(15)
                            ->appends($request->query()); 
        
        $totalDanaAlur = (clone $query)->get();

        $totalPenjualanNetto = $totalDanaAlur->sum('jumlah_dana');
        
        $totalPenjualanBruto = $totalDanaAlur->sum(function($alurDana) {
            return $alurDana->pesanan->total_harga ?? 0;
        });

        $jumlahTransaksi = $totalDanaAlur->count();
        
        return view('Jastiper.laporan-keuntungan.index', compact('transaksis', 'totalPenjualanNetto', 'totalPenjualanBruto', 'jumlahTransaksi', 'q'));
    }
}