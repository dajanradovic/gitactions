@section('title', $title)

<div class="card-header">
	<div class="card-title">
		<span class="card-icon">
			<i class="{{ $icon }}"></i>
		</span>
		<h3 class="card-label text-primary">
			{{ strtoupper($title) }}
			@isset($updated_at)
				<small title="{{ __('global.updated-at') }}" data-container="body" data-toggle="tooltip" data-placement="right" class="ml-5">
					<i class="fa fa-clock"></i> {{ formatLocalTimestamp($updated_at) }}
				</small>
			@endisset
		</h3>
	</div>

	<div class="card-toolbar">
		<a href="{{ url()->previous() }}" title="{{ __('global.back') }}" data-container="body" data-toggle="tooltip" data-placement="top" class="btn btn-icon btn-sm btn-hover-light-primary mr-1"><i class="la la-arrow-left"></i></a>
		<a href="{{ url()->full() }}" title="{{ __('global.refresh') }}" data-container="body" data-toggle="tooltip" data-placement="top" class="btn btn-icon btn-sm btn-hover-light-primary mr-1"><i class="la la-refresh"></i></a>
		<a href="#" data-card-tool="toggle" class="btn btn-icon btn-sm btn-hover-light-primary mr-1"><i class="la la-angle-down"></i></a>

		@isset($search)
			<?php

			$route = $search['route'] ?? Route::currentRouteName();
			$route_name = is_string($route) ? $route : key($route);
			$route_params = is_string($route) ? [] : $route[$route_name];

			?>

			@if(auth()->user()->canViewRoute($route_name, true))
				<a href="#" title="{{ __('global.search') }}" data-toggle="modal" data-target="#search-modal" class="btn btn-icon btn-sm btn-hover-light-primary mr-1"><i class="la la-search"></i></a>

				@include('layouts.modals.search', ['value' => $search['value'] ?? null, 'route' => route($route_name, $route_params), 'extra_fields' => $search['extra_fields'] ?? []])
			@endif
		@endisset

		@isset($actions)
			<div class="dropdown">
				<button type="button" class="btn btn-default btn-icon btn-sm btn-icon-md btn-hover-light-primary" title="{{ __('action-buttons.title') }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="flaticon-more-1"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-anim-up dropdown-menu-right">
					@include('layouts.action_buttons', compact('actions'))
				</div>
			</div>
		@endisset

		@if(isset($path) && auth()->user()->canViewRoute($path, true))
			<a href="{{ route($path) }}" class="btn btn-success ml-5">
				<i class="la la-plus"></i> {{ $add_button_label ?? __('global.add') }}
			</a>
		@endif
	</div>
</div>