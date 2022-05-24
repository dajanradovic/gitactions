<div class="card-footer">
	<button type="submit" class="btn btn-success" form="main-form">
		<i class="{{ $icon ?? 'la la-save' }}"></i> {{ $label ?? __('forms.save') }}
	</button>

	@if($save_and_return ?? true)
		<button type="submit" class="btn btn-success" form="main-form" name="save_and_return" value="{{ $save_and_return ?? '' }}">
			<i class="{{ $icon ?? 'la la-save' }}"></i> {{ $save_and_return_label ?? __('forms.save-and-return') }}
		</button>
	@endif

	<button type="reset" class="btn btn-secondary" form="main-form">
		<i class="la la-undo-alt"></i> {{ __('forms.reset') }}
	</button>
</div>