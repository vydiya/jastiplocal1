<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard (index.blade.php)
     */
    public function index()
    {
        // Jika mau kirim data ke view, buat array/data di sini
        // contoh: $stats = [...];
        // return view('admin.dashboard.index', compact('stats'));

        return view('admin.dashboard.index');
    }

    /**
     * Tampilkan form untuk membuat data baru
     */
    public function create()
    {
        return view('admin.dashboard.create');
    }

    /**
     * Simpan data baru (store)
     */
    public function store(Request $request)
    {
        // Contoh validasi sederhana:
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // TODO: simpan ke database (Model belum disediakan) ->
        // Example:
        // Item::create($validated);

        // Untuk sekarang hanya beri feedback dan redirect
        return redirect()->route('admin.dashboard.index')
                         ->with('success', 'Data berhasil disimpan (stub).');
    }

    /**
     * Tampilkan detail item (show)
     */
    public function show($id)
    {
        // TODO: ambil data dari model, contoh:
        // $item = Item::findOrFail($id);
        // return view('admin.dashboard.show', compact('item'));

        // Karena belum ada model, kita kirim id sebagai contoh
        return view('admin.dashboard.show', ['id' => $id]);
    }

    /**
     * Tampilkan form edit untuk item
     */
    public function edit($id)
    {
        // TODO: ambil data dari model, contoh:
        // $item = Item::findOrFail($id);
        // return view('admin.dashboard.edit', compact('item', 'id'));

        // Stub:
        $item = (object)[
            'id' => $id,
            'name' => 'Contoh Nama '.$id,
            'description' => 'Contoh deskripsi untuk item '.$id,
        ];

        return view('admin.dashboard.edit', compact('item', 'id'));
    }

    /**
     * Simpan perubahan (update)
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // TODO: update model:
        // $item = Item::findOrFail($id);
        // $item->update($validated);

        return redirect()->route('admin.dashboard.show', $id)
                         ->with('success', 'Data berhasil diupdate (stub).');
    }

    /**
     * Hapus item
     */
    public function destroy($id)
    {
        // TODO: hapus model:
        // $item = Item::findOrFail($id);
        // $item->delete();

        return redirect()->route('admin.dashboard.index')
                         ->with('success', 'Data berhasil dihapus (stub).');
    }
}
