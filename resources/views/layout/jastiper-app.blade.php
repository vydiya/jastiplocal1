{{-- resources/views/layout/jastiper-app.blade.php --}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Jastiper Dashboard')</title>
    <meta name="description" content="@yield('meta_description','Ela Admin - Jastiper Dashboard')">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ICON --}}
    <link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="shortcut icon" href="https://i.imgur.com/QRAUqs9.png">

    {{-- External CSS (CDN) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">

    {{-- Template CSS --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/css/cs-skin-elastic.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-dashboard.css') }}">

    @stack('styles')
    @yield('head')
</head>
<body>
    {{-- Left Panel (sidebar) khusus Jastiper --}}
    @include('layout.partials.sidebar-jastiper')

    {{-- Right Panel (header + content + footer) --}}
    <div id="right-panel" class="right-panel">
        @include('layout.partials.header')

        <div class="content">
            <div class="animated fadeIn">
                @yield('content')
            </div>
        </div>

        @include('layout.partials.footer')
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>

    <script src="{{ asset('admin/assets/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
