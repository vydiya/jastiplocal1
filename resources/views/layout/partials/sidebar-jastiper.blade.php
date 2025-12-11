@if (Auth::check() && Auth::user()->role === 'jastiper')
    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">

            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">

                    {{-- Dashboard jastiper bisa diarahkan ke admin/dashboard dulu --}}
                    <li class="{{ request()->is('jastiper') || request()->is('jastiper/dashboard*') ? 'active' : '' }}">
                        <a href="{{ route('jastiper.dashboard.index') }}">
                            <img src="{{ asset('admin/assets/images/icons/dashboard.svg') }}"
                                style="width:20px;margin-right:12px" alt="Dashboard">
                            Dashboard
                        </a>
                    </li>

                    <li class="{{ request()->is('jastiper/pesanan*') ? 'active' : '' }}">
                        <a href="{{ route('jastiper.pesanan.index') }}">
                            <img src="{{ asset('admin/assets/images/icons/pesanan.svg') }}"
                                style="width:20px;margin-right:12px" alt="Pesanan">
                            Pesanan
                        </a>
                    </li>

                    <li class="{{ request()->is('jastiper/detail-pesanan*') ? 'active' : '' }}">
                        <a href="{{ route('jastiper.detail-pesanan.index') }}">
                            <img src="{{ asset('admin/assets/images/icons/pesanan.svg') }}"
                                style="width:20px;margin-right:12px" alt="Detail Pesanan">
                            Detail Pesanan
                        </a>
                    </li>

                    <li class="{{ request()->is('jastiper/barang*') ? 'active' : '' }}">
                        <a href="{{ route('jastiper.barang.index') }}">
                            <img src="{{ asset('admin/assets/images/icons/barang.svg') }}"
                                style="width:20px;margin-right:12px" alt="Barang">
                            Barang
                        </a>
                    </li>

                    {{-- <li class="{{ request()->is('jastiper/kategori-barang*') ? 'active' : '' }}">
                    <a href="{{ route('jastiper.kategori-barang.index') }}">
                        <img src="{{ asset('admin/assets/images/icons/barang.svg') }}"
                            style="width:20px;margin-right:12px" alt="Kategori Barang">
                        Kategori Barang
                    </a>
                </li> --}}

                    {{-- <li class="{{ request()->is('jastiper/rekening*') ? 'active' : '' }}">
                    <a href="{{ route('jastiper.rekening.index') }}">
                        <img src="{{ asset('admin/assets/images/icons/pembayaran.svg') }}"
                            style="width:20px;margin-right:12px" alt="Rekening">
                        Rekening
                    </a>
                </li> --}}

                    {{-- kalau jastiper juga bisa lihat ulasan --}}
                    <li class="{{ request()->is('jastiper/ulasans*') ? 'active' : '' }}">
                        <a href="{{ route('jastiper.ulasans.index') }}"> {{-- route khusus jastiper bisa dibuat nanti --}}
                            <img src="{{ asset('admin/assets/images/icons/ulasan.svg') }}"
                                style="width:20px;margin-right:12px" alt="Ulasan">
                            Ulasan
                        </a>
                    </li>
                    {{-- Notifikasi - Jastiper --}}
                    {{-- <li class="{{ request()->is('jastiper/notifikasi*') ? 'active' : '' }}">
                    <a href="{{ route('jastiper.notifikasi.index') }}">
                        <img src="{{ asset('admin/assets/images/icons/notification.svg') }}" alt="Notifikasi"
                            style="width:20px;margin-right:12px">
                        Notifikasi
                    </a>
                </li> --}}
                    <li class="{{ request()->is('jastiper/laporan*') ? 'active' : '' }}">
                        <a href="{{ route('jastiper.laporan-keuntungan.index') }}">
                            <img src="{{ asset('admin/assets/images/icons/laporan.svg') }}"
                                style="width:20px;margin-right:12px" alt="Laporan">
                            Laporan Penjualan
                        </a>
                    </li>
                    <li class="{{ request()->is('jastiper/profile*') ? 'active' : '' }}">
                        <a href="{{ route('jastiper.profile.index') }}">
                            <img src="{{ asset('admin/assets/images/icons/profil.svg') }}"
                                style="width:20px;margin-right:12px" alt="Profile">
                            Profile
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
