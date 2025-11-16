{{-- resources/views/jastiper/pesanan/index.blade.php --}}
@extends('admin.layout.app')

@section('title', 'Pesanan - Jastiper')
@section('page-title', 'Data Pesanan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
@endpush

@section('content')
    <div class="user-table-card">
        <h2 class="user-table-title">Data Pesanan</h2>

        {{-- SEARCH + TOTAL + ADD --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="user-controls" style="display:flex; align-items:center; gap:8px;">
                <form method="GET" action="{{ route('jastiper.pesanan.index') }}"
                    style="display:flex; gap:8px; align-items:center;">

                    <input name="q" value="{{ request('q', $q ?? '') }}" class="user-search-input" type="text"
                        placeholder="Cari berdasarkan ID / No HP / Alamat"
                        style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px; width:320px;"
                        autocomplete="off">

                    <button type="submit" class="btn-search"
                        style="padding:8px 18px; border-radius:8px; border:1px solid #2b6be6; background:#fff; color:#2b6be6;">
                        Search
                    </button>
                </form>

                <div style="margin-left:8px; color:#6c7680;">
                    Total: <strong>{{ $pesanans->total() }}</strong>
                </div>
            </div>

            <a href="{{ route('jastiper.pesanan.create') }}" class="btn-add-user"
                style="padding:8px 14px; background:#2b6be6; color:white; border-radius:8px;">
                + Tambah Pesanan
            </a>
        </div>

        <div class="table-responsive">
            <table id="pesanansTable" class="table table-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pemesan</th>
                        <th>Jastiper</th>
                        <th>Tanggal Pesan</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Alamat</th>
                        <th>No. HP</th>
                        <th class="col-actions">Operasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesanans as $p)
                        <tr data-id="{{ $p->id }}">
                            <td>{{ $p->id }}</td>
                            <td>{{ $p->user?->name ?? '-' }}</td>
                            <td>{{ $p->jastiper?->nama_toko ?? '-' }}</td>
                            <td>{{ $p->tanggal_pesan?->format('Y-m-d H:i') }}</td>
                            <td>{{ number_format($p->total_harga, 2) }}</td>
                            <td>{{ $p->status_pesanan }}</td>
                            <td><span class="truncate"
                                    title="{{ $p->alamat_pengiriman }}">{{ $p->alamat_pengiriman ?? '-' }}</span></td>
                            <td>{{ $p->no_hp ?? '-' }}</td>
                            <td class="col-actions" style="text-align:right;">
                                <div class="table-actions">
                                    <a href="{{ route('jastiper.pesanan.edit', $p) }}" class="btn-action edit" title="Edit">
                                        <img src="{{ asset('admin/assets/images/icons/edit.svg') }}" alt="Edit">
                                    </a>
                                    <form action="{{ route('jastiper.pesanan.destroy', $p) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action del"
                                            onclick="return confirm('Hapus pesanan #{{ $p->id }}?')">
                                            <img src="{{ asset('admin/assets/images/icons/delete.svg') }}" alt="Hapus">
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $pesanans->links() }}
        </div>
    </div>
@endsection