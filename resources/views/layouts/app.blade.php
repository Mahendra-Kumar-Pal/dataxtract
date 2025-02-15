<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}" />
    @stack('style')
</head>

<body class="main">
    <div class="wrapper">
        @include('partial.navbar')
        @yield('contents')
        @include('partial.footer')
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/datatables.min.js') }}" defer></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    @stack('script')
</body>
</html>
