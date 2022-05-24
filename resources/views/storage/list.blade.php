<?php

$actions = [
	[
		'type' => 'remove',
		'action' => 'storage.remove-multi',
		'condition' => $records->isNotEmpty()
	]
];

$storages = [
	'public' => __('settings.local'),
	's3' => 'AWS S3 bucket'
];

$all_disks = ['%' => __('global.all')];

foreach ($disks as $row) {
	$all_disks[$row->disk] = $storages[$row->disk] ?? $row->disk;
}

$fields_disks = [
	[
		'label' => __('storage.disk'),
		'tag' => 'select',
		'options' => $all_disks,
		'selected' => $disk,
		'attributes' => [
			'id' => 'disk',
			'name' => 'disk',
			'required' => true,
			'onchange' => 'this.form.submit();'
		]
	]
];

$chartDataFiles = $chartDataSize = [];

?>

@extends('layouts.master')

@push('scripts')
	@include('layouts.chart_scripts')
@endpush

@section('content')
	@include('layouts.list_header', ['title' => __('storage.title'), 'icon' => 'fa fa-database', 'actions' => $actions])
	<div class="card-body">
		<ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-primary" role="tablist" data-active-tab="tab">
			<li class="nav-item">
				<a href="#btabs-basic" class="nav-link active" data-toggle="tab">
					<span class="nav-icon"><i class="fa fa-info"></i></span>
            		<span class="nav-text">{{ __('settings.menu-basic') }}</span>
				</a>
			</li>
			<li class="nav-item">
				<a href="#btabs-charts" class="nav-link update-storage-charts" data-toggle="tab">
					<span class="nav-icon"><i class="fa fa-chart-line"></i></span>
            		<span class="nav-text">{{ __('dashboard.charts') }}</span>
				</a>
			</li>
		</ul>
		<div class="tab-content mt-5">
			<div class="tab-pane fade show active" id="btabs-basic" role="tabpanel">
				<form class="form">
					<div class="row">
						<div class="col-sm-6">
							@include('layouts.forms.generate_form_fields', ['fields' => $fields_disks])
						</div>
					</div>
				</form>

				<table width="100%" class="table table-head-custom js-datatable">
					<thead>
						<tr>
							<th>{{ __('storage.model') }}</th>
							<th>{{ __('storage.files-count') }}</th>
							<th>{{ __('storage.files-size') }}</th>
							@include('layouts.options_column_header')
						</tr>
					</thead>
					<tbody>
						@foreach($records as $row)
							<?php

							$chartDataFiles[] = [
								'model' => class_basename($row->model_type),
								'value' => $row->files_count
							];

							$chartDataSize[] = [
								'model' => class_basename($row->model_type),
								'value' => $row->files_size
							];

							?>
							<tr>
								<td>{{ class_basename($row->model_type) }}</td>
								<td>{{ $row->files_count }}</td>
								<td>{{ round($row->files_size / (1024 * 1024), 2) }} MB</td>
								<td>@include('layouts.option_buttons', ['value' => $row->model_type])</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<div class="tab-pane fade" id="btabs-charts" role="tabpanel">
				<div class="row">
					<div class="col-sm-6">
						<h3>{{ __('storage.files-count') }}</h3>
						<div id="storage-chart-files" class="chart-area" data-chart-data='@json($chartDataFiles)'></div>
					</div>
					<div class="col-sm-6">
						<h3>{{ __('storage.files-size') }}</h3>
						<div id="storage-chart-size" class="chart-area" data-chart-data='@json($chartDataSize)'></div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection