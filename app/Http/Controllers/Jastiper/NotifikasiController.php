<?php
namespace App\Http\Controllers\Jastiper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $q = $request->query('q');

        $query = Notifikasi::where('user_id', $user->id)->orderBy('tanggal_kirim','desc');

        if ($q) {
            $query->where(function($w) use ($q){
                $w->where('pesan','like',"%{$q}%")
                  ->orWhere('jenis_notifikasi','like',"%{$q}%");
            });
        }

        $notifikasis = $query->paginate(20)->withQueryString();
        return view('jastiper.notifikasi.index', compact('notifikasis','q'));
    }

    public function create()
    {
        // Jastiper mungkin tidak boleh bikin notifikasi — tapi saya sediakan form jika butuh.
        return view('jastiper.notifikasi.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'jenis_notifikasi' => 'required|in:pembayaran,pesanan,ulasan,sistem',
            'pesan' => 'required|string',
        ]);
        $data['user_id'] = $user->id;
        $data['tanggal_kirim'] = now();
        Notifikasi::create($data);
        return redirect()->route('jastiper.notifikasi.index')->with('success','Notifikasi dibuat.');
    }

    public function edit(Notifikasi $notifikasi)
    {
        $this->authorize('update', $notifikasi); // optional: gate/policy
        return view('jastiper.notifikasi.edit', compact('notifikasi'));
    }

    public function update(Request $request, Notifikasi $notifikasi)
    {
        $this->authorize('update', $notifikasi);
        $data = $request->validate([
            'jenis_notifikasi' => 'required|in:pembayaran,pesanan,ulasan,sistem',
            'pesan' => 'required|string',
            'status_baca' => 'nullable|in:belum,sudah',
        ]);
        $notifikasi->update($data);
        return redirect()->route('jastiper.notifikasi.index')->with('success','Notifikasi diperbarui.');
    }

    public function destroy(Notifikasi $notifikasi)
    {
        $this->authorize('delete', $notifikasi);
        $notifikasi->delete();
        return redirect()->route('jastiper.notifikasi.index')->with('success','Notifikasi dihapus.');
    }
}
