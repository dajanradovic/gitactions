@foreach (($options ?? []) as $key => $option)
	@if (is_array($option))
		<optgroup label="{{ $key }}">
			@include('layouts.forms.partials.generate_select_options', ['options' => $option, 'selected' => $selected])
		</optgroup>
	@else
		<option value="{{ $key }}" {{ in_array($key, $selected) ? 'selected' : '' }}>{{ $option }}</option>
	@endif
@endforeach