{{-- resources/views/jastiper/detail-pesanan/edit.blade.php --}}
@extends('admin.layout.app')

@section('title', 'Edit Detail Pesanan')
@section('page-title', 'Edit Detail Pesanan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
@endpush

@section('content')

<div class="form-panel">

    <h2 class="form-title">Edit Detail Pesanan</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('jastiper.detail-pesanan.update', $detail->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Pesanan ID (readonly) --}}
        <div class="form-group">
            <label class="form-label">Nomor Pesanan</label>
            <input type="text" class="form-control" value="#{{ $detail->pesanan_id }}" readonly>
        </div>

        {{-- Barang --}}
        <div class="form-group">
            <label class="form-label">Barang <span class="text-danger">*</span></label>
            <select name="barang_id" class="form-control" required>
                @foreach(\App\Models\Barang::orderBy('nama_barang')->get() as $b)
                    <option value="{{ $b->id }}"
                        {{ $detail->barang_id == $b->id ? 'selected' : '' }}>
                        {{ $b->nama_barang }} — Rp {{ number_format($b->harga, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>
            @error('barang_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Jumlah --}}
        <div class="form-group">
            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
            <input type="number" name="jumlah" min="1" class="form-control"
                   value="{{ $detail->jumlah }}" required>
            @error('jumlah') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Subtotal --}}
        <div class="form-group">
            <label class="form-label">Subtotal (Rp)</label>
            <input type="number" name="subtotal" step="0.01" class="form-control"
                   value="{{ $detail->subtotal }}">
            @error('subtotal') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('jastiper.detail-pesanan.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Perbarui</button>
        </div>
    </form>

</div>

@endsection
