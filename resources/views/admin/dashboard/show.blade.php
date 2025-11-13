{{-- resources/views/admin/dashboard/show.blade.php --}}
@extends('admin.layout.app')

@section('title','Detail Item')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><strong>Detail Item</strong></div>
                <div class="card-body">
                    {{-- Contoh menampilkan detail data --}}
                    <p><strong>ID:</strong> {{ $id ?? '-' }}</p>
                    <p><strong>Nama:</strong> {{ $item->name ?? 'Nama contoh' }}</p>
                    <p><strong>Deskripsi:</strong> {{ $item->description ?? 'Deskripsi contoh' }}</p>

                    <a href="{{ route('admin.dashboard.edit', $id ?? 0) }}" class="btn btn-primary">Edit</a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection
