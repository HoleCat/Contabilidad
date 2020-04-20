<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.head')
<body>
    <div id="app">
        @include('layouts.nav')
        <div class="col-12 py-5"></div>
        @yield('content')
        <div class="col-12 py-5"></div>
    </div>
</body>
</html>
