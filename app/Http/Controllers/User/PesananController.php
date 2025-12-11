<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pesanan;
use App\Models\User;
use App\Models\Jastiper;
use Illuminate\Support\Facades\Notification; 
use App\Notifications\PesananSelesaiAdmin; 
use App\Notifications\PesananSelesaiJastiper;

class PesananController extends Controller
{
    public function riwayat(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $q = $request->query('q');
        
        $status = $request->query('status'); 
        
        $query = Pesanan::with(['user', 'jastiper', 'detailPesanans.barang'])
            ->where('user_id', $userId)
            ->orderBy('tanggal_pesan', 'desc');

        if ($status) {
            $query->whereIn('status_pesanan', (array) $status);
        }

        if ($q) {
            $query->where(function($w) use ($q){
                $w->where('id', 'like', "%{$q}%")
                  ->orWhere('no_hp', 'like', "%{$q}%")
                  ->orWhere('alamat_pengiriman', 'like', "%{$q}%");
            });
        }
        
        $pesanans = $query->paginate(10)->withQueryString();

        $user = Auth::user();
        $userName = $user->name ?? 'Pengguna';
        $cartCount = count(session('cart', []));

        return view('user.pesanan.riwayat', compact('pesanans', 'q', 'status', 'userName', 'cartCount'));
    }

    public function show(Pesanan $pesanan)
    {
        if ($pesanan->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan. Pesanan bukan milik Anda.');
        }

        $pesanan->load(['user', 'jastiper', 'detailPesanans.barang', 'alurDana']);
        
        return view('user.pesanan.show', compact('pesanan'));
    }
    
   public function completeOrder($id)
{
    $pesanan = Pesanan::where('id', $id)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

    if ($pesanan->status_pesanan !== 'SIAP_DIKIRIM') {
        return back()->with('error', 'Pesanan tidak dalam status siap dikirim.');
    }

    try {
        DB::beginTransaction();
        
        // 1. Update Status
        $pesanan->update(['status_pesanan' => 'SELESAI']);
        if ($pesanan->jastiper) {
            $pesanan->jastiper->notify(new PesananSelesaiJastiper($pesanan));
        }

        $admins = User::where('role', 'admin')->get(); 
        if ($admins->count() > 0) {
            Notification::send($admins, new PesananSelesaiAdmin($pesanan));
        }

        DB::commit();
        return back()->with('success', 'Pesanan berhasil diselesaikan. Terima kasih!');
        
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Gagal menyelesaikan pesanan: ' . $e->getMessage());
    }
}
}