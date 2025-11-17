<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Dashboard') - GoMeal</title>

    <!-- fonts (pastikan ada di public/user/assets/fonts) -->
    <link rel="stylesheet" href="{{ asset('user/assets/css/style.css') }}">
</head>

<body class="theme-yellow"> {{-- default: theme-yellow atau theme-blue --}}
    <div class="app">
        @include('user.layout.sidebar')
        <div class="main-content">
            @include('user.layout.header')
            <main class="page-body">
                @yield('content')
            </main>
            @include('user.layout.footer')
        </div>
        @include('user.layout.rightpanel')
    </div>

    <script src="{{ asset('user/assets/js/app.js') }}"></script>
</body>

</html>
