@if(Auth::check() && Auth::user()->role === 'admin')
<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">

        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                {{-- Admin only --}}
                <li class="{{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard.index') }}">
                        <img src="{{ asset('admin/assets/images/icons/dashboard.svg') }}" alt="Dashboard"
                            style="width:20px;margin-right:12px">
                        Dashboard
                    </a>
                </li>

                <li class="{{ request()->is('admin/pengguna*') ? 'active' : '' }}">
                    <a href="{{ route('admin.pengguna.index') }}">
                        <img src="{{ asset('admin/assets/images/icons/data-master.svg') }}"
                            style="width:20px;margin-right:12px" alt="Data Pengguna">
                        Data Pengguna
                    </a>
                </li>

                <li class="{{ request()->is('admin/jastiper*') ? 'active' : '' }}">
                    <a href="{{ route('admin.jastiper.index') }}">
                        <img src="{{ asset('admin/assets/images/icons/data-master.svg') }}"
                            style="width:20px;margin-right:12px" alt="Data Jastiper">
                        Data Jastiper
                    </a>
                </li>

                <li class="{{ request()->is('admin/kategori*') ? 'active' : '' }}">
                    <a href="{{ route('admin.kategori.index') }}">
                        <img src="{{ asset('admin/assets/images/icons/barang.svg') }}"
                            style="width:20px;margin-right:12px" alt="Kategori Barang">
                        Kategori Barang
                    </a>
                </li>
                
                {{-- REKENING BARU DITAMBAHKAN --}}
                <li class="{{ request()->is('admin/rekening*') ? 'active' : '' }}">
                    <a href="{{ route('admin.rekening.index') }}">
                        <img src="{{ asset('admin/assets/images/icons/data-master.svg') }}"
                            style="width:20px;margin-right:12px" alt="Data Rekening">
                        Data Rekening
                    </a>
                </li>

                <li class="{{ request()->is('admin/konfirmasi-pembayaran*') || request()->is('admin/pelepasan-dana*') ? 'active' : '' }}">
                    <a href="{{ route('admin.konfirmasi-pembayaran.index') }}">
                        <img src="{{ asset('admin/assets/images/icons/kelola-dana.svg') }}"
                            style="width:20px;margin-right:12px" alt="Kelola Dana">
                        Konfirmasi & Pelepasan Dana
                    </a>
                </li>

                <li class="{{ request()->is('admin/log-aktivitas*') ? 'active' : '' }}">
                    <a href="{{ route('admin.log-aktivitas.index') }}">
                        <img src="{{ asset('admin/assets/images/icons/tarnsaksi.svg') }}"
                            style="width:20px;margin-right:12px" alt="Log Aktivitas">
                        Log Aktivitas
                    </a>
                </li>

                <li class="{{ request()->is('admin/ulasans*') ? 'active' : '' }}">
                    <a href="{{ route('admin.ulasans.index') }}">
                        <img src="{{ asset('admin/assets/images/icons/ulasan.svg') }}"
                            style="width:20px;margin-right:12px" alt="Ulasan">
                        Ulasan
                    </a>
                </li>

                <li class="{{ request()->is('admin/laporan.keuntungan') ? 'active' : '' }}">
                    <a href="{{ route('admin.laporan.keuntungan') }}" aria-label="Laporan Keuntungan">
                        <img src="{{ asset('admin/assets/images/icons/validasi-produk.svg') }}"
                            style="width:20px;margin-right:12px" alt="Icon Validasi Produk">
                        Laporan Keuntungan
                    </a>
                </li>
                {{-- Notifikasi - Admin --}}
                <li class="{{ request()->is('admin/notifikasi*') ? 'active' : '' }}">
                    <a href="{{ route('admin.notifikasi.index') }}">
                        <img src="{{ asset('admin/assets/images/icons/notification.svg') }}" alt="Notifikasi"
                            style="width:20px;margin-right:12px">
                        Notifikasi
                    </a>
                </li>


                <li>
                    <form action="{{ route('logout') }}" method="POST" style="margin:0; padding:0;">
                        @csrf
                        <button type="submit" class="sidebar-logout-btn"
                            style="background:none; border:0; padding:10px 18px; width:100%; text-align:left; display:flex; align-items:center; gap:12px; cursor:pointer;">
                            <img src="{{ asset('admin/assets/images/icons/logout.svg') }}" alt="Logout"
                                style="width:20px; height:20px; display:block; object-fit:contain;">
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
</aside>
@endif