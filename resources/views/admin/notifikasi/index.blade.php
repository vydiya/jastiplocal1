@extends('layout.admin-app') 
{{-- untuk admin; untuk jastiper gunakan layout.jastiper-app --}}

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
@endpush

@section('content')
<div class="user-table-card">
    <h2 class="user-table-title">Notifikasi</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <form method="GET" action="{{ url()->current() }}" style="display:flex; gap:8px; align-items:center;">
            <input name="q" value="{{ $q ?? '' }}" class="user-search-input" type="text"
                   placeholder="Cari notifikasi / jenis"
                   style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px; width:320px;">
            <button type="submit" class="btn-search"
                    style="padding:8px 18px; border-radius:8px; border:1px solid #2b6be6; background:#fff; color:#2b6be6;">
                Search
            </button>
        </form>
        {{-- tombol tambah dihapus --}}
    </div>

    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Jenis</th>
                    <th>Pesan</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    {{-- kolom operasi dihapus --}}
                </tr>
            </thead>
            <tbody>
                @forelse($notifikasis as $n)
                <tr>
                    <td>{{ $n->id }}</td>
                    <td>{{ $n->user?->name ?? '-' }}</td>
                    <td>{{ $n->jenis_notifikasi }}</td>
                    <td class="truncate" title="{{ $n->pesan }}">{{ Str::limit($n->pesan, 80) }}</td>
                    <td>{{ $n->status_baca }}</td>
                    <td>{{ $n->tanggal_kirim?->format('Y-m-d H:i') ?? '-' }}</td>
                    {{-- tidak ada aksi edit/delete --}}
                </tr>
                @empty
                <tr>
                    @php $colspan = 6; @endphp
                    <td colspan="{{ $colspan }}" class="text-center">Belum ada notifikasi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $notifikasis->withQueryString()->links() }}</div>
</div>
@endsection
