{{-- resources/views/admin/kategori-barang/edit.blade.php --}}
@extends('layout.admin-app')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-form.css') }}">
@endpush

@section('content')
<div class="form-panel">
    <h2 class="form-title">Edit Kategori</h2>

    @if ($errors->any())
        <div class="alert alert-danger mb-4" style="margin-bottom:18px; padding:10px 14px; border-radius:8px; background:#fff0f0; border:1px solid #f2c6c6; color:#8a1f1f;">
            <ul class="mb-0" style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.kategori.update', $kategori) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
            <input type="text" name="nama" value="{{ old('nama', $kategori->nama) }}" class="form-control" required maxlength="100" placeholder="Contoh: Elektronik">
            @error('nama') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Perbarui</button>
        </div>
    </form>
</div>
@endsection
