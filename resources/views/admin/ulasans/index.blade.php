@extends('layout.admin-app')
@section('title', 'Ulasan')
@section('page-title', 'Ulasan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
@endpush

@section('content')

    <div class="user-table-card">
        <h2 class="user-table-title">Ulasan</h2>

        {{-- SEARCH + TOTAL --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form action="{{ route('admin.ulasans.index') }}" method="GET"
                style="display:flex; gap:8px; align-items:center;">
                <input name="q" value="{{ $q ?? '' }}" class="user-search-input" type="text"
                    placeholder="Cari ID / Nama Pengguna / Nama Toko / Komentar"
                    style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px; width:360px;">
                <button type="submit" class="btn-search"
                    style="padding:8px 18px; border-radius:8px; border:1px solid #2b6be6; background:#fff; color:#2b6be6;">
                    Search
                </button>
            </form>
            <div style="margin-left:12px; color:#6c7680;">Total: <strong>{{ $ulasans->total() }}</strong></div>
        </div>

        @if (session('success'))
            <div class="alert alert-success"
                style="padding:10px; background:#e8f5e9; color:#2e7d32; border-radius:8px; margin-bottom:15px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Jastiper</th>
                        <th>Pesanan ID</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                        <th>Tanggal</th>
                        {{-- <th class="col-actions">Operasi</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse($ulasans as $u)
                        <tr>
                            <td>{{ $u->id }}</td>
                            <td>{{ $u->user?->name ?? '-' }}</td>
                            <td>{{ $u->jastiper?->nama_toko ?? '-' }}</td>
                            <td>{{ $u->pesanan_id }}</td>
                            <td>{{ $u->rating }}</td>
                            <td><span class="truncate" title="{{ $u->komentar }}">{{ $u->komentar ?: '-' }}</span></td>
                            <td>{{ $u->tanggal_ulasan?->format('Y-m-d H:i') }}</td>
                            {{-- <td class="col-actions" style="text-align:right;">
                                <div class="table-actions">
                                    <a href="{{ route('admin.ulasans.show', $u) }}" class="btn-action edit"
                                        title="Lihat Detail">
                                        <img src="{{ asset('admin/assets/images/icons/show.svg') }}" alt="Lihat">
                                    </a>
                                    <form action="{{ route('admin.ulasans.destroy', $u) }}" method="POST"
                                        style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus ulasan ini?')"
                                            class="btn-action del" title="Hapus">
                                            <img src="{{ asset('admin/assets/images/icons/delete.svg') }}" alt="Hapus">
                                        </button>
                                    </form>
                                </div>
                            </td> --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center" style="padding:20px 0; color:#6c7680;">Tidak ada data
                                ulasan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $ulasans->links() }}</div>


    </div>
@endsection
