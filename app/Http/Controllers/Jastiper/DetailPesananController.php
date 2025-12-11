<?php

namespace App\Http\Controllers\Jastiper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\Barang;

class DetailPesananController extends Controller
{

    protected function getJastiperId()
    {
        if (!Auth::check()) {
            abort(403, 'Akses ditolak: Anda harus login.');
        }

        $user = Auth::user();

        $jastiperId = $user->jastiper->id ?? null;

        if (!$jastiperId) {
            abort(403, 'Akses ditolak: Anda bukan terdaftar sebagai Jastiper.');
        }

        return $jastiperId;
    }
    public function index()
    {
        $jastiperId = $this->getJastiperId();

        $pesananIds = Pesanan::where('jastiper_id', $jastiperId)
            ->pluck('id');

        $detailPesanans = DetailPesanan::with(['pesanan', 'barang'])
            ->whereIn('pesanan_id', $pesananIds)
            ->paginate(15);

        return view('Jastiper.detail_pesanan.index', compact('detailPesanans'));
    }


  

}
