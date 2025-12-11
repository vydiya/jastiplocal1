@extends('layout.jastiper-app')

@section('title', 'Barang Saya')
@section('page-title', 'Barang Saya')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
@endpush

@section('content')
    <div class="user-table-card">

        <h2 class="user-table-title">Barang Saya</h2>

        {{-- SEARCH + TOTAL + ADD --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="user-controls" style="display:flex; align-items:center; gap:8px;">
                <form method="GET" action="{{ route('jastiper.barang.index') }}"
                    style="display:flex; gap:8px; align-items:center;">
                    <input id="searchBarang" name="q" class="user-search-input" type="text"
                        placeholder="Cari ID / Nama Barang" value="{{ request('q', $q ?? '') }}"
                        style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px; width:320px;"
                        autocomplete="off">

                    <button type="submit" id="btnSearchBarang" class="btn-search"
                        style="padding:8px 18px; border-radius:8px; border:1px solid #2b6be6; background:#fff; color:#2b6be6;">
                        Search
                    </button>
                </form>

                <div style="margin-left:12px; color:#6c7680;">
                    Total: <strong>{{ $barangs->total() ?? $barangs->count() }}</strong>
                    &nbsp;|&nbsp;
                    Ditampilkan: <strong>{{ $barangs->count() }}</strong>
                </div>
            </div>

            <a href="{{ route('jastiper.barang.create') }}" class="btn-add-user"
                style="padding:8px 14px; background:#2b6be6; color:white; border-radius:8px;">
                + Tambah Barang
            </a>
        </div>

        <div class="table-responsive">
            <table id="barangTable" class="table table-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Nama Barang</th>
                        <th>Ongkos Jastip</th>
                        <th>Deskripsi</th>
                        <th>Stok</th>
                        <th>Kategori</th>
                        <th>Tersedia</th>
                        <th>Tanggal Input</th>
                        <th class="col-actions">Operasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $b)
                        <tr data-id="{{ $b->id }}">
                            <td>{{ $b->id }}</td>

                            <td>
                                @if ($b->foto_barang && file_exists(storage_path('app/public/' . $b->foto_barang)))
                                    <img src="{{ Storage::url($b->foto_barang) }}" alt="foto" class="prod-thumb">
                                @else
                                    <img src="{{ asset('admin/assets/images/no-image.png') }}" alt="no-image"
                                        class="prod-thumb no-image">
                                @endif
                            </td>

                            <td>{{ $b->nama_barang }}</td>

                            <td>Rp {{ number_format($b->harga, 0, ',', '.') }}</td>

                            <td>{{ $b->deskripsi }}</td>
                            <td>{{ $b->stok }}</td>
                            <td>{{ $b->kategori?->nama ?? '-' }}</td>
                            <td>{{ $b->is_available === 'yes' ? 'Tersedia' : 'Tidak' }}</td>
                            <td>{{ $b->tanggal_input ? \Carbon\Carbon::parse($b->tanggal_input)->format('Y-m-d') : '-' }}
                            </td>

                            <td class="col-actions" style="text-align:right;">
                                <div class="table-actions">
                                    <a href="{{ route('jastiper.barang.edit', $b) }}" class="btn-action edit"
                                        title="Edit">
                                        <img src="{{ asset('admin/assets/images/icons/edit.svg') }}" alt="Edit">
                                    </a>

                                    <form action="{{ route('jastiper.barang.destroy', $b) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action del"
                                            onclick="return confirm('Hapus barang {{ addslashes($b->nama_barang) }}?')">
                                            <img src="{{ asset('admin/assets/images/icons/delete.svg') }}" alt="Hapus">
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center" style="padding:20px 12px; color:#6c7680;">
                                Belum ada data barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $barangs->withQueryString()->links() }}
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Optional filter
            function filterTable(q) {
                q = (q || '').toLowerCase().trim();
                if (!q) return $('#barangTable tbody tr').show();
                $('#barangTable tbody tr').each(function() {
                    const txt = ($(this).text() || '').toLowerCase();
                    $(this).toggle(txt.indexOf(q) !== -1);
                });
            }
        });
    </script>
@endpush
