@foreach (($fields ?? []) as $field)
	@if (!($field['condition'] ?? true))
		@continue
	@endif

	<?php

	$tag = strtolower(trim($field['tag']));
	$label = $field['label'] ?? null;
	$tooltip = $field['tooltip'] ?? null;
	$help_message = $field['message'] ?? '';
	$attributes = $field['attributes'] ?? [];
	$id = $attributes['id'] ?? '';
	$name = $attributes['name'] ?? '';
	$form_group_class = $errors->first($name) ? 'form-group validated' : 'form-group';
	$input_class = $errors->first($name) ? 'form-control is-invalid' : 'form-control';

	?>

	@if (!isset($attributes['class']))
		<?php $attributes['class'] = $input_class; ?>
	@elseif (!empty($attributes['class']))
		<?php $attributes['class'] .= ' ' . $input_class; ?>
	@endif

	@switch ($tag)
		@case('input')
			<?php

			$value = isset($attributes['value']) ? trim($attributes['value']) : '';
			$attributes['value'] = old($name, htmlspecialchars($value, ENT_QUOTES));

			?>

			<div class="{{ $form_group_class }}">
				@include('layouts.forms.partials.generate_input_label', ['label' => $label, 'for' => $id, 'tooltip' => $tooltip])

				@if (isset($field['group']))
					<?php $field_group = $field['group'] ?>

					<div class="input-group">

						@if (isset($field_group['left']))
							<div class="input-group-prepend">
								<span class="input-group-text">{!! $field_group['left'] !!}</span>
							</div>
						@elseif (isset($field_group['left-button']))
							<div class="input-group-prepend">{!! $field_group['left-button'] !!}</div>
						@endif

						<input {!! stringifyAttr($attributes) !!}>

						@if (isset($field_group['right']))
							<div class="input-group-append">
								<span class="input-group-text">{!! $field_group['right'] !!}</span>
							</div>
						@elseif (isset($field_group['right-button']))
							<div class="input-group-append">{!! $field_group['right-button'] !!}</div>
						@endif
					</div>
				@else
					<input {!! stringifyAttr($attributes) !!}>
				@endif

				@error ($name)
					<div class="invalid-feedback">* {{ $errors->first($name) }}</div>
				@enderror

				<span class="form-text text-muted">{!! $help_message !!}</span>
			</div>

			@break

		@case('hidden')
			<?php

			$value = isset($attributes['value']) ? trim($attributes['value']) : '';
			$attributes['value'] = old($name, htmlspecialchars($value, ENT_QUOTES));

			?>

			<input {!! stringifyAttr($attributes) !!}>

			@break

		@case('textarea')
			<?php

			$value = isset($field['value']) ? trim($field['value']) : '';
			$value = old($name, $value);

			?>

			<div class="{{ $form_group_class }}">
				@include('layouts.forms.partials.generate_input_label', ['label' => $label, 'for' => $id, 'tooltip' => $tooltip])

				@if (isset($field['group']))
					<?php $field_group = $field['group'] ?>

					<div class="input-group">

						@if (isset($field_group['left']))
							<div class="input-group-prepend">
								<span class="input-group-text">{!! $field_group['left'] !!}</span>
							</div>
						@elseif (isset($field_group['left-button']))
							<div class="input-group-prepend">{!! $field_group['left-button'] !!}</div>
						@endif

						<textarea {!! stringifyAttr($attributes) !!}>{!! $value !!}</textarea>

						@if (isset($field_group['right']))
							<div class="input-group-append">
								<span class="input-group-text">{!! $field_group['right'] !!}</span>
							</div>
						@elseif (isset($field_group['right-button']))
							<div class="input-group-append">{!! $field_group['right-button'] !!}</div>
						@endif
					</div>
				@else
					<textarea {!! stringifyAttr($attributes) !!}>{!! $value !!}</textarea>
				@endif

				@error ($name)
					<div class="invalid-feedback">* {{ $errors->first($name) }}</div>
				@enderror

				<span class="form-text text-muted">{!! $help_message !!}</span>
			</div>

			@break

		@case('html')
			<?php

			$value = isset($field['value']) ? trim($field['value']) : '';
			$value = old($name, $value);
			$height = $field['height'] ?? 300;

			?>

			<div class="{{ $form_group_class }}">
				@include('layouts.forms.partials.generate_input_label', ['label' => $label, 'for' => $id, 'tooltip' => $tooltip])

				<div class="summernote" data-textarea="{{ $attributes['id'] }}" data-height="{{ $height }}">{!! $value !!}</div>
				<textarea hidden {!! stringifyAttr($attributes) !!}>{!! $value !!}</textarea>

				@error ($name)
					<div class="invalid-feedback">* {{ $errors->first($name) }}</div>
				@enderror

				<span class="form-text text-muted">{!! $help_message !!}</span>
			</div>

			@break

		@case('map')
			<?php

			$extra_fields = $field['extra_fields'] ?? [];
			$use_current = $field['use_current'] ?? true;
			$div_class = $use_current ? 'col-sm-5' : 'col-sm-6';
			$base_name = $field['base_name'];
			$lat = $field['lat'] ?? '';
			$lng = $field['lng'] ?? '';

			$fields_lat = [
				[
					'label' => __('forms.latitude'),
					'tag' => 'input',
					'attributes' => array_merge([
						'id' => $base_name . '_lat',
						'name' => $base_name . '_lat',
						'type' => 'number',
						'value' => $lat,
						'min' => -90,
						'max' => 90,
						'step' => 'any',
						'data-map-latlng-change' => $base_name
					], $attributes),
				],
			];

			$fields_lng = [
				[
					'label' => __('forms.longitude'),
					'tag' => 'input',
					'attributes' => array_merge([
						'id' => $base_name . '_lng',
						'name' => $base_name . '_lng',
						'type' => 'number',
						'value' => $lng,
						'min' => -180,
						'max' => 180,
						'step' => 'any',
						'data-map-latlng-change' => $base_name
					], $attributes),
				],
			];

			?>

			<div class="{{ $form_group_class }}">
				@include('layouts.forms.partials.generate_input_label', ['label' => $label, 'for' => $base_name, 'tooltip' => $tooltip])

				<div class="row">
					<div class="{{ $div_class }}">
						@include('layouts.forms.generate_form_fields', ['fields' => $fields_lat])
					</div>
					<div class="{{ $div_class }}">
						@include('layouts.forms.generate_form_fields', ['fields' => $fields_lng])
					</div>

					@if ($use_current)
						<div class="col-sm-2 m-auto" align="right">
							<button type="button" class="btn btn-primary" data-map-current-location-button="{{ $base_name }}">
								<i class="fa fa-map-marker-alt"></i> {{ __('forms.use-location') }}
							</button>
						</div>
					@endif
				</div>

				@include('layouts.forms.generate_form_fields', ['fields' => $extra_fields])

				<div class="form-group">
					<div class="map-area" data-basename="{{ $base_name }}"></div>
					<span class="form-text text-muted">{!! $help_message !!}</span>
				</div>
			</div>

			@break

		@case('checkbox')
		@case('radio')
			<?php

			$attributes['checked'] = old($name, $attributes['checked'] ?? false);

			?>

			<div class="{{ $form_group_class }}">
				@include('layouts.forms.partials.generate_input_label', ['label' => $label, 'tooltip' => $tooltip])

				<div>
					<span class="switch switch-icon">
						<label>
							<input {!! stringifyAttr($attributes) !!}><span></span>
						</label>
					</span>

					@error ($name)
						<div class="invalid-feedback">* {{ $errors->first($name) }}</div>
					@enderror

					<span class="form-text text-muted">{!! $help_message !!}</span>
				</div>
			</div>

			@break

		@case('checkbox-list')
		@case('radio-list')
		@case('checkbox-inline')
		@case('radio-inline')
			<?php

			$options = $field['options'] ?? [];
			$selected = (array) old($name, $field['selected'] ?? []);
			$type = strpos($tag, 'radio') === false ? 'checkbox' : 'radio';

			?>

			<div class="{{ $form_group_class }}">
				@include('layouts.forms.partials.generate_input_label', ['label' => $label, 'tooltip' => $tooltip])

				<div class="{{ $tag }}">
					@foreach ($options as $key => $value)
						<label class="{{ $type }} {{ $type }}-primary">
							<input {!! stringifyAttr($attributes) !!} value="{{ $key }}" {{ in_array($key, $selected) ? 'checked' : '' }}>
							<span></span>
							{!! $value !!}
						</label>
					@endforeach
				</div>

				@error ($name)
					<div class="invalid-feedback">* {{ $errors->first($name) }}</div>
				@enderror

				<span class="form-text text-muted">{!! $help_message !!}</span>
			</div>

			@break

		@case('select')
			<?php

			if (strpos($attributes['class'], 'dual-listbox') !== false && ($attributes['multiple'] ?? false)) {
				$attributes = array_merge($attributes, [
					'data-available-title' => __('global.available-options'),
					'data-selected-title' => __('global.selected-options'),
					'data-add-button' => __('global.add'),
					'data-remove-button' => __('action-buttons.remove'),
					'data-add-all-button' => __('global.add-all'),
					'data-remove-all-button' => __('global.remove-all'),
					'data-search-placeholder' => __('global.search'),
				]);
			} elseif (strpos($attributes['class'], 'no-bootstrap-select') === false) {
				$attributes['class'] .= ' kt-bootstrap-select';
				$attributes = array_merge($attributes, [
					'data-live-search' => 'true',
					'data-live-search-placeholder' => __('global.search'),
					'data-none-selected-text' => __('global.nothing-selected'),
					'data-select-all-text' => __('global.select-all'),
					'data-deselect-all-text' => __('global.deselect-all')
				]);
			}

			$options = $field['options'] ?? [];
			$selected = (array) old($name, $field['selected'] ?? []);

			?>

			<div class="{{ $form_group_class }}">
				@include('layouts.forms.partials.generate_input_label', ['label' => $label, 'for' => $id, 'tooltip' => $tooltip])

				@if (isset($field['group']))
					<?php $field_group = $field['group'] ?>

					<div class="input-group">

						@if (isset($field_group['left']))
							<div class="input-group-prepend">
								<span class="input-group-text">{!! $field_group['left'] !!}</span>
							</div>
						@elseif (isset($field_group['left-button']))
							<div class="input-group-prepend">{!! $field_group['left-button'] !!}</div>
						@endif

						<select {!! stringifyAttr($attributes) !!}>
							@include('layouts.forms.partials.generate_select_options', ['options' => $options, 'selected' => $selected])
						</select>

						@if (isset($field_group['right']))
							<div class="input-group-append">
								<span class="input-group-text">{!! $field_group['right'] !!}</span>
							</div>
						@elseif (isset($field_group['right-button']))
							<div class="input-group-append">{!! $field_group['right-button'] !!}</div>
						@endif
					</div>
				@else
					<select {!! stringifyAttr($attributes) !!}>
						@include('layouts.forms.partials.generate_select_options', ['options' => $options, 'selected' => $selected])
					</select>
				@endif

				@error ($name)
					<div class="invalid-feedback">* {{ $errors->first($name) }}</div>
				@enderror

				<span class="form-text text-muted">{!! $help_message !!}</span>
			</div>

			@break

		@case('static')
			<div class="{{ $form_group_class }}">
				@include('layouts.forms.partials.generate_input_label', ['label' => $label, 'for' => $id, 'tooltip' => $tooltip])

				<p class="form-control-plaintext">{{ trim($field['value']) }}</p>
				<span class="form-text text-muted">{!! $help_message !!}</span>
			</div>

			@break

		@case('gallery')
			@include('layouts.forms.generate_gallery_fields', ['data' => $field])

			@break
	@endswitch
@endforeach
