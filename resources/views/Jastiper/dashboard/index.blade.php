@extends('layout.jastiper-app') 
{{-- Sesuaikan dengan layout utama Anda --}}

@section('title', 'Dashboard - Jastiper')

@section('content')

<style>
    body {
        font-family: 'Inter', sans-serif;
    }

    .dashboard-wrapper {
        background: #F3F4F6;
        padding: 24px;
        min-height: 100vh;
    }

    .title-big {
        font-size: 32px;
        font-weight: 700;
        color: #0A68FF;
        margin-bottom: 4px;
    }

    .subtitle {
        font-size: 16px;
        color: #4B5563;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        display: flex;
        align-items: center;
        gap: 14px;
        height: 100%;
    }

    .stat-icon {
        background: #D7E8FF;
        padding: 12px;
        border-radius: 12px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-shrink: 0;
    }

    .stat-text {
        flex: 1;
        min-width: 0;
    }

    .stat-text p {
        margin: 0;
        padding: 0;
        line-height: 1.2;
    }

    .stat-number {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 4px;
        word-break: break-word;
    }
    
    .stat-number.small-text {
        font-size: 18px;
    }

    .stat-label {
        color: #6B7280;
        font-size: 14px;
        font-weight: 500;
    }

    .chart-card {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        height: 100%;
    }

    .chart-container {
        position: relative;
        height: 280px; /* Default height untuk Doughnut Chart */
        width: 100%;
    }
    
    .chart-container-lg {
        position: relative;
        height: 300px; /* Height lebih besar untuk Line/Bar Chart */
        width: 100%;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #4B5563;
    }

    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 4px;
    }

    @media (max-width: 768px) {
        .dashboard-wrapper {
            padding: 16px;
        }
        
        .title-big {
            font-size: 24px;
        }
        
        .stat-number {
            font-size: 22px;
        }
        
        .stat-icon svg {
            width: 28px;
            height: 28px;
        }
    }
</style>

<div class="dashboard-wrapper">

    <h2 class="title-big">Hai, {{ Auth::user()->name }}</h2>
    <p class="subtitle">Selamat datang di Jastiper Dashboard</p>

    {{-- BARIS 1: STATISTIK RINGKAS --}}
    <div class="row mb-4 g-3">

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#0A68FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                </div>
                <div class="stat-text">
                    <p class="stat-number">{{ $totalPesanan }}</p>
                    <p class="stat-label">Total Pesanan</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#0A68FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                </div>
                <div class="stat-text">
                    <p class="stat-number">{{ $pesananSelesai }}</p>
                    <p class="stat-label">Pesanan Selesai</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#0A68FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                </div>
                <div class="stat-text">
                    <p class="stat-number small-text">Rp{{ number_format($pendapatan, 0, ',', '.') }}</p>
                    <p class="stat-label">Pendapatan</p>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#0A68FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                </div>
                <div class="stat-text">
                    <p class="stat-number">{{ $ulasan }}</p>
                    <p class="stat-label">Ulasan</p>
                </div>
            </div>
        </div>

    </div>

    {{-- BARIS 2: BAR CHART PENJUALAN DAN DOUGHNUT CHART DISTRIBUSI --}}
    <div class="row g-3">

        <div class="col-12 col-lg-8">
            <div class="chart-card">
                <h6 class="fw-bold mb-1">Penjualan Bulanan</h6>
                <p class="m-0 mb-3 text-muted" style="font-size: 14px;">Total Pendapatan: <strong>Rp{{ number_format($pendapatan, 0, ',', '.') }}</strong></p> 

                <div class="chart-container-lg">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="chart-card">
                <h6 class="fw-bold mb-3">Distribusi Pesanan</h6>

                <div class="chart-container">
                    <canvas id="doughnutChart"></canvas>
                </div>

                {{-- LEGEND DENGAN 3 STATUS DAN WARNA BARU --}}
                <div class="mt-4 d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="legend-item">
                            {{-- DIPROSES (Biru: #006FFF) --}}
                            <div class="legend-color" style="background: #006FFF;"></div> 
                            <span>Diproses</span>
                        </div>
                        <strong>{{ $chartStatus['DIPROSES'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="legend-item">
                            {{-- SIAP DIKIRIM (Kuning: #FFDD00) --}}
                            <div class="legend-color" style="background: #FFDD00;"></div> 
                            <span>Siap Dikirim</span>
                        </div>
                        <strong>{{ $chartStatus['SIAP_DIKIRIM'] }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="legend-item">
                            {{-- SELESAI (Biru Muda: #C1E0F4) --}}
                            <div class="legend-color" style="background: #C1E0F4;"></div> 
                            <span>Selesai</span>
                        </div>
                        <strong>{{ $chartStatus['SELESAI'] }}</strong>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    {{-- BARIS 3: LINE CHART BARANG TERJUAL --}}
    <div class="row g-3 mt-3">
        <div class="col-12">
            <div class="chart-card">
                <h6 class="fw-bold mb-3">Total Jumlah Barang Terjual (12 Bulan Terakhir)</h6>

                <div class="chart-container-lg">
                    <canvas id="barangTerjualChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {

    // Data dari PHP. Menggunakan json_encode untuk mencegah Undefined Index jika tidak ada data.
    const chartStatusFromPHP = {!! json_encode($chartStatus) !!};
    
    // Label bulan diambil dari Controller (asumsi $labelsPenjualan dikirim dari Controller)
    const labelsPenjualan = {!! json_encode($labelsPenjualan) !!}; 


    /* ========================================================================= */
    /* BAR CHART - Penjualan Bulanan */
    /* ========================================================================= */
    const salesChartCanvas = document.getElementById('salesChart');
    if (salesChartCanvas) {
        new Chart(salesChartCanvas, {
            type: 'bar',
            data: {
                labels: labelsPenjualan, 
                datasets: [{
                    label: 'Penjualan',
                    data: {!! json_encode($dataPenjualan) !!}, 
                    backgroundColor: '#0A68FF',
                    borderRadius: 8,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000) + 'M';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000) + 'K';
                                }
                                return 'Rp ' + value;
                            }
                        },
                        grid: {
                            color: '#F3F4F6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }


    /* ========================================================================= */
    /* DOUGHNUT CHART - Distribusi Pesanan (3 Status & Warna Baru) */
    /* ========================================================================= */
    const doughnutChartCanvas = document.getElementById('doughnutChart');
    if (doughnutChartCanvas) {
        
        // Data DOUGHNUT HANYA 3 STATUS
        const chartData = [
            chartStatusFromPHP['DIPROSES'] || 0,
            chartStatusFromPHP['SIAP_DIKIRIM'] || 0,
            chartStatusFromPHP['SELESAI'] || 0,
        ];
        
        const hasData = chartData.some(val => val > 0);
        
        // WARNA BARU: Biru (#006FFF), Kuning (#FFDD00), Biru Muda (#C1E0F4)
        const newColors = ['#006FFF', '#FFDD00', '#C1E0F4']; 

        new Chart(doughnutChartCanvas, {
            type: 'doughnut',
            data: {
                // Labels diselaraskan dengan data
                labels: ['Diproses', 'Siap Dikirim', 'Selesai'],
                datasets: [{
                    data: hasData ? chartData : [1], 
                    backgroundColor: hasData 
                        ? newColors 
                        : ['#E5E7EB'], // Warna placeholder abu-abu
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: hasData,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.parsed;
                                const percentage = ((value / total) * 100).toFixed(1);
                                return context.label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
        
        // Tampilkan pesan jika tidak ada data
        if (!hasData) {
            const container = doughnutChartCanvas.parentElement;
            const message = document.createElement('div');
            message.style.cssText = 'position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: #9CA3AF;';
            message.innerHTML = '<p style="margin: 0; font-size: 14px;">Belum ada pesanan</p>';
            container.style.position = 'relative';
            container.appendChild(message);
        }
    }

    /* ========================================================================= */
    /* LINE CHART - Jumlah Barang Terjual Per Bulan (KODE BARU) */
    /* ========================================================================= */
    const barangTerjualChartCanvas = document.getElementById('barangTerjualChart');
    if (barangTerjualChartCanvas) {
        // Ambil data barang terjual dari Controller
        const dataBarang = {!! json_encode($dataBarangTerjual) !!};

        new Chart(barangTerjualChartCanvas, {
            type: 'line',
            data: {
                labels: labelsPenjualan, // Menggunakan label bulan yang sama
                datasets: [{
                    label: 'Jumlah Barang Terjual',
                    data: dataBarang,
                    backgroundColor: 'rgba(0, 111, 255, 0.1)', // #006FFF dengan opacity
                    borderColor: '#006FFF',
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#006FFF',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toLocaleString('id-ID') + ' Unit';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                // Tampilkan nilai hanya jika bilangan bulat (untuk unit barang)
                                if (Number.isInteger(value)) {
                                    return value;
                                }
                            }
                        },
                        grid: {
                            color: '#F3F4F6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

});
</script>

@endpush