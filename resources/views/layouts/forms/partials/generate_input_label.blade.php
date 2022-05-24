@if ($label ?? null)
	<label for="{{ $for ?? '' }}">
		@isset ($tooltip['left'])
			<i class="la la-info-circle" title="{!! $tooltip['left'] !!}" data-container="body" data-toggle="tooltip" data-html="true" data-placement="top"></i>
		@endisset

		{!! $label !!}

		@isset ($tooltip['right'])
			<i class="la la-info-circle" title="{!! $tooltip['right'] !!}" data-container="body" data-toggle="tooltip" data-html="true" data-placement="top"></i>
		@endisset
	</label>
@endif