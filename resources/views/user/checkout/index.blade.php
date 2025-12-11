@php
    use Illuminate\Support\Str;

    // --- LOGIC AUTENTIKASI DAN KERANJANG ---
    $user = Auth::check() ? Auth::user() : (object) ['name' => 'Tamu', 'no_hp' => null];
    $userName = $user->name ?? 'Pengguna';
    $cartCount = count(session('cart', []));

    // Variabel dari Controller: $cartDetails, $subtotal, $totalBerat, $total_final, $step, $pesananId, $rekeningAdmin

    $steps = [
        1 => 'Pemesanan',
        2 => 'Alamat',
        3 => 'Pembayaran',
        4 => 'Konfirmasi',
    ];

    // Ambil data alamat dari sesi untuk Step 2
    $alamatLama = session('checkout_address', [
        'alamat_lengkap' => '',
        'provinsi' => '',
        'kota' => '',
        'kode_pos' => '',
        'catatan' => '',
    ]);

    $userIsLoggedIn = Auth::check();
    $userName = Auth::user()->name ?? 'Pengguna';
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Checkout - {{ $steps[$step] ?? 'Pemesanan' }} - JASTGO</title>
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
            background: #f7f7f7;
            color: #333;
        }

        .text-right {
            text-align: right;
        }

        .text-blue {
            color: #006FFF;
        }

        .font-bold {
            font-weight: 700;
        }

        /* ================================================= */
        /* CHECKOUT LAYOUT */
        /* ================================================= */
        .checkout-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            min-height: auto;
        }

        .main-content {
            width: 100%;
            padding: 2rem;
            min-height: auto;
        }

        /* --- Step Header --- */
        .step-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-grow: 1;
            text-align: center;
            opacity: 0.5;
            position: relative;
        }

        .step-item.active {
            opacity: 1;
            font-weight: bold;
        }

        .step-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ddd;
            color: #777;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.25rem;
            position: relative;
            z-index: 10;
        }

        .step-item.active .step-icon {
            background: #006FFF;
            color: white;
        }

        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 20px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: #ddd;
            z-index: 5;
            transform: translateX(20px);
        }

        /* --- Order Card & Form Styling --- */
        .order-item-card {
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            gap: 1rem;
            align-items: center;
            background: #fff;
        }

        .item-checkout-image {
            width: 100px;
            height: 100px;
            flex-shrink: 0;
            border-radius: 8px;
            overflow: hidden;
            background: #f0f0f0;
        }

        .item-checkout-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-checkout-details .name {
            font-size: 1.2rem;
            font-weight: 700;
        }

        .item-checkout-details .price-info {
            color: #006FFF;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        /* File Upload */
        .file-upload-wrapper {
            border: 2px dashed #006FFF;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            background: #f0f8ff;
            position: relative;
            overflow: hidden;
        }

        .file-upload-wrapper input[type="file"] {
            opacity: 0;
            position: absolute;
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;
            cursor: pointer;
        }

        .file-upload-wrapper span {
            display: block;
            color: #006FFF;
            font-weight: 600;
        }

        /* === Button Styles === */
        .btn-base {
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 50px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 1rem;
            display: inline-block;
            text-decoration: none !important;
            text-align: center;
        }

        .btn-next {
            background: #FFDD00;
            color: #333;
        }
        
        .btn-primary-blue {
            background: #006FFF;
            color: white;
        }
        
        .btn-back,
        .btn-next,
        .btn-primary-blue {
             padding: 0.8rem 2rem; 
             border: none; 
             border-radius: 50px; 
             font-weight: 700; 
             cursor: pointer; 
             margin-top: 1rem;
             text-decoration: none !important;
             display: inline-block;
        }
    </style>
</head>

<body>
    {{-- HEADER --}}
     @include('user.layout.header', [
         'isLoggedIn' => $userIsLoggedIn,
         'cartCount' => $cartCount,
         'searchValue' => '',
         'userName' => $userName,
     ])

    {{-- Main Checkout Area --}}
    <div class="checkout-container">

        <div class="main-content">
            <h1 style="color: #006FFF; font-size: 2rem;">Proses Checkout</h1>

            {{-- Alur Langkah --}}
            <div class="step-header">
                <div class="step-item {{ $step == 1 ? 'active' : '' }}">
                    <div class="step-icon"><i class="fas fa-shopping-cart"></i></div>
                    Pemesanan
                </div>
                <div class="step-item {{ $step == 2 ? 'active' : '' }}">
                    <div class="step-icon"><i class="fas fa-map-marker-alt"></i></div>
                    Alamat
                </div>
                <div class="step-item {{ $step == 3 ? 'active' : '' }}">
                    <div class="step-icon"><i class="fas fa-credit-card"></i></div>
                    Pembayaran
                </div>
                <div class="step-item {{ $step == 4 ? 'active' : '' }}">
                    <div class="step-icon"><i class="fas fa-check-circle"></i></div>
                    Konfirmasi
                </div>
            </div>

            {{-- Tampilkan pesan error/success --}}
            @if (session('success'))
                <div style="background: #e6ffed; color: #1f7a22; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div style="background: #ffe6e6; color: #cc0000; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    {{ session('error') }}
                </div>
            @endif

            {{-- CONTENT PER STEP --}}

            @if ($step == 1)
                {{-- STEP 1: PEMESANAN (ORDER SUMMARY) --}}
                <h2 style="margin-bottom: 1.5rem;">Ringkasan Pesanan</h2>
                <p>Silakan tinjau kembali daftar produk yang akan dibeli.</p>

                @foreach ($cartDetails as $item)
                    <div class="order-item-card">
                        <div class="item-checkout-image">
                            @if ($item->produk->foto_barang)
                                <img src="{{ asset('storage/' . $item->produk->foto_barang) }}"
                                    alt="{{ $item->produk->nama_barang }}">
                            @else
                                <i class="fas fa-box"
                                    style="font-size: 3rem; color: rgba(0,0,0,0.1); padding: 1rem;"></i>
                            @endif
                        </div>
                        <div class="item-checkout-details">
                            <div class="name">{{ $item->produk->nama_barang }}</div>
                            <div class="price-info">
                                Rp {{ number_format($item->produk->harga, 0, ',', '.') }} / item
                            </div>
                            <div class="item-store" style="color: #4b5563;">Jastiper:
                                {{ $item->produk->jastiper->nama_toko ?? 'N/A' }}</div>
                        </div>
                        <div class="text-right font-bold text-blue">x{{ $item->qty }}</div>
                    </div>
                @endforeach

                <div style="padding: 1.5rem; border: 1px solid #eee; border-radius: 12px; background: #fafafa; margin-top: 2rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span>Subtotal Harga Barang</span>
                        <span class="font-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; border-top: 1px solid #eee; padding-top: 0.5rem;">
                        <span class="font-bold text-blue">TOTAL PESANAN</span>
                        <span class="font-bold text-blue">Rp {{ number_format($total_final, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="text-right">
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="current_step" value="1">
                        <button type="submit" class="btn-next btn-base">Selanjutnya</button>
                    </form>
                </div>

            @elseif ($step == 2)
                {{-- STEP 2: ALAMAT & CATATAN --}}
                <h2 style="margin-bottom: 1.5rem;">Informasi Pengiriman</h2>
                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="current_step" value="2">

                    <div class="form-group">
                        <label for="alamat_lengkap">Alamat Lengkap (Jalan, Nomor Rumah)</label>
                        <textarea id="alamat_lengkap" name="alamat_lengkap" rows="3" required>{{ old('alamat_lengkap', $alamatLama['alamat_lengkap']) }}</textarea>
                    </div>

                    <div style="display: flex; gap: 1.5rem;">
                        <div class="form-group" style="flex: 1;">
                            <label for="provinsi">Provinsi</label>
                            <input type="text" id="provinsi" name="provinsi"
                                value="{{ old('provinsi', $alamatLama['provinsi']) }}" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label for="kota">Kota/Kabupaten</label>
                            <input type="text" id="kota" name="kota"
                                value="{{ old('kota', $alamatLama['kota']) }}" required>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1.5rem;">
                        <div class="form-group" style="flex: 1;">
                            <label for="kode_pos">Kode Pos</label>
                            <input type="text" id="kode_pos" name="kode_pos"
                                value="{{ old('kode_pos', $alamatLama['kode_pos']) }}" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                             {{-- Spacer --}}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="catatan">Catatan Pesanan (Opsional)</label>
                        <textarea id="catatan" name="catatan" rows="2" placeholder="">{{ old('catatan', $alamatLama['catatan'] ?? '') }}</textarea>
                    </div>

                    <div class="text-right">
                        <a href="{{ route('checkout.previous') }}" class="btn-back btn-base">Kembali</a>
                        <button type="submit" class="btn-next btn-base">Selanjutnya</button>
                    </div>
                </form>

            @elseif ($step == 3)
                {{-- STEP 3: PEMBAYARAN --}}
                <h2 style="margin-bottom: 1.5rem;">Lakukan Pembayaran</h2>

                <div style="padding: 1.5rem; border: 2px solid #006FFF; border-radius: 12px; background: #e0f0ff; margin-bottom: 2rem;">
                    <h3 style="color: #006FFF; margin-top: 0;">Total yang Harus Dibayar:</h3>
                    <p style="font-size: 2rem; font-weight: 700; margin: 0;">Rp
                        {{ number_format($total_final, 0, ',', '.') }}</p>
                    <p style="font-size: 0.9rem; color: #4b5563; margin-top: 0.5rem;">Lakukan pembayaran ke salah satu
                        rekening di bawah ini. ID Pesanan Anda: **#{{ $pesananId }}**</p>
                </div>

                {{-- Daftar Rekening --}}
                <div style="margin-bottom: 2rem;">
                    <h4 style="color: #333; border-bottom: 1px solid #eee; padding-bottom: 0.5rem;">Rekening Pembayaran (Admin)</h4>

                    @forelse ($rekeningAdmin as $rekening)
                        <div style="border: 1px solid #ddd; border-left: 5px solid #006FFF; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                            <span class="font-bold">{{ $rekening->nama_penyedia }} - {{ $rekening->tipe_rekening }}</span>
                            <div style="margin-top: 0.5rem;">
                                <span class="font-bold text-blue">{{ $rekening->nomor_akun }}</span>
                                <small>(A/N: {{ $rekening->nama_pemilik }})</small>
                                <button
                                    onclick="navigator.clipboard.writeText('{{ $rekening->nomor_akun }}'); alert('Nomor rekening disalin!');"
                                    style="background: none; border: none; color: gray; cursor: pointer;"><i class="fas fa-copy"></i></button>
                            </div>
                        </div>
                    @empty
                        <p>Maaf, belum ada rekening pembayaran yang aktif saat ini. Silakan hubungi Admin.</p>
                    @endforelse
                </div>

                <h4 style="margin-bottom: 1.5rem; color: #333;">Unggah Bukti Transfer</h4>
                <form action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="current_step" value="3">
                    <input type="hidden" name="pesanan_id" value="{{ $pesananId }}">

                    <div class="form-group">
                        <label for="bukti_transfer">Pilih Bukti Transfer (JPG, PNG)</label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="bukti_transfer" name="bukti_transfer"
                                accept="image/jpeg,image/png,image/jpg" required
                                onchange="document.getElementById('file-name').innerText = this.files[0].name">
                            <span id="file-name"><i class="fas fa-upload"></i> Klik untuk Unggah Gambar</span>
                        </div>
                        @error('bukti_transfer')
                            <p style="color: red; font-size: 0.9em;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="text-right">
                        <a href="{{ route('checkout.previous') }}" class="btn-back btn-base">Kembali</a>
                        <button type="submit" class="btn-primary-blue btn-base">Konfirmasi Pembayaran</button>
                    </div>
                </form>

            @elseif ($step == 4)
                {{-- STEP 4: KONFIRMASI AKHIR --}}
                <h2 style="margin-bottom: 1.5rem;">Pembayaran Berhasil Diunggah!</h2>
                <div style="text-align: center; padding: 3rem; border: 1px solid #eee; border-radius: 12px;">

                    <i class="fas fa-clock" style="font-size: 4rem; color: orange; margin-bottom: 1.5rem;"></i>

                    <p class="font-bold" style="margin-bottom: 0.5rem;">
                        Bukti transfer Anda telah diterima dan sedang diverifikasi oleh Admin. Status pesanan Anda saat
                        ini adalah **MENUNGGU KONFIRMASI ADMIN**.
                    </p>

                    <p style="margin-bottom: 2rem;">
                        Mohon tunggu maksimal 1x24 jam.
                    </p>

                    <a href="{{ route('checkout.finish') }}" class="btn-primary-blue btn-base">Lihat Riwayat Pesanan</a>
                </div>

            @endif

        </div>
    </div>
</body>
</html>