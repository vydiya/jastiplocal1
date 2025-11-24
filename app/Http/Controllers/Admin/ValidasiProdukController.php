<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ValidasiProduk;
use Illuminate\Support\Facades\Auth;

class ValidasiProdukController extends Controller
{
    // tampilkan daftar (sesuai nama variabel di view: $validasiProduks)
    public function index(Request $request)
    {
        $q = $request->get('q');

        $validasiProduks = ValidasiProduk::with(['barang','admin','barang.jastiper'])
            ->when($q, function($query) use ($q) {
                // jangan search kolom yang sudah dihapus (status_validasi)
                $query->where('id', 'like', "%{$q}%")
                      ->orWhereHas('barang', function($qb) use ($q) {
                          $qb->where('nama_barang', 'like', "%{$q}%");
                      });
            })
            ->orderByDesc('id')
            ->paginate(15);

        return view('admin.validasi-produk.index', compact('validasiProduks', 'q'));
    }

    public function action(Request $request, ValidasiProduk $validasi)
    {
        $request->validate([
            'action' => 'required|in:setujui,tolak'
        ]);

        $status = $request->action === 'setujui' ? 'disetujui' : 'ditolak';
        $validasi->status_validasi = $status;
        $validasi->admin_id = Auth::id();
        $validasi->tanggal_validasi = now();
        $validasi->save();

        return response()->json([
            'success' => true,
            'status' => $status, // transient status untuk frontend
            'tanggal_validasi' => $validasi->tanggal_validasi ? $validasi->tanggal_validasi->format('Y-m-d H:i') : null,
            'admin_name' => $validasi->admin?->name ?? Auth::user()->name,
            'id' => $validasi->id,
        ]);
    }
}
