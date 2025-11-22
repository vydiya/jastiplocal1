{{-- resources/views/jastiper/rekening/edit.blade.php --}}
@extends('layout.jastiper-app')

@section('title', 'Edit Rekening')
@section('page-title', 'Edit Rekening')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
@endpush

@section('content')
<div class="form-panel">
    <h2 class="form-title">Edit Rekening</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-4" style="margin-bottom:18px; padding:10px 14px; border-radius:8px; background:#fff0f0; border:1px solid #f2c6c6; color:#8a1f1f;">
            <ul class="mb-0" style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('jastiper.rekening.update', $rekening) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Tipe Rekening</label>
            <select name="tipe_rekening" class="form-control">
                <option value="">-- pilih --</option>
                <option value="bank" {{ old('tipe_rekening', $rekening->tipe_rekening) == 'bank' ? 'selected' : '' }}>Bank</option>
                <option value="e-wallet" {{ old('tipe_rekening', $rekening->tipe_rekening) == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
            </select>
            @error('tipe_rekening') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Nama Penyedia <span class="text-danger">*</span></label>
            <input type="text" name="nama_penyedia" value="{{ old('nama_penyedia', $rekening->nama_penyedia) }}" class="form-control" required maxlength="100" placeholder="Contoh: BCA / OVO">
            @error('nama_penyedia') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Nama Pemilik <span class="text-danger">*</span></label>
            <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik', $rekening->nama_pemilik) }}" class="form-control" required maxlength="100" placeholder="Nama sesuai pemilik rekening">
            @error('nama_pemilik') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Nomor Akun <span class="text-danger">*</span></label>
            <input type="text" name="nomor_akun" value="{{ old('nomor_akun', $rekening->nomor_akun) }}" class="form-control" required maxlength="50" placeholder="Contoh: 1234567890">
            <small class="form-help">Masukkan nomor akun tanpa spasi. Untuk e-wallet boleh gunakan nomor atau ID penyedia.</small>
            @error('nomor_akun') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status_aktif" class="form-control">
                <option value="aktif" {{ old('status_aktif', $rekening->status_aktif) == 'aktif' ? 'selected' : '' }}>aktif</option>
                <option value="nonaktif" {{ old('status_aktif', $rekening->status_aktif) == 'nonaktif' ? 'selected' : '' }}>nonaktif</option>
            </select>
            @error('status_aktif') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('jastiper.rekening.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Perbarui</button>
        </div>
    </form>
</div>
@endsection
