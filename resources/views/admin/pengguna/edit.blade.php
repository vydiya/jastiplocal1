{{-- resources/views/admin/pengguna/edit.blade.php --}}
@extends('layout.admin-app')

@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')

@push('styles')
    {{-- pakai custom form yang sama dengan create --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
@endpush

@section('content')
<div class="form-panel">
    <h2 class="form-title">Edit Pengguna</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-4" style="margin-bottom:18px; padding:10px 14px; border-radius:8px; background:#fff0f0; border:1px solid #f2c6c6; color:#8a1f1f;">
            <ul class="mb-0" style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.pengguna.update', $user) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Nama (name) <span class="text-danger">*</span></label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required maxlength="100" placeholder="Nama singkat (untuk tampilan)">
            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Nama Lengkap (nama_lengkap)</label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" class="form-control" maxlength="150" placeholder="Nama lengkap (opsional)">
            @error('nama_lengkap') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Username</label>
            <input type="text" name="username" value="{{ old('username', $user->username) }}" class="form-control" maxlength="50" placeholder="Username (boleh kosong)">
            <div class="form-help">Boleh dikosongkan — jika diisi harus unik.</div>
            @error('username') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required maxlength="255" placeholder="email@domain.com">
            @error('email') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- dua kolom: password optional + confirm --}}
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label class="form-label">Password (kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" name="password" class="form-control" minlength="6" autocomplete="new-password" placeholder="Biarkan kosong untuk tidak mengubah">
                    @error('password') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" minlength="6" autocomplete="new-password" placeholder="Ulangi password">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">No. HP (no_hp)</label>
            <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" class="form-control" maxlength="30" placeholder="0812xxxxxxx">
            @error('no_hp') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="4" placeholder="Alamat lengkap (opsional)">{{ old('alamat', $user->alamat) }}</textarea>
            @error('alamat') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Role</label>
            <select name="role" class="form-control">
                <option value="pengguna" {{ old('role', $user->role) === 'pengguna' ? 'selected' : '' }}>pengguna</option>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>admin</option>
                <option value="jastiper" {{ old('role', $user->role) === 'jastiper' ? 'selected' : '' }}>jastiper</option>
            </select>
            @error('role') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Tanggal Daftar (opsional)</label>
            <input type="datetime-local" name="tanggal_daftar" value="{{ old('tanggal_daftar', $user->tanggal_daftar ? $user->tanggal_daftar->format('Y-m-d\TH:i') : '') }}" class="form-control">
            <div class="form-help">Kosongkan agar menggunakan waktu yang sudah tersimpan / DB.</div>
            @error('tanggal_daftar') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.pengguna.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
