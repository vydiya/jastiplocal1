@extends('layout.admin-app')

@section('title', 'Laporan Keuntungan Admin')
@section('page-title', 'Laporan Keuntungan Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
<style>
    .summary-card {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .summary-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #495057;
    }
    .summary-value {
        font-size: 2.0rem;
        font-weight: 700;
        color: #198754; /* Warna hijau tua untuk keuntungan */
    }
    .filter-controls label {
        margin-right: 10px;
        font-weight: 500;
        color: #495057;
    }
    .filter-controls input[type="date"] {
        padding: 6px 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        margin-right: 10px;
    }
</style>
@endpush

@section('content')
<div class="user-table-card">
    <h2 class="user-table-title">Laporan Keuntungan Admin</h2>

    {{-- SUMMARY CARD --}}
   

    {{-- FILTER CONTROLS --}}
    <div class="d-flex justify-content-between align-items-center mb-4 filter-controls">
        <form method="GET" action="{{ route('admin.laporan.keuntungan') }}" style="display:flex; align-items:center; gap:12px;">
            <div style="display:flex; align-items:center;">
                <label for="start_date">Dari Tanggal:</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="form-control">
            </div>
            <div style="display:flex; align-items:center;">
                <label for="end_date">Sampai Tanggal:</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="form-control">
            </div>

            <input name="q" value="{{ request('q', $q ?? '') }}" class="user-search-input" type="text"
                placeholder="Cari ID Transaksi/Pesanan" style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px; width:220px;">
            
            <button type="submit" class="btn-search" style="padding:8px 18px; border-radius:8px; border:1px solid #2b6be6; background:#fff; color:#2b6be6;">
                Filter
            </button>
            
            <a href="{{ route('admin.laporan.keuntungan') }}" class="btn btn-sm btn-secondary" style="padding:8px 18px; border-radius:8px; background:#6c757d; color:white;">Reset</a>
        </form>
    </div>
     <div class="summary-card row">
        <div class="col-md-6">
            <div class="summary-title">Total Keuntungan Periode Ini:</div>
            <div class="summary-value">
                Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}
            </div>
            <small class="text-muted">Total dari <strong>{{ $jumlahTransaksi }}</strong> transaksi pelepasan dana yang dikonfirmasi.</small>
        </div>
        <div class="col-md-6 d-flex align-items-center justify-content-end">
            <div class="summary-title text-success">Keuntungan berasal dari Biaya Admin yang terpotong saat Pelepasan Dana ke Jastiper.</div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table id="keuntunganTable" class="table table-custom">
            <thead>
                <tr>
                    <th style="width:5%;">ID Transaksi</th>
                    <th style="width:15%;">Tgl. Pelepasan</th>
                    <th style="width:10%;">ID Pesanan</th>
                    <th style="width:15%;">User Pemesan</th>
                    <th style="width:15%;">Jastiper Penerima</th>
                    <th style="width:15%;">Total Harga Pesanan</th>
                    <th style="width:15%;" class="text-right">Biaya Admin (Keuntungan)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $t)
                <tr data-id="{{ $t->id }}">
                    <td>{{ $t->id }}</td>
                    <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                    {{-- Asumsi Anda memiliki route admin.pesanan.show --}}
                    {{-- <td><a href="{{ route('admin.pesanan.index', $t->pesanan_id) }}" style="color:#2b6be6; text-decoration:underline;">#{{ $t->pesanan_id }}</a></td> --}}
                    <td>{{ $t->pesanan_id ?? 'N/A' }}</td>
                    <td>{{ $t->pesanan->user->name ?? 'N/A' }}</td>
                    <td>{{ $t->pesanan->jastiper->nama_toko ?? 'N/A' }}</td>
                    <td>Rp {{ number_format($t->pesanan->total_harga ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right text-success" style="font-weight:600;">Rp {{ number_format($t->biaya_admin, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding:20px 10px; color:#6c7680;">Tidak ada data laporan keuntungan dalam periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $transaksis->links() }}
    </div>
</div>
@endsection