<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ulasan;

class UlasanController extends Controller
{
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

    public function show(Ulasan $ulasans)
    {
        return view('admin.ulasans.show', compact('ulasans'));
    }

    public function destroy(Ulasan $ulasans)
    {
        $ulasans->delete();
        return redirect()->route('admin.ulasans.index')->with('success','Ulasan dihapus.');
    }
}
