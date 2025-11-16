@extends('admin.layout.app')

@section('title', 'Edit Jastiper - Admin')
@section('page-title', 'Edit Jastiper')

@section('content')
<div class="max-w-lg mx-auto mt-6 bg-white p-6 rounded-lg shadow">
    <form action="{{ route('admin.jastiper.update', $jastiper) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">User (Pemilik)</label>
            <select name="user_id" class="form-control">
                <option value="">-- Pilih (opsional) --</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ old('user_id', $jastiper->user_id) == $u->id ? 'selected' : '' }}>
                        {{ $u->name }} ({{ $u->email }})
                    </option>
                @endforeach
            </select>
            @error('user_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Toko</label>
            <input type="text" name="nama_toko" value="{{ old('nama_toko', $jastiper->nama_toko) }}" class="form-control" required>
            @error('nama_toko') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">No. HP</label>
            <input type="text" name="no_hp" value="{{ old('no_hp', $jastiper->no_hp) }}" class="form-control">
            @error('no_hp') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" rows="3" class="form-control">{{ old('alamat', $jastiper->alamat) }}</textarea>
            @error('alamat') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Metode Pembayaran</label>
            <select name="metode_pembayaran" class="form-control">
                <option value="transfer" {{ old('metode_pembayaran', $jastiper->metode_pembayaran) == 'transfer' ? 'selected' : '' }}>Transfer</option>
                <option value="e-wallet" {{ old('metode_pembayaran', $jastiper->metode_pembayaran) == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
            </select>
            @error('metode_pembayaran') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Status Verifikasi</label>
            <select name="status_verifikasi" class="form-control">
                <option value="pending" {{ old('status_verifikasi', $jastiper->status_verifikasi) == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="disetujui" {{ old('status_verifikasi', $jastiper->status_verifikasi) == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak" {{ old('status_verifikasi', $jastiper->status_verifikasi) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            @error('status_verifikasi') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Rating</label>
            <input type="number" step="0.1" min="0" max="5" name="rating" value="{{ old('rating', $jastiper->rating) }}" class="form-control">
            @error('rating') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('admin.jastiper.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
