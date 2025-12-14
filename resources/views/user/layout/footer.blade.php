{{-- FOOTER --}}
<footer class="jastgo-footer">
    <div class="footer-container container">

        {{-- 1. BRAND --}}
        <div class="footer-brand">
            <h3>JASTGO</h3>
            <p>Platform titip beli produk lokal dari seluruh Indonesia. Mudah, aman, dan terpercaya.</p>
        </div>

        {{-- 2. KATEGORI --}}
        <div class="footer-links">
            <h4>Kategori</h4>
            <ul>
                <li><a href="#">Elektronik</a></li>
                <li><a href="#">Fashion</a></li>
                <li><a href="#">Makanan & Minuman</a></li>
                <li><a href="#">Kesehatan</a></li>
            </ul>
        </div>

        {{-- 3. BANTUAN --}}
        <div class="footer-links">
            <h4>Bantuan</h4>
            <ul>
                <li><a href="{{ route('cara-belanja') }}">Cara Belanja</a></li>
                {{-- Pastikan route 'tentang-kami' sudah ada di web.php --}}
                <li><a href="{{ Route::has('tentang-kami') ? route('tentang-kami') : '#' }}">Tentang Kami</a></li>
            </ul>
        </div>


        {{-- 5. IKUTI KAMI (SOCIAL LINKS) --}}
        <div class="footer-social">
            <h4>Ikuti Kami</h4>
            {{-- PERBAIKAN: Menggunakan class 'social-icons' agar sesuai dengan CSS --}}
            <div class="social-icons">
                <a href="https://www.instagram.com/jastgo.id" target="_blank">
                    <i class="fab fa-instagram"></i> Instagram
                </a>
                <a href="https://wa.me/6281268433470" target="_blank">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
                <a href="https://www.facebook.com/share/1Ag85mEB6R/?mibextid=wwXIfr" target="_blank">
                    <i class="fab fa-facebook"></i> Facebook
                </a>
            </div>
        </div>

    </div>

    <div class="footer-bottom">
        © {{ date('Y') }} <strong>JASTGO</strong>. Belanja lokal jadi lebih mudah.
    </div>
</footer>

{{-- FULL CSS --}}
<style>
    /* Container Footer */
    .jastgo-footer {
        background: #FFDD00;
        padding: 40px 20px 18px;
        margin-top: 48px;
        color: #000;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Opsional: Agar font konsisten */
    }

    .jastgo-footer .footer-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Brand Section */
    .jastgo-footer .footer-brand h3 {
        margin-bottom: 8px;
        font-weight: 800;
        font-size: 1.5rem;
    }

    .jastgo-footer .footer-brand p {
        max-width: 260px;
        line-height: 1.4;
        font-size: 0.95rem;
    }

    /* Headings (H4) */
    .jastgo-footer .footer-links h4,
    .jastgo-footer .footer-social h4 {
        margin-bottom: 12px;
        font-size: 1.1rem;
        font-weight: 700;
    }

    /* Links Lists (UL/LI) */
    .jastgo-footer .footer-links ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .jastgo-footer .footer-links ul li {
        margin-bottom: 8px;
    }

    .jastgo-footer .footer-links ul li a {
        color: #0000AA; /* Biru gelap agar kontras dengan kuning */
        text-decoration: none;
        font-weight: 500;
    }
    
    .jastgo-footer .footer-links ul li a:hover {
        text-decoration: underline;
    }

    /* Social Icons Area */
    .footer-social {
        display: block;
    }

    /* PERBAIKAN: Class ini sekarang cocok dengan HTML */
    .social-icons {
        display: flex;
        flex-direction: column; /* Membuat link turun ke bawah (vertikal) */
        gap: 10px;
        align-items: flex-start;
        margin-top: 6px;
    }

    .social-icons a {
        display: flex;
        align-items: center;
        gap: 8px; /* Jarak antara ikon dan teks */
        margin: 0;
        padding: 0;
        color: #0000AA;
        text-decoration: none;
        font-weight: 500;
        transition: 0.3s;
    }

    .social-icons a:hover {
        opacity: 0.7;
        text-decoration: underline;
    }

    /* Footer Bottom (Copyright) */
    .jastgo-footer .footer-bottom {
        text-align: center;
        padding-top: 20px;
        margin-top: 30px;
        font-size: 14px;
        border-top: 1px solid rgba(0,0,0,0.1);
    }

    /* Responsive: Mobile Friendly */
    @media (max-width: 768px) {
        .jastgo-footer .footer-container {
            flex-direction: column;
            align-items: flex-start;
            gap: 30px;
        }
    }
</style>