<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\AlurDana;
use App\Notifications\DanaDilepaskan;
use App\Notifications\PembayaranDikonfirmasi;
use App\Notifications\PembayaranBerhasilDikonfirmasi;
use App\Notifications\PembayaranDitolak; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PembayaranAdminController extends Controller
{
    public function daftarKonfirmasiPembayaran(Request $request)
    {
        $search = $request->query('search');

        $query = Pesanan::where(function ($q_base) {
            
            // Tampilkan yang MENUNGGU dan yang DIBATALKAN (agar admin bisa lihat riwayat tolak)
            $q_base->whereIn('status_pesanan', ['MENUNGGU_KONFIRMASI_ADMIN', 'DIBATALKAN']) 
            
            ->orWhere(function ($q_pelepasan) {
                $q_pelepasan->where('status_pesanan', 'SELESAI')
                            ->where('status_dana_jastiper', '!=', 'DILEPASKAN');
            });
            
        })->with(['pembayaranUser', 'user', 'jastiper']); 

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%") 
                  ->orWhereHas('user', function ($q_user) use ($search) {
                      $q_user->where('name', 'like', "%{$search}%")
                             ->orWhere('username', 'like', "%{$search}%");
                  })
                  ->orWhereHas('jastiper', function ($q_jastiper) use ($search) {
                      $q_jastiper->where('nama_toko', 'like', "%{$search}%");
                  });
            });
        }

        $pesanans = $query->orderBy('created_at', 'desc')
                          ->paginate(10)
                          ->appends(['search' => $search]);
        return view('admin.konfirmasi-dana.index', compact('pesanans', 'search'));
    }

    public function konfirmasiPembayaran(Pesanan $pesanan)
    {
        if ($pesanan->status_pesanan !== 'MENUNGGU_KONFIRMASI_ADMIN') {
            return back()->with('error', 'Pesanan tidak dalam status yang dapat dikonfirmasi.');
        }

        try {
            DB::beginTransaction();

            $pembayaran = $pesanan->pembayaranUser;

            if (!$pembayaran) {
                DB::rollBack();
                return back()->with('error', 'Data pembayaran user tidak ditemukan.');
            }

            $pembayaran->update([
                'status_konfirmasi' => 'DIKONFIRMASI',
                'konfirmator_id'    => Auth::id(), 
            ]);

            $pesanan->update([
                'status_pesanan'       => 'DIPROSES',
                'status_dana_jastiper' => 'TERTAHAN',
            ]);

            DB::commit();
            
            $pesanan->jastiper->notify(new PembayaranDikonfirmasi($pesanan)); 
            $pesanan->user->notify(new PembayaranBerhasilDikonfirmasi($pesanan));

            return back()->with('success', 'Pembayaran User berhasil dikonfirmasi. Pesanan diteruskan ke Jastiper.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengonfirmasi pembayaran: ' . $e->getMessage());
        }
    }

    // --- METHOD TOLAK PEMBAYARAN (DIBATALKAN) ---
    public function tolakPembayaran(Pesanan $pesanan)
    {
        if ($pesanan->status_pesanan !== 'MENUNGGU_KONFIRMASI_ADMIN') {
            return back()->with('error', 'Pesanan tidak dalam status yang dapat ditolak.');
        }

        try {
            DB::beginTransaction();

            $pembayaran = $pesanan->pembayaranUser;

            if (!$pembayaran) {
                DB::rollBack();
                return back()->with('error', 'Data pembayaran user tidak ditemukan.');
            }

            $pembayaran->update([
                'status_konfirmasi' => 'DITOLAK',
                'konfirmator_id'    => Auth::id(),
            ]);

            // 2. Update status pesanan -> DIBATALKAN
            $pesanan->update([
                'status_pesanan' => 'DIBATALKAN',
            ]);

            DB::commit();

            // 3. Kirim Notifikasi ke User
            $pesanan->user->notify(new PembayaranDitolak($pesanan));

            return back()->with('success', 'Pembayaran ditolak. Status pesanan diubah menjadi DIBATALKAN.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }

   public function lepasDanaKeJastiper(Request $request, Pesanan $pesanan)
    {
        if ($pesanan->status_pesanan !== 'SELESAI' || $pesanan->status_dana_jastiper === 'DILEPASKAN') {
            return back()->with('error', 'Dana belum siap dilepas karena status pesanan belum SELESAI atau dana sudah DILEPASKAN.');
        }

        $request->validate([
            'bukti_tf_admin' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $path = null;
        try {
            DB::beginTransaction();

            $biayaAdmin = $pesanan->total_harga * 0.05;
            $jumlahBersih = $pesanan->total_harga - $biayaAdmin;
            
            $path = $request->file('bukti_tf_admin')->store('bukti_transfer/admin', 'public');

            AlurDana::create([
                'pesanan_id'        => $pesanan->id,
                'jenis_transaksi'   => 'PELEPASAN_DANA',
                'jumlah_dana'       => $jumlahBersih, 
                'bukti_tf_path'     => $path,
                'status_konfirmasi' => 'DIKONFIRMASI',
                'konfirmator_id'    => Auth::id(),
                'tanggal_transfer'  => now(),
                'biaya_admin'       => $biayaAdmin, 
            ]);

            $pesanan->update([
                'status_dana_jastiper' => 'DILEPASKAN',
            ]);

            DB::commit();
            
            $pesanan->jastiper->notify(new DanaDilepaskan($pesanan, $jumlahBersih, $biayaAdmin));

            return back()->with('success', 'Dana berhasil dilepas ke Jastiper (Dipotong Biaya Admin: Rp ' . number_format($biayaAdmin, 0, ',', '.') . ').');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }
            return back()->with('error', 'Gagal melepaskan dana: ' . $e->getMessage());
        }
    }
}