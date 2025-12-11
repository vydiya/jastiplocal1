@extends('layout.admin-app')

@section('title', 'Dashboard - Admin')

@push('styles')
    {{-- CSS Kustom untuk tampilan dashboard --}}
    <style>
        /* Definisi Warna Baru */
        :root {
            --color-blue: #006FFF;
            --color-light-blue: #C1E0F4;
            --color-yellow: #FFDD00;
            --color-light-yellow: #FFF6BE;
            --color-text-dark: #333;
            --color-text-muted: #666;
        }

        /* Header Dashboard */
        .dashboard-header {
            padding: 0;
            margin-bottom: 24px;
        }
        .dashboard-header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: var(--color-blue);
        }
        .dashboard-header p {
            margin: 4px 0 0 0;
            font-size: 16px;
            color: var(--color-text-muted);
        }

        /* Stats Cards - Row 1 (STRUKTUR SESUAI GAMBAR) */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* Tetapkan 4 kolom */
            gap: 16px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 12px 16px; /* Padding lebih kecil */
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            display: flex;
            align-items: center; /* Rata tengah vertikal */
            gap: 10px;
            height: 80px; /* Tinggi disesuaikan */
        }
        .stat-card .icon-wrapper {
            /* Warna latar belakang stat card di gambar: Biru Muda atau Kuning */
            background-color: var(--color-light-blue); /* Default untuk kartu biru */
            width: 50px;
            height: 50px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .stat-card .icon-wrapper.bg-yellow { 
             background-color: var(--color-yellow); /* Untuk kartu kuning (Dana Dilepas) */
        }
        .stat-card .icon-wrapper i {
            font-size: 1.5rem;
            color: var(--color-blue); /* Ikon berwarna biru */
        }

        /* Stat Card Text/Value Styling */
        .stat-text {
             display: flex;
             flex-direction: column;
             justify-content: center;
        }
        .stat-card .stat-value {
            font-size: 20px; /* Lebih kecil */
            font-weight: 700;
            color: var(--color-text-dark);
            line-height: 1.2;
            margin-bottom: 2px;
        }
        .stat-card .stat-label {
            font-size: 12px;
            color: var(--color-text-muted);
            line-height: 1.2;
        }
        
        /* Chart Cards */
        .chart-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            height: 100%;
        }
        .chart-card h5 {
            font-size: 18px;
            font-weight: 600;
            color: var(--color-text-dark);
            margin-bottom: 16px;
        }
        .chart-container-lg {
            position: relative;
            height: 300px;
        }
        .chart-container {
            position: relative;
            height: 200px; /* Lebih pendek untuk Doughnut */
        }
        
        /* Legend Styling */
        .chart-legend {
             display: flex;
             gap: 1.5rem;
             font-size: 12px;
             color: var(--color-text-muted);
        }
        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 4px;
        }
        .legend-item {
            display: flex;
            align-items: center;
        }
    </style>
@endpush

@section('content')
    
    {{-- Header Dashboard --}}
    <div class="dashboard-header">
        <h2>Hai, {{ Auth::user()->name ?? 'Anita' }}</h2>
        <p>Selamat datang di Admin Dashboard</p>
    </div>
    
    {{-- BARIS 1: STATISTIK RINGKAS (4 KARTU SESUAI VISUAL) --}}
    <div class="stats-grid">
        
        {{-- 1. Banyak User --}}
        <div class="stat-card">
            <div class="icon-wrapper">
                <i class="fa fa-th-large"></i>
            </div>
            <div class="stat-text">
                <div class="stat-value">{{ $totalUsers }}</div>
                <div class="stat-label">Banyak User</div>
            </div>
        </div>

        {{-- 2. Pembayaran Selesai --}}
        <div class="stat-card">
            <div class="icon-wrapper">
                <i class="fa fa-th-large"></i>
            </div>
            <div class="stat-text">
                <div class="stat-value">{{ $totalTransaksiSelesai }}</div>
                <div class="stat-label">Pembayaran Selesai</div>
            </div>
        </div>

        {{-- 3. Pendapatan Admin --}}
        <div class="stat-card">
            <div class="icon-wrapper">
                <i class="fa fa-th-large"></i>
            </div>
            <div class="stat-text">
                {{-- Format agar mirip Rp80.000.000 (jika nilainya besar, tampilkan full) --}}
                <div class="stat-value">Rp{{ number_format($pendapatanAdminTotal, 0, ',', '.') }}</div>
                <div class="stat-label">Pendapatan Admin</div>
            </div>
        </div>

        {{-- 4. Dana dilepas (Kartu Kuning) --}}
        <div class="stat-card">
            <div class="icon-wrapper bg-yellow">
                <i class="fa fa-unlock-alt"></i>
            </div>
            <div class="stat-text">
                {{-- Menggunakan count Dana Dilepas --}}
                <div class="stat-value">{{ $danaChartData['DILEPAS'] ?? 0 }}</div>
                <div class="stat-label">Dana dilepas</div>
            </div>
        </div>
    </div>

    {{-- BARIS 2 & 3: CHARTS (Kode Chart tidak diulang karena sudah benar) --}}
    <div class="row g-4 mb-4">
        
        {{-- Bar Chart: Pendapatan Bulanan --}}
        <div class="col-lg-4">
            <div class="chart-card">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <h5 class="mb-0">Pendapatan Bulanan</h5>
                    <span style="font-size:16px; font-weight: 700;">Rp{{ number_format($pendapatanAdminTotal, 0, ',', '.') }}</span>
                </div>
                <div class="chart-container-lg">
                    <canvas id="pendapatanChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Doughnut: Konfirmasi Pembayaran --}}
        <div class="col-lg-4">
            <div class="chart-card">
                <h5>Konfirmasi pembayaran</h5>
                <div class="chart-container">
                    <canvas id="konfirmasiChart"></canvas>
                </div>
                {{-- Legend Konfirmasi --}}
                <div class="chart-legend mt-4">
                    <div class="legend-item">
                        <div class="legend-dot" style="background: var(--color-blue);"></div>
                        <span>Menunggu Konfirmasi Pembayaran</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot" style="background: var(--color-light-blue);"></div>
                        <span>Selesai</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Doughnut: Kelola Dana --}}
        <div class="col-lg-4">
            <div class="chart-card">
                <h5>Kelola Dana</h5>
                <div class="chart-container">
                    <canvas id="kelolaDanaChart"></canvas>
                </div>
                {{-- Legend Kelola Dana --}}
                <div class="chart-legend mt-4">
                    <div class="legend-item">
                        <div class="legend-dot" style="background: var(--color-light-yellow);"></div>
                        <span>Dana Ditahan</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot" style="background: var(--color-yellow);"></div>
                        <span>Dana Dilepas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS 3: LINE CHART PERTUMBUHAN PENGGUNA --}}
    <div class="row">
        <div class="col-12">
            <div class="chart-card">
                
                <div class="chart-legend" style="justify-content: flex-start; margin-bottom: 24px;">
                    <h5 class="me-4 mb-0">Pelanggan</h5>
                    <h5 class="me-4 mb-0">Jastiper</h5>
                </div>

                <div class="chart-container-lg">
                    <canvas id="pertumbuhanChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@push('scripts')
{{-- Kode JavaScript Chart.js (Tetap sama seperti yang sudah disesuaikan warnanya) --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    jQuery(document).ready(function ($) {
        "use strict";

        // Data dari Controller
        const dataPendapatan = {!! json_encode($dataPendapatan) !!};
        const labelsPendapatan = {!! json_encode($labelsPendapatan) !!};
        const konfirmasiData = {!! json_encode(array_values($konfirmasiChartData)) !!};
        const danaData = {!! json_encode(array_values($danaChartData)) !!};
        const pelangganData = {!! json_encode($dataPelanggan) !!}; 
        const jastiperData = {!! json_encode($dataJastiper) !!}; 

        // HEX COLORS
        const BLUE = '#006FFF';
        const LIGHT_BLUE = '#C1E0F4';
        const YELLOW = '#FFDD00';
        const LIGHT_YELLOW = '#FFF6BE';

        /* ========================================================================= */
        /* 1. Bar Chart: Pendapatan Bulanan */
        /* ========================================================================= */
        const ctxPendapatan = document.getElementById('pendapatanChart');
        if (ctxPendapatan) {
            new Chart(ctxPendapatan, {
                type: 'bar',
                data: {
                    labels: labelsPendapatan,
                    datasets: [{
                        label: 'Pendapatan',
                        data: dataPendapatan,
                        backgroundColor: BLUE, 
                        borderRadius: 4,
                        barPercentage: 0.8,
                        categoryPercentage: 0.8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: (ctx) => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: {
                                callback: (val) => {
                                    if (val >= 1000000) return (val/1000000) + 'M';
                                    if (val >= 1000) return (val/1000) + 'K';
                                    return val;
                                }
                            }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        /* ========================================================================= */
        /* 2. Doughnut: Konfirmasi Pembayaran */
        /* ========================================================================= */
        const ctxKonfirmasi = document.getElementById('konfirmasiChart');
        if (ctxKonfirmasi) {
            new Chart(ctxKonfirmasi, {
                type: 'doughnut',
                data: {
                    labels: ['Menunggu Konfirmasi Pembayaran', 'Selesai'],
                    datasets: [{
                        data: konfirmasiData,
                        backgroundColor: [BLUE, LIGHT_BLUE], // Biru Tua & Biru Muda
                        borderWidth: 0,
                        hoverOffset: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12,
                            cornerRadius: 8,
                        }
                    },
                    cutout: '75%',
                }
            });
        }

        /* ========================================================================= */
        /* 3. Doughnut: Kelola Dana */
        /* ========================================================================= */
        const ctxDana = document.getElementById('kelolaDanaChart');
        if (ctxDana) {
            new Chart(ctxDana, {
                type: 'doughnut',
                data: {
                    labels: ['Dana Ditahan', 'Dana Dilepas'],
                    datasets: [{
                        data: danaData,
                        backgroundColor: [LIGHT_YELLOW, YELLOW], // Kuning Muda & Kuning Tua
                        borderWidth: 0,
                        hoverOffset: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12,
                            cornerRadius: 8,
                        }
                    },
                    cutout: '75%',
                }
            });
        }
        
        /* ========================================================================= */
        /* 4. Line Chart: Pertumbuhan Pengguna */
        /* ========================================================================= */
        const ctxPertumbuhan = document.getElementById('pertumbuhanChart');
        if (ctxPertumbuhan) {
            new Chart(ctxPertumbuhan, {
                type: 'line',
                data: {
                    labels: labelsPendapatan,
                    datasets: [
                        {
                            label: 'Pelanggan',
                            data: pelangganData,
                            borderColor: YELLOW, // Kuning Tua
                            backgroundColor: 'rgba(255, 221, 0, 0.2)', 
                            fill: 'origin',
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 0,
                        },
                        {
                            label: 'Jastiper',
                            data: jastiperData,
                            borderColor: BLUE, // Biru Tua
                            backgroundColor: 'rgba(0, 111, 255, 0.2)', 
                            fill: 'origin',
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 0,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                             backgroundColor: 'rgba(0,0,0,0.8)',
                             padding: 12,
                             cornerRadius: 8,
                             mode: 'index',
                             intersect: false,
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    });
</script>
@endpush 