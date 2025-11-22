@extends('layout.jastiper-app')

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

    <form action="{{ route('jastiper.barang.update', $barang) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')

        {{-- Kategori --}}
        <div class="form-group">
            <label class="form-label">Kategori</label>
            <select name="kategori_id" class="form-control">
                <option value="">-- Pilih kategori (opsional) --</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ (string) old('kategori_id', $barang->kategori_id) === (string) $k->id ? 'selected' : '' }}>
                        {{ $k->nama }}
                    </option>
                @endforeach
            </select>
            @error('kategori_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Nama Barang --}}
        <div class="form-group">
            <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
            <input type="text" name="nama_barang" class="form-control"
                   value="{{ old('nama_barang', $barang->nama_barang) }}" required maxlength="150" placeholder="Nama barang">
            @error('nama_barang') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Deskripsi --}}
        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4" placeholder="Deskripsi singkat (opsional)">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
            @error('deskripsi') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Harga + Stok --}}
        <div class="form-row">
            <div class="col form-group">
                <label class="form-label">Ongkos Jastip (Rp) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="harga" class="form-control"
                       value="{{ old('harga', $barang->harga) }}" required placeholder="0.00">
                @error('harga') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="col form-group">
                <label class="form-label">Stok <span class="text-danger">*</span></label>
                <input type="number" name="stok" class="form-control"
                       value="{{ old('stok', $barang->stok) }}" required placeholder="Jumlah stok">
                @error('stok') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Tersedia --}}
        <div class="form-group">
            <label class="form-label">Tersedia</label>
            <select name="is_available" class="form-control">
                <option value="yes" {{ old('is_available', $barang->is_available) == 'yes' ? 'selected' : '' }}>yes</option>
                <option value="no"  {{ old('is_available', $barang->is_available) == 'no' ? 'selected' : '' }}>no</option>
            </select>
            @error('is_available') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Foto Barang --}}
        <div class="form-group">
            <label class="form-label">Foto Barang (opsional)</label>

            @if($barang->foto_barang && file_exists(storage_path('app/public/' . $barang->foto_barang)))
                <div style="margin-bottom:8px;">
                    <img id="currentPhoto" src="{{ asset('storage/' . $barang->foto_barang) }}" alt="foto" style="height:80px;border-radius:6px; object-fit:cover;">
                </div>

                <label class="form-help" style="display:block; margin-bottom:6px;">
                    Jika ingin ganti foto, pilih file baru. Centang hapus jika ingin menghapus foto lama tanpa mengganti.
                </label>

                <div style="display:flex; gap:12px; align-items:center; margin-bottom:8px;">
                    <input type="file" name="foto_barang" id="fotoInput" class="form-control" accept=".jpg,.jpeg,.png">
                    <label style="font-weight:600; display:flex; align-items:center; gap:8px;">
                        <input type="checkbox" name="remove_foto" value="1" {{ old('remove_foto') ? 'checked' : '' }}> Hapus foto lama
                    </label>
                </div>

                <div id="previewWrapper" style="margin-top:6px;"></div>
            @else
                <input type="file" name="foto_barang" id="fotoInput" class="form-control" accept=".jpg,.jpeg,.png">
                <div id="previewWrapper" style="margin-top:8px;"></div>
            @endif

            @error('foto_barang') <div class="text-danger">{{ $message }}</div> @enderror
            <small class="form-help">Format: jpg, jpeg, png. Ukuran sesuai batas server (validasi di controller).</small>
        </div>

        {{-- Tanggal Input --}}
        <div class="form-group">
            <label class="form-label">Tanggal Input (opsional)</label>
            <input type="datetime-local" name="tanggal_input" class="form-control"
                   value="{{ old('tanggal_input', $barang->tanggal_input ? \Carbon\Carbon::parse($barang->tanggal_input)->format('Y-m-d\TH:i') : '') }}">
            <small class="form-help">Kosongkan agar otomatis.</small>
            @error('tanggal_input') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Aksi --}}
        <div class="form-actions">
            <a href="{{ route('jastiper.barang.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('fotoInput');
    const preview = document.getElementById('previewWrapper');

    if (input) {
        input.addEventListener('change', function () {
            preview.innerHTML = '';
            const file = this.files && this.files[0];
            if (!file) return;
            if (!file.type.startsWith('image/')) return;

            const img = document.createElement('img');
            img.style.height = '80px';
            img.style.borderRadius = '6px';
            img.style.objectFit = 'cover';
            img.alt = 'preview';

            const reader = new FileReader();
            reader.onload = function (e) {
                img.src = e.target.result;
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }
});
</script>
@endpush
