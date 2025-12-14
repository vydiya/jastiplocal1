@props(['isLoggedIn' => false, 'cartCount' => 0, 'searchValue' => '', 'userName' => 'Pengguna'])

@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Route;

    // Ambil notifikasi unread jika user login
    $notifications = $isLoggedIn ? auth()->user()->unreadNotifications : collect([]);
    $notifCount = $notifications->count();
@endphp

<style>
    /* ================================================= */
    /* GLOBAL & HEADER CONFIG */
    /* ================================================= */
    :root {
        --primary-color: #006FFF;
        --text-color: #4b5563;
        --border-color: #C1E0F4;
        --font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    header {
        background: #fff;
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.06);
        position: sticky;
        top: 0;
        z-index: 100;
        font-family: var(--font-family);
    }

    .header-container {
        max-width: 1180px;
        margin: 0 auto;
        padding: 0.8rem 1.4rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.2rem;
        flex-wrap: wrap;
    }

    /* LOGO & SEARCH */
    .logo { display: flex; align-items: center; gap: 8px; text-decoration: none; }
    .logo-img { height: 32px; }
    .logo-text { font-size: 1.5rem; font-weight: 700; color: var(--primary-color); }
    
    .search-bar { flex: 1; max-width: 460px; position: relative; }
    .search-bar input { width: 100%; padding: 0.65rem 1rem 0.65rem 2.8rem; border: 2px solid var(--border-color); border-radius: 40px; font-size: 0.93rem; outline: none; transition: .2s; }
    .search-bar input:focus { border-color: var(--primary-color); }
    .search-bar i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--primary-color); font-size: 0.9rem; }

    /* NAVIGATION AREA */
    .header-nav { display: flex; align-items: center; gap: 1.2rem; }
    .nav-icon-wrapper { display: flex; align-items: center; gap: 1.2rem; }
    
    .icon-link { 
        font-size: 1.35rem; color: var(--text-color); position: relative; 
        transition: .2s; cursor: pointer; display: flex; align-items: center;
        text-decoration: none;
    }
    .icon-link:hover { color: var(--primary-color); }

    .badge-count {
        position: absolute; top: -6px; right: -8px; 
        background: #ff3131; color: #fff; border-radius: 50%; 
        padding: 1px 5px; font-size: 0.65rem; font-weight: bold; 
        min-width: 16px; text-align: center;
    }

    /* ================================================= */
    /* NOTIFIKASI STYLING (MODERN) */
    /* ================================================= */
    .notif-wrapper { position: relative; }
    
    .notif-menu {
        position: absolute; top: 150%; right: -70px; width: 340px;
        background: white; border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15); border: 1px solid #f0f0f0;
        display: none; flex-direction: column; z-index: 200; overflow: hidden;
        animation: fadeIn 0.15s ease-out;
    }
    .notif-wrapper.active .notif-menu { display: flex; }

    .notif-header {
        padding: 14px 16px; font-weight: 700; border-bottom: 1px solid #eee;
        color: var(--text-color); display: flex; justify-content: space-between; align-items: center;
        background: #fff;
    }

    .notif-body { max-height: 380px; overflow-y: auto; background: #fff; }

    /* Item Container */
    .notif-item-container {
        position: relative;
        border-bottom: 1px solid #f5f5f5;
        transition: all 0.3s ease;
    }
    
    .notif-item-container:hover { background: #f9fbff; }
    
    /* Tombol Checklist Kecil (Floating) */
    .btn-mark-read {
        position: absolute; top: 12px; right: 12px;
        width: 24px; height: 24px; border-radius: 50%;
        background: #fff; border: 1px solid #ddd;
        color: #bbb; display: flex; align-items: center; justify-content: center;
        cursor: pointer; z-index: 10; font-size: 0.75rem;
        transition: .2s;
    }
    .btn-mark-read:hover { background: var(--primary-color); color: white; border-color: var(--primary-color); }
    .btn-mark-read:active { transform: scale(0.9); }

    /* Link Item */
    .notif-item {
        padding: 14px 16px; display: flex; flex-direction: column; gap: 4px;
        text-decoration: none; padding-right: 45px; /* Ruang utk tombol checklist */
    }
    .notif-item.unread { background: #fffbf2; } /* Warna background item unread */
    .notif-item-container:hover .notif-item.unread { background: #f0f7ff; }

    .notif-title { font-size: 0.9rem; font-weight: 700; color: var(--primary-color); }
    .notif-text { font-size: 0.85rem; color: #555; line-height: 1.4; }
    .notif-time { font-size: 0.75rem; color: #999; margin-top: 5px; align-self: flex-start; }

    /* Footer (Mark All) */
    .notif-footer {
        padding: 12px; text-align: center; border-top: 1px solid #eee; background: #fbfbfb;
    }
    .btn-mark-all {
        font-size: 0.85rem; color: var(--primary-color); font-weight: 600;
        background: none; border: none; cursor: pointer; text-decoration: none;
    }
    .btn-mark-all:hover { text-decoration: underline; }

    /* Empty State */
    .notif-empty { padding: 40px 20px; text-align: center; color: #aaa; }
    
    /* Visual Effect saat dihapus */
    .fade-out { opacity: 0; transform: translateX(20px); pointer-events: none; }

    /* DROPDOWN PROFILE */
    .profile-dropdown { position: relative; }
    .profile-dropdown-toggle { display: flex; align-items: center; gap: 0.35rem; cursor: pointer; color: var(--primary-color); font-weight: 600; font-size: 0.95rem; transition: .2s; }
    .profile-dropdown-toggle:hover { opacity: 0.9; }
    .profile-icon { font-size: 1.45rem; }
    .profile-dropdown-menu { position: absolute; top: 140%; right: 0; background: white; border-radius: 10px; width: 180px; box-shadow: 0 3px 12px rgba(0, 0, 0, 0.12); display: none; flex-direction: column; overflow: hidden; z-index: 200; }
    .profile-dropdown.active .profile-dropdown-menu { display: flex; }
    .profile-dropdown-menu a, .profile-dropdown-menu button { padding: 0.75rem 1rem; text-align: left; font-size: 0.92rem; border: none; background: none; width: 100%; color: var(--text-color); cursor: pointer; display: flex; gap: 0.6rem; align-items: center; text-decoration: none; }
    .profile-dropdown-menu a:hover, .profile-dropdown-menu button:hover { background: #F3F8FF; color: var(--primary-color); }
    
    .login-btn { background: var(--primary-color); color: white !important; padding: 0.55rem 1.2rem; border-radius: 40px; font-size: 0.92rem; font-weight: 600; border: 2px solid var(--primary-color); white-space: nowrap; transition: .2s; text-decoration: none; }
    .login-btn:hover { background: #005ce6; }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
    @media (max-width: 768px) { .header-container { flex-direction: column; align-items: flex-start; } .search-bar { order: 3; width: 100%; } .header-nav { width: 100%; justify-content: flex-end; } .notif-menu { right: -40px; width: 300px; } }
</style>

<header>
    <div class="header-container">
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('admin/assets/images/logo.png') }}" alt="Logo" class="logo-img">
            <span class="logo-text">JASTGO</span>
        </a>

        <form action="{{ route('home') }}" method="GET" class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Cari produk favoritmu..." value="{{ $searchValue }}">
        </form>

        <nav class="header-nav">
            <div class="nav-icon-wrapper">

                 <a href="{{ Route::has('tentang-kami') ? route('tentang-kami') : '#' }}" class="icon-link" title="Tentang Kami">
                    <i class="fas fa-info-circle"></i>
                </a>

                <a href="{{ Route::has('cara-belanja') ? route('cara-belanja') : '#' }}" class="icon-link" title="Bantuan / Cara Belanja">
                    <i class="fas fa-question-circle"></i>
                </a>

                <a href="{{ $isLoggedIn ? route('keranjang.index') : route('login') }}" class="icon-link" title="Keranjang">
                    <i class="fas fa-shopping-cart"></i>
                    @if ($cartCount > 0)
                        <span class="badge-count">{{ $cartCount }}</span>
                    @endif
                </a>

                @if ($isLoggedIn)
                    <div class="notif-wrapper" id="notifDropdown">
                        <div class="icon-link notif-toggle" title="Notifikasi">
                            <i class="fas fa-bell"></i>
                            @if ($notifCount > 0)
                                <span class="badge-count" id="notifBadge">{{ $notifCount }}</span>
                            @endif
                        </div>

                        <div class="notif-menu">
                            <div class="notif-header">
                                <span>Notifikasi</span>
                                <small style="color: var(--primary-color);" id="notifHeaderCount">
                                    {{ $notifCount > 0 ? $notifCount . ' Baru' : '' }}
                                </small>
                            </div>

                            <div class="notif-body" id="notifList">
                                @forelse($notifications as $notif)
                                    <div class="notif-item-container" id="notif-item-{{ $notif->id }}">
                                        
                                        <button class="btn-mark-read" 
                                                title="Tandai sudah dibaca" 
                                                onclick="markAsRead('{{ $notif->id }}', event)">
                                            <i class="fas fa-check"></i>
                                        </button>

                                        <a href="{{ isset($notif->data['pesanan_id']) ? route('pesanan.riwayat') : '#' }}" 
                                           class="notif-item unread"
                                           onclick="markAsRead('{{ $notif->id }}')">
                                            
                                            <div class="notif-title">
                                                {{ $notif->data['jenis_notifikasi'] ?? 'Info Sistem' }}
                                            </div>
                                            <div class="notif-text">
                                                {!! Str::markdown($notif->data['pesan'] ?? '') !!}
                                            </div>
                                            <div class="notif-time">
                                                <i class="far fa-clock" style="font-size: 0.7rem; margin-right:3px;"></i>
                                                {{ $notif->created_at->diffForHumans() }}
                                            </div>
                                        </a>
                                    </div>
                                @empty
                                    <div class="notif-empty" id="notifEmptyState">
                                        <i class="far fa-bell-slash" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.4;"></i>
                                        <p>Tidak ada notifikasi baru</p>
                                    </div>
                                @endforelse
                            </div>

                            @if($notifCount > 0)
                                <div class="notif-footer" id="notifFooter">
                                    <button onclick="markAllAsRead()" class="btn-mark-all">
                                        Tandai Semua Dibaca
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                @if ($isLoggedIn)
                    <div class="profile-dropdown" id="profileDropdown">
                        <div class="profile-dropdown-toggle">
                            <i class="fas fa-user-circle profile-icon"></i>
                            <span>{{ Str::limit($userName, 7, '..') }}</span>
                            <i class="fas fa-chevron-down" style="font-size: .8rem;"></i>
                        </div>

                        <div class="profile-dropdown-menu">
                            <a href="{{ route('pesanan.riwayat') }}">
                                <i class="fas fa-receipt"></i> Pesanan Saya
                            </a>
                            <a href="{{ route('jastiper.register.create') }}">
                                <i class="fas fa-shop"></i> Daftar Jastiper
                            </a>
                            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="login-btn">Login</a>
                @endif
            </div>
        </nav>
    </div>
</header>

<script>
    // Ambil token CSRF untuk request AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // 1. FUNGSI TANDAI SATU NOTIF
    function markAsRead(id, event = null) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        // Optimistic UI Update (Visual dulu biar cepat)
        const item = document.getElementById(`notif-item-${id}`);
        if (item) {
            item.classList.add('fade-out');
            setTimeout(() => { 
                item.remove(); 
                updateBadgeCount(-1); 
                checkEmptyState();    
            }, 300);
        }

        // Kirim Request ke Backend
        fetch(`/notifikasi/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            console.log('Success:', data.message);
        })
        .catch(err => console.error("Gagal update notif:", err));
    }

    // 2. FUNGSI TANDAI SEMUA
    function markAllAsRead() {
        // UI Update langsung
        const list = document.getElementById('notifList');
        const footer = document.getElementById('notifFooter');
        const badge = document.getElementById('notifBadge');
        const headerCount = document.getElementById('notifHeaderCount');

        if(list) list.innerHTML = ''; 
        if(footer) footer.remove();
        if(badge) badge.remove();
        if(headerCount) headerCount.innerText = '';
        
        checkEmptyState();

        // Request Backend
        fetch('{{ route("notifikasi.markAllAsRead") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .catch(err => console.error("Gagal mark all:", err));
    }

    // 3. HELPER: UPDATE JUMLAH BADGE
    function updateBadgeCount(amount) {
        const badge = document.getElementById('notifBadge');
        const headerCount = document.getElementById('notifHeaderCount');
        
        if (badge) {
            let current = parseInt(badge.innerText);
            let total = current + amount;

            if (total > 0) {
                badge.innerText = total;
                if(headerCount) headerCount.innerText = total + " Baru";
            } else {
                badge.remove();
                if(headerCount) headerCount.innerText = "";
            }
        }
    }

    // 4. HELPER: CEK KOSONG
    function checkEmptyState() {
        const list = document.getElementById('notifList');
        if (list && list.children.length === 0) {
            list.innerHTML = `
                <div class="notif-empty">
                    <i class="far fa-bell-slash" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.4;"></i>
                    <p>Tidak ada notifikasi baru</p>
                </div>
            `;
            const footer = document.getElementById('notifFooter');
            if(footer) footer.remove();
        }
    }

    // 5. DROPDOWN TOGGLE LOGIC
    document.addEventListener("DOMContentLoaded", () => {
        const notifWrapper = document.getElementById("notifDropdown");
        const profileWrapper = document.getElementById("profileDropdown");

        // Toggle Notif
        if (notifWrapper) {
            notifWrapper.querySelector(".notif-toggle").addEventListener("click", (e) => {
                e.stopPropagation();
                if(profileWrapper) profileWrapper.classList.remove("active");
                notifWrapper.classList.toggle("active");
            });
        }

        // Toggle Profile
        if (profileWrapper) {
            profileWrapper.querySelector(".profile-dropdown-toggle").addEventListener("click", (e) => {
                e.stopPropagation();
                if(notifWrapper) notifWrapper.classList.remove("active");
                profileWrapper.classList.toggle("active");
            });
        }

        // Klik Luar -> Tutup Semua
        document.addEventListener("click", (e) => {
            if (notifWrapper && !notifWrapper.contains(e.target)) notifWrapper.classList.remove("active");
            if (profileWrapper && !profileWrapper.contains(e.target)) profileWrapper.classList.remove("active");
        });
    });
</script>