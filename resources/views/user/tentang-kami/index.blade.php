@php
    // Logika Header
    $isLoggedIn = Auth::check();
    $userName = Auth::user()->name ?? 'Pengguna';
    $cartCount = $cartCount ?? 0; 
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - JASTGO</title>
    
    {{-- Font Awesome & Fonts --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ================================================= */
        /* BASE & RESET */
        /* ================================================= */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f5f7fa; color: #333; overflow-x: hidden; }

        /* ================================================= */
        /* HEADER (Jaga agar tetap di atas) */
        /* ================================================= */
        header { position: sticky; top: 0; z-index: 1000; }

        /* ================================================= */
        /* HALAMAN TENTANG KAMI */
        /* ================================================= */
        
        /* Wrapper Biru Penuh */
        .about-page-wrapper {
            background-color: #2563EB; /* Royal Blue */
            width: 100%;
            min-height: 100vh;
            color: white;
            padding-bottom: 50px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Typography */
        h1 { font-size: 3rem; font-weight: 700; margin-bottom: 1.5rem; }
        h2 { font-size: 2.5rem; font-weight: 700; margin-bottom: 2rem; }
        p { line-height: 1.6; font-size: 1.1rem; opacity: 0.9; }
        
        .text-center { text-align: center; }
        .text-white { color: white; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .mb-5 { margin-bottom: 4rem; }
        .pt-5 { padding-top: 4rem; }

        /* GRID SYSTEM */
        .row { display: flex; flex-wrap: wrap; margin: 0 -15px; }
        .col-3 { width: 25%; padding: 0 15px; }
        .col-4 { width: 33.33%; padding: 0 15px; }
        .col-6 { width: 50%; padding: 0 15px; }
        .justify-center { justify-content: center; }

        /* --- KARTU FITUR --- */
        .feature-card {
            background-color: rgba(255, 255, 255, 0.15); /* Glass Effect */
            border-radius: 20px;
            padding: 40px 25px;
            height: 100%;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            background-color: rgba(255, 255, 255, 0.25);
        }
        .icon-wrapper { margin-bottom: 20px; color: white; }
        .feature-title { font-weight: 700; font-size: 1.2rem; margin-bottom: 10px; }
        .feature-desc { font-size: 0.85rem; opacity: 0.8; }

        /* --- DOKUMENTASI IMAGES --- */
        .doc-image-container {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            border: 4px solid rgba(255,255,255,0.3);
            height: 300px; /* Tinggi fix agar rapi */
        }
        .doc-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Agar gambar tidak gepeng */
            transition: transform 0.5s;
        }
        .doc-image-container:hover img {
            transform: scale(1.05);
        }

        /* --- KELOMPOK KAMI (TEAM) --- */
        .team-card {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            height: 100%;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .avatar-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            margin-bottom: 15px;
            background: #ddd;
        }
        .team-name { font-weight: 700; font-size: 1.2rem; margin-bottom: 5px; }
        .team-role { font-size: 0.9rem; opacity: 0.7; }

        /* --- CONTACT BUTTONS --- */
        .contact-group { display: flex; justify-content: center; gap: 20px; margin-top: 20px; }
        .contact-btn {
            width: 70px; height: 70px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 2rem;
            text-decoration: none; transition: 0.3s;
        }
        .contact-btn:hover { background: white; color: #2563EB; transform: scale(1.1); }

        /* ================================================= */
        /* FOOTER KUNING */
        /* ================================================= */
        .jastgo-footer {
            background: #FFDD00;
            padding: 60px 20px 30px;
            color: #000;
        }
        .footer-container {
            max-width: 1200px; margin: 0 auto;
            display: flex; flex-wrap: wrap; justify-content: space-between; gap: 40px;
        }
        .footer-brand h3 { font-size: 1.5rem; font-weight: 800; margin-bottom: 10px; }
        .footer-links h4 { font-size: 1.1rem; font-weight: 700; margin-bottom: 15px; }
        .footer-links ul { list-style: none; }
        .footer-links li { margin-bottom: 8px; }
        .footer-links a { text-decoration: none; color: #0000AA; font-weight: 500; }
        .footer-links a:hover { text-decoration: underline; }
        .social-list a { display: flex; align-items: center; gap: 8px; color: #0000AA; text-decoration: none; margin-bottom: 8px; font-weight: 500; }
        .footer-bottom { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid rgba(0,0,0,0.1); font-size: 0.9rem; }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .col-3 { width: 50%; margin-bottom: 20px; }
            .col-4 { width: 50%; margin-bottom: 20px; }
        }
        @media (max-width: 768px) {
            h1 { font-size: 2rem; }
            .col-3, .col-4, .col-6 { width: 100%; margin-bottom: 20px; }
            .footer-container { flex-direction: column; }
        }
    </style>
</head>

<body>

    {{-- INCLUDE HEADER --}}
    @include('user.layout.header', [
        'isLoggedIn' => $isLoggedIn,
        'cartCount' => $cartCount,
        'searchValue' => '',
        'userName' => $userName,
    ])

    {{-- WRAPPER UTAMA --}}
    <div class="about-page-wrapper">
        <div class="container pt-5">
            
            {{-- 1. HEADER & DESKRIPSI --}}
            <div class="text-center text-white mb-5">
                <h1>Tentang Kami</h1>
                <p class="mx-auto" style="max-width: 800px;">
                    JASTGO adalah platform layanan jasa titip modern yang membantu pengguna mendapatkan barang 
                    dari berbagai kota dan marketplace tanpa harus repot keluar rumah. Melalui sistem yang transparan, 
                    aman, dan mudah digunakan, JASTGO menghubungkan User dan Jastiper secara cepat dan efisien.
                </p>
            </div>

            {{-- 2. FITUR --}}
            <div class="row justify-center mb-5">
                <div class="col-3">
                    <div class="feature-card">
                        <div class="icon-wrapper"><i class="fas fa-wallet fa-3x"></i></div>
                        <div class="feature-title">Transaksi Aman</div>
                        <div class="feature-desc">Pembayaran pengguna disimpan sementara oleh sistem escrow dan diteruskan setelah barang diterima.</div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="feature-card">
                        <div class="icon-wrapper"><i class="fas fa-shopping-bag fa-3x"></i></div>
                        <div class="feature-title">Jastiper Terverifikasi</div>
                        <div class="feature-desc">Semua jastiper telah melalui proses verifikasi identitas untuk menjamin keamanan layanan.</div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="feature-card">
                        <div class="icon-wrapper"><i class="fas fa-file-invoice fa-3x"></i></div>
                        <div class="feature-title">Info Transparan</div>
                        <div class="feature-desc">Detail barang, biaya jastip, foto pembelian, hingga status pengiriman ditampilkan lengkap.</div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="feature-card">
                        <div class="icon-wrapper"><i class="fas fa-credit-card fa-3x"></i></div>
                        <div class="feature-title">Pembayaran Digital</div>
                        <div class="feature-desc">Mendukung transfer bank dan e-wallet untuk transaksi yang cepat dan nyaman.</div>
                    </div>
                </div>
            </div>

            {{-- 3. DOKUMENTASI (FOTO DIAMBIL DARI FOLDER) --}}
            <div class="text-center text-white mb-5 pt-5">
                <h2>Dokumentasi</h2>
                
                <div class="row justify-center mb-5">
                    {{-- Foto 1 --}}
                    <div class="col-6">
                        <div class="doc-image-container">
                            {{-- Menggunakan path asset sesuai gambar folder Anda --}}
                            <img src="{{ asset('admin/assets/images/gambar_dokumentasi1.jpeg') }}" 
                                 alt="Dokumentasi Wawancara 1"
                                 onerror="this.src='https://placehold.co/600x400/eeeeee/2563EB?text=Foto+Dokumentasi+1'">
                        </div>
                    </div>
                    {{-- Foto 2 --}}
                    <div class="col-6">
                        <div class="doc-image-container">
                            <img src="{{ asset('admin/assets/images/gambar_dokumentasi2.jpeg') }}" 
                                 alt="Dokumentasi Wawancara 2"
                                 onerror="this.src='https://placehold.co/600x400/eeeeee/2563EB?text=Foto+Dokumentasi+2'">
                        </div>
                    </div>
                </div>

                <p class="mx-auto" style="max-width: 800px;">
                    Kami mewawancarai Owner Bosalad Bengkalis, pelaku jastip makanan lokal, untuk memahami proses kerja 
                    dan kebutuhan jastiper. Hasil wawancara ini menjadi acuan dalam merancang fitur keamanan di JASTGO.
                </p>
            </div>

            {{-- 4. KELOMPOK KAMI (FOTO TEAM) --}}
            <div class="text-center text-white mb-5 pt-5">
                <h2>Kelompok Kami</h2>
                <div class="row justify-center">
                    {{-- Anggota 1 --}}
                    <div class="col-4">
                        <div class="team-card">
                            <img src="{{ asset('admin/assets/images/gambar1.jpeg') }}" 
                                 alt="Anggota 1" class="avatar-img"
                                 onerror="this.src='https://placehold.co/150x150/ffffff/2563EB?text=User'">
                            <div class="team-name">Diky Wahyudi</div>
                            <div class="team-role">Pengembangan Backend</div>
                            <div class="team-role">Pengembangan Frontend</div>
                            <div class="team-role">Manajemen Database</div>
                        </div>
                    </div>
                    {{-- Anggota 2 --}}
                    <div class="col-4">
                        <div class="team-card">
                            <img src="{{ asset('admin/assets/images/gambar2.jpeg') }}" 
                                 alt="Anggota 2" class="avatar-img"
                                 onerror="this.src='https://placehold.co/150x150/ffffff/2563EB?text=User'">
                            <div class="team-name">Vydia Asyura</div>
                            <div class="team-role">Analisis Sistem</div>
                            <div class="team-role">Desain UI/UX</div>
                            <div class="team-role">Pengembangan Backend</div>
                        </div>
                    </div>
                    {{-- Anggota 3 --}}
                    <div class="col-4">
                        <div class="team-card">
                            <img src="{{ asset('admin/assets/images/gambar3.jpeg') }}" 
                                 alt="Anggota 3" class="avatar-img"
                                 onerror="this.src='https://placehold.co/150x150/ffffff/2563EB?text=User'">
                            <div class="team-name">Nama Anggota 3</div>
                            <div class="team-role">Desain UI/UX</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. HUBUNGI KAMI --}}
            <div class="text-center text-white pb-5">
                <h2>Hubungi kami</h2>
                <div class="contact-group">
                    <a href="https://wa.me/6281268433470" target="_blank" class="contact-btn">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="https://www.instagram.com/jastgo.id" target="_blank" class="contact-btn">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://www.facebook.com/share/1Ag85mEB6R/?mibextid=wwXIfr" target="_blank" class="contact-btn">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- FOOTER KUNING --}}
    <footer class="jastgo-footer">
        <div class="footer-container">
            <div class="footer-brand">
                <h3>JASTGO</h3>
                <p style="max-width: 300px; font-size: 0.95rem;">Platform titip beli produk lokal dari seluruh Indonesia. Mudah, aman, dan terpercaya.</p>
            </div>
            
            <div class="footer-links">
                <h4>Kategori</h4>
                <ul>
                    <li><a href="{{route('home')}}">Elektronik</a></li>
                    <li><a href="{{route('home')}}">Fashion</a></li>
                    <li><a href="{{route('home')}}">Makanan & Minuman</a></li>
                    <li><a href="{{route('home')}}">Kesehatan</a></li>
                </ul>
            </div>

            <div class="footer-links">
                <h4>Bantuan</h4>
                <ul>
                    <li><a href="{{ route('cara-belanja') }}">Cara Belanja</a></li>
                </ul>
            </div>

            <div class="footer-links">
                <h4>Ikuti Kami</h4>
                <div class="social-list">
                    <a href="https://www.instagram.com/jastgo.id" target="_blank"><i class="fab fa-instagram"></i> Instagram</a>
                    <a href="https://wa.me/6281268433470" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                    <a href="https://www.facebook.com/share/1Ag85mEB6R/?mibextid=wwXIfr" target="_blank"><i class="fab fa-facebook"></i> Facebook</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            © {{ date('Y') }} <strong>JASTGO</strong>. Belanja lokal jadi lebih mudah.
        </div>
    </footer>

</body>
</html>