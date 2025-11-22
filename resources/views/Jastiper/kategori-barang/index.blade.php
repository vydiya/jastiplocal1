@extends('layout.jastiper-app')

@section('title', 'Kategori Barang')
@section('page-title', 'Kategori Barang')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
@endpush

@section('content')
<div class="user-table-card">
    <h2 class="user-table-title">Kategori Barang</h2>

    {{-- SEARCH + TOTAL + ADD --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="user-controls" style="display:flex; align-items:center; gap:8px;">
            <form method="GET" action="{{ route('jastiper.kategori-barang.index') }}" style="display:flex; gap:8px; align-items:center;">
                <input name="q" value="{{ request('q', $q ?? '') }}" class="user-search-input" type="text"
                    placeholder="Cari ID / Nama kategori" style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px; width:320px;">
                <button type="submit" class="btn-search" style="padding:8px 18px; border-radius:8px; border:1px solid #2b6be6; background:#fff; color:#2b6be6;">
                    Search
                </button>
            </form>

            <div style="margin-left:12px; color:#6c7680;">
                Total: <strong>{{ $kategoris->total() ?? $kategoris->count() }}</strong>
                &nbsp;|&nbsp;
                Ditampilkan: <strong>{{ $kategoris->count() }}</strong>
            </div>
        </div>

        <a href="{{ route('jastiper.kategori-barang.create') }}" class="btn-add-user" style="padding:8px 14px; background:#2b6be6; color:white; border-radius:8px;">
            + Tambah Kategori
        </a>
    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table id="kategoriTable" class="table table-custom">
            <thead>
                <tr>
                    <th style="width:8%;">ID</th>
                    <th>Nama</th>
                    <th class="col-actions" style="text-align:right">Operasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoris as $k)
                <tr data-id="{{ $k->id }}">
                    <td>{{ $k->id }}</td>
                    <td>{{ $k->nama }}</td>
                    <td class="col-actions" style="text-align:right;">
                        <div class="table-actions">
                            <a href="{{ route('jastiper.kategori-barang.edit', $k) }}" class="btn-action edit" title="Edit">
                                <img src="{{ asset('admin/assets/images/icons/edit.svg') }}" alt="Edit">
                            </a>

                            <form action="{{ route('jastiper.kategori-barang.destroy', $k) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action del" onclick="return confirm('Hapus kategori {{ addslashes($k->nama) }}?')">
                                    <img src="{{ asset('admin/assets/images/icons/delete.svg') }}" alt="Hapus">
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center" style="padding:20px 10px; color:#6c7680;">Tidak ada data kategori.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $kategoris->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // simple client-side filter (optional; server search already available)
    function filterTable(q){
        q = (q||'').toLowerCase().trim();
        if(!q) return document.querySelectorAll('#kategoriTable tbody tr').forEach(tr => tr.style.display = '');
        document.querySelectorAll('#kategoriTable tbody tr').forEach(function(tr){
            const txt = (tr.textContent||'').toLowerCase();
            tr.style.display = (txt.indexOf(q) !== -1) ? '' : 'none';
        });
    }

    const btn = document.querySelector('.btn-search');
    const input = document.querySelector('.user-search-input');
    if (btn && input) {
        btn.addEventListener('click', function(e){
            // keep default form submit (server-side) — optional client filter:
            // e.preventDefault(); filterTable(input.value);
        });
    }
});
</script>
@endpush
