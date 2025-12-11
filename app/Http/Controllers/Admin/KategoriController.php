<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kategori;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::orderBy('nama', 'asc')->get();

        return view('admin.kategori-barang.index', compact('kategoris'));
    }
    public function create()
    {
        return view('admin.kategori-barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategoris,nama',
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique' => 'Nama kategori ini sudah ada.',
        ]);

        try {
            Kategori::create([
                'nama' => $request->nama,
            ]);

            return redirect()->route('kategori-barang.index')->with('success', 'Kategori baru berhasil ditambahkan.');

        } catch (\Exception $e) {
            Log::error("Gagal menyimpan kategori: " . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan kategori.');
        }
    }

    /**
     * Menampilkan form untuk mengedit Kategori (Update - Form).
     *
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\View\View
     */
    public function edit(Kategori $kategori)
    {
        return view('admin.kategori-barang.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategoris,nama,' . $kategori->id,
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique' => 'Nama kategori ini sudah ada.',
        ]);

        try {
            $kategori->update([
                'nama' => $request->nama,
            ]);

            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error("Gagal memperbarui kategori ID {$kategori->id}: " . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui kategori.');
        }
    }

    public function destroy(Kategori $kategori)
    {
        try {
            $kategori->delete();

            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus (soft deleted).');

        } catch (\Exception $e) {
            Log::error("Gagal menghapus kategori ID {$kategori->id}: " . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus kategori. Pastikan tidak ada barang yang terkait dengan kategori ini.');
        }
    }
}