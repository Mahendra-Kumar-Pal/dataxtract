<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    @stack('style')
</head>
<body>
    <div class="wrapper">
        @yield('contents')
    </div>
    
    @stack('script')
</body>
</html>
