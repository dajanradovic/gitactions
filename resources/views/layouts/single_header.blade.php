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

		<div class="btn-group ml-5">
			<button type="submit" form="main-form" class="btn btn-success">
				<i class="{{ $save_icon ?? 'la la-save' }}"></i> {{ $save_label ?? __('forms.save') }}
			</button>

			<button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>

			<div class="dropdown-menu dropdown-menu-anim-up dropdown-menu-right">
				@if($save_and_return ?? true)
					<button type="submit" class="dropdown-item" form="main-form" name="save_and_return">
						<i class="{{ $save_icon ?? 'la la-save' }}"></i>&nbsp;
						<span class="text-success">{{ $save_and_return_label ?? __('forms.save-and-return') }}</span>
					</button>
				@endif

				<button type="reset" class="dropdown-item" form="main-form">
					<i class="la la-undo-alt"></i>&nbsp;
					{{ __('forms.reset') }}
				</button>

				@isset($actions)
					<div class="dropdown-divider"></div>
					@include('layouts.action_buttons', compact('actions'))
				@endisset
			</div>
		</div>
	</div>
</div>