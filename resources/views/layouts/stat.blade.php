<?php

if (isset($route)) {
	$route_name = is_string($route) ? $route : key($route);
	$route_params = is_string($route) ? [] : $route[$route_name];
}

?>

<a href="{{ isset($route) && auth()->user()->canViewRoute($route_name) ? route($route_name, $route_params) : 'javascript:;' }}" class="{{ $width ?? 'col-sm-4' }}">
	<div class="card card-custom bg-{{ $color ?? 'primary' }} bg-hover-state-{{ $color ?? 'primary' }} gutter-b">
		<div class="card-body">
			<i class="text-inverse-{{ $color ?? 'primary' }} font-size-h2 {{ $icon }}"></i>
			<div class="text-inverse-{{ $color ?? 'primary' }} font-weight-bolder font-size-h1 mt-3">{{ $title }}</div>
			<span class="text-inverse-{{ $color ?? 'primary' }} font-weight-bold font-size-lg mt-1">{{ strtoupper($subtitle) }}</span>
		</div>
	</div>
</a>