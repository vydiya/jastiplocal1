{{-- resources/views/admin/jastiper/index.blade.php --}}
@extends('admin.layout.app')

@section('title', 'Jastiper - Admin')
@section('page-title', 'Data Jastiper')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
@endpush

@section('content')
<div class="user-table-card">
    {{-- JUDUL HALAMAN --}}
    <h2 class="user-table-title">Data Jastiper</h2>

    {{-- 🔍 SEARCH + TOTAL + ADD BUTTON (samakan dengan Data Pengguna) --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div class="user-controls" style="display:flex; align-items:center; gap:8px;">
            <input id="searchJastiper" class="user-search-input" type="text"
                   placeholder="Cari berdasarkan ID / Nama Toko / Username"
                   style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px;">

            <button id="btnSearchJastiper" class="btn-search"
                    style="padding:8px 18px; border-radius:8px; border:1px solid #2b6be6; background:#fff; color:#2b6be6;">
                Search
            </button>

            <div style="margin-left:8px; color:#6c7680;">
                Total: <strong>{{ $jastipers->total() ?? $jastipers->count() }}</strong>
            </div>
        </div>

        <a href="{{ route('admin.jastiper.create') }}" class="btn-add-user"
           style="padding:8px 14px; background:#2b6be6; color:white; border-radius:8px;">
           + Tambah Jastiper
        </a>
    </div>

    {{-- 🧾 TABLE --}}
    <div class="table-responsive">
        <table id="jastipersTable" class="table table-custom">
            <thead>
                <tr>
                    <th class="col-id">ID</th>
                    <th class="col-name">Nama Toko</th>
                    <th class="col-namalengkap">Pemilik (User)</th>
                    <th class="col-username">No. HP</th>
                    <th class="col-email">Alamat</th>
                    <th class="col-role">Metode</th>
                    <th class="col-tgl">Status</th>
                    <th class="col-tgl">Rating</th>
                    <th class="col-tgl">Tanggal Daftar</th>
                    <th class="col-actions" style="text-align:right">Operasi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($jastipers as $j)
                <tr data-id="{{ $j->id }}">
                    <td class="col-id">{{ $j->id }}</td>
                    <td class="col-name">{{ $j->nama_toko }}</td>
                    <td class="col-namalengkap">{{ $j->user?->name ?? '-' }}</td>
                    <td class="col-username">{{ $j->no_hp ?? '-' }}</td>
                    <td class="col-email"><span class="truncate" title="{{ $j->alamat }}">{{ $j->alamat ?? '-' }}</span></td>
                    <td class="col-role">{{ ucfirst($j->metode_pembayaran) }}</td>
                    <td class="col-tgl">{{ ucfirst($j->status_verifikasi) }}</td>
                    <td class="col-tgl">{{ number_format($j->rating,1) }}</td>
                    <td class="col-tgl">{{ $j->tanggal_daftar?->format('Y-m-d') ?? '-' }}</td>

                    <td class="col-actions" style="text-align:right;">
                        <div class="table-actions">
                            <a href="{{ route('admin.jastiper.edit', $j) }}" class="btn-action edit" title="Edit">
                                <img src="{{ asset('admin/assets/images/icons/edit.svg') }}" alt="Edit">
                            </a>

                            <form action="{{ route('admin.jastiper.destroy', $j) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action del" onclick="return confirm('Hapus jastiper {{ addslashes($j->nama_toko) }}?')">
                                    <img src="{{ asset('admin/assets/images/icons/delete.svg') }}" alt="Hapus">
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center; padding:20px 10px; color:#6c7680;">
                        Tidak ada data jastiper.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-3">
        {{ $jastipers->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Simple client-side search that matches any cell text
    function filterTable(q) {
        q = (q || '').toLowerCase().trim();
        if (!q) {
            // show all
            document.querySelectorAll('#jastipersTable tbody tr').forEach(tr => tr.style.display = '');
            return;
        }
        document.querySelectorAll('#jastipersTable tbody tr').forEach(function (tr) {
            const txt = (tr.textContent || tr.innerText || '').toLowerCase();
            tr.style.display = (txt.indexOf(q) !== -1) ? '' : 'none';
        });
    }

    const btn = document.getElementById('btnSearchJastiper');
    const input = document.getElementById('searchJastiper');

    if (btn && input) {
        btn.addEventListener('click', function () {
            filterTable(input.value);
        });
        input.addEventListener('keyup', function (e) {
            if (e.key === 'Enter') filterTable(this.value);
        });
    }
});
</script>
@endpush
