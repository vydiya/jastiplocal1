<header class="header">
    <div class="left">
        <h2 class="page-title">@yield('page-title', 'Dashboard')</h2>
    </div>

    <div class="right">
        <div class="search">
            <input type="text" placeholder="What do you want eat today...">
        </div>

        <!-- Theme toggle button -->
        <button id="btn-theme-toggle" class="btn-toggle" title="Toggle theme">
            Toggle Theme
        </button>

        <div class="avatar">
            <img src="{{ asset('user/assets/images/avatar.jpg') }}" alt="user" />
        </div>
    </div>
</header>
