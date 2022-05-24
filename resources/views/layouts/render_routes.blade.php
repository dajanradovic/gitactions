@foreach (($all_routes ?? []) as $key => $route)
	@if (is_array($route))
		<?php $div_id = mt_rand(); ?>

		<label class="checkbox checkbox-primary" title="{{ __('forms.toggle-all') }}">
			<input type="checkbox" data-toggle-nested-id="routes-{{ $div_id }}">
			<span></span>
			<strong>{{ $key }}</strong>
		</label>

		<div class="ml-10 mb-5" id="routes-{{ $div_id }}">
			@include('layouts.render_routes', ['all_routes' => $route, 'routes' => $routes])
		</div>

		@continue
	@endif

	<?php

	$name = $route->getName();
	$pretty_path = preg_replace('%(\{.+?\})%', '<code>$1</code>', $route->uri());
	$checked = $routes->where('route', $name)->isEmpty() ? '' : 'checked';

	?>

	<label class="checkbox checkbox-primary" data-container="body" data-toggle="popover" data-html="true" data-placement="left" title="{!! $pretty_path !!}" data-content='@include('layouts.method_badge', ['methods' => $route->methods()])'>
		<input type="checkbox" name="routes[]" value="{{ $name }}" {{ $checked }}>
		<span></span>
		{{ $key }}
	</label>
@endforeach