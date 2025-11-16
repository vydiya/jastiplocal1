{{-- resources/views/jastiper/detail_pesanan/index.blade.php --}}
@extends('admin.layout.app')

@section('title','Detail Pesanan')
@section('page-title','Detail Pesanan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
@endpush

@section('content')
<div class="user-table-card">
    <h2 class="user-table-title">Detail Pesanan</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="user-controls" style="display:flex; align-items:center; gap:8px;">
            <input id="searchDetail" class="user-search-input" type="text" placeholder="Cari ID Pesanan / Nama Barang">
            <button id="btnSearchDetail" class="btn-search">Search</button>
            <div style="margin-left:8px; color:#6c7680;">
                Total: <strong>{{ $detailPesanans->total() }}</strong>
            </div>
        </div>

        <a href="{{ route('jastiper.detail-pesanan.create') }}" class="btn-add-user">+ Tambah Detail</a>
    </div>

    <div class="table-responsive">
        <table id="detailTable" class="table table-custom">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pesanan ID</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Operasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detailPesanans as $d)
                    <tr>
                        <td>{{ $d->id }}</td>
                        <td>{{ $d->pesanan_id }}</td>
                        <td>{{ $d->barang?->nama ?? '-' }}</td>
                        <td>{{ $d->jumlah }}</td>
                        <td>{{ number_format($d->subtotal,2) }}</td>
                        <td class="col-actions">
                            <div class="table-actions">
                                <a href="{{ route('jastiper.detail-pesanan.edit', $d) }}" class="btn-action edit" title="Edit">
                                    <img src="{{ asset('admin/assets/images/icons/edit.svg') }}" alt="Edit">
                                </a>
                                <form action="{{ route('jastiper.detail-pesanan.destroy', $d) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action del" onclick="return confirm('Hapus detail pesanan ini?')">
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
        {{ $detailPesanans->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    function filterTable(q){
        q = (q||'').toLowerCase().trim();
        if(!q) return $('#detailTable tbody tr').show();
        $('#detailTable tbody tr').each(function(){
            const txt = ($(this).text()||'').toLowerCase();
            $(this).toggle(txt.indexOf(q)!==-1);
        });
    }
    document.getElementById('btnSearchDetail').addEventListener('click', function(){ filterTable(document.getElementById('searchDetail').value) });
    document.getElementById('searchDetail').addEventListener('keyup', function(e){ if(e.key==='Enter') filterTable(this.value) });
});
</script>
@endpush
