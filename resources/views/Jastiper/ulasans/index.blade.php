@extends('layout.jastiper-app')

@section('title', 'Ulasan Saya')
@section('page-title', 'Ulasan Saya')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
<style>
/* Menyembunyikan tombol 'Tambah' yang mungkin muncul di layout */
.user-table-card .btn-add,
.btn-add,
.table-custom .btn-add,
.table-custom .add-btn,
.add-button {
display: none !important;
visibility: hidden !important;
}

    /* Menyembunyikan kolom Operasi (Aksi) */
    th.col-actions,
    td.col-actions {
        display: none !important;
    }
</style>


@endpush

@section('content')
<div class="user-table-card">
<h2 class="user-table-title">Ulasan untuk Toko Saya</h2>

    {{-- SEARCH + TOTAL --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form action="{{ route('jastiper.ulasans.index') }}" method="GET"
            style="display:flex; gap:8px; align-items:center;">
            <input name="q" value="{{ $q ?? '' }}" class="user-search-input" type="text"
                placeholder="Cari ID / Nama Pengguna / Komentar"
                style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px; width:320px;">
            <button type="submit" class="btn-search"
                style="padding:8px 18px; border-radius:8px; border:1px solid #2b6be6; background:#fff; color:#2b6be6;">
                Search
            </button>
        </form>
        <div style="margin-left:12px; color:#6c7680;">Total: <strong>{{ $ulasans->total() }}</strong></div>
    </div>

    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    {{-- Kolom Jastiper dihapus karena Jastiper sudah tahu ini ulasan untuknya --}}
                    <th>Pesanan</th>
                    <th>Rating</th>
                    <th>Komentar</th>
                    <th>Tanggal</th>
                    <th class="col-actions">Operasi</th> {{-- Disembunyikan via CSS --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($ulasans as $u)
                    <tr>
                        <td>{{ $u->id }}</td>
                        <td>{{ $u->user?->name ?? '-' }}</td>
                        <td>{{ $u->pesanan_id }}</td>
                        <td>{{ $u->rating }}</td>
                        <td><span class="truncate" title="{{ $u->komentar }}">{{ $u->komentar ?: '-' }}</span></td>
                        <td>{{ $u->tanggal_ulasan?->format('Y-m-d H:i') }}</td>
                        <td class="col-actions" style="text-align:right;">
                            <div class="table-actions">
                                <a href="{{ route('jastiper.ulasans.show', $u) }}" class="btn-action edit" title="Lihat Detail">
                                    {{-- Menggunakan show.svg atau edit.svg tergantung aset Anda, di sini saya biarkan edit.svg --}}
                                    <img src="{{ asset('admin/assets/images/icons/edit.svg') }}" alt="Lihat">
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        {{-- Colspan disesuaikan menjadi 7 (jumlah kolom yang terlihat/dihitung: 6 + 1 tersembunyi) --}}
                        <td colspan="7" class="text-center" style="padding:20px 0; color:#6c7680;">Tidak ada data ulasan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $ulasans->links() }}</div>
</div>


@endsection