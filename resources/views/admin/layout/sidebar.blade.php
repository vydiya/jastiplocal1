{{-- resources/views/admin/layout/sidebar.blade.php --}}
<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">

                {{-- 1. Dashboard --}}
                <li class="{{ request()->is('admin') || request()->is('admin/dashboard*') ? 'active' : '' }}">
                    <a href="{{ url('admin/dashboard') }}">
                        <img src="{{ asset('admin/assets/images/icons/dashboard.svg') }}" alt="Dashboard"
                            style="width:20px;margin-right:12px">
                        Dashboard
                    </a>
                </li>

                {{-- 2. Data Master --}}
                <li class="menu-title">DATA MASTER</li>
                <li class="{{ request()->is('admin/master/*') ? 'active' : '' }}">
                    <a href="{{ url('admin/master') }}">
                        <img src="{{ asset('admin/assets/images/icons/data-master.svg') }}" alt="Data Master"
                            style="width:20px;margin-right:12px">
                        Data Master
                    </a>
                    {{-- Jika mau dropdown sub-item (opsional), uncomment bagian bawah dan sesuaikan URL --}}
                    {{--
                    <ul class="sub-menu children">
                        <li><a href="{{ url('admin/master/users') }}"><img
                                    src="{{ asset('admin/assets/images/icons/users.svg') }}"
                                    style="width:16px;margin-right:8px">Data Pengguna</a></li>
                        <li><a href="{{ url('admin/master/merchants') }}"><img
                                    src="{{ asset('admin/assets/images/icons/merchants.svg') }}"
                                    style="width:16px;margin-right:8px">Data Jastiper</a></li>
                        <li><a href="{{ url('admin/master/products') }}"><img
                                    src="{{ asset('admin/assets/images/icons/products.svg') }}"
                                    style="width:16px;margin-right:8px">Data Produk</a></li>
                        <li><a href="{{ url('admin/master/payment-methods') }}"><img
                                    src="{{ asset('admin/assets/images/icons/payment-methods.svg') }}"
                                    style="width:16px;margin-right:8px">Metode Pembayaran</a></li>
                        <li><a href="{{ url('admin/master/categories') }}"><img
                                    src="{{ asset('admin/assets/images/icons/categories.svg') }}"
                                    style="width:16px;margin-right:8px">Kategori Produk</a></li>
                    </ul>
                    --}}
                </li>

                {{-- 3. Pesanan --}}
                <li class="{{ request()->is('admin/orders*') || request()->is('admin/pesanan*') ? 'active' : '' }}">
                    <a href="{{ url('admin/pesanan') }}">
                        <img src="{{ asset('admin/assets/images/icons/pesanan.svg') }}" alt="Pesanan"
                            style="width:20px;margin-right:12px">
                        Pesanan
                    </a>
                </li>

                {{-- 4. Pembayaran --}}
                <li
                    class="{{ request()->is('admin/payments*') || request()->is('admin/pembayaran*') ? 'active' : '' }}">
                    <a href="{{ url('admin/pembayaran') }}">
                        <img src="{{ asset('admin/assets/images/icons/pembayaran.svg') }}" alt="Pembayaran"
                            style="width:20px;margin-right:12px">
                        Pembayaran
                    </a>
                </li>

                {{-- 5. Transaksi --}}
                <li
                    class="{{ request()->is('admin/transaction*') || request()->is('admin/transaksi*') ? 'active' : '' }}">
                    <a href="{{ url('admin/transaction') }}">
                        <img src="{{ asset('admin/assets/images/icons/tarnsaksi.svg') }}" alt="Transaksi"
                            style="width:20px;margin-right:12px">
                        Transaksi
                    </a>
                </li>

                {{-- 6. Kelola Dana --}}
                <li class="{{ request()->is('admin/escrow*') || request()->is('admin/kelola-dana*') ? 'active' : '' }}">
                    <a href="{{ url('admin/kelola-dana') }}">
                        <img src="{{ asset('admin/assets/images/icons/kelola-dana.svg') }}" alt="Kelola Dana"
                            style="width:20px;margin-right:12px">
                        Kelola Dana
                    </a>
                </li>

                {{-- 7. Validasi Produk --}}
                <li
                    class="{{ request()->is('admin/validation*') || request()->is('admin/validasi-produk*') ? 'active' : '' }}">
                    <a href="{{ url('admin/validasi-produk') }}">
                        <img src="{{ asset('admin/assets/images/icons/validasi-produk.svg') }}" alt="Validasi Produk"
                            style="width:20px;margin-right:12px">
                        Validasi Produk
                    </a>
                </li>

                {{-- 8. Laporan --}}
                <li class="{{ request()->is('admin/reports*') || request()->is('admin/laporan*') ? 'active' : '' }}">
                    <a href="{{ url('admin/laporan') }}">
                        <img src="{{ asset('admin/assets/images/icons/laporan.svg') }}" alt="Laporan"
                            style="width:20px;margin-right:12px">
                        Laporan
                    </a>
                </li>

                {{-- 9. Bantuan --}}
                <li class="{{ request()->is('admin/help*') || request()->is('admin/bantuan*') ? 'active' : '' }}">
                    <a href="{{ url('admin/bantuan') }}">
                        <img src="{{ asset('admin/assets/images/icons/bantuan.svg') }}" alt="Bantuan"
                            style="width:20px;margin-right:12px">
                        Bantuan
                    </a>
                </li>

                {{-- 10. Logout --}}
                <li>
                    <a href="#"
                        onclick="alert('Logout belum diaktifkan. Nanti akan dibuatkan route logout.'); return false;">
                        <img src="{{ asset('admin/assets/images/icons/logout.svg') }}" alt="Logout"
                            style="width:20px;margin-right:12px">
                        Logout
                    </a>
                </li>


            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>
</aside>