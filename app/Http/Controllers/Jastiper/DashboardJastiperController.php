<?php

namespace App\Http\Controllers\Jastiper;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pesanan;
use App\Models\Ulasan;
use App\Models\AlurDana;
use App\Models\DetailPesanan; 
use Carbon\Carbon; 

class DashboardJastiperController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $jastiper = $user->jastiper;

        if (!$jastiper) {
            abort(403, 'Anda bukan jastiper.');
        }
        
        $jastiperId = $jastiper->id;

        $totalPesanan   = Pesanan::where('jastiper_id', $jastiperId)->count();
        $pesananSelesai = Pesanan::where('jastiper_id', $jastiperId)
                                 ->where('status_pesanan', 'SELESAI')
                                 ->count();
        $ulasan = Ulasan::where('jastiper_id', $jastiperId)->count();

        $statusPesanan = Pesanan::where('jastiper_id', $jastiperId)
                                 ->selectRaw('status_pesanan, COUNT(*) as total')
                                 ->groupBy('status_pesanan')
                                 ->pluck('total', 'status_pesanan')
                                 ->toArray();

        $chartStatus = [
            'DIPROSES'      => $statusPesanan['DIPROSES'] ?? 0,
            'SIAP_DIKIRIM'  => $statusPesanan['SIAP_DIKIRIM'] ?? 0,
            'SELESAI'       => $statusPesanan['SELESAI'] ?? 0,
        ];
        
        $pendapatan = AlurDana::where('jenis_transaksi', 'PELEPASAN_DANA')
                              ->where('status_konfirmasi', 'DIKONFIRMASI')
                              ->whereHas('pesanan', function ($q_pesanan) use ($jastiperId) {
                                  $q_pesanan->where('jastiper_id', $jastiperId); 
                              })
                              ->sum('jumlah_dana');

        $lastTwelveMonths = Carbon::now()->subMonths(11)->startOfDay();
        $penjualanRaw = AlurDana::where('jenis_transaksi', 'PELEPASAN_DANA')
                               ->where('status_konfirmasi', 'DIKONFIRMASI')
                               ->where('created_at', '>=', $lastTwelveMonths)
                               ->whereHas('pesanan', function ($q_pesanan) use ($jastiperId) {
                                   $q_pesanan->where('jastiper_id', $jastiperId);
                               })
                               ->select(
                                   DB::raw('YEAR(created_at) as tahun'),
                                   DB::raw('MONTH(created_at) as bulan'),
                                   DB::raw('SUM(jumlah_dana) as total')
                               )
                               ->groupBy('tahun', 'bulan')
                               ->orderBy('tahun', 'asc')
                               ->orderBy('bulan', 'asc')
                               ->get()
                               ->keyBy(function ($item) {
                                   return $item->tahun . '-' . $item->bulan;
                               })
                               ->toArray();

        $dataPenjualan = [];
        $labelsPenjualan = [];
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->subMonths(11 - $i);
            $key = $date->year . '-' . $date->month;
            
            $dataPenjualan[] = $penjualanRaw[$key]['total'] ?? 0;
            $labelsPenjualan[] = $date->translatedFormat('M Y'); 
        }
        
        $pesananSelesaiIds = Pesanan::where('jastiper_id', $jastiperId)
                                    ->where('status_pesanan', 'SELESAI')
                                    ->where('tanggal_pesan', '>=', $lastTwelveMonths)
                                    ->pluck('id');

        $barangTerjualRaw = DetailPesanan::whereIn('pesanan_id', $pesananSelesaiIds)
                                       ->join('pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id')
                                       ->select(
                                           DB::raw('YEAR(pesanans.tanggal_pesan) as tahun'),
                                           DB::raw('MONTH(pesanans.tanggal_pesan) as bulan'),
                                           DB::raw('SUM(detail_pesanans.jumlah) as total_barang')
                                       )
                                       ->groupBy('tahun', 'bulan')
                                       ->orderBy('tahun', 'asc')
                                       ->orderBy('bulan', 'asc')
                                       ->get()
                                       ->keyBy(function ($item) {
                                           return $item->tahun . '-' . $item->bulan;
                                       })
                                       ->toArray();

        $dataBarangTerjual = [];
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->subMonths(11 - $i);
            $key = $date->year . '-' . $date->month;
            
            $dataBarangTerjual[] = $barangTerjualRaw[$key]['total_barang'] ?? 0;
        }


        return view('Jastiper.dashboard.index', compact(
            'totalPesanan',
            'pesananSelesai',
            'ulasan',
            'chartStatus',
            'pendapatan',
            'dataPenjualan',
            'labelsPenjualan',
            'dataBarangTerjual'
        ));
    }
}