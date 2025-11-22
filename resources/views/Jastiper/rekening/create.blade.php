@extends('layout.jastiper-app')

@section('title', 'Tambah Rekening')
@section('page-title', 'Tambah Rekening')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
@endpush

@section('content')
<div class="form-panel">
    <h2 class="form-title">Tambah Rekening</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-4"><ul class="mb-0">@foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('jastiper.rekening.store') }}" method="POST" autocomplete="off">
        @csrf

        <div class="form-group">
            <label class="form-label">Tipe Rekening</label>
            <select name="tipe_rekening" class="form-control">
                <option value="">-- pilih --</option>
                <option value="bank" {{ old('tipe_rekening') == 'bank' ? 'selected' : '' }}>Bank</option>
                <option value="e-wallet" {{ old('tipe_rekening') == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Nama Penyedia <span class="text-danger">*</span></label>
            <input type="text" name="nama_penyedia" value="{{ old('nama_penyedia') }}" class="form-control" required maxlength="100">
        </div>

        <div class="form-group">
            <label class="form-label">Nama Pemilik <span class="text-danger">*</span></label>
            <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik') }}" class="form-control" required maxlength="100">
        </div>

        <div class="form-group">
            <label class="form-label">Nomor Akun <span class="text-danger">*</span></label>
            <input type="text" name="nomor_akun" value="{{ old('nomor_akun') }}" class="form-control" required maxlength="50">
        </div>

        <div class="form-actions">
            <a href="{{ route('jastiper.rekening.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
