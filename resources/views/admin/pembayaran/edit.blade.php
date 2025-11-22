{{-- resources/views/admin/pembayaran/edit.blade.php --}}
@extends('layout.admin-app')

@section('title','Edit Pembayaran')
@section('page-title','Edit Pembayaran')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
@endpush

@section('content')
<div class="form-panel">
    <h2 class="form-title">Edit Pembayaran #{{ $pembayaran->id }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-4" style="margin-bottom:18px; padding:10px 14px; border-radius:8px; background:#fff0f0; border:1px solid #f2c6c6; color:#8a1f1f;">
            <ul class="mb-0" style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $err) <li>{{ $err }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.pembayaran.update', $pembayaran) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Pesanan <span class="text-danger">*</span></label>
            <select name="pesanan_id" class="form-control" required>
                <option value="">-- Pilih Pesanan --</option>
                @foreach($pesanans as $ps)
                    <option value="{{ $ps->id }}" {{ (string) old('pesanan_id', $pembayaran->pesanan_id) === (string) $ps->id ? 'selected' : '' }}>
                        #{{ $ps->id }} — {{ $ps->no_hp ?? '-' }} — Rp {{ number_format($ps->total_harga,0,',','.') }}
                    </option>
                @endforeach
            </select>
            @error('pesanan_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label class="form-label">Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="form-control">
                        <option value="transfer" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="e-wallet" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                        <option value="cod" {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'cod' ? 'selected' : '' }}>COD</option>
                    </select>
                    @error('metode_pembayaran') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-label">Jumlah Bayar <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="jumlah_bayar" value="{{ old('jumlah_bayar', $pembayaran->jumlah_bayar) }}" class="form-control" required>
                    @error('jumlah_bayar') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Bukti Bayar (jpg/png/pdf)</label>

            @if($pembayaran->bukti_bayar)
                <div style="margin-bottom:8px;">
                    @php
                        $bukti = asset('storage/'.$pembayaran->bukti_bayar);
                        $ext = pathinfo(storage_path('app/public/'.$pembayaran->bukti_bayar), PATHINFO_EXTENSION);
                    @endphp

                    {{-- tampilkan link preview; jika gambar bisa juga tampilkan thumbnail sederhana --}}
                    <a href="{{ $bukti }}" target="_blank" rel="noopener">Lihat bukti saat ini</a>

                    @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif']))
                        <div style="margin-top:8px;">
                            <img src="{{ $bukti }}" alt="bukti-bayar" style="max-width:220px; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,0.08);">
                        </div>
                    @endif
                </div>
            @endif

            <input type="file" name="bukti_bayar" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            <small class="form-help">Unggah file baru untuk mengganti bukti (jpg/png/pdf). Maks ukuran sesuai konfigurasi server.</small>
            @error('bukti_bayar') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Status Pembayaran</label>
            <select name="status_pembayaran" class="form-control">
                <option value="menunggu" {{ old('status_pembayaran', $pembayaran->status_pembayaran) == 'menunggu' ? 'selected' : '' }}>menunggu</option>
                <option value="valid" {{ old('status_pembayaran', $pembayaran->status_pembayaran) == 'valid' ? 'selected' : '' }}>valid</option>
                <option value="tidak_valid" {{ old('status_pembayaran', $pembayaran->status_pembayaran) == 'tidak_valid' ? 'selected' : '' }}>tidak_valid</option>
            </select>
            @error('status_pembayaran') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
