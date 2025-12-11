<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rekening;
use App\Models\User; // Digunakan untuk validasi user_id
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RekeningController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        
        $query = Rekening::query();

        $query->where('user_id', Auth::id());

        if ($q) {
            $query->where(function ($subQuery) use ($q) {
                $subQuery->where('nomor_akun', 'like', '%' . $q . '%')
                         ->orWhere('nama_pemilik', 'like', '%' . $q . '%')
                         ->orWhere('id', $q);
            });
        }
        
        $rekenings = $query->with('user') 
                           ->orderBy('created_at', 'desc')
                           ->get(); 

        return view('admin.rekening.index', compact('rekenings', 'q'));
    }


    /**
     * Menampilkan form untuk membuat rekening baru.
     */
    public function create()
    {
        return view('admin.rekening.create');
    }

    /**
     * Menyimpan rekening baru ke database (Admin dapat menetapkan user_id).
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id', // Pastikan user_id valid
            'tipe_rekening' => ['required', Rule::in(['bank', 'e-wallet'])], 
            'nama_penyedia' => 'required|string|max:100',
            'nama_pemilik' => 'required|string|max:100',
            'nomor_akun' => 'required|string|max:50|unique:rekenings,nomor_akun,NULL,id,user_id,' . $request->user_id,
        ], [
            'user_id.exists' => 'User ID tidak ditemukan.',
            'nomor_akun.unique' => 'Nomor akun ini sudah terdaftar untuk user yang dipilih.'
        ]);
        
        Rekening::create([
            'user_id' => $validatedData['user_id'],
            'tipe_rekening' => $validatedData['tipe_rekening'],
            'nama_penyedia' => $validatedData['nama_penyedia'],
            'nama_pemilik' => $validatedData['nama_pemilik'],
            'nomor_akun' => $validatedData['nomor_akun'],
            'status_aktif' => 'aktif', // Default saat membuat selalu aktif
            'tanggal_input' => now(),
        ]);

        return redirect()->route('admin.rekening.index')
                         ->with('success', 'Rekening berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail rekening tertentu.
     */
    public function show(Rekening $rekening)
    {
        return view('admin.rekening.show', compact('rekening'));
    }

    /**
     * Menampilkan form edit rekening.
     */
    public function edit(Rekening $rekening)
    {
        // Admin bisa mengedit rekening mana pun
        return view('admin.rekening.edit', compact('rekening'));
    }

    /**
     * Memperbarui data rekening.
     */
    public function update(Request $request, Rekening $rekening)
    {
        // Admin bisa mengupdate rekening mana pun, tidak perlu cek Auth::id()
        $validatedData = $request->validate([
            'tipe_rekening' => ['sometimes', 'required', Rule::in(['bank', 'e-wallet'])],
            'nama_penyedia' => 'sometimes|required|string|max:100',
            'nama_pemilik' => 'sometimes|required|string|max:100',
            'nomor_akun' => 'sometimes|required|string|max:50|unique:rekenings,nomor_akun,' . $rekening->id . ',id,user_id,' . $rekening->user_id,
            'status_aktif' => ['sometimes', 'required', Rule::in(['aktif', 'nonaktif'])], 
        ]);

        $rekening->update($validatedData);

        return redirect()->route('admin.rekening.index')
                         ->with('success', 'Rekening berhasil diperbarui.');
    }

    /**
     * Menghapus (soft delete) rekening.
     */
    public function destroy(Rekening $rekening)
    {
        // Admin bisa menghapus rekening mana pun, tidak perlu cek Auth::id()
        
        // FIX: Cek Jastiper harus dilakukan pada User pemilik rekening, bukan Admin
        $user = $rekening->user;

        if ($user && $user->jastiper && $user->jastiper->rekening_id === $rekening->id) {
            $user->jastiper->rekening_id = null;
            $user->jastiper->save();
        }

        $rekening->delete();

        return redirect()->route('admin.rekening.index')
                         ->with('success', 'Rekening berhasil dihapus.');
    }
}