@extends('layout.jastiper-app')

@section('title', 'Bantuan Jastiper')

@section('page-title', 'Pusat Bantuan & Panduan')

@section('content')

{{-- BOOTSTRAP ICONS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

{{-- CUSTOM STYLES --}}
<style>
    :root {
        /* COLOR PALETTE USER */
        --brand-blue: #006FFF;       /* Biru Utama */
        --brand-blue-light: #C1E0F4; /* Biru Muda */
        --brand-yellow: #FFDD00;     /* Kuning */
        --brand-yellow-light: #FFF6BE; /* Kuning Muda */
        
        --text-main: #2B2D42;
        --text-muted: #64748B;
        --bg-body: #F8FAFC;
        --card-bg: #FFFFFF;
        
        --radius-xl: 20px;
        --radius-lg: 16px;
        
        --shadow-soft: 0 10px 30px -10px rgba(0, 111, 255, 0.1);
    }

    body {
        background-color: var(--bg-body);
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        color: var(--text-main);
    }

    /* ANIMATIONS */
    .animate-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
        transform: translateY(20px);
    }

    @keyframes fadeInUp {
        to { opacity: 1; transform: translateY(0); }
    }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }

    /* HEADER - Menggunakan Warna Biru (#006FFF) */
    .hero-section {
        background: linear-gradient(135deg, var(--brand-blue) 0%, #0056cc 100%);
        padding: 3.5rem 0;
        border-radius: 0 0 30px 30px;
        margin-bottom: 3rem;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 111, 255, 0.2);
    }
    
    .hero-bg-pattern {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        opacity: 0.1;
        background-image: radial-gradient(#ffffff 2px, transparent 2px);
        background-size: 24px 24px;
    }

    /* CARD STYLES */
    .glass-card {
        background: var(--card-bg);
        border-radius: var(--radius-xl);
        border: 1px solid rgba(0,0,0,0.04);
        box-shadow: var(--shadow-soft);
        height: 100%;
        overflow: hidden;
    }

    .card-header-custom {
        padding: 1.5rem;
        background: white;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    /* Ikon Box Biru Muda */
    .icon-box-blue {
        width: 45px;
        height: 45px;
        background: var(--brand-blue-light);
        color: var(--brand-blue);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    /* Ikon Box Kuning Muda */
    .icon-box-yellow {
        width: 45px;
        height: 45px;
        background: var(--brand-yellow-light);
        color: #b39b00; /* Versi gelap dari kuning agar terlihat di background terang */
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .card-title-custom {
        font-weight: 700;
        font-size: 1.15rem;
        margin: 0;
        color: var(--text-main);
    }

    /* MENU ITEMS (LEFT) */
    .feature-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border-radius: var(--radius-lg);
        background: #fff;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .feature-item:hover {
        border-color: var(--brand-blue);
        background: #f8fbff; /* Very light blue tint */
        transform: translateX(5px);
    }

    .feature-icon {
        width: 45px;
        height: 45px;
        flex-shrink: 0;
        border-radius: 12px;
        background: var(--brand-blue-light); /* Background Biru Muda */
        color: var(--brand-blue); /* Icon Biru */
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-right: 1rem;
        transition: 0.3s;
    }

    .feature-item:hover .feature-icon {
        background: var(--brand-blue);
        color: white;
    }

    .feature-text h5 {
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 0.15rem;
        color: var(--text-main);
    }

    .feature-text p {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.4;
    }

    /* TIMELINE (RIGHT) - FIXED LAYOUT */
    .timeline {
        position: relative;
        padding-left: 0.5rem;
        margin-top: 1rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 10px;
        bottom: 50px;
        width: 2px;
        background: #e2e8f0;
        z-index: 0;
    }

    .timeline-item {
        position: relative;
        padding-left: 3.8rem; /* Ruang aman agar teks tidak menabrak angka */
        margin-bottom: 2rem;
    }

    .timeline-number {
        position: absolute;
        left: 0;
        top: 0;
        width: 42px;
        height: 42px;
        background: white;
        border: 2px solid var(--brand-blue); /* Border Biru */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        color: var(--brand-blue); /* Teks Angka Biru */
        z-index: 2;
        box-shadow: 0 4px 6px rgba(0, 111, 255, 0.1);
    }

    .timeline-content {
        padding-top: 0.25rem;
    }

    .timeline-content h6 {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 0.35rem;
        color: var(--brand-blue); /* Judul step warna Biru */
    }

    .timeline-content p {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.5;
    }

    /* CONTACT SECTION - Kuning sebagai Highlight */
    .contact-card {
        background: white;
        border: 1px solid var(--brand-blue-light);
        border-radius: var(--radius-xl);
        padding: 2.5rem 1.5rem;
        text-align: center;
        box-shadow: var(--shadow-soft);
        position: relative;
        overflow: hidden;
    }
    
    .contact-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 6px;
        background: linear-gradient(90deg, var(--brand-blue), var(--brand-yellow));
    }

    .btn-contact {
        padding: 0.7rem 1.5rem;
        border-radius: 50px;
        font-size: 0.95rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0.25rem;
        transition: transform 0.2s;
        border: none;
        color: var(--text-main);
    }
    
    .btn-contact:hover { transform: translateY(-3px); }
    
    /* Tombol Biru */
    .bg-main { 
        background-color: var(--brand-blue); 
        color: white; 
    }
    .bg-main:hover { background-color: #005bc4; color: white; }

    /* Tombol Kuning (Highlight) */
    .bg-accent { 
        background-color: var(--brand-yellow); 
        color: #2B2D42; /* Teks gelap agar terbaca di kuning */
    }
    .bg-accent:hover { background-color: #e6c700; color: #2B2D42; }

    @media (max-width: 768px) {
        .timeline-item { padding-left: 3.5rem; }
        .timeline-number { width: 36px; height: 36px; font-size: 0.9rem; }
        .timeline::before { left: 17px; }
    }
</style>

{{-- HERO SECTION --}}
<div class="hero-section animate-up">
    <div class="hero-bg-pattern"></div>
    <div class="container text-center position-relative">
        <h1 class="fw-bold mb-2">Pusat Bantuan Mitra</h1>
        <p class="mb-0 white-color">Informasi lengkap untuk kelancaran bisnis Jastip Anda</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row g-4">
        
        {{-- MENU NAVIGASI (KIRI) --}}
        <div class="col-lg-6 animate-up delay-1">
            <div class="glass-card">
                <div class="card-header-custom">
                    {{-- Ikon Biru Muda --}}
                    <div class="icon-box-blue">
                        <i class="bi bi-grid-fill"></i>
                    </div>
                    <div>
                        <h3 class="card-title-custom">Navigasi Aplikasi</h3>
                        <small class="text-muted">Penjelasan fitur utama</small>
                    </div>
                </div>
                <div class="p-3 p-md-4">
                    {{-- Item 1 --}}
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-clipboard-data"></i></div>
                        <div class="feature-text">
                            <h5>Menu Pesanan</h5>
                            <p>Dashboard utama memantau pesanan masuk.</p>
                        </div>
                    </div>
                    {{-- Item 2 --}}
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-journal-text"></i></div>
                        <div class="feature-text">
                            <h5>Detail Pesanan</h5>
                            <p>Cek rincian barang & alamat pengiriman.</p>
                        </div>
                    </div>
                    {{-- Item 3 --}}
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-box-seam-fill"></i></div>
                        <div class="feature-text">
                            <h5>Manajemen Barang</h5>
                            {{-- Menggunakan warna kuning untuk highlight text --}}
                            <p>Upload produk. <span style="background: var(--brand-yellow-light); padding: 0 4px; border-radius: 4px; font-weight: 600; font-size: 0.8rem;">*Wajib harga final</span></p>
                        </div>
                    </div>
                    {{-- Item 4 --}}
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-graph-up-arrow"></i></div>
                        <div class="feature-text">
                            <h5>Laporan Keuangan</h5>
                            <p>Pantau profit bersih & riwayat pencairan.</p>
                        </div>
                    </div>
                     {{-- Item 5 --}}
                     <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-gear-wide-connected"></i></div>
                        <div class="feature-text">
                            <h5>Pengaturan Akun</h5>
                            <p>Kelola profil toko & keamanan data.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PROTOKOL (KANAN) --}}
        <div class="col-lg-6 animate-up delay-2">
            <div class="glass-card">
                <div class="card-header-custom">
                    {{-- Ikon Kuning Muda --}}
                    <div class="icon-box-yellow">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <h3 class="card-title-custom">Protokol Mitra</h3>
                        <small class="text-muted">Ketentuan wajib dipatuhi</small>
                    </div>
                </div>
                
                <div class="p-4">
                    <div class="timeline">
                        {{-- Step 1 --}}
                        <div class="timeline-item">
                            <div class="timeline-number">1</div>
                            <div class="timeline-content">
                                <h6>Wajib Update Status</h6>
                                <p>Segera tekan <strong>"Kirim"</strong> saat barang diserahkan ke kurir untuk mengaktifkan sistem tracking.</p>
                            </div>
                        </div>

                        {{-- Step 2 --}}
                        <div class="timeline-item">
                            <div class="timeline-number">2</div>
                            <div class="timeline-content">
                                <h6>Sistem Escrow (Rekber)</h6>
                                <p>Dana pembeli ditahan Admin dan diteruskan ke Mitra setelah pesanan selesai.</p>
                            </div>
                        </div>

                        {{-- Step 3 --}}
                        <div class="timeline-item">
                            <div class="timeline-number">3</div>
                            <div class="timeline-content">
                                <h6>Mekanisme Pencairan</h6>
                                <p>Saldo otomatis masuk Dompet setelah pembeli klik <strong>"Pesanan Diterima"</strong>.</p>
                            </div>
                        </div>

                        {{-- Step 4 --}}
                        <div class="timeline-item">
                            <div class="timeline-number">4</div>
                            <div class="timeline-content">
                                <h6>Garansi Barang</h6>
                                <p>Barang rusak/salah = Refund 100%. Lakukan QC sebelum pengiriman.</p>
                            </div>
                        </div>

                        {{-- Step 5 --}}
                        <div class="timeline-item">
                            <div class="timeline-number">5</div>
                            <div class="timeline-content">
                                <h6>Biaya Layanan (Fee)</h6>
                                <p>Potongan admin sebesar <strong>8%</strong> dari total transaksi saat pencairan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FOOTER CONTACT --}}
        <div class="col-12 animate-up delay-3">
            <div class="contact-card">
                <h4 class="fw-bold mb-3" style="color: var(--brand-blue);">Butuh Bantuan Lebih Lanjut?</h4>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    {{-- Tombol dengan warna Palette --}}
                    <a href="https://www.instagram.com/jastgo.id" class="btn-contact bg-main"><i class="bi bi-whatsapp"></i>WhatsApp</a>
                    <a href="https://wa.me/6281268433470" class="btn-contact bg-accent"><i class="bi bi-instagram"></i>Instagram</a>
                    <a href="https://www.facebook.com/share/1Ag85mEB6R/?mibextid=wwXIfr" class="btn-contact bg-main"><i class="bi bi-facebook"></i> Facebook</a>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection