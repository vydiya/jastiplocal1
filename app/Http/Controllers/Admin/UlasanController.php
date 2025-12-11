<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ulasan;

class UlasanController extends Controller
{
    /**
     * Tampilkan daftar semua ulasan (untuk Admin) dengan fitur pencarian.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = Ulasan::with(['user','jastiper','pesanan'])
            ->orderBy('tanggal_ulasan', 'desc');

        if ($q) {
            $query->where(function($w) use ($q) {
                $w->where('komentar', 'like', "%{$q}%")
                  ->orWhere('rating', $q)
                  ->orWhereHas('user', fn($u)=> $u->where('name', 'like', "%{$q}%"))
                  ->orWhereHas('jastiper', fn($j)=> $j->where('nama_toko', 'like', "%{$q}%"));
            });
        }

        $ulasans = $query->paginate(20)->withQueryString();

        return view('admin.ulasans.index', compact('ulasans','q'));
    }

    /**
     * Tampilkan detail ulasan tunggal.
     * Menggunakan $ulasan (singular) sebagai parameter.
     */
    public function show(Ulasan $ulasan)
    {
        $ulasan->load(['user', 'jastiper', 'pesanan']);
        return view('admin.ulasans.show', compact('ulasan'));
    }

    /**
     * Hapus ulasan.
     * Menggunakan $ulasan (singular) sebagai parameter.
     */
    public function destroy(Ulasan $ulasan)
    {
        $ulasan->delete();
        return redirect()->route('admin.ulasans.index')->with('success','Ulasan berhasil dihapus.');
    }
}