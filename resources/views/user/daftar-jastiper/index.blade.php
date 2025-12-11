@php
    // Data untuk Header
    $userIsLoggedIn = Auth::check();
    $userName = Auth::user()->name ?? 'Pengguna';
    $cartCount = 0; // Asumsi cartCount 0 di halaman ini
    $isJastiper = Auth::check() && Auth::user()->jastiper()->exists();
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Sebagai Jastiper</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* CSS yang Anda berikan di sini */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; color: #333; }
        .container { max-width: 700px; margin: 3rem auto; padding: 2rem; background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); }
        .form-title { color: #006FFF; margin-bottom: 2rem; font-size: 2rem; text-align: center; }
        
        .form-group { margin-bottom: 1.5rem; }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #4b5563;
        }
        .form-group input[type="text"],
        .form-group input[type="file"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #C1E0F4;
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        .form-group textarea { resize: vertical; }
        .btn-submit {
            background: #006FFF;
            color: white;
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-submit:hover { background: #008f77; }
        .alert { padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px; }
        .alert-error { background: #ffe3e3; color: #cc0000; border: 1px solid #cc0000; }
        .alert-success { background: #e6fffb; color: #00A388; border: 1px solid #00A388; } /* Tambahan Style Success */
        
        .photo-upload-container {
            text-align: center;
            margin-bottom: 2rem;
            padding: 1rem;
            border: 1px dashed #C1E0F4;
            border-radius: 8px;
            background: #fcfcfc;
        }
        .photo-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 10px;
            border: 3px solid #eee;
            background: #e9ecef;
            display: block;
        }
        .photo-placeholder {
            font-size: 2rem;
            line-height: 100px;
            color: #999;
        }
    </style>
</head>
<body>
    {{-- Memanggil Header --}}
    @include('user.layout.header', [
        'isLoggedIn' => $userIsLoggedIn,
        'cartCount' => $cartCount,
        'searchValue' => '',
        'userName' => $userName,
        'isJastiper' => $isJastiper
    ])

    <div class="container">
        <h1 class="form-title"><i class="fas fa-store"></i> Pendaftaran Jastiper</h1>

        {{-- Menampilkan Pesan Sukses --}}
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        {{-- Menampilkan Pesan Error --}}
        @if ($errors->any() || session('error'))
            <div class="alert alert-error">
                @if(session('error'))
                    <p>{{ session('error') }}</p>
                @endif
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('jastiper.register.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="photo-upload-container">
                <img id="profile_toko_preview" src="https://placehold.co/100x100/f0f4f8/999999?text=FOTO" 
                    alt="Foto Profil Toko" class="photo-preview">
                <p class="mb-2" style="font-weight: 600;">Foto Profil Toko (Opsional, Max 2MB)</p>
                <input type="file" name="profile_toko" id="profile_toko_input" 
                        class="@error('profile_toko') is-invalid @enderror" 
                        accept="image/*"
                        onchange="document.getElementById('profile_toko_preview').src = window.URL.createObjectURL(this.files[0])">
            </div>

            <h3 style="color: #4b5563; margin-top: 0; margin-bottom: 1.5rem;"><i class="fas fa-tag"></i> Detail Toko</h3>
            
            <div class="form-group">
                <label for="nama_toko">Nama Toko / Brand Anda</label>
                <input type="text" id="nama_toko" name="nama_toko" value="{{ old('nama_toko') }}" required placeholder="Contoh: Jastip Kilat Solo">
            </div>

            <div class="form-group">
                <label for="no_hp">Nomor HP/WhatsApp</label>
                <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', Auth::user()->no_hp ?? '') }}" required placeholder="Contoh: 081234567890">
            </div>
            
            <div class="form-group">
                <label for="jangkauan">Jangkauan/Area Layanan (Kota/Provinsi)</label>
                <input type="text" id="jangkauan" name="jangkauan" value="{{ old('jangkauan') }}" required placeholder="Contoh: Area Jakarta Selatan, Seluruh Jawa Tengah">
            </div>
            

            <h3 style="color: #4b5563; margin-top: 2rem; margin-bottom: 1.5rem;"><i class="fas fa-wallet"></i> Rekening Pembayaran</h3>

            <div class="form-group">
                <label for="tipe_rekening">Tipe Rekening</label>
                <select name="tipe_rekening" id="tipe_rekening" class="form-select" required>
                    <option value="">-- Pilih Tipe --</option>
                    <option value="bank" {{ old('tipe_rekening') == 'bank' ? 'selected' : '' }}>Bank</option>
                    <option value="e-wallet" {{ old('tipe_rekening') == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                </select>
            </div>

            <div class="form-group">
                <label for="nama_penyedia">Nama Penyedia (Nama Bank / Nama E-Wallet)</label>
                <input type="text" id="nama_penyedia" name="nama_penyedia" value="{{ old('nama_penyedia') }}" required placeholder="Contoh: Bank BCA / Dana">
            </div>

            <div class="form-group">
                <label for="nama_pemilik">Nama Pemilik Akun</label>
                <input type="text" id="nama_pemilik" name="nama_pemilik" value="{{ old('nama_pemilik') }}" required placeholder="Sesuai nama di buku tabungan/akun">
            </div>

            <div class="form-group">
                <label for="nomor_akun">Nomor Rekening / Nomor HP E-Wallet</label>
                <input type="text" id="nomor_akun" name="nomor_akun" value="{{ old('nomor_akun') }}" required placeholder="Nomor rekening atau nomor HP yang terdaftar">
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane"></i> Daftar
            </button>
        </form>
    </div>
</body>
</html>