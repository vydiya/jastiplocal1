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

    /**
     * Terima AJAX action: setujui | tolak
     * POST admin/validasi-produk/{validasi}/action
     *
     * Karena kolom `status_validasi` telah dihapus dari DB,
     * controller tidak mencoba menulis kolom itu. Sebagai gantinya
     * kita menyimpan admin_id & tanggal_validasi ke DB (kolom ada),
     * dan mengembalikan status sebagai value di response JSON
     * agar frontend bisa menampilkan / mewarnai tombol.
     */
    public function action(Request $request, ValidasiProduk $validasi)
    {
        $request->validate([
            'action' => 'required|in:setujui,tolak'
        ]);

        // tentukan status secara transient (tidak disimpan ke DB karena kolom di-drop)
        $status = $request->action === 'setujui' ? 'disetujui' : 'ditolak';

        // update fields yang masih ada
        $validasi->admin_id = Auth::id();
        $validasi->tanggal_validasi = now();

        // Jika kamu punya kolom lain untuk menyimpan keputusan, simpan di situ.
        // Karena kolom status_validasi tidak ada, kita *tidak* mencoba menulisnya.
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
