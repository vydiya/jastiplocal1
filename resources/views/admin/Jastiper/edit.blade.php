{{-- resources/views/admin/jastiper/edit.blade.php --}}
@extends('layout.admin-app')

@section('title', 'Edit Jastiper - Admin')
@section('page-title', 'Edit Jastiper')

@push('styles')
    {{-- pakai custom form yang sama dengan create / edit pengguna --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
@endpush

@section('content')
<div class="form-panel">
    <h2 class="form-title">Edit Jastiper</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-4" style="margin-bottom:18px; padding:10px 14px; border-radius:8px; background:#fff0f0; border:1px solid #f2c6c6; color:#8a1f1f;">
            <ul class="mb-0" style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.jastiper.update', $jastiper) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">User (Pemilik)</label>
            <select name="user_id" class="form-control">
                <option value="">-- Pilih (opsional) --</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ (string) old('user_id', $jastiper->user_id) === (string) $u->id ? 'selected' : '' }}>
                        {{ $u->name }} ({{ $u->email }})
                    </option>
                @endforeach
            </select>
            @error('user_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Nama Toko <span class="text-danger">*</span></label>
            <input type="text" name="nama_toko" value="{{ old('nama_toko', $jastiper->nama_toko) }}" class="form-control" required maxlength="150" placeholder="Nama toko / usaha">
            @error('nama_toko') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $jastiper->no_hp) }}" class="form-control" maxlength="30" placeholder="0812xxxxxxx">
                    @error('no_hp') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-label">Rating</label>
                    <input type="number" name="rating" step="0.1" min="0" max="5" value="{{ old('rating', $jastiper->rating) }}" class="form-control" placeholder="0.0 - 5.0">
                    <div class="form-help">Kosongkan atau isi jika ingin menyesuaikan rating.</div>
                    @error('rating') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="4" placeholder="Alamat lengkap (opsional)">{{ old('alamat', $jastiper->alamat) }}</textarea>
            @error('alamat') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label class="form-label">Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="form-control">
                        <option value="transfer" {{ old('metode_pembayaran', $jastiper->metode_pembayaran) == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="e-wallet" {{ old('metode_pembayaran', $jastiper->metode_pembayaran) == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                    </select>
                    @error('metode_pembayaran') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-label">Status Verifikasi</label>
                    <select name="status_verifikasi" class="form-control">
                        <option value="pending" {{ old('status_verifikasi', $jastiper->status_verifikasi) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="disetujui" {{ old('status_verifikasi', $jastiper->status_verifikasi) == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ old('status_verifikasi', $jastiper->status_verifikasi) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    @error('status_verifikasi') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.jastiper.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
