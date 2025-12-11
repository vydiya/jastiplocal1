@php
    use Illuminate\Support\Str;
    // Variabel $pesanans, $q, $status, $userName, $cartCount, dll. datang dari Controller
    $isLoggedIn = Auth::check();

    // Map status untuk CSS
    $statusMap = [
        'MENUNGGU_PEMBAYARAN' => 'status-menunggu',
        'MENUNGGU_KONFIRMASI_ADMIN' => 'status-menunggu',
        'DIPROSES' => 'status-diproses',
        'SIAP_DIKIRIM' => 'status-dikirim',
        'SELESAI' => 'status-selesai',
        'DIBATALKAN' => 'status-batal', // CSS sudah ada di bawah (background: red)
    ];
    
    // FUNGSI UNTUK MENYEDERHANAKAN TAMPILAN STATUS
    $getDisplayStatus = function ($status) {
        $map = [
            'MENUNGGU_PEMBAYARAN' => 'MENUNGGU BAYAR',
            'MENUNGGU_KONFIRMASI_ADMIN' => 'MENUNGGU KONFIRMASI',
            'SIAP_DIKIRIM' => 'DIKIRIM',
            'DIBATALKAN' => 'DIBATALKAN',
            'DIPROSES' => 'DIPROSES',
            'SELESAI' => 'SELESAI',
        ];
        return $map[$status] ?? str_replace('_', ' ', $status);
    };

    // Variabel ini dijamin ada dari Controller/Middleware
    $userIsLoggedIn = Auth::check();
    $userName = Auth::user()->name ?? 'Pengguna';
    // Asumsi $cartCount datang dari View Composer atau Controller
    $cartCount = $cartCount ?? 0; 
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Pesanan Saya - Riwayat Pesanan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ================================================= */
        /* BASE & HEADER STYLES */
        /* ================================================= */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #333;
        }

        /* Container Header */
        header {
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        /* ... (Style Header & Navigasi tetap sama seperti sebelumnya) ... */

        /* ================================================= */
        /* RIWAYAT PESANAN STYLES */
        /* ================================================= */
        .container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .section-title {
            color: #006FFF;
            margin-bottom: 2rem;
            font-size: 2rem;
        }

        /* --- Form dan Filter Styling --- */
        .search-filter-area {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .search-input-group {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .search-input-group input {
            flex-grow: 1;
            padding: 0.6rem 1rem;
            border: 1px solid #C1E0F4;
            border-radius: 8px;
            font-size: 1rem;
        }

        .search-input-group button {
            background: #006FFF;
            color: white;
            border: none;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .status-filter-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .status-filter-nav a {
            text-decoration: none;
            color: #4b5563;
            font-weight: 600;
            padding: 0.4rem 0.8rem;
            border: 1px solid #C1E0F4;
            border-radius: 6px;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .status-filter-nav a.active,
        .status-filter-nav a:hover {
            background: #006FFF;
            color: white;
            border-color: #006FFF;
        }

        /* --- Order Cards --- */
        .order-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            padding: 1.5rem;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }

        .order-id {
            font-size: 1.1rem;
            font-weight: 700;
            color: #333;
        }

        .order-status {
            font-weight: 600;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            color: white;
        }

        /* CSS Class Status */
        .status-menunggu {
            background: orange;
        }

        .status-diproses {
            background: #006FFF;
        }

        .status-dikirim {
            background: #00A388;
        }

        .status-selesai {
            background: green;
        }

        .status-batal {
            background: red;
        }

        .item-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .item-list li {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px dashed #f0f0f0;
            font-size: 0.95rem;
        }

        .item-list li:last-child {
            border-bottom: none;
        }

        .order-footer {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-price {
            font-size: 1.4rem;
            font-weight: 700;
            color: #006FFF;
        }

        .btn-detail {
            background: #FFDD00;
            color: #333;
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.2s;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        
        .btn-complete {
            background: green;
            color: white !important;
            margin-left: 0.5rem;
        }
        
        /* --- Tambahan CSS Ulasan --- */
        .rating-stars {
            display: flex;
            justify-content: center;
            direction: rtl;
            gap: 2px;
        }

        .rating-stars input {
            display: none;
        }

        .rating-stars label {
            font-size: 1.8rem;
            color: #ccc;
            cursor: pointer;
            transition: color 0.2s;
        }

        .rating-stars label:hover,
        .rating-stars label:hover ~ label,
        .rating-stars input:checked ~ label {
            color: gold;
        }

        .rating-form {
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-top: 1rem;
            background: #fcfcfc;
        }

        .rating-form textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #C1E0F4;
            border-radius: 6px;
            margin-top: 0.5rem;
            resize: vertical;
        }

        .rating-form button {
            margin-top: 1rem;
            background: #00A388;
            color: white;
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6b7280;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #C1E0F4;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }
        .wa-btn {
            background: #25D366;
            color: white;
            border: none;
            padding: 0.5rem 0.9rem;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }
    </style>
</head>

<body>
    @include('user.layout.header', [
        'isLoggedIn' => $userIsLoggedIn,
        'cartCount' => $cartCount,
        'searchValue' => '',
        'userName' => $userName,
    ])

    {{-- Pesan Status --}}
    @if (session('success'))
        <div class="container" style="padding-top: 1rem;">
            <div style="background: #e6ffed; color: #1f7743; padding: 1rem; border-radius: 8px; border: 1px solid #b7eb8f; margin-bottom: 1rem;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="container" style="padding-top: 1rem;">
            <div style="background: #fff0f6; color: #d43f3f; padding: 1rem; border-radius: 8px; border: 1px solid #ffa39e; margin-bottom: 1rem;">
                <i class="fas fa-times-circle"></i> {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="container">
        <h1 class="section-title"><i class="fas fa-history"></i> Riwayat Pesanan</h1>

        {{-- Area Pencarian dan Filter --}}
        <div class="search-filter-area">
            {{-- Form Pencarian --}}
            <form action="{{ route('pesanan.riwayat') }}" method="GET" class="search-input-group">
                <input type="text" name="q" placeholder="Cari ID, HP, atau Alamat..."
                    value="{{ $q ?? '' }}">
                <button type="submit">
                    <i class="fas fa-search"></i> Cari
                </button>
            </form>

            {{-- Filter Status --}}
            <nav class="status-filter-nav">
                <a href="{{ route('pesanan.riwayat', ['status' => 'MENUNGGU_PEMBAYARAN']) }}"
                    class="{{ $status == 'MENUNGGU_PEMBAYARAN' ? 'active' : '' }}">Bayar</a>
                
                <a href="{{ route('pesanan.riwayat', ['status' => 'DIPROSES']) }}"
                    class="{{ $status == 'DIPROSES' ? 'active' : '' }}">Diproses</a>
                
                <a href="{{ route('pesanan.riwayat', ['status' => 'SIAP_DIKIRIM']) }}"
                    class="{{ $status == 'SIAP_DIKIRIM' ? 'active' : '' }}">Dikirim</a>
                
                <a href="{{ route('pesanan.riwayat', ['status' => 'SELESAI']) }}"
                    class="{{ $status == 'SELESAI' ? 'active' : '' }}">Selesai</a>

                {{-- === TOMBOL DIBATALKAN (DITAMBAHKAN) === --}}
                <a href="{{ route('pesanan.riwayat', ['status' => 'DIBATALKAN']) }}"
                    class="{{ $status == 'DIBATALKAN' ? 'active' : '' }}">Dibatalkan</a>
                {{-- ========================================= --}}

                <a href="{{ route('pesanan.riwayat') }}"
                    class="{{ !$status || $status == 'Semua Aktif' ? 'active' : '' }}">Semua</a>
            </nav>
        </div>


        @forelse ($pesanans as $pesanan)
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <div class="order-id">#{{ $pesanan->id }}</div>
                        <small
                            class="text-muted">{{ \Carbon\Carbon::parse($pesanan->tanggal_pesan)->translatedFormat('d F Y, H:i') }}</small>
                    </div>
                    {{-- Status Badge --}}
                    <span class="order-status {{ $statusMap[$pesanan->status_pesanan] ?? 'status-batal' }}">
                        {{ $getDisplayStatus($pesanan->status_pesanan) }}
                    </span>
                </div>

                <p style="font-weight: 600; margin-bottom: 0.5rem;"><i class="fas fa-store"></i> Jastiper:
                    {{ $pesanan->jastiper->nama_toko ?? 'Admin' }}</p>

                <ul class="item-list">
                    @foreach ($pesanan->detailPesanans as $detail)
                        <li>
                            <span>{{ $detail->barang->nama_barang ?? 'Produk Dihapus' }}
                                (x{{ $detail->jumlah }})</span>
                            <span class="font-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="order-footer" style="display:flex; align-items:center; gap:1rem;">
                    <!-- Left: total -->
                    <div style="flex: 1 1 auto;">
                        <div class="total-price">Total: Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
                    </div>

                    <!-- Right: aksi/tombol -->
                    <div style="flex: 0 0 auto; display:flex; align-items:center; gap:0.75rem;">
                        {{-- Contoh kondisi tombol untuk berbagai status --}}
                        @if ($pesanan->status_pesanan == 'MENUNGGU_PEMBAYARAN')
                            <a href="{{ route('checkout.index', ['pesanan_id' => $pesanan->id]) }}" class="btn-detail" style="background:red; color:white;">
                                <i class="fas fa-credit-card"></i> Bayar Sekarang
                            </a>

                        @elseif ($pesanan->status_pesanan == 'SIAP_DIKIRIM')
                            <form action="{{ route('pesanan.complete', $pesanan->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn-detail btn-complete" onclick="return confirm('Apakah Anda yakin pesanan sudah diterima?')">
                                    <i class="fas fa-check"></i> Pesanan Diterima
                                </button>
                            </form>

                        @elseif ($pesanan->status_pesanan == 'SELESAI' && !$pesanan->has_reviewed)
                            <a href="#" class="btn-detail" onclick="document.getElementById('review-form-{{ $pesanan->id }}').style.display='block'; this.style.display='none';" style="background:#00A388; color:white;">
                                <i class="fas fa-star"></i> Beri Ulasan
                            </a>

                        @elseif ($pesanan->status_pesanan == 'DIBATALKAN')
                            {{-- Tombol WhatsApp sejajar dengan Total (kanan) --}}
                            <a href="https://wa.me/6283194075092?text={{ urlencode('Halo Admin, saya ingin mengurus pengembalian dana untuk pesanan ID ' . $pesanan->id) }}" 
                            class="wa-btn" target="_blank" rel="noopener">
                                <i class="fab fa-whatsapp"></i> Hubungi Admin
                            </a>

                        @else
                            @if ($pesanan->has_reviewed)
                                <span class="order-status status-selesai" style="background:#90ee90; color:#333; padding:0.3rem 0.8rem; border-radius:12px;">
                                    <i class="fas fa-star"></i> Sudah Diulas
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
                
                {{-- FORM ULASAN --}}
                @if ($pesanan->status_pesanan == 'SELESAI' && !$pesanan->has_reviewed)
                    <div id="review-form-{{ $pesanan->id }}" class="rating-form" style="display: none;">
                        <h4 style="margin-top: 0; color: #006FFF;">Beri Ulasan Anda</h4>
                        <p style="font-size: 0.9rem;">Untuk **{{ $pesanan->jastiper->nama_toko ?? 'Admin' }}**</p>

                        <form action="{{ route('ulasan.store', $pesanan->id) }}" method="POST">
                            @csrf
                            
                            <div class="rating-stars">
                                <input type="radio" id="star5-{{ $pesanan->id }}" name="rating" value="5" required>
                                <label for="star5-{{ $pesanan->id }}" title="5 stars"><i class="fas fa-star"></i></label>
                                
                                <input type="radio" id="star4-{{ $pesanan->id }}" name="rating" value="4">
                                <label for="star4-{{ $pesanan->id }}" title="4 stars"><i class="fas fa-star"></i></label>
                                
                                <input type="radio" id="star3-{{ $pesanan->id }}" name="rating" value="3">
                                <label for="star3-{{ $pesanan->id }}" title="3 stars"><i class="fas fa-star"></i></label>
                                
                                <input type="radio" id="star2-{{ $pesanan->id }}" name="rating" value="2">
                                <label for="star2-{{ $pesanan->id }}" title="2 stars"><i class="fas fa-star"></i></label>
                                
                                <input type="radio" id="star1-{{ $pesanan->id }}" name="rating" value="1">
                                <label for="star1-{{ $pesanan->id }}" title="1 star"><i class="fas fa-star"></i></label>
                            </div>

                            <textarea name="komentar" rows="3" placeholder="Tulis komentar/pengalaman Anda (Opsional)"></textarea>
                            <button type="submit"><i class="fas fa-paper-plane"></i> Kirim Ulasan</button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <p style="margin-bottom: 1rem;">Anda belum memiliki riwayat pesanan.</p> <a href="{{ route('home') }}"
                    class="btn-detail" style="background: #006FFF; color: white; margin-top: 1rem;">Mulai Belanja</a>
            </div>
        @endforelse

    </div>
</body>
</html>