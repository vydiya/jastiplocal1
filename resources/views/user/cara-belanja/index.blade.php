<section class="belanja-section">
    <div class="belanja-container container">

        <!-- Back Arrow -->
        <div class="back-link">
            <a href="{{ url()->previous() }}">&#8592; Kembali</a>
        </div>

        <h2 class="belanja-title">Tata Cara Belanja</h2>

        <div class="belanja-steps">

            <div class="step-item">
                <div class="step-number">1</div>
                <div class="step-text">
                    <h4>Cari Produk</h4>
                    <p>Mulailah dengan mencari barang yang Anda inginkan melalui kolom pencarian atau menggunakan filter kategori yang tersedia.</p>
                </div>
            </div>

            <div class="step-item">
                <div class="step-number">2</div>
                <div class="step-text">
                    <h4>Pilih Cara Membeli</h4>
                    <p>• Tambahkan ke keranjang jika ingin membeli nanti.<br>
                    • Tekan tombol Pembayaran jika ingin langsung transaksi.</p>
                </div>
            </div>

            <div class="step-item">
                <div class="step-number">3</div>
                <div class="step-text">
                    <h4>Pembayaran Melalui Keranjang (Opsional)</h4>
                    <p>Buka halaman keranjang dan tekan tombol Pembayaran untuk melanjutkan.</p>
                </div>
            </div>

            <div class="step-item">
                <div class="step-number">4</div>
                <div class="step-text">
                    <h4>Isi Alamat Pengiriman</h4>
                    <p>Lengkapi alamat secara benar lalu tekan Selanjutnya.</p>
                </div>
            </div>

            <div class="step-item">
                <div class="step-number">5</div>
                <div class="step-text">
                    <h4>Pilih Rekening Admin</h4>
                    <p>Pilih salah satu rekening admin lalu salin nomor rekening tersebut.</p>
                </div>
            </div>

            <div class="step-item">
                <div class="step-number">6</div>
                <div class="step-text">
                    <h4>Lakukan Transfer Pembayaran</h4>
                    <p>Lakukan pembayaran sesuai total belanja melalui mobile banking atau transfer bank.</p>
                </div>
            </div>

            <div class="step-item">
                <div class="step-number">7</div>
                <div class="step-text">
                    <h4>Unggah Bukti Pembayaran</h4>
                    <p>Unggah bukti pembayaran lalu tunggu proses verifikasi admin.</p>
                </div>
            </div>

            <div class="step-item">
                <div class="step-number">8</div>
                <div class="step-text">
                    <h4>Pantau Status Pesanan</h4>
                    <p>Anda akan diarahkan ke halaman Status Pesanan hingga status berubah menjadi Dikirim.</p>
                </div>
            </div>

            <div class="step-item">
                <div class="step-number">9</div>
                <div class="step-text">
                    <h4>Konfirmasi Barang Diterima</h4>
                    <p>Tekan tombol Selesai setelah barang Anda terima.</p>
                </div>
            </div>

            <div class="step-item">
                <div class="step-number">10</div>
                <div class="step-text">
                    <h4>Beri Rating & Ulasan</h4>
                    <p>Berikan bintang dan ulasan untuk jastiper sebagai penilaian layanan.</p>
                </div>
            </div>

            <div class="step-item">
                <div class="step-number">11</div>
                <div class="step-text">
                    <h4>Jika Pembayaran Ditolak / Gagal</h4>
                    <p>Hubungi admin. Dana akan dikembalikan 100%.</p>
                </div>
            </div>

            <div class="step-item">
                <div class="step-number">12</div>
                <div class="step-text">
                    <h4>Jika Barang Tidak Diterima</h4>
                    <p>Segera hubungi admin melalui media sosial resmi yang ada di halaman Beranda.</p>
                </div>
            </div>

        </div>

    </div>
</section>


<style>
    .belanja-section {
        background: #ffffff; /* background putih */
        padding: 40px 20px;
        color: #000;
        text-align: center;
    }

    .back-link {
        text-align: left;
        margin-bottom: 15px;
    }

    .back-link a {
        color: #2f7bff;
        font-weight: 600;
        text-decoration: none;
        font-size: 15px;
        transition: 0.3s;
    }

    .back-link a:hover {
        color: #ffd400;
    }

    .belanja-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 30px;
        color: #2f7bff;
    }

    .belanja-steps {
        max-width: 850px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .step-item {
        background: #f5f5f5;
        padding: 18px 22px;
        border-radius: 18px;
        display: flex;
        gap: 18px;
        align-items: flex-start;
        text-align: left;
        box-shadow: 0 6px 12px rgba(0,0,0,0.08);
        transition: transform 0.3s, background 0.3s;
    }

    .step-item:hover {
        transform: translateY(-3px);
        background: #e0eaff;
    }

    .step-number {
        width: 42px;
        height: 42px;
        background: #2f7bff;
        color: #fff;
        font-weight: 700;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .step-text h4 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
    }

    .step-text p {
        margin-top: 5px;
        font-size: 14px;
        line-height: 1.5;
        color: #333;
    }

    /* RESPONSIVE */
    @media (max-width: 520px) {
        .step-item {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }

        .step-number {
            margin: 0 auto;
        }

        .back-link {
            text-align: center;
        }
    }
</style>
