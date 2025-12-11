@extends('layout.jastiper-app')

@section('title', 'Pesanan - Jastiper')
@section('page-title', 'Data Pesanan')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
{{-- Custom Styling --}}
<style>
    /* Styling untuk Navigasi Tab */
    .nav-tabs .nav-link {
        border: 1px solid transparent;
        border-top-left-radius: 0.25rem;
        border-top-right-radius: 0.25rem;
        margin-right: 5px;
        padding: 8px 15px;
        font-weight: 500;
        color: #6c757d; /* Warna default */
    }

    .nav-tabs .nav-link.active {
        color: #2b6be6 !important;
        border-color: #dee2e6 #dee2e6 #fff;
        border-bottom: 3px solid #2b6be6 !important;
    }

    /* Styling untuk Tombol Aksi */
    .btn-kirim {
        padding: 5px 10px;
        background: #28a745;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.2s;
    }

    .btn-kirim:hover {
        background: #218838;
    }
    
    .btn-detail {
        padding: 5px 10px;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.2s;
    }
    .btn-detail:hover {
        background: #0056b3;
    }


    /* Styling tambahan untuk modal */
    .modal-body p {
        margin-bottom: 5px;
    }

    .table-custom tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@section('content')
<div class="user-table-card">
    <h2 class="user-table-title">Data Pesanan</h2>

    {{-- ALERT SUCCESS --}}
    @if (session('success'))
    <div class="alert alert-success mb-3" role="alert"
        style="padding:10px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px;">
        {{ session('success') }}
    </div>
    @endif

    {{-- ALERT ERROR --}}
    @if ($errors->any())
    <div class="alert alert-danger mb-3" role="alert"
        style="padding:10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">
        @foreach ($errors->all() as $error)
        {{ $error }}<br>
        @endforeach
    </div>
    @endif
    
    {{-- ALERT ERROR dari Controller Status Update --}}
    @if (session('error'))
    <div class="alert alert-danger mb-3" role="alert"
        style="padding:10px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">
        {{ session('error') }}
    </div>
    @endif


    {{-- SEARCH + TOTAL --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="user-controls" style="display:flex; align-items:center; gap:8px;">
            <form method="GET" action="{{ route('jastiper.pesanan.index') }}"
                style="display:flex; gap:8px; align-items:center;">

                {{-- Pertahankan filter status saat mencari --}}
                @foreach((array) $status as $s)
                <input type="hidden" name="status[]" value="{{ $s }}">
                @endforeach

                <input name="q" value="{{ request('q', $q ?? '') }}" class="user-search-input" type="text"
                    placeholder="Cari berdasarkan ID / No HP / Alamat"
                    style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px; width:320px;"
                    autocomplete="off">

                <button type="submit" class="btn-search"
                    style="padding:8px 18px; border-radius:8px; border:1px solid #2b6be6; background:#fff; color:#2b6be6;">
                    Search
                </button>

                @if ($q)
                <a href="{{ route('jastiper.pesanan.index', request()->except('q', 'page')) }}"
                    style="padding:8px 18px; border-radius:8px; border:1px solid #DDE0E3; background:#f8f9fa; color:#6c757d; text-decoration: none;">
                    Reset
                </a>
                @endif
            </form>

            <div style="margin-left:8px; color:#6c7680;">
                Total: <strong>{{ $pesanans->total() }}</strong>
            </div>
        </div>
    </div>

    {{-- TAB NAVIGASI STATUS --}}
    <ul class="nav nav-tabs mb-3">
        @php
        $statusArray = array_map('strtoupper', (array) $status); // Pastikan status di-uppercase untuk perbandingan
        $isDiprosesActive = (count($statusArray) === 1 && in_array('DIPROSES', $statusArray));
        $isSiapKirimActive = (count($statusArray) === 1 && in_array('SIAP_DIKIRIM', $statusArray));
        $isRiwayatActive = (count(array_intersect(['SELESAI', 'DIBATALKAN'], $statusArray)) > 0);
        $isDefaultActive = (!$isDiprosesActive && !$isSiapKirimActive && !$isRiwayatActive && (count($statusArray) === 2 && in_array('DIPROSES', $statusArray) && in_array('SIAP_DIKIRIM', $statusArray))); // Default ketika array isinya DIPROSES & SIAP_DIKIRIM
        @endphp

        <li class="nav-item">
            {{-- Menggunakan [DIPROSES] sebagai filter tunggal --}}
            <a class="nav-link @if($isDiprosesActive) active @endif"
                href="{{ route('jastiper.pesanan.index', array_merge(request()->except('status', 'page'), ['status' => ['DIPROSES'], 'page' => 1])) }}">
                DIPROSES
            </a>
        </li>
        <li class="nav-item">
            {{-- Menggunakan [SIAP_DIKIRIM] sebagai filter tunggal --}}
            <a class="nav-link @if($isSiapKirimActive) active @endif"
                href="{{ route('jastiper.pesanan.index', array_merge(request()->except('status', 'page'), ['status' => ['SIAP_DIKIRIM'], 'page' => 1])) }}">
                DIKIRIM
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if($isRiwayatActive) active @endif"
                href="{{ route('jastiper.pesanan.index', array_merge(request()->except('status', 'page'), ['status' => ['SELESAI', 'DIBATALKAN'], 'page' => 1])) }}">
                RIWAYAT
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if($isDefaultActive) active @endif"
                href="{{ route('jastiper.pesanan.index', array_merge(request()->except('status', 'page'), ['status' => ['DIPROSES', 'SIAP_DIKIRIM'], 'page' => 1])) }}">
                SEMUA
            </a>
        </li>
    </ul>

    <div class="table-responsive">
        <table id="pesanansTable" class="table table-custom">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pemesan</th>
                    <th>Tanggal Pesan</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Alamat</th>     
                    <th>No. HP</th>
                    <th>Catatan</th>
                    <th class="col-actions" style="text-align:right;">Operasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesanans as $p)
                <tr data-id="{{ $p->id }}">
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->user?->name ?? '-' }}</td>
                    <td>{{ $p->tanggal_pesan?->format('Y-m-d H:i') }}</td>
                    <td>Rp {{ number_format($p->total_harga, 2, ',', '.') }}</td> 
                    <td>{{ str_replace('_', ' ', strtoupper($p->status_pesanan)) }}</td> 
                    <td><span class="truncate"
                            title="{{ $p->alamat_pengiriman }}">{{ $p->alamat_pengiriman ?? '-' }}</span></td>
                            <td>{{ $p->no_hp ?? '-' }}</td>
                            <td>{{ $p->catatan?? '-' }}</td>
                    <td class="col-actions" style="text-align:right;">
                        <div class="table-actions" style="display:flex; justify-content:flex-end; gap:5px;">

                            {{-- Tombol Lihat Detail (Diperlukan untuk memicu modal)
                            <button type="button" class="btn-detail view-detail" data-id="{{ $p->id }}" data-toggle="modal" data-target="#pesananDetailModal" title="Lihat Detail Pesanan">
                                Detail
                            </button> --}}
                            
                            {{-- Tombol Ubah Status (Hanya muncul jika DIPROSES) --}}
                            @if (strtoupper($p->status_pesanan) == 'DIPROSES')
                            {{-- Menggunakan rute baru updateStatusToSiapDikirim --}}
                            <form action="{{ route('jastiper.pesanan.update.siap.kirim', $p) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('PUT')

                                <button type="submit" class="btn-kirim" title="Ubah ke Siap Dikirim"
                                    onclick="return confirm('Apakah Anda yakin pesanan #{{ $p->id }} sudah siap dikirim? Status akan berubah menjadi DIKIRIM. Tindakan ini tidak bisa dibatalkan.')">
                                    Siap Kirim
                                </button>
                            </form>
                            @endif

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $pesanans->links() }}
    </div>
</div>

{{-- MODAL DETAIL PESANAN --}}
<div class="modal fade" id="pesananDetailModal" tabindex="-1" aria-labelledby="pesananDetailModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pesananDetailModalLabel">Detail Pesanan #<span id="detail-id"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="loading-spinner" style="text-align:center; padding:20px;">
                    <p>Memuat data...</p>
                </div>
                <div id="detail-content" style="display:none;">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Utama</h6>
                            <p><strong>Pemesan:</strong> <span id="detail-pemesan"></span></p>
                            <p><strong>Tanggal Pesan:</strong> <span id="detail-tanggal"></span></p>
                            <p><strong>Total Harga:</strong> <span id="detail-total-harga"></span></p>
                            <p><strong>Status Pesanan:</strong> <span id="detail-status"></span></p>
                            <p><strong>Status Dana Jastiper:</strong> <span id="detail-status-dana"></span></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Pengiriman</h6>
                            <p><strong>No. HP:</strong> <span id="detail-no-hp"></span></p>
                            <p><strong>Alamat:</strong> <span id="detail-alamat"></span></p>
                        </div>
                    </div>

                    <hr>
                    <h6>Detail Barang</h6>
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th style="width: 10%;">Qty</th>
                                <th style="width: 25%;">Harga Satuan</th>
                                <th style="width: 25%;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="detail-barang-table">
                            </tbody>
                    </table>

                    <hr>
                    <h6>Informasi Pembayaran</h6>
                    <div id="detail-pembayaran">
                        <p>Loading pembayaran...</p>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Fungsi number_format sederhana (menggunakan format Rupiah Indonesia)
        function number_format(number, decimals, decPoint, thousandsSep) {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousandsSep === 'undefined') ? '.' : thousandsSep,
                dec = (typeof decPoint === 'undefined') ? ',' : decPoint,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + (Math.round(n * k) / k).toFixed(prec);
                };
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return 'Rp ' + s.join(dec); // Tambahkan 'Rp ' di sini
        }

        // Handler untuk tombol Lihat Detail
        // Gunakan event delegation karena tombol view-detail mungkin tidak ada saat DOM Ready
        $('#pesanansTable').on('click', '.view-detail', function() {
            var pesananId = $(this).data('id');
            // Route menggunakan parameter pesanan ID (pastikan route name benar)
            var url = "{{ route('jastiper.pesanan.show.data', ':id') }}";
            url = url.replace(':id', pesananId);

            // Reset tampilan modal
            $('#detail-content').hide();
            $('#loading-spinner').show();
            $('#detail-id').text(pesananId);

            // Fetch data via AJAX
            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {

                    $('#loading-spinner').hide();
                    $('#detail-content').show();

                    // Fungsi helper untuk memformat status
                    function formatStatus(status) {
                        return (status ?? '-').replace(/_/g, ' ').toUpperCase();
                    }
                    
                    // --- Mengisi Data Utama ---
                    $('#detail-pemesan').text(data.pemesan ?? '-');
                    $('#detail-tanggal').text(data.tanggal_pesan ?? '-');
                    $('#detail-total-harga').text(number_format(data.total_harga, 2, ',', '.'));
                    $('#detail-status').text(formatStatus(data.status_pesanan));
                    $('#detail-status-dana').text(formatStatus(data.status_dana_jastiper));
                    $('#detail-no-hp').text(data.no_hp ?? '-');
                    $('#detail-alamat').text(data.alamat_pengiriman ?? '-');

                    // --- Mengisi Detail Barang ---
                    var barangHtml = '';
                    if (data.detail_pesanans && data.detail_pesanans.length > 0) {
                        data.detail_pesanans.forEach(function(item) {
                            barangHtml += `
                                <tr>
                                    <td>${item.nama_barang ?? 'Barang Dihapus'}</td>
                                    <td>${item.jumlah ?? '0'}</td>
                                    <td>${number_format(item.harga_satuan, 2, ',', '.')}</td>
                                    <td>${number_format(item.subtotal, 2, ',', '.')}</td>
                                </tr>
                            `;
                        });
                    } else {
                        barangHtml = '<tr><td colspan="4" class="text-center">Tidak ada detail barang.</td></tr>';
                    }
                    $('#detail-barang-table').html(barangHtml);

                    // --- Mengisi Informasi Pembayaran ---
                    var pembayaranHtml = '<p>Pembayaran belum tercatat/terverifikasi.</p>';
                    if (data.pembayaran && data.pembayaran.length > 0) {
                        var bayar = data.pembayaran[0];
                        pembayaranHtml = `
                            <p><strong>Metode Pembayaran:</strong> ${bayar.metode_pembayaran ?? '-'}</p>
                            <p><strong>Jumlah Bayar:</strong> ${number_format(bayar.jumlah_bayar, 2, ',', '.')}</p>
                            <p><strong>Status Pembayaran:</strong> ${formatStatus(bayar.status_pembayaran)}</p>
                        `;
                    }
                    $('#detail-pembayaran').html(pembayaranHtml);

                },
                error: function(xhr) {
                    $('#loading-spinner').hide();
                    // Tampilkan pesan error
                    $('#detail-content').html(
                        '<p class="text-danger">Gagal memuat detail pesanan: ' + (xhr.responseJSON
                            ?.error || 'Terjadi kesalahan server.') + '</p>'
                    );
                    $('#detail-content').show();
                }
            });
        });
    });
</script>
@endpush