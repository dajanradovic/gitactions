<link rel="canonical" href="{{ url()->current() }}">

<meta name="twitter:card" content="summary">
<meta property="og:type" content="website">
<meta property="og:locale" content="{{ app()->getLocale() }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ $meta['image'] ?? asset('img/logo.png') }}">
<meta property="og:title" content="{{ $meta['title'] ?? setting('app_name') }}">
<meta property="og:description" content="{{ $meta['description'] ?? setting('app_description') }}">
<meta name="description" content="{{ $meta['description'] ?? setting('app_description') }}">
