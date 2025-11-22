@extends('layout.jastiper-app')

@section('title', 'Tambah Barang')
@section('page-title', 'Tambah Barang')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
@endpush

@section('content')
<div class="form-panel">
    <h2 class="form-title">Tambah Barang</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-3"><ul class="mb-0">@foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach</ul></div>
    @endif

    <form action="{{ route('jastiper.barang.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- kategori_id --}}
        <div class="form-group">
            <label class="form-label">Kategori</label>
            <select name="kategori_id" class="form-control">
                <option value="">-- Pilih kategori (opsional) --</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- nama_barang --}}
        <div class="form-group">
            <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
            <input type="text" name="nama_barang" class="form-control" value="{{ old('nama_barang') }}" required maxlength="150">
        </div>

        {{-- deskripsi --}}
        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="form-row">
            <div class="col form-group">
                <label class="form-label"> Ongkos Jastip (Rp) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="harga" class="form-control" value="{{ old('harga', 0) }}" required>
            </div>

            <div class="col form-group">
                <label class="form-label">Stok <span class="text-danger">*</span></label>
                <input type="number" name="stok" class="form-control" value="{{ old('stok', 0) }}" required>
            </div>
        </div>

        {{-- is_available --}}
        <div class="form-group">
            <label class="form-label">Tersedia</label>
            <select name="is_available" class="form-control">
                <option value="yes" {{ old('is_available','yes') == 'yes' ? 'selected' : '' }}>yes</option>
                <option value="no"  {{ old('is_available') == 'no' ? 'selected' : '' }}>no</option>
            </select>
        </div>

        {{-- foto_barang --}}
        <div class="form-group">
            <label class="form-label">Foto Barang (opsional)</label>
            <input type="file" name="foto_barang" class="form-control">
        </div>

        {{-- tanggal_input --}}
        <div class="form-group">
            <label class="form-label">Tanggal Input (opsional)</label>
            <input type="datetime-local" name="tanggal_input" class="form-control"
                   value="{{ old('tanggal_input') }}">
            <small class="form-help">Kosongkan agar otomatis.</small>
        </div>

        <div class="form-actions">
            <a href="{{ route('jastiper.barang.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
