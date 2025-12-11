{{-- resources/views/layout/admin-app.blade.php --}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Admin Dashboard')</title>
    <meta name="description" content="@yield('meta_description','Ela Admin - HTML5 Admin Template')">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ICON --}}
    <link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="shortcut icon" href="https://i.imgur.com/QRAUqs9.png">

    {{-- External CSS (CDN) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">

    
    {{-- Template CSS dari public/admin/assets/css --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/css/cs-skin-elastic.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom-dashboard.css') }}">
<!-- Font Awesome 5 (untuk icon fas fa-trash, fas fa-check, fas fa-bell-slash, dll) -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
      integrity="sha512-dymI4R4Ggw7M8O6exdFv+DE5jBqFaUG2RglN6E466+vWXTjhBMWrMUR3pvN8I2c2MvmFo0bKjrwG7Y9OZz1xkQ=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- optional css push --}}
    @stack('styles')

    <style>
        #weatherWidget .currentDesc { color: #ffffff!important; }
        .traffic-chart { min-height: 335px; }
        #flotPie1  { height: 150px; }
        #flotPie1 td { padding:3px; }
        #flotPie1 table { top: 20px!important; right: -10px!important; }
        .chart-container { display: table; min-width: 270px ; text-align: left; padding-top: 10px; padding-bottom: 10px; }
        #flotLine5  { height: 105px; }
        #flotBarChart { height: 150px; }
        #cellPaiChart{ height: 160px; }
    </style>

    @yield('head')
</head>
<body>
    {{-- Left Panel (sidebar) --}}
    @include('layout.partials.sidebar-admin')

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

    {{-- Template JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js"></script>
    <script src="{{ asset('admin/assets/js/main.js') }}"></script>

    {{-- Chart libs (CDN) --}}
    <script src="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.js"></script>

    @stack('scripts')
</body>
</html>
