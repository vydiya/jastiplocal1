<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jastiper;
use App\Models\User;

class JastiperController extends Controller
{
    // GET /admin/jastiper
    public function index()
    {
        $jastipers = Jastiper::with('user')->orderBy('id', 'desc')->paginate(15);
        return view('admin.jastiper.index', compact('jastipers'));
    }

    // GET /admin/jastiper/create
    public function create()
    {
        // jika ingin memilih user yang tersedia:
        $users = User::orderBy('name')->get();
        return view('admin.jastiper.create', compact('users'));
    }

    // POST /admin/jastiper
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nama_toko' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
            'metode_pembayaran' => 'required|in:transfer,e-wallet',
            'status_verifikasi' => 'required|in:pending,disetujui,ditolak',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        $data['rating'] = $data['rating'] ?? 0.0;
        Jastiper::create($data);

        return redirect()->route('admin.jastiper.index')->with('success', 'Jastiper berhasil dibuat.');
    }

    // GET /admin/jastiper/{jastiper}/edit
    public function edit(Jastiper $jastiper)
    {
        $users = User::orderBy('name')->get();
        return view('admin.jastiper.edit', compact('jastiper','users'));
    }

    // PUT/PATCH /admin/jastiper/{jastiper}
    public function update(Request $request, Jastiper $jastiper)
    {
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'nama_toko' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:30',
            'alamat' => 'nullable|string',
            'metode_pembayaran' => 'required|in:transfer,e-wallet',
            'status_verifikasi' => 'required|in:pending,disetujui,ditolak',
            'rating' => 'nullable|numeric|min:0|max:5',
        ]);

        $data['rating'] = $data['rating'] ?? 0.0;
        $jastiper->update($data);

        return redirect()->route('admin.jastiper.index')->with('success', 'Jastiper berhasil diperbarui.');
    }

    // DELETE /admin/jastiper/{jastiper}
    public function destroy(Jastiper $jastiper)
    {
        $jastiper->delete();
        return redirect()->route('admin.jastiper.index')->with('success', 'Jastiper berhasil dihapus.');
    }

    // (Optional) show detail
    public function show(Jastiper $jastiper)
    {
        return view('admin.jastiper.show', compact('jastiper'));
    }
}
