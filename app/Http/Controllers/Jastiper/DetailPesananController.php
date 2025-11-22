<?php

namespace App\Http\Controllers\Jastiper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\Barang;

class DetailPesananController extends Controller
{
    public function index()
    {
        $detailPesanans = DetailPesanan::with(['pesanan', 'barang'])->paginate(15);
        return view('jastiper.detail_pesanan.index', compact('detailPesanans'));
    }

    public function create()
    {
        $pesanans = Pesanan::orderBy('id', 'desc')->pluck('id', 'id');
        $barangs = Barang::orderBy('nama_barang')->get();
        return view('jastiper.detail_pesanan.create', compact('pesanans', 'barangs'));
    }

    public function store(Request $request) { /* ... */ }

    public function show(DetailPesanan $detail_pesanan)
    {
        return view('jastiper.detail_pesanan.show', ['detail' => $detail_pesanan->load('pesanan', 'barang')]);
    }

    public function edit(DetailPesanan $detail_pesanan)
    {
        $pesanans = Pesanan::pluck('id', 'id');
        $barangs = Barang::orderBy('nama_barang')->get();
        return view('jastiper.detail_pesanan.edit', ['detail' => $detail_pesanan, 'pesanans' => $pesanans, 'barangs' => $barangs]);
    }

    public function update(Request $request, DetailPesanan $detail_pesanan) { /* ... */ }
    public function destroy(DetailPesanan $detail_pesanan) { /* ... */ }
}
