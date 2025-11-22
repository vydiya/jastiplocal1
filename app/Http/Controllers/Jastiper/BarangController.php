<?php

namespace App\Http\Controllers\Jastiper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Jastiper;
use App\Models\ValidasiProduk;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    // Index: tampilkan hanya barang milik jastiper yang login
    public function index(Request $request)
    {
        $user = Auth::user();
        $jastiper = $user->jastiper; // relation, bisa null
        $q = $request->query('q');

        // Jika user belum terdaftar sebagai jastiper, tampilkan fallback sementara (development).
        if (!$jastiper) {
            // Opsi: tampilkan semua barang supaya UI bisa ditata
            $query = Barang::with(['kategori'])->orderBy('created_at', 'desc');
        } else {
            // Normal: tampilkan hanya barang milik jastiper yang login
            $query = Barang::where('jastiper_id', $jastiper->id)
                ->with(['kategori'])
                ->orderBy('created_at', 'desc');
        }

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('nama_barang', 'like', "%{$q}%")
                    ->orWhere('id', $q);
            });
        }

        $barangs = $query->paginate(15)->withQueryString();

        $isJastiper = $jastiper ? true : false;

        return view('jastiper.barang.index', compact('barangs', 'q', 'isJastiper'));
    }

    // Show form create
    public function create()
    {
        $kategoris = Kategori::orderBy('nama')->get();
        return view('jastiper.barang.create', compact('kategoris'));
    }

    // Store barang (otomatis set jastiper_id dari user yang login)
    public function store(Request $request)
    {
        $user = Auth::user();
        $jastiper = $user->jastiper;
        if (!$jastiper)
            abort(403, 'Anda belum terdaftar sebagai jastiper.');

        $data = $request->validate([
            'kategori_id' => 'nullable|exists:kategoris,id',
            'nama_barang' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'is_available' => ['required', Rule::in(['yes', 'no'])],
            'foto_barang' => 'nullable|image|max:2048',
            'tanggal_input' => 'nullable|date',
        ]);

        $data['jastiper_id'] = $jastiper->id;
        // jangan set admin_id di sini kecuali memang diperlukan
        // $data['admin_id'] = $user->id; // biasanya admin diisi saat validasi

        if ($request->hasFile('foto_barang')) {
            $path = $request->file('foto_barang')->store('barangs', 'public');
            $data['foto_barang'] = $path;
        }

        // simpan barang
        $barang = Barang::create($data);

        // buat record validasi agar admin bisa memprosesnya
        ValidasiProduk::create([
            'barang_id' => $barang->id,
            // jangan set admin_id / tanggal_validasi sekarang — admin yang akan set
            // jika tabel validasi_produks punya kolom status_validasi default 'pending' maka akan otomatis pending
        ]);

        return redirect()->route('jastiper.barang.index')->with('success', 'Barang berhasil ditambahkan dan menunggu validasi admin.');
    }

    // Edit
    public function edit(Barang $barang)
    {
        $user = Auth::user();
        $jastiper = $user->jastiper;
        if (!$jastiper || $barang->jastiper_id !== $jastiper->id) {
            abort(403, 'Akses ditolak.');
        }

        $kategoris = Kategori::orderBy('nama')->get();
        return view('jastiper.barang.edit', compact('barang', 'kategoris'));
    }

    // Update
    public function update(Request $request, Barang $barang)
    {
        $user = Auth::user();
        $jastiper = $user->jastiper;
        if (!$jastiper || $barang->jastiper_id !== $jastiper->id) {
            abort(403, 'Akses ditolak.');
        }

        $data = $request->validate([
            'kategori_id' => 'nullable|exists:kategoris,id',
            'nama_barang' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'is_available' => ['required', Rule::in(['yes', 'no'])],
            'foto_barang' => 'nullable|image|max:2048',
            'tanggal_input' => 'nullable|date',
        ]);

        if ($request->hasFile('foto_barang')) {
            if ($barang->foto_barang && Storage::disk('public')->exists($barang->foto_barang)) {
                Storage::disk('public')->delete($barang->foto_barang);
            }
            $data['foto_barang'] = $request->file('foto_barang')->store('barangs', 'public');
        }

        $barang->update($data);
        return redirect()->route('jastiper.barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    // Destroy
    public function destroy(Barang $barang)
    {
        $user = Auth::user();
        $jastiper = $user->jastiper;
        if (!$jastiper || $barang->jastiper_id !== $jastiper->id) {
            abort(403, 'Akses ditolak.');
        }

        if ($barang->foto_barang && Storage::disk('public')->exists($barang->foto_barang)) {
            Storage::disk('public')->delete($barang->foto_barang);
        }
        $barang->delete();

        return redirect()->route('jastiper.barang.index')->with('success', 'Barang dihapus.');
    }
}
