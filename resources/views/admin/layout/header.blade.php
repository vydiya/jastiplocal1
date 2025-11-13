{{-- resources/views/admin/layout/header.blade.php --}}
<header id="header" class="header">
    <div class="top-left">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ url('/admin/dashboard') }}">
                <img src="{{ asset('admin/assets/images/logo.png') }}" alt="Logo"
                    style="height:30px; object-fit:contain;">
                <span style="font-size:20px; font-weight:600; color:#ffd500; margin-left:8px;">
                    JastGo
                </span>
            </a>

            <a class="navbar-brand hidden" href="{{ url('/admin/dashboard') }}">
                <img src="{{ asset('admin/assets/images/logo.png') }}" alt="Logo" style="height:30px;">
            </a>
        </div>
    </div>

    <div class="top-right">
        <div class="header-menu d-flex align-items-center" style="gap:18px;">

            {{-- SEARCH ICON (hanya tampilan) --}}
            <div class="header-icon" title="Search" style="cursor:pointer;">
                <img src="{{ asset('admin/assets/images/icons/search.svg') }}" alt="Search"
                     style="width:22px; display:block;">
            </div>

            {{-- NOTIFICATION ICON (hanya tampilan) --}}
            <div class="header-icon position-relative" title="Notifications" style="cursor:pointer;">
                <img src="{{ asset('admin/assets/images/icons/notification.svg') }}" alt="Notification"
                     style="width:22px; display:block;">
                <span style="
                    position:absolute;
                    top:-4px;
                    right:-6px;
                    background:#e63946;
                    color:#fff;
                    font-size:10px;
                    height:16px;
                    width:16px;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    border-radius:50%;
                    font-weight:600;
                ">3</span>
            </div>

            {{-- PROFILE AVATAR (klik buka modal edit avatar) --}}
            <div class="header-profile" style="display:flex; align-items:center;">
                <a href="#" id="profileBtn" title="Edit Profile"
                   data-toggle="modal" data-target="#editAvatarModal"
                   style="display:flex; align-items:center;">
                    <img id="headerAvatar"
                         src="{{ Auth::check() ? (Auth::user()->avatar_url ?? asset('admin/assets/images/avatar.png')) : asset('admin/assets/images/avatar.png') }}"
                         alt="User"
                         style="width:36px; height:36px; border-radius:50%; object-fit:cover; border:2px solid #fff;">
                </a>
            </div>
        </div>
    </div>
</header>

{{-- Modal Edit Avatar (tetap di sini agar mudah, Bootstrap akan memunculkannya) --}}
<div class="modal fade" id="editAvatarModal" tabindex="-1" role="dialog" aria-labelledby="editAvatarLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <form id="avatarForm" method="POST" action="{{ route('admin.profile.updateAvatar') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title" id="editAvatarLabel">Edit Foto Profil</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
        </div>

        <div class="modal-body text-center">
          <img id="previewAvatar"
               src="{{ Auth::check() ? (Auth::user()->avatar_url ?? asset('admin/assets/images/avatar.png')) : asset('admin/assets/images/avatar.png') }}"
               alt="Preview"
               style="width:120px;height:120px;border-radius:50%;object-fit:cover;margin-bottom:10px;">

          <div class="form-group">
            <input type="file" name="avatar" id="avatarInput" accept="image/*" class="form-control-file" required>
            <small class="form-text text-muted">Format: jpg, png. Max 2MB.</small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Unggah</button>
        </div>

      </div>
    </form>
  </div>
</div>
