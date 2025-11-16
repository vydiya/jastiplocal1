{{-- resources/views/admin/jastiper/create.blade.php --}}
@extends('admin.layout.app')

@section('title', 'Tambah Jastiper')
@section('page-title', 'Tambah Jastiper')

@push('styles')
    {{-- panggil file CSS form yang sudah kamu buat --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
    {{-- (opsional) juga panggil custom-user_table.css jika butuh konsistensi --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
@endpush

@section('content')
<div class="form-panel">
    <h2 class="form-title">Tambah Jastiper</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.jastiper.store') }}" method="POST" autocomplete="off">
        @csrf

        {{-- owner (user) (optional: allow select existing user) --}}
        <div class="form-group">
            <label class="form-label">Pemilik (User)</label>
            <select name="user_id" class="form-control">
                <option value="">-- Pilih Pemilik (opsional) --</option>
                @foreach(\App\Models\User::orderBy('name')->get() as $u)
                    <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                        {{ $u->name }} ({{ $u->email }})
                    </option>
                @endforeach
            </select>
            @error('user_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Nama Toko <span class="text-danger">*</span></label>
            <input type="text" name="nama_toko" value="{{ old('nama_toko') }}" class="form-control" required maxlength="100">
            @error('nama_toko') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">No. HP</label>
            <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="form-control" maxlength="30">
            @error('no_hp') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="3">{{ old('alamat') }}</textarea>
            @error('alamat') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Metode Pembayaran</label>
            <select name="metode_pembayaran" class="form-control">
                <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                <option value="e-wallet" {{ old('metode_pembayaran') == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
            </select>
            @error('metode_pembayaran') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Status Verifikasi</label>
            <select name="status_verifikasi" class="form-control">
                <option value="pending" {{ old('status_verifikasi') == 'pending' ? 'selected' : '' }}>pending</option>
                <option value="disetujui" {{ old('status_verifikasi') == 'disetujui' ? 'selected' : '' }}>disetujui</option>
                <option value="ditolak" {{ old('status_verifikasi') == 'ditolak' ? 'selected' : '' }}>ditolak</option>
            </select>
            @error('status_verifikasi') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- rating optional (boleh dikosongkan saat create) --}}
        <div class="form-group">
            <label class="form-label">Rating</label>
            <input type="number" step="0.1" min="0" max="5" name="rating" value="{{ old('rating', '0.0') }}" class="form-control">
            @error('rating') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Tanggal Daftar (opsional)</label>
            <input type="datetime-local" name="tanggal_daftar" value="{{ old('tanggal_daftar') }}" class="form-control">
            <small class="form-help">Kosongkan agar menggunakan waktu sekarang (default DB).</small>
            @error('tanggal_daftar') <small class="text-danger d-block">{{ $message }}</small> @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.jastiper.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
