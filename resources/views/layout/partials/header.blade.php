<header id="header" class="header">
    @php
        use Illuminate\Support\Str;
        use Illuminate\Support\Facades\Auth;

        // 1. Ambil User yang sedang login
        $user = Auth::user();

        // 2. Ambil data profil Jastiper-nya
        // Asumsi: Di model User ada relasi 'public function jastiper() { return $this->hasOne(Jastiper::class); }'
        $jastiper = $user->jastiper;

        // 3. Logika Pengambilan Notifikasi
        // Jika dia punya profil Jastiper, ambil notifikasi milik Jastiper.
        // Jika tidak, kosongkan koleksi.
        if ($jastiper) {
            $notifications = $jastiper->unreadNotifications;
        } else {
            $notifications = collect([]);
        }

        $notifCount = $notifications->count();
    @endphp
    <style>
        /* Memastikan container kanan menggunakan flexbox dan rata tengah vertikal */
        .header-menu {
            display: flex;
            align-items: center;
            /* KUNCI AGAR CENTER VERTIKAL */
            justify-content: flex-end;
            height: 100%;
            /* Mengisi tinggi header */
            gap: 25px;
            /* Jarak antar elemen (Notif dengan Profile) */
            padding-right: 15px;
        }

        /* --- Styling Wrapper Notifikasi --- */
        .notif-wrapper-admin {
            position: relative;
            height: 100%;
            display: flex;
            align-items: center;
        }

        .notif-icon-btn {
            position: relative;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: background 0.3s;
            color: #555;
            display: flex;
            /* Agar icon di dalamnya center */
            align-items: center;
            justify-content: center;
        }

        .notif-icon-btn:hover,
        .notif-wrapper-admin.active .notif-icon-btn {
            background: rgba(0, 0, 0, 0.05);
            color: #006FFF;
        }

        .notif-icon-btn i {
            font-size: 1.3rem;
            /* Ukuran icon lonceng */
        }

        /* Badge Merah */
        .badge-counter {
            position: absolute;
            top: 2px;
            right: 2px;
            background: #ff3131;
            color: white;
            font-size: 0.65rem;
            font-weight: bold;
            padding: 2px 5px;
            border-radius: 10px;
            border: 2px solid #fff;
            min-width: 18px;
            text-align: center;
        }

        /* --- Styling User Profile --- */
        /* Saya hapus margin-top:15px yang bikin berantakan tadi */
        .user-profile-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-weight: 500;
            color: #000;
            height: 100%;
            padding: 5px 10px;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .user-profile-wrapper:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        /* Menambahkan icon user kecil biar lebih manis */
        .user-avatar-small {
            width: 32px;
            height: 32px;
            background: #f0f2f5;
            color: #006FFF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        /* --- Styling Dropdown (Sama seperti sebelumnya) --- */
        .notif-dropdown-menu {
            position: absolute;
            top: 90%;
            /* Muncul sedikit di bawah header */
            right: 0;
            width: 320px;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            border: 1px solid #eee;
            display: none;
            flex-direction: column;
            z-index: 999;
            overflow: hidden;
        }

        .notif-wrapper-admin.active .notif-dropdown-menu {
            display: flex;
        }

        .notif-list-container {
            max-height: 300px;
            overflow-y: auto;
        }

        .notif-item-wrap {
            position: relative;
            border-bottom: 1px solid #f5f5f5;
        }

        .notif-item-wrap:hover {
            background: #f9f9f9;
        }

        .notif-link-item {
            display: block;
            padding: 12px 15px;
            text-decoration: none;
            color: inherit;
            padding-right: 40px;
        }

        .btn-check-mark {
            position: absolute;
            top: 12px;
            right: 10px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ccc;
            cursor: pointer;
            transition: .2s;
        }

        .btn-check-mark:hover {
            background: #006FFF;
            border-color: #006FFF;
            color: white;
        }

        .notif-footer-btn {
            padding: 10px;
            text-align: center;
            border-top: 1px solid #eee;
            background: #fff;
            cursor: pointer;
            color: #006FFF;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .empty-notif {
            padding: 30px;
            text-align: center;
            color: #999;
        }

        .fade-effect {
            opacity: 0;
            transition: 0.3s;
        }
    </style>

    <div class="top-left">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ url('/admin/dashboard') }}">
                <img src="{{ asset('admin/assets/images/logo.png') }}" alt="Logo"
                    style="height:30px; object-fit:contain;">
                <span style="font-size:20px; font-weight:500; color:#ffd500; margin-left:8px;">
                    JastGo
                </span>
            </a>
            <a class="navbar-brand hidden" href="{{ url('/admin/dashboard') }}">
                <img src="{{ asset('admin/assets/images/logo.png') }}" alt="Logo" style="height:30px;">
            </a>
        </div>
    </div>

    <div class="top-right">
        <div class="header-menu">

            <div class="notif-wrapper-admin" id="adminNotifTrigger">
                <div class="notif-icon-btn">
                    <i class="fa fa-bell"></i>
                    @if ($notifCount > 0)
                        <span class="badge-counter" id="admBadge">{{ $notifCount }}</span>
                    @endif
                </div>

                <div class="notif-dropdown-menu" id="admDropdown">
                    <div style="padding: 12px 15px; border-bottom:1px solid #eee; font-weight:bold;">
                        Notifikasi <small style="color:#006FFF"
                            id="admHeadCount">{{ $notifCount > 0 ? $notifCount . ' Baru' : '' }}</small>
                    </div>

                    <div class="notif-list-container" id="admList">
                        @forelse($notifications as $notif)
                            <div class="notif-item-wrap" id="adm-item-{{ $notif->id }}">
                                <div class="btn-check-mark" onclick="markOne('{{ $notif->id }}', event)"><i
                                        class="fa fa-check"></i></div>
                                <a href="{{ isset($notif->data['pesanan_id']) ? route('pesanan.riwayat') : '#' }}"
                                    class="notif-link-item" onclick="markOne('{{ $notif->id }}')">
                                    <div style="font-weight:bold; color:#006FFF; font-size:0.9rem;">
                                        {{ $notif->data['jenis_notifikasi'] ?? 'Info' }}</div>
                                    <div style="font-size:0.85rem; color:#555; margin:2px 0;">{!! Str::markdown($notif->data['pesan'] ?? '') !!}
                                    </div>
                                    <small style="color:#999;">{{ $notif->created_at->diffForHumans() }}</small>
                                </a>
                            </div>
                        @empty
                            <div class="empty-notif" id="admEmpty"><i class="fa fa-bell-slash"
                                    style="font-size:1.5rem; margin-bottom:5px;"></i><br>Tidak ada notifikasi baru</div>
                        @endforelse
                    </div>

                    @if ($notifCount > 0)
                        <div class="notif-footer-btn" id="admFooter" onclick="markAll()">Tandai Semua Dibaca</div>
                    @endif
                </div>
            </div>

            <div class="user-profile-wrapper">
                <div class="user-avatar-small">
                    @if (Auth::check() &&
                            Auth::user()->role === 'jastiper' &&
                            Auth::user()->jastiper &&
                            !empty(Auth::user()->jastiper->profile_toko))
                        <img src="{{ asset('storage/' . Auth::user()->jastiper->profile_toko) }}" alt="Toko"
                            style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                    @else
                        <i class="fa fa-user"></i>
                    @endif
                </div>

                <span>{{ Auth::check() ? Auth::user()->name : 'Guest' }}</span>
            </div>

        </div>
    </div>
</header>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const trigger = document.getElementById("adminNotifTrigger");
        const dropdown = document.getElementById("admDropdown");

        // Toggle Dropdown
        if (trigger) {
            trigger.addEventListener("click", function(e) {
                if (e.target.closest('.notif-dropdown-menu')) return;
                trigger.classList.toggle("active");
            });
        }
        document.addEventListener("click", function(e) {
            if (trigger && !trigger.contains(e.target)) trigger.classList.remove("active");
        });

        // Fungsi Mark One
        window.markOne = function(id, evt = null) {
            if (evt) {
                evt.preventDefault();
                evt.stopPropagation();
            }
            const item = document.getElementById(`adm-item-${id}`);
            if (item) {
                item.classList.add('fade-effect');
                setTimeout(() => {
                    item.remove();
                    updBadge(-1);
                    chkEmpty();
                }, 300);
            }
            fetch(`/notifikasi/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Content-Type': 'application/json'
                }
            });
        };

        // Fungsi Mark All
        window.markAll = function() {
            document.getElementById("admList").innerHTML = '';
            document.getElementById("admFooter")?.remove();
            document.getElementById("admBadge")?.remove();
            document.getElementById("admHeadCount").innerText = '';
            chkEmpty();
            fetch('/notifikasi/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Content-Type': 'application/json'
                }
            });
        };

        function updBadge(n) {
            const b = document.getElementById("admBadge");
            const h = document.getElementById("admHeadCount");
            if (b) {
                let c = parseInt(b.innerText) + n;
                if (c > 0) {
                    b.innerText = c;
                    if (h) h.innerText = c + ' Baru';
                } else {
                    b.remove();
                    if (h) h.innerText = '';
                }
            }
        }

        function chkEmpty() {
            const l = document.getElementById("admList");
            if (l && l.children.length === 0) l.innerHTML =
                '<div class="empty-notif"><i class="fa fa-bell-slash" style="font-size:1.5rem; margin-bottom:5px;"></i><br>Tidak ada notifikasi baru</div>';
        }
    });
</script>
