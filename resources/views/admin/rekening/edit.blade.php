@extends('layout.admin-app')

@section('title', 'Edit Rekening')
@section('page-title', 'Edit Rekening')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
@endpush

@section('content')
<div class="form-panel">
    <h2 class="form-title">Edit Rekening (ID: {{ $rekening->id }})</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-4" style="margin-bottom:18px; padding:10px 14px; border-radius:8px; background:#fff0f0; border:1px solid #f2c6c6; color:#8a1f1f;">
            <ul class="mb-0" style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.rekening.update', $rekening) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')

        {{-- Field: user_id (User ID ditampilkan, tidak bisa diubah) --}}
        <div class="form-group">
            <label class="form-label">User ID Pemilik</label>
            <input type="text" value="{{ $rekening->user_id }}" class="form-control" disabled>
        </div>
        
        {{-- Field: tipe_rekening (Enum/Select) --}}
        <div class="form-group">
            <label class="form-label">Tipe Rekening <span class="text-danger">*</span></label>
            <select name="tipe_rekening" class="form-control" required>
                @php $oldTipe = old('tipe_rekening', $rekening->tipe_rekening); @endphp
                <option value="bank" {{ $oldTipe == 'bank' ? 'selected' : '' }}>Bank</option>
                <option value="e-wallet" {{ $oldTipe == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
            </select>
            @error('tipe_rekening') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Field: nama_penyedia --}}
        <div class="form-group">
            <label class="form-label">Nama Penyedia (Bank/E-Wallet) <span class="text-danger">*</span></label>
            <input type="text" name="nama_penyedia" value="{{ old('nama_penyedia', $rekening->nama_penyedia) }}" class="form-control" required maxlength="100">
            @error('nama_penyedia') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- Field: nama_pemilik --}}
        <div class="form-group">
            <label class="form-label">Nama Pemilik Akun <span class="text-danger">*</span></label>
            <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik', $rekening->nama_pemilik) }}" class="form-control" required maxlength="100">
            @error('nama_pemilik') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        
        {{-- Field: nomor_akun --}}
        <div class="form-group">
            <label class="form-label">Nomor Akun/Nomor Telepon <span class="text-danger">*</span></label>
            <input type="text" name="nomor_akun" value="{{ old('nomor_akun', $rekening->nomor_akun) }}" class="form-control" required maxlength="50">
            @error('nomor_akun') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        
        {{-- Field: status_aktif (Enum/Select) --}}
        <div class="form-group">
            <label class="form-label">Status Aktif <span class="text-danger">*</span></label>
            <select name="status_aktif" class="form-control" required>
                @php $oldStatus = old('status_aktif', $rekening->status_aktif); @endphp
                <option value="aktif" {{ $oldStatus == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ $oldStatus == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            @error('status_aktif') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.rekening.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Perbarui Rekening</button>
        </div>
    </form>
</div>
@endsection