<?php

namespace App\Http\Controllers\Jastiper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rekening;
use Illuminate\Support\Facades\Auth;

class ControllerRekening extends Controller
{
    /**
     * Tampilkan daftar rekening.
     * Jika user memiliki jastiper (bukan admin) tampilkan hanya milik user tersebut,
     * jika admin tampilkan semua.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = Rekening::query()->orderBy('created_at', 'desc');

        // Jika user biasa (jastiper), batasi ke milik user yang login
        $user = Auth::user();
        if ($user && ! $user->can('admin')) { // sesuaikan cek role jika kamu punya gate/role
            $query->where('user_id', $user->id);
        }

        if ($q) {
            $query->where(function($w) use ($q) {
                $w->where('id', $q)
                  ->orWhere('nomor_akun', 'like', "%{$q}%")
                  ->orWhere('nama_penyedia', 'like', "%{$q}%")
                  ->orWhere('nama_pemilik', 'like', "%{$q}%");
            });
        }

        $rekenings = $query->paginate(15)->withQueryString();

        return view('jastiper.rekening.index', compact('rekenings','q'));
    }

    public function create()
    {
        return view('jastiper.rekening.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipe_rekening'  => 'nullable|in:bank,e-wallet',
            'nama_penyedia'  => 'required|string|max:100',
            'nama_pemilik'   => 'required|string|max:100',
            'nomor_akun'     => 'required|string|max:50',
            'status_aktif'   => 'nullable|in:aktif,nonaktif',
            'tanggal_input'  => 'nullable|date',
        ]);

        $user = Auth::user();
        $data['user_id'] = $user->id;
        if (empty($data['status_aktif'])) $data['status_aktif'] = 'aktif';
        if (empty($data['tanggal_input'])) $data['tanggal_input'] = now();

        Rekening::create($data);

        return redirect()->route('jastiper.rekening.index')->with('success', 'Rekening berhasil ditambahkan.');
    }

    public function show(Rekening $rekening)
    {
        // optional: jika butuh detail page
        $this->authorizeAccess($rekening);
        return view('jastiper.rekening.show', compact('rekening'));
    }

    public function edit(Rekening $rekening)
    {
        $this->authorizeAccess($rekening);
        return view('jastiper.rekening.edit', compact('rekening'));
    }

    public function update(Request $request, Rekening $rekening)
    {
        $this->authorizeAccess($rekening);

        $data = $request->validate([
            'tipe_rekening'  => 'nullable|in:bank,e-wallet',
            'nama_penyedia'  => 'required|string|max:100',
            'nama_pemilik'   => 'required|string|max:100',
            'nomor_akun'     => 'required|string|max:50',
            'status_aktif'   => 'nullable|in:aktif,nonaktif',
            'tanggal_input'  => 'nullable|date',
        ]);

        $rekening->update($data);

        return redirect()->route('jastiper.rekening.index')->with('success', 'Rekening berhasil diperbarui.');
    }

    public function destroy(Rekening $rekening)
    {
        $this->authorizeAccess($rekening);
        $rekening->delete();
        return redirect()->route('jastiper.rekening.index')->with('success', 'Rekening dihapus.');
    }

    /**
     * Pastikan hanya owner atau admin yang bisa mengedit/hapus
     */
    protected function authorizeAccess(Rekening $rekening)
    {
        $user = Auth::user();
        if (! $user) abort(403);
        if ($user->can('admin')) return true; // sesuaikan gate/role
        if ($rekening->user_id !== $user->id) abort(403, 'Akses ditolak.');
        return true;
    }
}
