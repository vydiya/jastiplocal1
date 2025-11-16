@extends('admin.layout.app')

@section('title', 'Edit Barang')
@section('page-title', 'Edit Barang')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
@endpush

@section('content')
<div class="form-panel">
    <h2 class="form-title">Edit Barang #{{ $barang->id }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-3"><ul class="mb-0">@foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach</ul></div>
    @endif

    <form action="{{ route('jastiper.barang.update', $barang) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- kategori_id --}}
        <div class="form-group">
            <label class="form-label">Kategori</label>
            <select name="kategori_id" class="form-control">
                <option value="">-- Pilih kategori (opsional) --</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ old('kategori_id', $barang->kategori_id) == $k->id ? 'selected' : '' }}>
                        {{ $k->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- nama_barang --}}
        <div class="form-group">
            <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
            <input type="text" name="nama_barang" class="form-control"
                   value="{{ old('nama_barang', $barang->nama_barang) }}" required maxlength="150">
        </div>

        {{-- deskripsi --}}
        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
        </div>

        <div class="form-row">
            <div class="col form-group">
                <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="harga" class="form-control"
                       value="{{ old('harga', $barang->harga) }}" required>
            </div>

            <div class="col form-group">
                <label class="form-label">Stok <span class="text-danger">*</span></label>
                <input type="number" name="stok" class="form-control"
                       value="{{ old('stok', $barang->stok) }}" required>
            </div>
        </div>

        {{-- is_available --}}
        <div class="form-group">
            <label class="form-label">Tersedia</label>
            <select name="is_available" class="form-control">
                <option value="yes" {{ old('is_available', $barang->is_available) == 'yes' ? 'selected' : '' }}>yes</option>
                <option value="no"  {{ old('is_available', $barang->is_available) == 'no' ? 'selected' : '' }}>no</option>
            </select>
        </div>

        {{-- foto preview + upload --}}
        <div class="form-group">
            <label class="form-label">Foto Barang</label>
            @if($barang->foto_barang)
                <div style="margin-bottom:8px;">
                    <img src="{{ asset('storage/'.$barang->foto_barang) }}" alt="foto" style="height:80px;border-radius:8px;">
                </div>
            @endif
            <input type="file" name="foto_barang" class="form-control">
        </div>

        {{-- status_validasi --}}
        <div class="form-group">
            <label class="form-label">Status Validasi</label>
            <select name="status_validasi" class="form-control">
                <option value="pending"    {{ old('status_validasi', $barang->status_validasi) == 'pending' ? 'selected':'' }}>pending</option>
                <option value="disetujui"  {{ old('status_validasi', $barang->status_validasi) == 'disetujui' ? 'selected':'' }}>disetujui</option>
                <option value="ditolak"    {{ old('status_validasi', $barang->status_validasi) == 'ditolak' ? 'selected':'' }}>ditolak</option>
            </select>
        </div>

        {{-- tanggal_input --}}
        <div class="form-group">
            <label class="form-label">Tanggal Input (opsional)</label>
            <input type="datetime-local" name="tanggal_input" class="form-control"
                   value="{{ old('tanggal_input', $barang->tanggal_input ? $barang->tanggal_input->format('Y-m-d\TH:i') : '') }}">
        </div>

        <div class="form-actions">
            <a href="{{ route('jastiper.barang.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
