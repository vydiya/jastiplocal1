{{-- SECTION: TENTANG KAMI + DOKUMENTASI --}}
<section class="about-section">
  <div class="about-inner container">
    <h2 class="about-title">Tentang Kami</h2>

    <div class="team-row">
      <div class="team-item">
        <div class="team-photo">
          <img src="{{ asset('admin/assets/images/gambar1.jpeg') }}" alt="Diky Wahyudi" />
        </div>
        <div class="team-name">Diky Wahyudi</div>
      </div>

      <div class="team-item">
        <div class="team-photo">
          <img src="{{ asset('admin/assets/images/gambar2.jpeg') }}" alt="Vydiya Asyura" />
        </div>
        <div class="team-name">Vydiya Asyura</div>
      </div>

      <div class="team-item">
        <div class="team-photo">
          <img src="{{ asset('admin/assets/images/gambar3.jpeg') }}" alt="M.Fatih Ramadhan" />
        </div>
        <div class="team-name">M.Fatih Ramadhan</div>
      </div>
    </div> <!-- .team-row -->

    <h3 class="doc-title">Dokumentasi Asli dengan Narasumber</h3>

    <div class="doc-row">
      <div class="doc-item">
        <img src="{{ asset('admin/assets/images/gambar_dokumentasi1.jpeg') }}" alt="dokumentasi1" />
      </div>
      <div class="doc-item">
        <img src="{{ asset('admin/assets/images/gambar_dokumentasi2.jpeg') }}" alt="dokumentasi2" />
      </div>
    </div>

    <div class="doc-caption">
      Narasumber : Bapak Najib Akbar Owner Bosalad dan Jastiper Makanan Lokal
    </div>
  </div> <!-- .about-inner -->
</section>

{{-- UPDATED FOOTER --}}
<footer class="jastgo-footer">
  <div class="footer-container container">
    <div class="footer-brand">
      <h3>JASTGO</h3>
      <p>Platform titip beli produk lokal dari seluruh Indonesia. Mudah, aman, dan terpercaya.</p>
    </div>

    <div class="footer-links">
      <h4>Kategori</h4>
      <ul>
        <li><a href="#">Elektronik</a></li>
        <li><a href="#">Fashion</a></li>
        <li><a href="#">Makanan &amp; Minuman</a></li>
        <li><a href="#">Kesehatan</a></li>
      </ul>
    </div>

    <div class="footer-links">
      <h4>Bantuan</h4>
      <ul>
        <li><a href="#">Cara Belanja</a></li>
        <li><a href="#">FAQ</a></li>
        <li><a href="#">Syarat &amp; Ketentuan</a></li>
        <li><a href="#">Kebijakan Privasi</a></li>
      </ul>
    </div>

    <div class="footer-social">
      <h4>Ikuti Kami</h4>
      <div class="social-icons">
        <a href="#" aria-label="Instagram">Instagram</a>
        <a href="#" aria-label="WhatsApp">WhatsApp</a>
        <a href="#" aria-label="Facebook">Facebook</a>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    © {{ date('Y') }} <strong>JASTGO</strong>. Belanja lokal jadi lebih mudah.
  </div>
</footer>

{{-- STYLES (tempel ini ke <head> atau file CSS global) --}}
<style>
/* --- Mengurangi jarak antar section --- */
.section-title {
    margin-top: 10px !important; 
    margin-bottom: 10px !important;
}

/* --- Foto Tim --- */
.team-photos {
    margin-top: 5px !important;
    margin-bottom: 0px !important;
}

/* --- Jarak antara foto dan nama --- */
.team-name {
    margin-top: 3px !important;
}

/* --- Section Dokumentasi --- */
.doc-section-title {
    margin-top: 15px !important; 
    margin-bottom: 10px !important;
}

/* --- Frame Dokumentasi --- */
.doc-item {
    padding: 6px !important;
    margin-bottom: 6px !important;
}

.doc-item img {
    height: 170px !important;  /* lebih pendek */
    border-radius: 14px !important;
}

/* --- Jarak bawah narasumber --- */
.narasumber-text {
    margin-top: 6px !important;
    margin-bottom: 6px !important;
}

/* Container helper */
.container { 
    max-width:1100px; 
    margin:0 auto; 
    padding:0 10px;  /* lebih rapat */
}

/* ABOUT / TEAM */
.about-section {
  background: linear-gradient(135deg,#2f7bff 0%, #a8c7ff 100%);
  color: #fff;
  padding: 35px 0 25px;      /* lebih pendek dari 60px → 35px */
  text-align: center;
}

.about-title { 
  font-size:26px; 
  font-weight:700; 
  margin-bottom:18px;       /* lebih kecil */
}

/* team row */
.team-row {
  display:flex;
  gap:26px;                 /* lebih rapat dari 40px */
  justify-content:center;
  align-items:flex-end;
  margin-bottom:18px;
  flex-wrap:wrap;
}
.team-item { width:200px; text-align:center; }

/* Foto tim */
.team-photo {
  width: 160px;             /* lebih kecil */
  height: 160px;
  margin: 0 auto 8px;
  border-radius: 50%;
  background: #fff;
  padding: 5px;
  border: 3px solid #ffd400;
  box-shadow: 0 4px 12px rgba(0,0,0,0.12);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.team-photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 50%;
}

/* documentation */
.doc-title { 
    font-size:20px; 
    font-weight:700; 
    margin:20px 0 12px; 
    color:#fff; 
}

.doc-row {
  display:flex;
  gap:26px;                   /* lebih rapat */
  justify-content:center;
  align-items:center;
  flex-wrap:wrap;
  margin-bottom:8px;
}

.doc-item {
    width: 36%;               /* lebih ramping */
    background: white;
    padding: 8px;             /* lebih kecil dari 10px */
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    display: flex;
    justify-content: center;
    align-items: center;
}

.doc-item img {
    width: 100%;
    height: auto;
    border-radius: 10px;
    object-fit: cover;
}

.doc-caption { 
    color:#e8f0ff; 
    margin-top:10px;          /* lebih pendek */
    font-weight:600; 
}

/* FOOTER */
.jastgo-footer { background: #FFDD00; color:#000000; padding:40px 0 18px; margin-top:48px; }
.jastgo-footer .footer-container { display:flex; gap:40px; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; }
.jastgo-footer .footer-brand h3 { color:#000000; margin-bottom:8px; }
.jastgo-footer .footer-brand p { color:#000000; max-width:260px; line-height:1.4; }
.jastgo-footer .footer-links h4, .jastgo-footer .footer-social h4 { color:#000000; margin-bottom:8px; font-size:16px; }
.jastgo-footer .footer-links ul { list-style:none; margin:0; padding:0; }
.jastgo-footer .footer-links ul li { margin:6px 0; }
.jastgo-footer .footer-links ul li a { color:#0202ff; text-decoration:none; }
.jastgo-footer .social-icons a { display:inline-block; margin-right:10px; color:#0202ff; text-decoration:none; padding:6px 10px; background:#0202ff(0, 0, 0, 0.02); border-radius:6px; }

/* bottom */
.jastgo-footer .footer-bottom { text-align:center; color:#000000; padding-top:18px; font-size:14px; }

/* Responsive */
@media (max-width:900px) {
  .team-row { gap:20px; }
  .team-item { width:170px; }
  .team-photo { width:140px; height:140px; }
  .doc-item { width:280px; height:160px; }
  .jastgo-footer .footer-container { gap:20px; padding:0 20px; }
}
@media (max-width:520px) {
  .team-row { flex-direction:row; gap:10px; }
  .team-item { width:130px; }
  .team-photo { width:110px; height:110px; border-width:3px; }
  .doc-row { flex-direction:column; gap:18px; }
  .doc-item { width:100%; max-width:420px; height:160px; }
  .about-section { padding:40px 0 28px; }
}
</style>
