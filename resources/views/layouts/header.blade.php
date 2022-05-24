<title>{{ count($errors) ? '('.count($errors).') ' : '' }}{{ setting('app_name') }} | @yield('title')</title>

<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="shortcut icon" type="image/png" href="{{ asset('img/favicon.png') }}">
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
<link rel="stylesheet" type="text/css" href="{{ asset('metronic/plugins/global/plugins.bundle.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('metronic/css/style.bundle.css') }}">

<script src="{{ asset('metronic/plugins/global/plugins.bundle.js') }}"></script>
<script defer src="{{ asset('metronic/js/scripts.bundle.js') }}"></script>

@vite
