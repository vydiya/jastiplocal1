<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Ulasan;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    public function store(Request $request, $pesananId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        $pesanan = Pesanan::where('id', $pesananId)
                          ->where('user_id', Auth::id())
                          ->where('status_pesanan', 'SELESAI')
                          ->firstOrFail();

        if ($pesanan->has_reviewed) {
            return back()->with('error', 'Pesanan ini sudah diulas.');
        }

        Ulasan::create([
            'pesanan_id' => $pesanan->id,
            'user_id' => Auth::id(),
            'jastiper_id' => $pesanan->jastiper_id, // Asumsi relasi jastiper_id ada di Pesanan
            'rating' => $request->rating,
            'komentar' => $request->komentar,
            'tanggal_ulasan' => now(),
        ]);

        $pesanan->has_reviewed = true;
        $pesanan->save();

        return back()->with('success', 'Ulasan Anda berhasil disimpan!');
    }
}