@extends('admin.layout.app')

@section('title','Tambah Detail Pesanan')
@section('page-title','Tambah Detail Pesanan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
@endpush

@section('content')
<div class="form-panel">
    <h2 class="form-title">Tambah Detail Pesanan</h2>

    <form action="{{ route('jastiper.detail-pesanan.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label class="form-label">Pesanan</label>
            <select name="pesanan_id" class="form-control" required>
                <option value="">-- Pilih Pesanan --</option>
                @foreach($pesanans as $id => $val)
                    <option value="{{ $id }}" {{ old('pesanan_id') == $id ? 'selected' : '' }}>{{ $id }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Barang</label>
            <select name="barang_id" class="form-control" required>
                <option value="">-- Pilih Barang --</option>
                @foreach($barangs as $b)
                    <option value="{{ $b->id }}" {{ old('barang_id')==$b->id ? 'selected' : '' }}>{{ $b->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Jumlah</label>
            <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah',1) }}" min="1" required>
        </div>

        <div class="form-group">
            <label class="form-label">Subtotal</label>
            <input type="number" step="0.01" name="subtotal" class="form-control" value="{{ old('subtotal',0) }}" required>
        </div>

        <div class="form-actions">
            <a href="{{ route('jastiper.detail-pesanan.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
