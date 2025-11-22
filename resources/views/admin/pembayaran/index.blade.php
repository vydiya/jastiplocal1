@extends('layout.admin-app')

@section('title','Data Pembayaran')
@section('page-title','Data Pembayaran')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
@endpush

@section('content')
<div class="user-table-card">
    <h2 class="user-table-title">Data Pembayaran</h2>

    {{-- SEARCH + TOTAL + ADD --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="user-controls" style="display:flex; align-items:center; gap:8px;">
            <form method="GET" action="{{ route('admin.pembayaran.index') }}" style="display:flex; gap:8px; align-items:center;">
                <input name="q" value="{{ $q ?? '' }}" class="user-search-input" type="text"
                       placeholder="Cari berdasarkan ID / Metode / Status / No HP / Alamat"
                       style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px; width:320px;">
                <button type="submit" class="btn-search" style="padding:8px 18px; border-radius:8px; border:1px solid #2b6be6; background:#fff; color:#2b6be6;">
                    Search
                </button>
            </form>

            <div style="margin-left:12px; color:#6c7680;">
                Total: <strong>{{ $pembayarans->total() }}</strong>
            </div>
        </div>

        <a href="{{ route('admin.pembayaran.create') }}" class="btn-add-user" style="padding:8px 14px; background:#2b6be6; color:white; border-radius:8px;">
            + Tambah Pembayaran
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pesanan</th>
                    <th>Metode</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Tanggal Bayar</th>
                    <th class="col-actions">Bukti</th>
                    <th class="col-actions">Operasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembayarans as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>
                            @if($p->pesanan)
                                #{{ $p->pesanan->id }} — {{ $p->pesanan->no_hp ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ ucfirst($p->metode_pembayaran) }}</td>
                        <td>Rp {{ number_format($p->jumlah_bayar,2,',','.') }}</td>
                        <td>{{ ucfirst($p->status_pembayaran) }}</td>
                        <td>{{ $p->tanggal_bayar?->format('Y-m-d H:i') ?? '-' }}</td>
                        <td class="col-actions">
                            @if($p->bukti_bayar)
                                <a href="{{ asset('storage/'.$p->bukti_bayar) }}" target="_blank" class="btn-action" title="Lihat Bukti">
                                    <img src="{{ asset('admin/assets/images/icons/eye.svg') }}" alt="lihat">
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="col-actions" style="text-align:right;">
                            <div class="table-actions">
                                <a href="{{ route('admin.pembayaran.edit', $p) }}" class="btn-action edit" title="Edit">
                                    <img src="{{ asset('admin/assets/images/icons/edit.svg') }}" alt="Edit">
                                </a>
                                <form action="{{ route('admin.pembayaran.destroy', $p) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action del" onclick="return confirm('Hapus pembayaran ini?')">
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
        {{ $pembayarans->links() }}
    </div>
</div>
@endsection
