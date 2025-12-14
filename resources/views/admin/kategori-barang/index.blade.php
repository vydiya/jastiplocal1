@extends('layout.admin-app')

@section('title', 'Kategori Barang')
@section('page-title', 'Kategori Barang')

@push('styles')
    {{-- Asumsi custom-user_table.css mendefinisikan .user-table-card, .user-table-title, dll. --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">

    <style>
        /* Styling tambahan untuk aksi jika belum ada di CSS kustom */
        .table-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .btn-action {
            padding: 4px;
            border-radius: 4px;
            background: none;
            border: 1px solid #ccc;
            transition: all 0.2s;
        }

        .btn-action:hover {
            opacity: 0.8;
        }

        .btn-action img {
            width: 18px;
            height: 18px;
            display: block;
        }
    </style>
@endpush

@section('content')
    <div class="user-table-card">
        <h2 class="user-table-title">Daftar Kategori Barang</h2>

        {{-- SEARCH + TOTAL + ADD --}}
        <div class="d-flex justify-content-between align-items-center mb-3">

            <div class="d-flex align-items-center">
                {{-- Form Pencarian --}}
                <form method="GET" action="{{ route('admin.kategori.index') }}" style="display:flex; align-items:center; gap:8px;">

                    <input name="q" value="{{ request('q', $q ?? '') }}" class="user-search-input"
                        type="text" placeholder="Cari ID / Nama kategori"
                        style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px; width:320px;"
                        value="{{ request('q', $q ?? '') }}">
                    <button id="btnSearchJastiper" class="btn-search"
                        style="padding:8px 18px; border-radius:8px; border:1px solid #2b6be6; background:#fff; color:#2b6be6;">
                        Search
                    </button>
                </form>

                {{-- Informasi Total --}}
                <div style="margin-left:8px; color:#6c7680;">
                    @if ($kategoris instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        Total Data: <strong>{{ $kategoris->total() }}</strong>
                        &nbsp;|&nbsp;
                        Ditampilkan: <strong>{{ $kategoris->count() }}</strong>
                    @else
                        Total: <strong>{{ $kategoris->count() }}</strong>
                    @endif
                </div>
            </div>

            {{-- Tombol Tambah --}}
            <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary btn-add-user " style="border-radius: 8px; padding: 8px">
                <i class=""></i> Tambah Kategori
            </a>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table id="kategoriTable" class="table table-custom table-hover">
                <thead>
                    <tr>
                        <th style="width:8%;">ID</th>
                        <th>Nama</th>
                        <th class="col-actions text-end" style="width:15%;">Operasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $k)
                        <tr data-id="{{ $k->id }}">
                            <td>{{ $k->id }}</td>
                            <td>{{ $k->nama }}</td>
                            <td class="col-actions">
                                <div class="table-actions">
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.kategori.edit', $k) }}"
                                        class="btn-action edit border-0 bg-transparent p-0" title="Edit">
                                        <img src="{{ asset('admin/assets/images/icons/edit.svg') }}" alt="Edit">
                                    </a>

                                    {{-- Hapus --}}
                                    <form action="{{ route('admin.kategori.destroy', $k) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action del border-0 bg-transparent p-0"
                                            title="Hapus"
                                            onclick="return confirm('Hapus kategori {{ addslashes($k->nama) }}?')">
                                            <img src="{{ asset('admin/assets/images/icons/delete.svg') }}" alt="Hapus">
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Tidak ada data kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($kategoris instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-3">
                {{ $kategoris->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    {{-- Jika menggunakan pagination, filter client-side di bawah ini tidak perlu/bisa dihapus, karena sudah ada filter server-side dari form GET. --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Memastikan tombol Delete berfungsi dengan konfirmasi
            // Logika filter client-side yang lama telah dihapus karena menggunakan form GET (server-side filter)
        });
    </script>
@endpush
