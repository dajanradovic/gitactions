<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<head>
		@include('layouts.header')

		@includeWhen(($seo ?? null) !== false, 'layouts.seo', ['meta' => $seo ?? null])

		<link rel="stylesheet" type="text/css" href="{{ asset('metronic/css/pages/login/classic/login-2.css') }}">

		@stack('scripts')
	</head>
	<body>
		<div class="d-flex flex-column flex-root">
			@yield('content')
		</div>
	</body>
</html>
