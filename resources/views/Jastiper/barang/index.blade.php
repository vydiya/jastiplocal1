@extends('admin.layout.app')

@section('title', 'Barang Saya')
@section('page-title', 'Barang Saya')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
@endpush

@section('content')
<div class="user-table-card">

    <h2 class="user-table-title">Barang Saya</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="user-controls" style="display:flex; align-items:center; gap:8px;">
            <input id="searchBarang" class="user-search-input" type="text" placeholder="Cari ID / Nama Barang">
            <button id="btnSearchBarang" class="btn-search">Search</button>
            <div style="margin-left:8px; color:#6c7680;">
                Total: <strong>{{ $barangs->total() }}</strong>
            </div>
        </div>

        <a href="{{ route('jastiper.barang.create') }}" class="btn-add-user">+ Tambah Barang</a>
    </div>

    <div class="table-responsive">
        <table id="barangTable" class="table table-custom">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Foto</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Kategori</th>
                    <th>Tersedia</th>
                    <th>Validasi</th>
                    <th>Tanggal Input</th>
                    <th class="col-actions">Operasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barangs as $b)
                <tr data-id="{{ $b->id }}">
                    <td>{{ $b->id }}</td>

                    <td>
                        @if($b->foto_barang)
                            <img src="{{ asset('storage/'.$b->foto_barang) }}" alt="foto" style="height:40px;border-radius:6px;">
                        @else
                            <img src="{{ asset('admin/assets/images/no-image.png') }}" alt="no-image" style="height:40px;border-radius:6px;opacity:.6">
                        @endif
                    </td>

                    <td>{{ $b->nama_barang }}</td>
                    <td>Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
                    <td>{{ $b->stok }}</td>
                    <td>{{ $b->kategori?->nama ?? '-' }}</td>
                    <td>{{ $b->is_available === 'yes' ? 'Tersedia' : 'Tidak' }}</td>
                    <td>{{ $b->status_validasi }}</td>
                    <td>{{ $b->tanggal_input ? $b->tanggal_input->format('Y-m-d') : '-' }}</td>

                    <td class="col-actions" style="text-align:right;">
                        <div class="table-actions">
                            <a href="{{ route('jastiper.barang.edit', $b) }}" class="btn-action edit" title="Edit">
                                <img src="{{ asset('admin/assets/images/icons/edit.svg') }}" alt="Edit">
                            </a>

                            <form action="{{ route('jastiper.barang.destroy', $b) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action del" onclick="return confirm('Hapus barang {{ addslashes($b->nama_barang) }}?')">
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
        {{ $barangs->links() }}
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function filterTable(q){
        q = (q||'').toLowerCase().trim();
        if(!q) return $('#barangTable tbody tr').show();
        $('#barangTable tbody tr').each(function(){
            const txt = ($(this).text()||'').toLowerCase();
            $(this).toggle(txt.indexOf(q) !== -1);
        });
    }
    document.getElementById('btnSearchBarang').addEventListener('click', function(){ filterTable(document.getElementById('searchBarang').value) });
    document.getElementById('searchBarang').addEventListener('keyup', function(e){ if(e.key === 'Enter') filterTable(this.value) });
});
</script>
@endpush
