<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>
  <script src="{{ asset('assets/js/proyecto/efectos.js') }}" defer></script>
  <script src="{{ asset('assets/js/proyecto/tablas.js') }}" defer></script>
  <script src="{{ asset('assets/js/proyecto/vistas.js') }}" defer></script>
  <script src="{{ asset('assets/js/proyecto/config.js') }}" defer></script>
  <!-- Fonts -->

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>