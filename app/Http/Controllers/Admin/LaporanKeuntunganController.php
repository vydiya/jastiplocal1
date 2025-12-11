<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlurDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanKeuntunganController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $q = $request->input('q');

        $query = AlurDana::where('jenis_transaksi', 'PELEPASAN_DANA')
                         ->where('status_konfirmasi', 'DIKONFIRMASI') 
                         ->with(['pesanan.user', 'pesanan.jastiper']);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        if ($q) {
            $query->where(function ($subQuery) use ($q) {
                $subQuery->where('id', $q)
                         ->orWhere('pesanan_id', $q)
                         ->orWhereHas('pesanan', function ($q_pesanan) use ($q) {
                             $q_pesanan->where('id', $q);
                         });
            });
        }
        
        $totalKeuntungan = (clone $query)->sum('biaya_admin');
        $jumlahTransaksi = (clone $query)->count();

        $transaksis = $query->orderBy('created_at', 'desc')
                            ->paginate(15)
                            ->appends($request->query()); 

        return view('admin.laporan-keuntungan.index', compact('transaksis', 'totalKeuntungan', 'jumlahTransaksi', 'startDate', 'endDate', 'q'));
    }
}