{{-- resources/views/admin/kelola-dana/edit.blade.php --}}
@extends('layout.admin-app')

@section('title', 'Edit Kelola Dana')
@section('page-title', 'Edit Kelola Dana')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
@endpush

@section('content')
<div class="form-panel">

    <h2 class="form-title">Edit Kelola Dana #{{ $kelolaDana->id }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.kelola-dana.update', $kelolaDana) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')

        {{-- Pembayaran --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Pembayaran <span class="text-danger">*</span></label>
                <select name="pembayaran_id" class="form-control" required>
                    <option value="">-- Pilih Pembayaran --</option>
                    @foreach($pembayarans as $p)
                        <option value="{{ $p->id }}"
                            {{ old('pembayaran_id', $kelolaDana->pembayaran_id) == $p->id ? 'selected' : '' }}>
                            #{{ $p->id }} — {{ $p->metode_pembayaran }}
                            ({{ number_format($p->jumlah_bayar, 2) }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Admin --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Admin (Penanggung Jawab)</label>
                <select name="admin_id" class="form-control">
                    <option value="">-- Pilih Admin --</option>
                    @foreach($admins as $a)
                        <option value="{{ $a->id }}"
                            {{ old('admin_id', $kelolaDana->admin_id) == $a->id ? 'selected' : '' }}>
                            {{ $a->name }} ({{ $a->email }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Status Dana --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Status Dana <span class="text-danger">*</span></label>
                <select name="status_dana" class="form-control" required>
                    <option value="ditahan" {{ old('status_dana', $kelolaDana->status_dana) == 'ditahan' ? 'selected' : '' }}>ditahan</option>
                    <option value="dilepaskan" {{ old('status_dana', $kelolaDana->status_dana) == 'dilepaskan' ? 'selected' : '' }}>dilepaskan</option>
                    <option value="dikembalikan" {{ old('status_dana', $kelolaDana->status_dana) == 'dikembalikan' ? 'selected' : '' }}>dikembalikan</option>
                </select>
            </div>
        </div>

        {{-- Tanggal Update --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Tanggal Update (Opsional)</label>
                <input type="datetime-local" name="tanggal_update"
                       value="{{ optional($kelolaDana->tanggal_update)->format('Y-m-d\TH:i') }}"
                       class="form-control">
                <small class="form-help">Kosongkan untuk menggunakan waktu sekarang.</small>
            </div>
        </div>

        {{-- Catatan --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control">{{ old('catatan', $kelolaDana->catatan) }}</textarea>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="form-actions">
            <a href="{{ route('admin.kelola-dana.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>

    </form>

</div>
@endsection
