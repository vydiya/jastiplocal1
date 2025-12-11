<?php

namespace App\Http\Controllers\Jastiper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ulasan;

class UlasanController extends Controller
{
    /**
     * Tampilkan daftar ulasan untuk jastiper yang login
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $jastiper = $user->jastiper;

        if (!$jastiper) {
            abort(403, 'Anda bukan jastiper.');
        }

        $q = $request->query('q');

        $query = Ulasan::with('user', 'pesanan')
            ->where('jastiper_id', $jastiper->id)
            ->orderBy('tanggal_ulasan', 'desc');

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('komentar', 'like', "%{$q}%")
                  ->orWhere('rating', $q)
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%"));
            });
        }

        $ulasans = $query->paginate(15)->withQueryString();

        return view('Jastiper.ulasans.index', compact('ulasans', 'q'));
    }

    /**
     * Tampilkan detail ulasan
     */
    public function show(Ulasan $ulasan)
    {
        $user = Auth::user();
        $jastiper = $user->jastiper;

        if (!$jastiper || $ulasan->jastiper_id !== $jastiper->id) {
            abort(403, 'Anda tidak berhak melihat ulasan ini.');
        }

        return view('Jastiper.ulasans.show', compact('ulasan'));
    }
}
