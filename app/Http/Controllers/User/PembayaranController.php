<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\AlurDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function uploadBuktiTransfer(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'bukti_tf' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        if ($pesanan->status_pesanan !== 'MENUNGGU_PEMBAYARAN') {
            return back()->with('error', 'Pesanan ini tidak lagi dalam status menunggu pembayaran.');
        }

        try {
            DB::beginTransaction();

            $path = $request->file('bukti_tf')->store('bukti_transfer/user', 'public');

            AlurDana::create([
                'pesanan_id'        => $pesanan->id,
                'jenis_transaksi'   => 'PEMBAYARAN_USER',
                'jumlah_dana'       => $pesanan->total_harga,
                'bukti_tf_path'     => $path,
                'status_konfirmasi' => 'MENUNGGU_CEK', 
                'konfirmator_id'    => null,
                'tanggal_transfer'  => now(),
            ]);

            $pesanan->update([
                'status_pesanan' => 'MENUNGGU_KONFIRMASI_ADMIN',
            ]);

            DB::commit();
            
            
            return redirect()->route('user.pesanan.detail', $pesanan)->with('success', 'Bukti transfer berhasil diunggah. Menunggu konfirmasi Admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($path)) {
                 Storage::disk('public')->delete($path);
            }
            return back()->with('error', 'Terjadi kesalahan saat menyimpan pembayaran: ' . $e->getMessage());
        }
    }
}