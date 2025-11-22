<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KelolaDana;
use App\Models\Pembayaran;
use App\Models\User;

class KelolaDanaController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = KelolaDana::with(['pembayaran', 'admin'])->orderBy('created_at', 'desc');

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('id', $q)
                  ->orWhere('status_dana', 'like', "%{$q}%")
                  ->orWhere('catatan', 'like', "%{$q}%")
                  ->orWhereHas('pembayaran', function($qq) use ($q) {
                      $qq->where('id', $q);
                  });
            });
        }

        $kelolaDanas = $query->paginate(15)->withQueryString();

        return view('admin.kelola-dana.index', compact('kelolaDanas','q'));
    }

    public function create()
    {
        // Tampilkan daftar pembayaran untuk dipilih (baru / menunggu / valid sesuai kebutuhan)
        $pembayarans = Pembayaran::orderBy('tanggal_bayar','desc')->limit(200)->get();
        $admins = User::orderBy('name')->get();
        return view('admin.kelola-dana.create', compact('pembayarans','admins'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pembayaran_id' => 'required|exists:pembayarans,id',
            'admin_id'      => 'nullable|exists:users,id',
            'status_dana'   => 'required|in:ditahan,dilepaskan,dikembalikan',
            'tanggal_update'=> 'nullable|date',
            'catatan'       => 'nullable|string',
        ]);

        KelolaDana::create($data);

        return redirect()->route('admin.kelola-dana.index')->with('success','Kelola dana berhasil disimpan.');
    }

    public function edit(KelolaDana $kelolaDana)
    {
        $pembayarans = Pembayaran::orderBy('tanggal_bayar','desc')->limit(200)->get();
        $admins = User::orderBy('name')->get();
        return view('admin.kelola-dana.edit', compact('kelolaDana','pembayarans','admins'));
    }

    public function update(Request $request, KelolaDana $kelolaDana)
    {
        $data = $request->validate([
            'pembayaran_id' => 'required|exists:pembayarans,id',
            'admin_id'      => 'nullable|exists:users,id',
            'status_dana'   => 'required|in:ditahan,dilepaskan,dikembalikan',
            'tanggal_update'=> 'nullable|date',
            'catatan'       => 'nullable|string',
        ]);

        $kelolaDana->update($data);

        return redirect()->route('admin.kelola-dana.index')->with('success','Kelola dana berhasil diperbarui.');
    }

    public function destroy(KelolaDana $kelolaDana)
    {
        $kelolaDana->delete();
        return redirect()->route('admin.kelola-dana.index')->with('success','Kelola dana dihapus.');
    }

    public function show(KelolaDana $kelolaDana)
    {
        return view('admin.kelola-dana.show', compact('kelolaDana'));
    }
}
