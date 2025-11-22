<?php

namespace App\Http\Controllers\Jastiper;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjualan;
use App\Models\Barang;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function __construct()
    {
        // jastiper harus login; jika kamu punya middleware role jastiper, tambahkan 'role:jastiper'
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $q = $request->query('q');
        $query = LaporanPenjualan::with('barang')->orderBy('created_at', 'desc');

        if ($q) {
            $query->where(function ($qr) use ($q) {
                $qr->where('id', $q)
                   ->orWhere('nama_barang', 'like', "%$q%")
                   ->orWhere('status', 'like', "%$q%");
            });
        }

        $laporans = $query->paginate(15)->withQueryString();

        return view('jastiper.laporan.index', compact('laporans', 'q'));
    }

    public function create()
    {
        // perlu list barang untuk pilih
        $barangs = Barang::orderBy('nama_barang')->get();
        return view('jastiper.laporan.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'nama_barang' => 'required|string|max:150',
            'harga_barang' => 'required|numeric|min:0',
            'dana_masuk' => 'required|numeric|min:0',
            'status' => 'required|in:pending,selesai,dana_masuk',
            'tanggal_masuk' => 'nullable|date',
        ]);

        if (empty($data['tanggal_masuk'])) {
            $data['tanggal_masuk'] = now();
        }

        LaporanPenjualan::create($data);

        return redirect()->route('jastiper.laporan.index')
                         ->with('success', 'Laporan penjualan berhasil ditambahkan.');
    }

    public function edit(LaporanPenjualan $laporan)
    {
        $barangs = Barang::orderBy('nama_barang')->get();
        return view('jastiper.laporan.edit', compact('laporan', 'barangs'));
    }

    public function update(Request $request, LaporanPenjualan $laporan)
    {
        $data = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'nama_barang' => 'required|string|max:150',
            'harga_barang' => 'required|numeric|min:0',
            'dana_masuk' => 'required|numeric|min:0',
            'status' => 'required|in:pending,selesai,dana_masuk',
            'tanggal_masuk' => 'nullable|date',
        ]);

        $laporan->update($data);

        return redirect()->route('jastiper.laporan.index')
                         ->with('success', 'Laporan penjualan berhasil diperbarui.');
    }

    public function destroy(LaporanPenjualan $laporan)
    {
        $laporan->delete();

        return redirect()->route('jastiper.laporan.index')
                         ->with('success', 'Laporan penjualan berhasil dihapus.');
    }

    public function show(LaporanPenjualan $laporan)
    {
        // opsional: jika mau menampilkan detail
        return view('jastiper.laporan.show', compact('laporan'));
    }
}
