@extends('layout.jastiper-app')

@section('title','Ulasan Saya')
@section('page-title','Ulasan Saya')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
<style>

    .user-table-card .btn-add,
    .btn-add,
    .table-custom .btn-add,
    .table-custom .add-btn,
    .add-button {
        display: none !important;
        visibility: hidden !important;
    }
    th.col-actions,
    td.col-actions {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="user-table-card">
    <h2 class="user-table-title">Ulasan untuk Toko Saya</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <form action="{{ route('jastiper.ulasans.index') }}" method="GET" style="display:flex; gap:8px; align-items:center;">
            <input name="q" value="{{ $q ?? '' }}" class="user-search-input" type="text"
                placeholder="Cari ID / Nama Pengguna / Komentar"
                style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px; width:320px;">
            <button type="submit" class="btn-search">Search</button>
        </form>
        <div style="margin-left:12px; color:#6c7680;">Total: <strong>{{ $ulasans->total() }}</strong></div>
    </div>

    <div class="table-responsive">
        <table class="table table-custom">
            <thead><tr>
                <th>ID</th><th>User</th><th>Pesanan</th><th>Rating</th><th>Komentar</th><th>Tanggal</th><th class="col-actions">Operasi</th>
            </tr></thead>
            <tbody>
            @foreach($ulasans as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td>{{ $u->user?->name ?? '-' }}</td>
                <td>{{ $u->pesanan_id }}</td>
                <td>{{ $u->rating }}</td>
                <td><span class="truncate" title="{{ $u->komentar }}">{{ $u->komentar ?: '-' }}</span></td>
                <td>{{ $u->tanggal_ulasan?->format('Y-m-d H:i') }}</td>
                <td class="col-actions">
                    <div class="table-actions">
                        <a href="{{ route('jastiper.ulasans.show', $u) }}" class="btn-action edit"><img src="{{ asset('admin/assets/images/icons/edit.svg') }}" alt="Lihat"></a>
                    </div>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $ulasans->links() }}</div>
</div>
@endsection
