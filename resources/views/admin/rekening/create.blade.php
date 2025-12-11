@extends('layout.admin-app')

@section('title', 'Tambah Rekening')
@section('page-title', 'Tambah Rekening')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
@endpush

@section('content')
<div class="form-panel">
    <h2 class="form-title">Tambah Rekening</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-4" style="margin-bottom:18px; padding:10px 14px; border-radius:8px; background:#fff0f0; border:1px solid #f2c6c6; color:#8a1f1f;">
            <ul class="mb-0" style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.rekening.store') }}" method="POST" autocomplete="off">
        @csrf

        {{-- Field: user_id (Input User ID) --}}
        <div class="form-group">
            <label class="form-label">User ID Pemilik Rekening <span class="text-danger">*</span></label>
            {{-- Admin perlu menginput User ID yang valid --}}
            <input type="number" name="user_id" value="{{ old('user_id') }}" class="form-control" required placeholder="Contoh: 123">
            @error('user_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        
        {{-- Field: tipe_rekening (Enum/Select) --}}
        <div class="form-group">
            <label class="form-label">Tipe Rekening <span class="text-danger">*</span></label>
            <select name="tipe_rekening" class="form-control" required>
                <option value="" disabled selected>Pilih Tipe</option>
                <option value="bank" {{ old('tipe_rekening') == 'bank' ? 'selected' : '' }}>Bank</option>
                <option value="e-wallet" {{ old('tipe_rekening') == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
            </select>
            @error('tipe_rekening') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Field: nama_penyedia --}}
        <div class="form-group">
            <label class="form-label">Nama Penyedia (Bank/E-Wallet) <span class="text-danger">*</span></label>
            <input type="text" name="nama_penyedia" value="{{ old('nama_penyedia') }}" class="form-control" required maxlength="100" placeholder="Contoh: BCA / DANA">
            @error('nama_penyedia') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Field: nama_pemilik --}}
        <div class="form-group">
            <label class="form-label">Nama Pemilik Akun <span class="text-danger">*</span></label>
            <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik') }}" class="form-control" required maxlength="100" placeholder="Sesuai nama di buku tabungan/akun">
            @error('nama_pemilik') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        
        {{-- Field: nomor_akun --}}
        <div class="form-group">
            <label class="form-label">Nomor Akun/Nomor Telepon <span class="text-danger">*</span></label>
            <input type="text" name="nomor_akun" value="{{ old('nomor_akun') }}" class="form-control" required maxlength="50" placeholder="Contoh: 1234567890 / 0812xxxxxx">
            @error('nomor_akun') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.rekening.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Rekening</button>
        </div>
    </form>
</div>
@endsection