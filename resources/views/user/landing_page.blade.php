@php
    use Illuminate\Support\Str;

   $userIsLoggedIn = Auth::check(); 
    $userName = $userIsLoggedIn ? (Auth::user()->name ?? 'Pengguna') : 'Guest';
    $cart = session('cart', []);
    // Di detail produk, count sering menggunakan total item (tanpa filter user ID)
    $cartCount = count($cart); 
    // Jika Anda ingin count spesifik user, ganti dengan logika di landing.blade.php
    
    // Asumsi variabel $barang dan $produkSerupa dilewatkan dari controller
    // $barang = \App\Models\Barang::find($id); // Contoh
    // $produkSerupa = [...];
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <title>JASTGO - Titip Produk Lokal Favoritmu</title>
    {{-- Memanggil komponen Styles --}}
    @include('user.layout.style')
    
    <style>
        /* LANDING PAGE SPECIFIC STYLES */
        /* Filter Section */
        .filter-section {
            background: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid #C1E0F4;
        }

        .filter-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .filter-container select {
            padding: 0.6rem 1.2rem;
            border: 2px solid #C1E0F4;
            border-radius: 8px;
            font-size: 0.95rem;
            cursor: pointer;
            background: white;
            transition: border-color 0.3s;
        }

        .filter-container select:focus {
            outline: none;
            border-color: #006FFF;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #006FFF 0%, #C1E0F4 100%);
            color: white;
            padding: 4rem 2rem;
            text-align: center;
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .discount-badge {
            display: inline-block;
            background: #FFDD00;
            color: #1f2937;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .discount-badge .big {
            font-size: 2rem;
            display: block;
            margin-bottom: 0.3rem;
        }
        
        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        /* Product Card Specific for Landing */
        .product-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: #FFDD00;
            color: #1f2937;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 1;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            padding: 2rem 0;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            padding: 0.6rem 1rem;
            border: 2px solid #C1E0F4;
            border-radius: 8px;
            text-decoration: none;
            color: #4b5563;
            transition: all 0.3s;
            background: white;
        }

        .pagination a:hover {
            background: #006FFF;
            color: white;
            border-color: #006FFF;
        }

        .pagination .active {
            background: #006FFF;
            color: white;
            border-color: #006FFF;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 1.8rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }
    </style>
</head>

<body>
    {{-- Menggunakan Komponen Header --}}
     @include('user.layout.header', [
    'isLoggedIn' => $userIsLoggedIn,
    // 'cartCount' => $cartCount,
    'searchValue' => '',
    'userName' => $userName,
])

    <div class="filter-section">
        <form action="{{ route('home') }}" method="GET" class="filter-container">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="kategori" value="{{ request('kategori') }}">

            <select name="sort" onchange="this.form.submit()">
                <option value="">Urutkan</option>
                <option value="harga_terendah" {{ request('sort') == 'harga_terendah' ? 'selected' : '' }}>Harga
                    Terendah</option>
                <option value="harga_tertinggi" {{ request('sort') == 'harga_tertinggi' ? 'selected' : '' }}>Harga
                    Tertinggi</option>
                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                <option value="nama_az" {{ request('sort') == 'nama_az' ? 'selected' : '' }}>Nama A-Z</option>
            </select>
        </form>
    </div>

    <section class="hero">
        <div class="hero-content">
            <h1>Titip Apa Aja Produk Lokal Favoritmu!</h1>
            <p>Semua produk khas daerah bisa sampai hanya dengan sekali titip. Belanja jadi gampang, aman, dan cepat.
            </p>
           <div class="discount-badge">
    <span class="big">BELANJA HEMAT, PASTI UNTUNG!</span>
</div>
        </div>
    </section>

    <main class="container">
        
        <section class="category-section" style="padding: 2rem 0 1rem;">
            <h2 class="section-title" style="margin-bottom: 1rem;">Pilih Kategori</h2>
            <div style="display: flex; gap: 0.75rem; overflow-x: auto; padding-bottom: 1rem;">
                {{-- Tombol Semua Kategori --}}
                <a href="{{ route('home', array_merge(request()->except('kategori', 'page'), ['kategori' => null])) }}"
                    style="text-decoration: none; padding: 0.5rem 1.2rem; border-radius: 50px; font-weight: 600; flex-shrink: 0; 
                    background: {{ !request('kategori') ? '#006FFF' : 'white' }}; 
                    color: {{ !request('kategori') ? 'white' : '#4b5563' }}; 
                    border: 2px solid {{ !request('kategori') ? '#006FFF' : '#C1E0F4' }};">
                    Semua
                </a>

                {{-- Daftar Kategori --}}
                @foreach ($kategoris as $kat)
                    @php
                        $isActive = request('kategori') == $kat->nama; 
                    @endphp
                    <a href="{{ route('home', array_merge(request()->except('kategori', 'page'), ['kategori' => $kat->nama])) }}"
                        style="text-decoration: none; padding: 0.5rem 1.2rem; border-radius: 50px; font-weight: 600; flex-shrink: 0;
                        background: {{ $isActive ? '#006FFF' : 'white' }};
                        color: {{ $isActive ? 'white' : '#4b5563' }};
                        border: 2px solid {{ $isActive ? '#006FFF' : '#C1E0F4' }};">
                        {{ $kat->nama }}
                    </a>
                @endforeach
            </div>
        </section>
        
        {{-- Produk Bulan Ini (Hanya Tampil di Home Awal) --}}
        @if (!request('search') && !request('kategori') && !request('sort'))
            <section class="product-section">
                <h2 class="section-title">Produk Bulan Ini (Terbaru)</h2>
                <div class="product-grid">
                    @forelse ($produkBulanIni as $barang)
                        <a href="{{ route('produk.detail', $barang->id) }}" class="product-card">
                            <div class="product-badge">TERBARU</div>
                            <div class="product-image">
                                @if ($barang->foto_barang)
                                    <img src="{{ asset('storage/' . $barang->foto_barang) }}"
                                        alt="{{ $barang->nama_barang }}">
                                @else
                                    <i class="fas fa-box"></i>
                                @endif
                            </div>
                            <div class="product-info">
                                <div class="store-name">
                                    <i class="fas fa-store"></i>
                                    {{ $barang->jastiper->nama_toko ?? 'Toko Tidak Dikenal' }}
                                </div>
                                <div class="store-name">
                                    <i class="fas fa-location"></i>
                                    {{ $barang->jastiper->jangkauan ?? 'Toko Tidak Dikenal' }}
                                </div>
                                <div class="product-name">{{ Str::limit($barang->nama_barang, 20) }}</div>
                                <div class="product-price">Rp {{ number_format($barang->harga, 0, ',', '.') }}</div>
                            </div>
                        </a>
                    @empty
                        <div class="empty-state" style="grid-column: 1 / -1;">
                            <i class="fas fa-box-open"></i>
                            <p>Maaf, belum ada produk terbaru yang tersedia saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        @endif

        <section class="product-section">
            <h2 class="section-title">
                @if (request('search'))
                    Hasil Pencarian: "{{ request('search') }}"
                @elseif(request('kategori'))
                    Kategori: {{ request('kategori') }}
                @else
                    Semua Produk yang Tersedia
                @endif
            </h2>
            <div class="product-grid">
                @forelse ($semuaProduk as $barang)
                    <a href="{{ route('produk.detail', $barang->id) }}" class="product-card">
                        <div class="product-image">
                            @if ($barang->foto_barang)
                                <img src="{{ asset('storage/' . $barang->foto_barang) }}"
                                    alt="{{ $barang->nama_barang }}">
                            @else
                                <i class="fas fa-box"></i>
                            @endif
                        </div>
                        <div class="product-info">
                            <div class="store-name">
                                <i class="fas fa-store"></i>
                                {{ $barang->jastiper->nama_toko ?? 'Toko Tidak Dikenal' }}
                            </div>
                            <div class="store-name">
                                <i class="fas fa-location"></i>
                                {{ $barang->jastiper->jangkauan ?? 'Toko Tidak Dikenal' }}
                            </div>
                            <div class="product-name">{{ Str::limit($barang->nama_barang, 20) }}</div>
                            <div class="product-price">Rp {{ number_format($barang->harga, 0, ',', '.') }}</div>
                        </div>
                    </a>
                @empty
                    <div class="empty-state" style="grid-column: 1 / -1;">
                        <i class="fas fa-box-open"></i>
                        <p>Tidak ada produk yang ditemukan.</p>
                    </div>
                @endforelse
            </div>

            <div class="pagination">
                {{ $semuaProduk->appends(request()->query())->links() }}
            </div>
        </section>
    </main>

    @include('user.layout.footer')
</body>

</html>