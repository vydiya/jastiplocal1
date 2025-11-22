{{-- resources/views/admin/pengguna/create.blade.php --}}
@extends('layout.admin-app')

@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna')

@push('styles')
    {{-- panggil CSS panel-form (letakkan file custom-form.css di public/admin/assets/css/) --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
@endpush

@section('content')
<div class="form-panel">
    <h2 class="form-title">Tambah Pengguna</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-4" style="margin-bottom:18px; padding:10px 14px; border-radius:8px; background:#fff0f0; border:1px solid #f2c6c6; color:#8a1f1f;">
            <ul class="mb-0" style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.pengguna.store') }}" method="POST" autocomplete="off">
        @csrf

        <div class="form-group">
            <label class="form-label">Nama (name) <span class="text-danger">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required maxlength="100" placeholder="Nama singkat (untuk tampilan)">
            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Nama Lengkap (nama_lengkap)</label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="form-control" maxlength="150" placeholder="Nama lengkap (opsional)">
            @error('nama_lengkap') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Username</label>
            <input type="text" name="username" value="{{ old('username') }}" class="form-control" maxlength="50" placeholder="Username (boleh kosong)">
            <div class="form-help">Boleh dikosongkan — jika diisi harus unik.</div>
            @error('username') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required maxlength="255" placeholder="email@domain.com">
            @error('email') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- dua kolom: password + konfirmasi --}}
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required minlength="6" autocomplete="new-password" placeholder="Minimal 6 karakter">
                    @error('password') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required minlength="6" autocomplete="new-password" placeholder="Ulangi password">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">No. HP (no_hp)</label>
            <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="form-control" maxlength="30" placeholder="0812xxxxxxx">
            @error('no_hp') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="4" placeholder="Alamat lengkap (opsional)">{{ old('alamat') }}</textarea>
            @error('alamat') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Role</label>
            <select name="role" class="form-control">
                <option value="pengguna" {{ old('role') === 'pengguna' ? 'selected' : '' }}>pengguna</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>admin</option>
                <option value="jastiper" {{ old('role') === 'jastiper' ? 'selected' : '' }}>jastiper</option>
            </select>
            @error('role') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Tanggal Daftar (opsional)</label>
            <input type="datetime-local" name="tanggal_daftar" value="{{ old('tanggal_daftar') }}" class="form-control">
            <div class="form-help">Kosongkan agar menggunakan waktu sekarang (default DB).</div>
            @error('tanggal_daftar') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.pengguna.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
