@extends('layout.admin-app')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
@endpush

@section('content')
<div class="form-panel">
    <h2 class="form-title">Tambah Kategori</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.kategori.store') }}" method="POST" autocomplete="off">
        @csrf

        <div class="form-group">
            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
            <input type="text" name="nama" value="{{ old('nama') }}" class="form-control" required maxlength="100">
            @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>
@endsection
