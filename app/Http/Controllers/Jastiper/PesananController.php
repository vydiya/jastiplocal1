<?php

namespace App\Http\Controllers\Jastiper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pesanan;
use App\Models\User;
use App\Models\Jastiper;
use App\Notifications\PesananSiapDikirim;

class PesananController extends Controller
{
    protected function getJastiperId()
    {
        if (!Auth::check()) {
            abort(403, 'Anda harus login.');
        }

        $user = Auth::user();
        
        $jastiperId = $user->jastiper->id ?? null; 
        
        if (!$jastiperId) {
            abort(403, 'Akses tidak diizinkan. Anda bukan terdaftar sebagai Jastiper.');
        }

        return $jastiperId;
    }

    public function index(Request $request)
    {
        $jastiperId = $this->getJastiperId(); 
        
        $q = $request->query('q');
        
        $status = $request->query('status', ['DIPROSES', 'SIAP_DIKIRIM']); 
        
        $query = Pesanan::with(['user','jastiper'])
            ->where('jastiper_id', $jastiperId) 
            ->whereIn('status_pesanan', (array) $status) 
            ->orderBy('tanggal_pesan','desc');

        if ($q) {
            $query->where(function($w) use ($q){
                $w->where('id', $q)
                  ->orWhere('no_hp', 'like', "%{$q}%")
                  ->orWhere('alamat_pengiriman', 'like', "%{$q}%");
            });
        }
        
        $pesanans = $query->paginate(15)->withQueryString();

        return view('jastiper.pesanan.index', compact('pesanans','q', 'status')); 
    }
    public function showData(Pesanan $pesanan)
    {
        $jastiperId = $this->getJastiperId(); 
        
        if ($pesanan->jastiper_id !== $jastiperId) {
            return response()->json(['error' => 'Pesanan tidak ditemukan atau akses tidak diizinkan.'], 404);
        }
        
        $pesanan->load(['user','jastiper','detailPesanans.barang','pembayaran']); 
        
        $data = [
            'id' => $pesanan->id,
            'pemesan' => $pesanan->user?->name ?? 'N/A',
            'tanggal_pesan' => $pesanan->tanggal_pesan ? $pesanan->tanggal_pesan->format('d M Y H:i') : 'N/A',
            'total_harga' => $pesanan->total_harga, 
            'status_pesanan' => $pesanan->status_pesanan,
            'status_dana_jastiper' => $pesanan->status_dana_jastiper,
            'alamat_pengiriman' => $pesanan->alamat_pengiriman,
            'no_hp' => $pesanan->no_hp,
            
            'detail_pesanans' => $pesanan->detailPesanans->map(function ($detail) {
                return [
                    'nama_barang' => $detail->barang?->nama_barang ?? 'Barang Dihapus',
                    'jumlah' => $detail->jumlah,
                    'harga_satuan' => $detail->harga_satuan,
                    'subtotal' => $detail->subtotal,
                ];
            }),
            
            'pembayaran' => $pesanan->pembayaran->map(function ($pembayaran) {
                return [
                    'metode_pembayaran' => $pembayaran->metode_pembayaran,
                    'jumlah_bayar' => $pembayaran->jumlah_bayar,
                    'status_pembayaran' => $pembayaran->status_pembayaran,
                ];
            }),
        ];

        return response()->json($data);
    }

    public function edit(Pesanan $pesanan)
    {
        $this->getJastiperId(); 
        
        $users = User::orderBy('name')->limit(200)->get();
        $jastipers = Jastiper::orderBy('nama_toko')->limit(200)->get();

        return view('Jastiper.pesanan.edit', compact('pesanan','users','jastipers'));
    }

    public function update(Request $request, Pesanan $pesanan)
    {
        $jastiperId = $this->getJastiperId(); 
        if ($pesanan->jastiper_id !== $jastiperId) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit pesanan ini.');
        }

        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'jastiper_id' => 'nullable|exists:jastipers,id',
            'tanggal_pesan' => 'nullable|date',
            'total_harga' => 'required|numeric|min:0',
            'status_pesanan' => 'required|in:MENUNGGU,DIPROSES,SIAP_DIKIRIM,DIKIRIM,SELESAI,DIBATALKAN', 
            'alamat_pengiriman' => 'nullable|string',
            'no_hp' => 'nullable|string|max:30',
        ]);

        if (empty($data['tanggal_pesan'])) unset($data['tanggal_pesan']);

        $pesanan->update($data);

        return redirect()->route('jastiper.pesanan.index')->with('success', 'Pesanan berhasil diupdate.');
    }
    
public function updateStatusToSiapDikirim(Pesanan $pesanan)
    {
        // Otorisasi
        $jastiperId = $this->getJastiperId(); 
        if ($pesanan->jastiper_id !== $jastiperId) {
            abort(403, 'Anda tidak memiliki izin untuk mengupdate pesanan ini.');
        }

        // Hanya update jika status saat ini masih DIPROSES
        if ($pesanan->status_pesanan == 'DIPROSES') {
            $pesanan->update(['status_pesanan' => 'SIAP_DIKIRIM']);

            // ======================================================
            // KIRIM NOTIFIKASI KE PEMBELI (USER)
            // ======================================================
            // Pastikan relasi 'user' ada di model Pesanan
            if ($pesanan->user) {
                $pesanan->user->notify(new PesananSiapDikirim($pesanan));
            }

            return redirect()->route('jastiper.pesanan.index')
                ->with('success', "Status Pesanan #{$pesanan->id} berhasil diubah menjadi SIAP DIKIRIM.");
        }

        return redirect()->route('jastiper.pesanan.index')
            ->with('error', "Status Pesanan #{$pesanan->id} tidak dapat diubah ke SIAP DIKIRIM karena status saat ini adalah {$pesanan->status_pesanan}.");
    }

    // DELETE /jastiper/pesanan/{pesanan}
    public function destroy(Pesanan $pesanan)
    {
        // Otorisasi
        $jastiperId = $this->getJastiperId(); 
        if ($pesanan->jastiper_id !== $jastiperId) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus pesanan ini.');
        }

        $pesanan->delete();
        return redirect()->route('jastiper.pesanan.index')->with('success', 'Pesanan dihapus.');
    }

    // optional show route (jika butuh)
    public function show(Pesanan $pesanan)
    {
        $this->getJastiperId(); // Otorisasi
        if ($pesanan->jastiper_id !== $this->getJastiperId()) {
            abort(404);
        }
        
        $pesanan->load(['user','jastiper','detailPesanans','pembayaran']);
        return view('Jastiper.pesanan.show', compact('pesanan'));
    }
}