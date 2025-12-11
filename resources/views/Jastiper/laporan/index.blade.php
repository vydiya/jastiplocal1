@extends('layout.jastiper-app')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
@endpush

@section('content')
<div class="user-table-card">
    <h2 class="user-table-title">Laporan Penjualan</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <form method="GET" action="{{ route('jastiper.laporan.index') }}" style="display:flex; gap:8px; align-items:center;">
            <input name="q" value="{{ $q ?? '' }}" class="user-search-input" type="text" placeholder="Cari ID / nama barang / status"
                   style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px; width:360px;">
            <button type="submit" class="btn-search" style="padding:8px 14px; border-radius:8px; border:1px solid #2b6be6; background:#fff; color:#2b6be6;">
                Search
            </button>
        </form>

        {{-- <a href="{{ route('jastiper.laporan.create') }}" class="btn-add-user" style="padding:8px 14px; background:#2b6be6; color:white; border-radius:8px;">
            + Tambah Laporan
        </a> --}}
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:12px;">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Barang</th>
                    <th>Harga</th>
                    <th>Dana Masuk</th>
                    <th>Status</th>
                    <th>Tanggal Masuk</th>
                    {{-- <th class="col-actions">Operasi</th> --}}
                </tr>
            </thead>
            <tbody>
                @forelse($laporans as $l)
                <tr>
                    <td>{{ $l->id }}</td>
                    <td>{{ $l->nama_barang }}</td>
                    <td>{{ number_format($l->harga_barang,0,',','.') }}</td>
                    <td>{{ number_format($l->dana_masuk,0,',','.') }}</td>
                    <td>{{ ucfirst($l->status) }}</td>
                    <td>{{ optional($l->tanggal_masuk)->format('Y-m-d H:i') ?? $l->created_at->format('Y-m-d H:i') }}</td>
                    {{-- <td class="col-actions" style="text-align:right;">
                        <div class="table-actions">
                            <a href="{{ route('jastiper.laporan.edit', $l) }}" class="btn-action edit" title="Edit">
                                <img src="{{ asset('admin/assets/images/icons/edit.svg') }}" alt="Edit">
                            </a>

                            <form action="{{ route('jastiper.laporan.destroy', $l) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action del" onclick="return confirm('Hapus data ini?')">
                                    <img src="{{ asset('admin/assets/images/icons/delete.svg') }}" alt="Hapus">
                                </button>
                            </form>
                        </div>
                    </td> --}}
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding:20px 0; color:#6c7680;">Tidak ada data laporan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $laporans->links() }}
    </div>
</div>
@endsection
