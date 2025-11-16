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
        $detailPesanans = DetailPesanan::with(['pesanan','barang'])->paginate(15);
        return view('jastiper.detail_pesanan.index', compact('detailPesanans'));
    }

    public function create()
    {
        $pesanans = Pesanan::orderBy('id','desc')->pluck('id','id'); // minimal data
        $barangs = Barang::orderBy('nama')->get();
        return view('jastiper.detail_pesanan.create', compact('pesanans','barangs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pesanan_id' => 'required|exists:pesanans,id',
            'barang_id'  => 'required|exists:barangs,id',
            'jumlah'     => 'required|integer|min:1',
            'subtotal'   => 'required|numeric|min:0',
        ]);

        DetailPesanan::create($data);
        return redirect()->route('jastiper.detail-pesanan.index')->with('success','Detail pesanan dibuat.');
    }

    public function show(DetailPesanan $detail_pesanan)
    {
        return view('jastiper.detail_pesanan.show', ['detail' => $detail_pesanan->load('pesanan','barang')]);
    }

    public function edit(DetailPesanan $detail_pesanan)
    {
        $pesanans = Pesanan::pluck('id','id');
        $barangs  = Barang::orderBy('nama')->get();
        return view('jastiper.detail_pesanan.edit', ['detail' => $detail_pesanan, 'pesanans'=>$pesanans, 'barangs'=>$barangs]);
    }

    public function update(Request $request, DetailPesanan $detail_pesanan)
    {
        $data = $request->validate([
            'pesanan_id' => 'required|exists:pesanans,id',
            'barang_id'  => 'required|exists:barangs,id',
            'jumlah'     => 'required|integer|min:1',
            'subtotal'   => 'required|numeric|min:0',
        ]);

        $detail_pesanan->update($data);
        return redirect()->route('jastiper.detail-pesanan.index')->with('success','Detail pesanan diperbarui.');
    }

    public function destroy(DetailPesanan $detail_pesanan)
    {
        $detail_pesanan->delete();
        return redirect()->route('jastiper.detail-pesanan.index')->with('success','Detail pesanan dihapus.');
    }
}
