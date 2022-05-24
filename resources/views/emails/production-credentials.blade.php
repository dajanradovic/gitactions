<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<head>
		<title>{{ __('emails.production-credentials', ['app_name' => setting('app_name')]) }}</title>
		<meta charset="utf-8">
		<meta http-equiv="Content-Type" content="text/html">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">

		<style>
			body {
				font-family: Poppins, sans-serif;
			}

			.hidden {
				background: #000;
				color: #000;
			}

			.hidden:hover{
				background: #fff;
			}

			.hidden a{
				color: #000;
				text-decoration: none;
			}
		</style>
	</head>
	<body>
		<a href="{{ config('app.url') }}" target="_blank">
			<img src="{{ asset('img/logo.png') }}" alt="{{ setting('app_name') }}">
		</a>

		<p>The app is live, deployed in production and here are seeded admin credentials:</p>
		<p><strong>NOTE: these should be added to mastersheet -> CMS sheet immediately.</strong></p>

		<ul>
			<li>Domain: <a href="{{ config('app.url') }}" target="_blank">{{ config('app.url') }}</a></li>
			<li>E-mail: <span class='hidden'><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></span></li>
			<li>Password: <span class='hidden'>{{ $password }}</span></li>
		</ul>
	</body>
</html>
