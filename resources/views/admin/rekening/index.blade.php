@extends('layout.admin-app')

@section('title', 'Data Rekening')
@section('page-title', 'Data Rekening')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
@endpush

@section('content')
<div class="user-table-card">
    <h2 class="user-table-title">Data Rekening Admin</h2>

    {{-- SEARCH + TOTAL + ADD --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="user-controls" style="display:flex; align-items:center; gap:8px;">
            {{-- Sesuaikan route dan variabel pencarian --}}
            <form method="GET" action="{{ route('admin.rekening.index') }}" style="display:flex; gap:8px; align-items:center;">
                <input name="q" value="{{ request('q', $q ?? '') }}" class="user-search-input" type="text"
                    placeholder="Cari ID / Nomor Akun / Nama Pemilik" style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px; width:320px;">
                <button type="submit" class="btn-search" style="padding:8px 18px; border-radius:8px; border:1px solid #2b6be6; background:#fff; color:#2b6be6;">
                    Search
                </button>
            </form>

            <div style="margin-left:12px; color:#6c7680;">
                {{-- Asumsi data rekening dikirim dengan nama $rekenings --}}
                Total: <strong>{{ $rekenings->count() }}</strong>
                &nbsp;|&nbsp;
                Ditampilkan: <strong>{{ $rekenings->count() }}</strong>
            </div>
        </div>

        <a href="{{ route('admin.rekening.create') }}" class="btn-add-user" style="padding:8px 14px; background:#2b6be6; color:white; border-radius:8px;">
            + Tambah Rekening
        </a>
    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table id="rekeningTable" class="table table-custom">
            <thead>
                <tr>
                    <th style="width:5%;">ID</th>
                    <th style="width:10%;">User ID</th>
                    <th style="width:10%;">Tipe</th>
                    <th style="width:15%;">Penyedia</th>
                    <th style="width:20%;">Nomor Akun</th>
                    <th style="width:20%;">Nama Pemilik</th>
                    <th style="width:10%;">Status</th>
                    <th class="col-actions" style="text-align:right">Operasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekenings as $r)
                <tr data-id="{{ $r->id }}">
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->user_id }}</td>
                    <td>{{ Str::title($r->tipe_rekening) }}</td>
                    <td>{{ $r->nama_penyedia }}</td>
                    <td>{{ $r->nomor_akun }}</td>
                    <td>{{ $r->nama_pemilik }}</td>
                    <td>
                        <span class="badge {{ $r->status_aktif === 'aktif' ? 'bg-success' : 'bg-secondary' }}" 
                              style="padding: 4px 8px; border-radius: 4px; color: white; background-color: {{ $r->status_aktif === 'aktif' ? '#28a745' : '#6c757d' }};">
                            {{ Str::title($r->status_aktif) }}
                        </span>
                    </td>
                    <td class="col-actions" style="text-align:right;">
                        <div class="table-actions">
                            <a href="{{ route('admin.rekening.edit', $r) }}" class="btn-action edit" title="Edit">
                                <img src="{{ asset('admin/assets/images/icons/edit.svg') }}" alt="Edit">
                            </a>

                            <form action="{{ route('admin.rekening.destroy', $r) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action del" onclick="return confirm('Hapus rekening {{ addslashes($r->nomor_akun) }} milik {{ addslashes($r->nama_pemilik) }}?')">
                                    <img src="{{ asset('admin/assets/images/icons/delete.svg') }}" alt="Hapus">
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding:20px 10px; color:#6c7680;">Tidak ada data rekening.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Asumsi tidak menggunakan pagination di admin, jika iya, buka komentar ini --}}
    {{-- <div class="mt-3">
        {{ $rekenings->links() }}
    </div> --}}
</div>
@endsection

@push('scripts')
<script>
// Logika filter sisi klien (jika diperlukan)
document.addEventListener('DOMContentLoaded', function () {
    // Fungsi filter dipertahankan dari contoh Kategori, hanya untuk pencarian klien
    function filterTable(q){
        q = (q||'').toLowerCase().trim();
        if(!q) return document.querySelectorAll('#rekeningTable tbody tr').forEach(tr => tr.style.display = '');
        document.querySelectorAll('#rekeningTable tbody tr').forEach(function(tr){
            const txt = (tr.textContent||'').toLowerCase();
            tr.style.display = (txt.indexOf(q) !== -1) ? '' : 'none';
        });
    }

    const btn = document.querySelector('.btn-search');
    const input = document.querySelector('.user-search-input');
    if (btn && input) {
        btn.addEventListener('click', function(e){
            // Saat ini menggunakan server-side search (form submit), jika mau client-side uncomment line bawah
            // e.preventDefault(); filterTable(input.value);
        });
    }
});
</script>
@endpush