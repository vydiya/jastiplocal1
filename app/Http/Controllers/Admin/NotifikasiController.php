<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $query = Notifikasi::with('user')->orderBy('tanggal_kirim', 'desc');

        if ($q) {
            $query->where(function($w) use ($q){
                $w->where('pesan','like',"%{$q}%")
                  ->orWhere('jenis_notifikasi','like',"%{$q}%")
                  ->orWhereHas('user', fn($u)=> $u->where('name','like',"%{$q}%"));
            });
        }

        $notifikasis = $query->paginate(20)->withQueryString();
        return view('admin.notifikasi.index', compact('notifikasis','q'));
    }

    public function create()
    {
        return view('admin.notifikasi.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jenis_notifikasi' => 'required|in:pembayaran,pesanan,ulasan,sistem',
            'pesan' => 'required|string',
            'status_baca' => 'nullable|in:belum,sudah',
        ]);
        $data['tanggal_kirim'] = now();
        Notifikasi::create($data);
        return redirect()->route('admin.notifikasi.index')->with('success','Notifikasi dibuat.');
    }

    public function edit(Notifikasi $notifikasi)
    {
        return view('admin.notifikasi.edit', compact('notifikasi'));
    }

    public function update(Request $request, Notifikasi $notifikasi)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jenis_notifikasi' => 'required|in:pembayaran,pesanan,ulasan,sistem',
            'pesan' => 'required|string',
            'status_baca' => 'nullable|in:belum,sudah',
        ]);
        $notifikasi->update($data);
        return redirect()->route('admin.notifikasi.index')->with('success','Notifikasi diperbarui.');
    }

    public function destroy(Notifikasi $notifikasi)
    {
        $notifikasi->delete();
        return redirect()->route('admin.notifikasi.index')->with('success','Notifikasi dihapus.');
    }
}
