@extends('layout.jastiper-app')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@push('styles')

<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
<style>
/* Perluas style summary-card agar bisa menampung 2 kolom */
.summary-container {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}
.summary-card {
    flex: 1;
    background: #e9f0ff;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #c2d4ff;
}
.summary-card.netto {
    background: #e6ffed; /* Hijau muda untuk Netto */
    border-color: #c2ffce;
}
.summary-card.netto h4 {
    color: #1a9c4d; /* Hijau tua */
}
.summary-card.netto p {
    color: #218838;
}

.summary-card h4 {
    color: #2b6be6;
    font-weight: 600;
    margin-bottom: 5px;
}
.summary-card p {
    font-size: 2rem;
    font-weight: bold;
    color: #1a4d9c;
}

.text-muted {
  color: #6c7680 !important;
}
</style>
@endpush

@section('content')

<div class="user-table-card">
<h2 class="user-table-title">Laporan Penjualan </h2>

{{-- Ringkasan Total Penjualan (Dibuat 2 Kolom) --}}
<div class="summary-container">
    {{-- BRUTO CARD (Total Harga Pesanan) --}}
    <div class="summary-card">
        <h4>Total Penjualan Kotor (Bruto)</h4>
        <p>
            Rp{{ number_format($totalPenjualanBruto ?? 0, 0, ',', '.') }}
        </p>
        <small class="text-muted">Total harga pesanan sebelum dipotong 10% biaya administrasi.</small>
    </div>

    {{-- NETTO CARD (Dana Masuk) --}}
    <div class="summary-card netto">
        <h4>Total Dana Masuk (Netto)</h4>
        <p>
            Rp{{ number_format($totalPenjualanNetto ?? 0, 0, ',', '.') }}
        </p>
        <small class="text-muted">Total {{ $jumlahTransaksi ?? 0 }} transaksi pelepasan dana yang sudah dikonfirmasi.</small>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
  <form method="GET" action="{{ route('jastiper.laporan-keuntungan.index') }}" style="display:flex; gap:8px; align-items:center;">
    <input name="q" value="{{ $q ?? '' }}" class="user-search-input" type="text" placeholder="Cari ID Transaksi / ID Pesanan"
       style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px; width:360px;" autocomplete="off">
    <button type="submit" class="btn-search" style="padding:8px 14px; border-radius:8px; border:1px solid #2b6be6; background:#fff; color:#2b6be6;">
      Cari
    </button>
    @if ($q)
    <a href="{{ route('jastiper.laporan-keuntungan.index') }}"
      style="padding:8px 18px; border-radius:8px; border:1px solid #DDE0E3; background:#f8f9fa; color:#6c757d; text-decoration: none;">
      Reset
    </a>
    @endif
  </form>
</div>

@if(session('success'))
  <div class="alert alert-success" style="margin-bottom:12px;">{{ session('success') }}</div>
@endif

<div class="table-responsive">
  <table class="table table-custom">
    <thead>
      <tr>
        <th>ID Transaksi</th>
        <th>ID Pesanan</th>
                <th>Dana Kotor (Bruto)</th> {{-- KOLOM BARU --}}
        <th>Dana Masuk (Netto)</th>
        <th>Tanggal Konfirmasi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($transaksis as $t)
      <tr>
        <td>{{ $t->id }}</td>
        <td><a href="#" class="text-primary">{{ $t->pesanan_id }}</a></td>
                {{-- Menampilkan Dana Bruto dari total_harga Pesanan --}}
                <td>Rp{{ number_format($t->pesanan->total_harga ?? 0, 0, ',', '.') }}</td>
                
        <td class="font-weight-bold text-success">
          Rp{{ number_format($t->jumlah_dana ?? 0, 0, ',', '.') }}
        </td>
        <td>{{ optional($t->updated_at)->format('Y-m-d H:i') }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="5" class="text-center" style="padding:20px 0; color:#6c7680;">Tidak ada data laporan penjualan yang dikonfirmasi.</td>
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