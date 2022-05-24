<div class="alert alert-custom alert-notice alert-light-{{ $state ?? 'primary' }} fade show" role="alert">
	<div class="alert-icon"><i class="{{ $icon }}"></i></div>
	<div class="alert-text">{!! $text !!}</div>
	<div class="alert-close">
		<button type="button" class="close" data-dismiss="alert" aria-label="{{ __('global.close') }}" title="{{ __('global.close') }}">
			<span aria-hidden="true">
				<i class="la la-close"></i>
			</span>
		</button>
	</div>
</div>