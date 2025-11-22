@extends('layout.jastiper-app')

@section('title', 'Edit Laporan')
@section('page-title', 'Edit Laporan')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
@endpush

@section('content')
<div class="form-panel">
    <h2 class="form-title">Edit Laporan #{{ $laporan->id }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">@foreach ($errors->all() as $err) <li>{{ $err }}</li> @endforeach</ul>
        </div>
    @endif

    <form action="{{ route('jastiper.laporan.update', $laporan) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Barang <span class="text-danger">*</span></label>
            <select name="barang_id" class="form-control" required>
                <option value="">-- Pilih Barang --</option>
                @foreach($barangs as $b)
                    <option value="{{ $b->id }}" {{ old('barang_id', $laporan->barang_id) == $b->id ? 'selected' : '' }}>
                        {{ $b->nama_barang }} — Rp {{ number_format($b->harga,0,',','.') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
            <input type="text" name="nama_barang" class="form-control" value="{{ old('nama_barang', $laporan->nama_barang) }}" required maxlength="150">
        </div>

        <div class="form-row">
            <div class="col form-group">
                <label class="form-label">Harga Barang (Rp) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="harga_barang" class="form-control" value="{{ old('harga_barang', $laporan->harga_barang) }}" required>
            </div>

            <div class="col form-group">
                <label class="form-label">Dana Masuk (Rp) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="dana_masuk" class="form-control" value="{{ old('dana_masuk', $laporan->dana_masuk) }}" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Tanggal Masuk (opsional)</label>
            <input type="datetime-local" name="tanggal_masuk" value="{{ optional($laporan->tanggal_masuk)->format('Y-m-d\\TH:i') }}" class="form-control">
            <small class="form-help">Kosongkan untuk menggunakan waktu sekarang.</small>
        </div>

        <div class="form-actions">
            <a href="{{ route('jastiper.laporan.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
