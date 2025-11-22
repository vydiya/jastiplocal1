@extends('layout.jastiper-app')

@section('title', 'Rekening')
@section('page-title', 'Rekening')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
@endpush

@section('content')
<div class="user-table-card">
    <h2 class="user-table-title">Rekening</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="user-controls" style="display:flex; align-items:center; gap:8px;">
            <form method="GET" action="{{ route('jastiper.rekening.index') }}" style="display:flex; gap:8px; align-items:center;">
                <input name="q" value="{{ request('q', $q ?? '') }}" class="user-search-input" type="text"
                    placeholder="Cari ID / Penyedia / No. Akun" style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px; width:320px;">
                <button type="submit" class="btn-search" style="padding:8px 18px; border-radius:8px; border:1px solid #2b6be6; background:#fff; color:#2b6be6;">Search</button>
            </form>

            <div style="margin-left:12px; color:#6c7680;">
                Total: <strong>{{ $rekenings->total() ?? $rekenings->count() }}</strong>
                &nbsp;|&nbsp;
                Ditampilkan: <strong>{{ $rekenings->count() }}</strong>
            </div>
        </div>

        <a href="{{ route('jastiper.rekening.create') }}" class="btn-add-user" style="padding:8px 14px; background:#2b6be6; color:white; border-radius:8px;">+ Tambah Rekening</a>
    </div>

    <div class="table-responsive">
        <table id="rekeningTable" class="table table-custom">
            <thead>
                <tr>
                    <th style="width:8%;">ID</th>
                    <th>Penyedia</th>
                    <th>Nama Pemilik</th>
                    <th>No. Akun</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th class="col-actions" style="text-align:right">Operasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekenings as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->nama_penyedia }}</td>
                    <td>{{ $r->nama_pemilik }}</td>
                    <td>{{ $r->nomor_akun }}</td>
                    <td>{{ $r->tipe_rekening ?? '-' }}</td>
                    <td>{{ $r->status_aktif }}</td>
                    <td class="col-actions" style="text-align:right;">
                        <div class="table-actions">
                            <a href="{{ route('jastiper.rekening.edit', $r) }}" class="btn-action edit" title="Edit"><img src="{{ asset('admin/assets/images/icons/edit.svg') }}" alt="Edit"></a>

                            <form action="{{ route('jastiper.rekening.destroy', $r) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action del" onclick="return confirm('Hapus rekening {{ addslashes($r->nomor_akun) }}?')">
                                    <img src="{{ asset('admin/assets/images/icons/delete.svg') }}" alt="Hapus">
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center" style="padding:20px;color:#6c7680;">Tidak ada data rekening.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $rekenings->links() }}
    </div>
</div>
@endsection
