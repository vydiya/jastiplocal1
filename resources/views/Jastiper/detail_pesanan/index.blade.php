{{-- resources/views/jastiper/detail_pesanan/index.blade.php --}}
@extends('layout.jastiper-app')

@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
@endpush

@section('content')
    <div class="user-table-card">
        <h2 class="user-table-title">Detail Pesanan</h2>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="user-controls" style="display:flex; align-items:center; gap:8px;">
                {{-- gunakan form GET agar pencarian dikirim ke server (seragam dg halaman lain) --}}
                <form action="{{ route('jastiper.detail-pesanan.index') }}" method="GET"
                    style="display:flex; gap:8px; align-items:center;">
                    <input id="searchDetailInput" name="q" value="{{ request('q', $q ?? '') }}"
                        class="user-search-input" type="text" placeholder="Cari ID Pesanan / Nama Barang / Nama Toko"
                        style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px; width:320px;"
                        autocomplete="off">

                    <button id="btnSearchDetail" type="submit" class="btn-search"
                        style="padding:8px 18px; border-radius:8px; border:1px solid #2b6be6; background:#fff; color:#2b6be6;">
                        Search
                    </button>
                </form>

                <div style="margin-left:12px; color:#6c7680;">
                    Total: <strong>{{ $detailPesanans->total() ?? $detailPesanans->count() }}</strong>
                    &nbsp;|&nbsp;
                    Ditampilkan: <strong>{{ $detailPesanans->count() }}</strong>
                </div>
            </div>

            {{-- <a href="{{ route('jastiper.detail-pesanan.create') }}" class="btn-add-user"
                style="padding:8px 14px; background:#2b6be6; color:white; border-radius:8px;">
                + Tambah Detail
            </a> --}}
        </div>

        <div class="table-responsive">
            <table id="detailTable" class="table table-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pesanan ID</th>
                        <th>Foto Barang</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        {{-- <th>Operasi</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailPesanans as $d)
                        <tr>
                            <td>{{ $d->id }}</td>
                            <td>{{ $d->pesanan_id }}</td>
<td>
                                @if ($d->barang?->foto_barang && file_exists(storage_path('app/public/' . $d->barang?->foto_barang)))
                                    <img src="{{ Storage::url($d->barang?->foto_barang) }}" alt="foto" class="prod-thumb">
                                @else
                                    <img src="{{ asset('admin/assets/images/no-image.png') }}" alt="no-image"
                                        class="prod-thumb no-image">
                                @endif

                            </td>                            <td>{{ $d->barang?->nama_barang ?? '-' }}</td>
                            <td>{{ $d->jumlah ?? '-' }}</td>
                            <td>{{ number_format($d->subtotal, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $detailPesanans->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function filterTable(q) {
                q = (q || '').toLowerCase().trim();
                if (!q) return $('#detailTable tbody tr').show();
                $('#detailTable tbody tr').each(function() {
                    const txt = ($(this).text() || '').toLowerCase();
                    $(this).toggle(txt.indexOf(q) !== -1);
                });
            }
            document.getElementById('btnSearchDetail').addEventListener('click', function() {
                filterTable(document.getElementById('searchDetail').value)
            });
            document.getElementById('searchDetail').addEventListener('keyup', function(e) {
                if (e.key === 'Enter') filterTable(this.value)
            });
        });
    </script>
@endpush
