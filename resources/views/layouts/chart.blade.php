@if(auth()->user()->canViewRoute(key($api), true))
	<div class="{{ $width ?? 'col-sm-12' }}" id="{{ $id }}-container">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="{{ $icon }}"></i>
					</span>
					<h3 class="card-label text-primary">{{ $title }}</h3>
				</div>
				<div class="card-toolbar">
					<a href="javascript:;" data-chart-resize="{{ $id }}-container" title="{{ __('global.resize') }}" class="btn btn-sm btn-icon btn-clean btn-icon-md"><i class="la la-arrows-h"></i></a>
				</div>
			</div>
			<div class="card-body">
				<div id="{{ $id }}" class="chart-area" data-api="{{ route(key($api), current($api)) }}" data-title="{{ $title }}" data-value-provider="{{ $value_provider ?? 'value' }}" data-date-provider="{{ $date_provider ?? 'date' }}"></div>
			</div>
		</div>
	</div>
@endif