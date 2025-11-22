<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $query = Pembayaran::with('pesanan')->orderBy('created_at', 'desc');

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('id', $q)
                    ->orWhere('metode_pembayaran', 'like', "%{$q}%")
                    ->orWhere('status_pembayaran', 'like', "%{$q}%")
                    ->orWhereHas('pesanan', function ($qq) use ($q) {
                        $qq->where('no_hp', 'like', "%{$q}%")
                            ->orWhere('alamat_pengiriman', 'like', "%{$q}%");
                    });
            });
        }

        $pembayarans = $query->paginate(15)->withQueryString();
        return view('admin.pembayaran.index', compact('pembayarans', 'q'));
    }

    public function create()
    {
        $pesanans = Pesanan::orderBy('tanggal_pesan', 'desc')->get();
        return view('admin.pembayaran.create', compact('pesanans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pesanan_id' => ['required', 'exists:pesanans,id'],
            'metode_pembayaran' => ['required', Rule::in(['transfer', 'e-wallet', 'cod'])],
            'jumlah_bayar' => ['required', 'numeric', 'min:0'],
            'bukti_bayar' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'status_pembayaran' => ['required', Rule::in(['menunggu', 'valid', 'tidak_valid'])],
            'tanggal_bayar' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('bukti_bayar')) {
            $data['bukti_bayar'] = $request->file('bukti_bayar')->store('pembayarans', 'public');
        }

        if (empty($data['tanggal_bayar']))
            $data['tanggal_bayar'] = now();

        Pembayaran::create($data);
        return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    public function edit(Pembayaran $pembayaran)
    {
        $pesanans = Pesanan::orderBy('tanggal_pesan', 'desc')->get();
        return view('admin.pembayaran.edit', compact('pembayaran', 'pesanans'));
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $data = $request->validate([
            'pesanan_id' => ['required', 'exists:pesanans,id'],
            'metode_pembayaran' => ['required', Rule::in(['transfer', 'e-wallet', 'cod'])],
            'jumlah_bayar' => ['required', 'numeric', 'min:0'],
            'bukti_bayar' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'status_pembayaran' => ['required', Rule::in(['menunggu', 'valid', 'tidak_valid'])],
            'tanggal_bayar' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('bukti_bayar')) {
            if ($pembayaran->bukti_bayar && Storage::disk('public')->exists($pembayaran->bukti_bayar)) {
                Storage::disk('public')->delete($pembayaran->bukti_bayar);
            }
            $data['bukti_bayar'] = $request->file('bukti_bayar')->store('pembayarans', 'public');
        }

        $pembayaran->update($data);
        return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy(Pembayaran $pembayaran)
    {
        if ($pembayaran->bukti_bayar && Storage::disk('public')->exists($pembayaran->bukti_bayar)) {
            Storage::disk('public')->delete($pembayaran->bukti_bayar);
        }
        $pembayaran->delete();
        return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran dihapus.');
    }

    public function show(Pembayaran $pembayaran)
    {
        return view('admin.pembayaran.show', compact('pembayaran'));
    }
}
