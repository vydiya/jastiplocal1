<?php

namespace App\Http\Controllers\Jastiper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;

class ControllerKategoriBarang extends Controller
{
    // ===========================
    // INDEX
    // ===========================
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = Kategori::orderBy('nama');

        if ($q) {
            $query->where('nama', 'like', "%{$q}%")
                  ->orWhere('id', $q);
        }

        $kategoris = $query->paginate(15)->withQueryString();

        return view('jastiper.kategori-barang.index', compact('kategoris', 'q'));
    }

    // ===========================
    // CREATE
    // ===========================
    public function create()
    {
        return view('jastiper.kategori-barang.create');
    }

    // ===========================
    // STORE
    // ===========================
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
        ]);

        Kategori::create($data);

        return redirect()
            ->route('jastiper.kategori-barang.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    // ===========================
    // EDIT
    // ===========================
    public function edit(Kategori $kategori)
    {
        return view('jastiper.kategori-barang.edit', compact('kategori'));
    }

    // ===========================
    // UPDATE
    // ===========================
    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
        ]);

        $kategori->update($data);

        return redirect()
            ->route('jastiper.kategori-barang.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    // ===========================
    // DESTROY
    // ===========================
    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return redirect()
            ->route('jastiper.kategori-barang.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
