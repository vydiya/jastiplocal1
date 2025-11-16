<?php

namespace App\Http\Controllers\Jastiper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\User;
use App\Models\Jastiper;

class PesananController extends Controller
{
    // GET /jastiper/pesanan
    public function index(Request $request)
    {
        $q = $request->query('q');
        $query = Pesanan::with(['user','jastiper'])->orderBy('tanggal_pesan','desc');

        if ($q) {
            $query->where(function($w) use ($q){
                $w->where('id', $q)
                  ->orWhere('no_hp', 'like', "%{$q}%")
                  ->orWhere('alamat_pengiriman', 'like', "%{$q}%");
            });
        }

        $pesanans = $query->paginate(15)->withQueryString();

        return view('jastiper.pesanan.index', compact('pesanans','q'));
    }

    // GET /jastiper/pesanan/create
    public function create()
    {
        // untuk select pemilik (user) dan jastiper
        $users = User::orderBy('name')->limit(200)->get();
        $jastipers = Jastiper::orderBy('nama_toko')->limit(200)->get();

        return view('jastiper.pesanan.create', compact('users','jastipers'));
    }

    // POST /jastiper/pesanan
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'jastiper_id' => 'nullable|exists:jastipers,id',
            'tanggal_pesan' => 'nullable|date',
            'total_harga' => 'required|numeric|min:0',
            'status_pesanan' => 'required|in:menunggu,diproses,dikirim,selesai,dibatalkan',
            'alamat_pengiriman' => 'nullable|string',
            'no_hp' => 'nullable|string|max:30',
        ]);

        if (empty($data['tanggal_pesan'])) unset($data['tanggal_pesan']);

        $pesanan = Pesanan::create($data);

        return redirect()->route('jastiper.pesanan.index')->with('success', 'Pesanan berhasil dibuat.');
    }

    // GET /jastiper/pesanan/{pesanan}/edit
    public function edit(Pesanan $pesanan)
    {
        $users = User::orderBy('name')->limit(200)->get();
        $jastipers = Jastiper::orderBy('nama_toko')->limit(200)->get();

        return view('jastiper.pesanan.edit', compact('pesanan','users','jastipers'));
    }

    // PUT /jastiper/pesanan/{pesanan}
    public function update(Request $request, Pesanan $pesanan)
    {
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'jastiper_id' => 'nullable|exists:jastipers,id',
            'tanggal_pesan' => 'nullable|date',
            'total_harga' => 'required|numeric|min:0',
            'status_pesanan' => 'required|in:menunggu,diproses,dikirim,selesai,dibatalkan',
            'alamat_pengiriman' => 'nullable|string',
            'no_hp' => 'nullable|string|max:30',
        ]);

        if (empty($data['tanggal_pesan'])) unset($data['tanggal_pesan']);

        $pesanan->update($data);

        return redirect()->route('jastiper.pesanan.index')->with('success', 'Pesanan berhasil diupdate.');
    }

    // DELETE /jastiper/pesanan/{pesanan}
    public function destroy(Pesanan $pesanan)
    {
        $pesanan->delete();
        return redirect()->route('jastiper.pesanan.index')->with('success', 'Pesanan dihapus.');
    }

    // optional show route (jika butuh)
    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['user','jastiper','detailPesanans','pembayaran']);
        return view('jastiper.pesanan.show', compact('pesanan'));
    }
}
