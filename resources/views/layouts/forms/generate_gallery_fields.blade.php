<?php

$model = $data['model'] ?? null;
$fields = $data['fields'];
$gallery_id = $data['gallery_id'];
$collection = $data['collection'] ?? '';
$actions = $data['actions'] ?? [];
$width = $data['width'] ?? 'col-sm-6';

?>

@include('layouts.forms.generate_form_fields', ['fields' => $fields])

<div class="row gallery-container" id="{{ $gallery_id }}"></div>

<template id="gallery-template-{{ $gallery_id }}">
	<div class="col-sm-2">
		<a target="_blank" class="gallery-type-file" hidden>
			<img class="img-thumbnail" src="{{ asset('img/file.png') }}">
		</a>
		<video controls class="img-thumbnail gallery-type-video" hidden></video>
		<audio controls class="img-thumbnail gallery-type-audio" hidden></audio>
		<p class="gallery-footer"></p>
	</div>
</template>

<?php

if (!$model) {
	return;
}

$storage = $model->storage();
$files = $storage->files($collection);

if (empty($files)) {
	return;
}

$filesCount = $files->count();
$filename_glue = $storage::FILENAME_GLUE;
$ids_key = $storage::IDS_KEY;
$new_names_key = $storage::NEW_NAMES_KEY;
$title_key = $storage::TITLE_KEY;
$order_key = $storage::ORDER_KEY;
$marked_removal_key = $storage::MARKED_REMOVAL_KEY

?>

<div class="row">
	@foreach ($files as $file)
		<?php

		$fileId = $storage->getFileId($file);
		$name = $storage->getName($file);
		$title = $storage->getTitle($file);
		$ext = $storage->getExt($file);
		$order = $storage->getOrder($file);
		$size = round($storage->size($file) / (1024 * 1024), 2);
		$url = $storage->url($file);
		$lastModified = formatLocalTimestamp($storage->lastModified($file));

		$fields = [
			[
				'label' => __('forms.name'),
				'tag' => 'input',
				'group' => [
					'right' => '.' . $ext,
				],
				'attributes' => [
					'id' => 'media-' . $gallery_id . '-new-name-' . $fileId,
					'name' => 'media[' . $new_names_key . '][' . $fileId . ']',
					'type' => 'text',
					'maxlength' => 250,
					'value' => $name,
					'required' => true,
					'data-safe-filename' => $filename_glue
				],
			],
			[
				'label' => __('forms.title'),
				'tag' => 'input',
				'attributes' => [
					'id' => 'media-' . $gallery_id . '-title-' . $fileId,
					'name' => 'media[' . $title_key . '][' . $fileId . ']',
					'type' => 'text',
					'maxlength' => 250,
					'value' => $title
				],
			],
			[
				'label' => __('forms.order'),
				'tag' => 'input',
				'condition' => $filesCount > 1,
				'attributes' => [
					'id' => 'media-' . $gallery_id . '-order-' . $fileId,
					'name' => 'media[' . $order_key . '][' . $fileId . ']',
					'type' => 'number',
					'min' => 0,
					'value' => $order
				],
			],
			[
				'tag' => 'hidden',
				'attributes' => [
					'name' => 'media[' . $ids_key . '][]',
					'type' => 'hidden',
					'value' => $fileId
				],
			]
		];

		?>

		<div class="{{ $width }}">
			<div class="row">
				<div class="col-sm-6">
					<figure>

						@if ($storage->isImage($file))
							<a target="_blank" href="{{ $url }}">
								<img class="img-thumbnail" loading="lazy" decoding="async" src="{{ $storage->getThumb($file) }}" alt="{{ $title }}">
							</a>
						@elseif ($storage->isVideo($file))
							<video controls class="img-thumbnail" preload="metadata" src="{{ $url }}" poster="{{ $storage->getThumb($file) }}"></video>
						@elseif ($storage->isAudio($file))
							<audio controls class="img-thumbnail" preload="metadata" src="{{ $url }}"></audio>
						@else
							<a target="_blank" href="{{ $url }}">
								<img class="img-thumbnail" loading="lazy" decoding="async" src="{{ asset('img/file.png') }}" alt="{{ $title }}">
							</a>
						@endif

						<figcaption>
							<a href="{{ $url }}" class="mr-5 text-hover-primary" title="{{ __('global.download') }}" download>
								<i class="fa fa-download"></i> ({{ $size }} MB)
							</a>
							<i class="fa fa-clock"></i> {{ $lastModified }}
						</figcaption>
					</figure>
				</div>
				<div class="col-sm-6">
					@include('layouts.forms.generate_form_fields', ['fields' => $fields])

					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<div class="checkbox-inline">
									<label class="checkbox checkbox-danger">
										<input type="checkbox" name="media[{{ $marked_removal_key }}][]" value="{{ $fileId }}">
										<span></span>
										{{ __('action-buttons.remove') }}
									</label>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<div class="radio-list">
									@foreach ($actions as $action)
										<?php

										$type = $action['type'] ?? 'radio';
										$state = $action['state'] ?? 'primary';
										$values = isset($action['checked']) ? ((array) $action['checked']) : [];
										$checked = in_array($file, $values) ? 'checked' : ''

										?>
										<label class="{{ $type }} {{ $type }}-{{ $state }}">
											<input type="{{ $type }}" name="{{ $action['name'] }}" value="{{ $fileId }}" {{ $checked }}>
											<span></span>
											{{ $action['label'] }}
										</label>
									@endforeach
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endforeach
</div>