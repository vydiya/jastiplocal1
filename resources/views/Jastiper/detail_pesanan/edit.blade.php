{{-- resources/views/jastiper/detail-pesanan/edit.blade.php --}}
@extends('layout.jastiper-app')

@section('title', 'Edit Detail Pesanan')
@section('page-title', 'Edit Detail Pesanan')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
@endpush

@section('content')
<div class="form-panel">
    <h2 class="form-title">Edit Detail Pesanan</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-3" style="margin-bottom:18px; padding:10px 14px; border-radius:8px; background:#fff0f0; border:1px solid #f2c6c6; color:#8a1f1f;">
            <ul class="mb-0" style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('jastiper.detail-pesanan.update', $detail->id) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')

        {{-- Nomor Pesanan (readonly) --}}
        <div class="form-group">
            <label class="form-label">Nomor Pesanan</label>
            <input type="text" class="form-control" value="#{{ $detail->pesanan_id }}" readonly>
            <input type="hidden" name="pesanan_id" value="{{ old('pesanan_id', $detail->pesanan_id) }}">
        </div>

        {{-- Barang --}}
        <div class="form-group">
            <label class="form-label">Barang <span class="text-danger">*</span></label>

            @php
                // gunakan $barangs jika disediakan oleh controller, jika tidak fallback query (lebih baik sediakan dari controller)
                $items = isset($barangs) ? $barangs : \App\Models\Barang::orderBy('nama_barang')->get();
            @endphp

            <select name="barang_id" class="form-control" required>
                <option value="">-- Pilih Barang --</option>
                @foreach($items as $b)
                    <option value="{{ $b->id }}"
                        {{ (string) old('barang_id', $detail->barang_id) === (string) $b->id ? 'selected' : '' }}>
                        {{ $b->nama_barang }} — Rp {{ number_format($b->harga, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>
            @error('barang_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Jumlah --}}
        <div class="form-group">
            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
            <input type="number" name="jumlah" min="1" class="form-control"
                   value="{{ old('jumlah', $detail->jumlah) }}" required>
            @error('jumlah') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Subtotal --}}
        <div class="form-group">
            <label class="form-label">Subtotal (Rp)</label>
            <input type="number" name="subtotal" step="0.01" class="form-control"
                   value="{{ old('subtotal', $detail->subtotal) }}">
            <div class="form-help">Biarkan kosong agar subtotal dihitung otomatis (jumlah × harga).</div>
            @error('subtotal') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('jastiper.detail-pesanan.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Perbarui</button>
        </div>
    </form>
</div>
@endsection
