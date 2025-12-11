@extends('layout.admin-app')

@section('title', 'Manajemen Dana Pesanan - Admin')
@section('page-title', 'Konfirmasi & Pelepasan Dana')

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-user_table.css') }}">
    <style>
        .status-badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
            min-width: 100px;
            text-align: center;
            letter-spacing: 0.3px;
        }

        .status-menunggu {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-selesai {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-gagal {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .btn-action-custom {
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 0.813rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-konfirmasi {
            background: #2b6be6;
            color: white;
            /* Hapus min-width agar muat dua tombol */
        }

        .btn-konfirmasi:hover {
            background: #225abc;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(43, 107, 230, 0.3);
        }

        /* --- STYLE BARU: TOMBOL TOLAK --- */
        .btn-tolak {
            background: #fff;
            color: #dc3545;
            border: 1px solid #dc3545;
            margin-left: 5px;
        }

        .btn-tolak:hover {
            background: #dc3545;
            color: white;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
            transform: translateY(-1px);
        }

        .row-pelepasan-dana>td {
            background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
            border-top: 2px solid #e9ecef !important;
            border-bottom: 2px solid #e9ecef !important;
            padding: 20px 15px !important;
        }

        .pelepasan-container {
            background: white;
            border: 1px solid #e3e6ea;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        }

        .pelepasan-title {
            color: #2b6be6;
            font-size: 0.938rem;
            font-weight: 700;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pelepasan-form {
            display: flex;
            align-items: flex-end;
            gap: 16px;
        }

        .form-group-custom {
            flex: 1;
            max-width: 500px;
        }

        .form-label-custom {
            font-weight: 600;
            font-size: 0.813rem;
            color: #495057;
            margin-bottom: 8px;
            display: block;
        }

        .file-input-custom {
            padding: 10px 14px;
            border: 1px solid #DDE0E3;
            border-radius: 8px;
            width: 100%;
            background: white;
            color: #495057;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .file-input-custom:focus {
            outline: none;
            border-color: #2b6be6;
            box-shadow: 0 0 0 3px rgba(43, 107, 230, 0.1);
        }

        .btn-lepas-dana {
            background: #dc3545;
            color: white;
            min-width: 140px;
            height: 44px;
            border-radius: 8px;
            padding: 0 20px;
            font-weight: 600;
            font-size: 0.813rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-lepas-dana:hover {
            background: #c82333;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
        }

        .info-subtitle {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .amount-highlight {
            color: #28a745;
            font-weight: 700;
            font-size: 0.938rem;
        }

        .link-primary {
            color: #2b6be6;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.813rem;
            transition: color 0.2s ease;
        }

        .link-primary:hover {
            color: #225abc;
            text-decoration: underline;
        }

        .user-info-label {
            color: #6c757d;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .user-info-value {
            color: #212529;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .alert-custom {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            border: 1px solid transparent;
        }

        .alert-success-custom {
            background: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .alert-danger-custom {
            background: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .total-badge {
            background: #fff3cd;
            color: #856404;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.813rem;
            border: 1px solid #ffeaa7;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .pelepasan-form {
                flex-direction: column;
                align-items: stretch;
            }

            .form-group-custom {
                max-width: 100%;
            }

            .btn-lepas-dana {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="user-table-card">
        <h2 class="user-table-title">Konfirmasi & Pelepasan Dana</h2>


        <div class="d-flex justify-content-between align-items-center mb-3">
            <form method="GET" action="{{ route('admin.konfirmasi-pembayaran.index') }}"
                style="display:flex; align-items:center; gap:8px;">

                <input name="search" id="searchAction" class="user-search-input" type="text"
                    placeholder="Cari Pesanan (ID/User/Jastiper)" value="{{ $search ?? '' }}"
                    style="padding:8px 12px; border:1px solid #DDE0E3; border-radius:8px; width:320px;">

                <button id="btnSearchJastiper" class="btn-search"
                    style="padding:8px 18px; border-radius:8px; border:1px solid #2b6be6; background:#fff; color:#2b6be6;">
                    Search
                </button>

                <div style="margin-left:8px; color:#6c757d;">
                    Total: <strong>{{ $pesanans->total() ?? $pesanans->count() }}</strong>
                </div>

            </form>
        </div>
        <p class="info-subtitle">
            Daftar ini mencakup pesanan yang memerlukan <strong>Konfirmasi Pembayaran User</strong> dan <strong>Pelepasan
                Dana</strong> ke Jastiper.
        </p>

        @if (session('success'))
            <div class="alert-custom alert-success-custom">
                ✓ {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert-custom alert-danger-custom">
                ✕ {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th class="col-id">ID</th>
                        <th class="col-name">User / Jastiper</th>
                        <th class="col-name">Rekening Jastiper</th>
                        <th class="col-email">Total Harga</th>
                        <th class="col-username">Bukti Transfer</th>
                        <th class="col-tgl">Tanggal Transfer</th>
                        <th class="col-role">Status</th>
                        <th class="col-actions" style="text-align:right">Operasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesanans as $pesanan)
                        {{-- BARIS UTAMA PESANAN --}}
                        <tr>
                            <td class="col-id">{{ $pesanan->id }}</td>
                            <td class="col-name">
                                <div class="mb-1">
                                    <span class="user-info-label">User:</span>
                                    <span class="user-info-value">{{ $pesanan->user?->name ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="user-info-label">Jastiper:</span>
                                    <span class="user-info-value">{{ $pesanan->jastiper?->nama_toko ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="col-rekening">
                                @if ($pesanan->jastiper->rekening)
                                    <strong
                                        style="font-weight: 600;">{{ $pesanan->jastiper->rekening->nomor_akun }}</strong>
                                    <br>
                                    {{ ucfirst($pesanan->jastiper->rekening->tipe_rekening) }} -
                                    {{ $pesanan->jastiper->rekening->nama_penyedia }} -
                                    {{ $pesanan->jastiper->rekening->nama_pemilik }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="col-email">
                                <span class="amount-highlight">Rp
                                    {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                            </td>
                            <td class="col-username">
                                @if ($pesanan->pembayaranUser && $pesanan->pembayaranUser->bukti_tf_path)
                                    <a href="{{ Storage::url($pesanan->pembayaranUser->bukti_tf_path) }}" target="_blank"
                                        class="link-primary" title="Lihat Bukti Transfer">
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-danger" style="font-size:0.813rem; font-weight:500;">
                                        Tidak Ada
                                    </span>
                                @endif
                            </td>
                            <td class="col-tgl">
                                {{ $pesanan->pembayaranUser?->tanggal_transfer?->format('d/m/Y H:i') ?? '-' }}
                            </td>
                            <td class="col-role">
                                @php
                                    $badgeClass = '';
                                    if ($pesanan->status_pesanan === 'MENUNGGU_KONFIRMASI_ADMIN') {
                                        $badgeClass = 'status-menunggu';
                                    } elseif ($pesanan->status_pesanan === 'PEMBAYARAN_GAGAL') {
                                        $badgeClass = 'status-gagal';
                                    } else {
                                        $badgeClass = 'status-selesai';
                                    }
                                @endphp
                                <span class="status-badge {{ $badgeClass }}">
                                    {{ str_replace('_', ' ', $pesanan->status_pesanan) }}
                                </span>
                            </td>
                            <td class="col-actions" style="text-align:right;">
                                <div class="table-actions">
                                    @if ($pesanan->status_pesanan === 'MENUNGGU_KONFIRMASI_ADMIN')
                                        {{-- TOMBOL KONFIRMASI (SUKSES) --}}
                                        <form action="{{ route('admin.konfirmasi-pembayaran', $pesanan->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn-action-custom btn-konfirmasi"
                                                title="Konfirmasi Pembayaran User"
                                                onclick="return confirm('KONFIRMASI: Apakah Anda yakin Pembayaran Pesanan ID {{ $pesanan->id }} Valid?')">
                                                ✓ Konfirmasi
                                            </button>
                                        </form>
                                        
                                        {{-- TOMBOL TOLAK (GAGAL) --}}
                                        <form action="{{ route('admin.tolak-pembayaran', $pesanan->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn-action-custom btn-tolak"
                                                title="Tolak Pembayaran"
                                                onclick="return confirm('TOLAK: Apakah Anda yakin nominal tidak sesuai? Pesanan akan dibatalkan.')">
                                                ✕ Gagal
                                            </button>
                                        </form>

                                    @elseif($pesanan->status_pesanan === 'DIBATALKAN')
                                         <span style="color:#dc3545; font-size:0.813rem; font-weight:500;">
                                            Ditolak Admin
                                        </span>
                                    @else
                                        <span style="color:#6c757d; font-size:0.813rem; font-weight:500;">
                                            Menunggu Pelepasan Dana
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- BARIS FORM PELEPASAN DANA --}}
                        {{-- FIX: Mengganti kondisi untuk mencakup semua status dana selain DILEPASKAN --}}
                        @if ($pesanan->status_pesanan === 'SELESAI' && $pesanan->status_dana_jastiper !== 'DILEPASKAN')
                            <tr class="row-pelepasan-dana">
                                <td colspan="8">
                                    <div class="pelepasan-container">
                                        <div class="pelepasan-title">
                                            Pelepasan Dana Escrow - Pesanan #{{ $pesanan->id }}
                                        </div>

                                        <form action="{{ route('admin.lepas-dana-jastiper', $pesanan->id) }}"
                                            method="POST" enctype="multipart/form-data" class="pelepasan-form">
                                            @csrf

                                            <div class="form-group-custom">
                                                <label for="bukti_tf_admin_{{ $pesanan->id }}" class="form-label-custom">
                                                    Upload Bukti Transfer Admin
                                                    <span class="amount-highlight">(Rp
                                                        {{ number_format($pesanan->total_harga * 0.05, 0, ',', '.') }})</span>
                                                </label>
                                                <input type="file" name="bukti_tf_admin"
                                                    id="bukti_tf_admin_{{ $pesanan->id }}" required accept="image/*,.pdf"
                                                    class="file-input-custom">

                                                @error('bukti_tf_admin')
                                                    <div class="text-danger"
                                                        style="font-size: 0.75rem; margin-top: 6px; font-weight: 500;">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <button type="submit" class="btn-lepas-dana"
                                                onclick="return confirm('LEPAS DANA: Yakin ingin melepas dana Rp {{ number_format($pesanan->total_harga * 0.9, 0, ',', '.') }} ke Jastiper?')">
                                                Lepas Dana
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endif

                    @empty
                        <tr>
                            <td colspan="8" class="empty-state">
                                Tidak ada pesanan yang memerlukan tindakan Admin saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $pesanans->links() }}
        </div>
    </div>
@endsection