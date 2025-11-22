@extends('layout.admin-app')

@section('title','Tambah Pembayaran')
@section('page-title','Tambah Pembayaran')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
@endpush

@section('content')
<div class="form-panel">
    <h2 class="form-title">Tambah Pembayaran</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">@foreach ($errors->all() as $err) <li>{{ $err }}</li> @endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.pembayaran.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label">Pesanan <span class="text-danger">*</span></label>
            <select name="pesanan_id" class="form-control" required>
                <option value="">-- Pilih Pesanan --</option>
                @foreach($pesanans as $ps)
                    <option value="{{ $ps->id }}" {{ old('pesanan_id') == $ps->id ? 'selected' : '' }}>
                        #{{ $ps->id }} — {{ $ps->no_hp ?? '-' }} — Rp {{ number_format($ps->total_harga,0,',','.') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label class="form-label">Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="form-control">
                        <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="e-wallet" {{ old('metode_pembayaran') == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                        <option value="cod" {{ old('metode_pembayaran') == 'cod' ? 'selected' : '' }}>COD</option>
                    </select>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label class="form-label">Jumlah Bayar <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="jumlah_bayar" value="{{ old('jumlah_bayar') }}" class="form-control" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Bukti Bayar (jpg/png/pdf)</label>
            <input type="file" name="bukti_bayar" class="form-control">
        </div>

        <div class="form-group">
            <label class="form-label">Status Pembayaran</label>
            <select name="status_pembayaran" class="form-control">
                <option value="menunggu" {{ old('status_pembayaran') == 'menunggu' ? 'selected' : '' }}>menunggu</option>
                <option value="valid" {{ old('status_pembayaran') == 'valid' ? 'selected' : '' }}>valid</option>
                <option value="tidak_valid" {{ old('status_pembayaran') == 'tidak_valid' ? 'selected' : '' }}>tidak_valid</option>
            </select>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
