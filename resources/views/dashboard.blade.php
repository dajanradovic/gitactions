<?php

$token = auth()->user()->token();
$last_month = now()->startOfMonth();

$date_scopes = [
	'Y' => __('forms.year'),
	'Y-m' => __('forms.month'),
	'Y-m-d' => __('forms.day'),
	'Y-m-d H' => __('forms.hour')
];

$fields_daterange = [
	[
		'label' => __('forms.daterange'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'chart-date-range',
			'type' => 'text',
			'value' => formatLocalTimestamp($last_month, 'd/m/Y') . ' 00:00 - ' . formatLocalTimestamp(null, 'd/m/Y') . ' 23:59',
			'class' => 'chart-daterangepicker',
			'readonly' => true
		]
	],
	[
		'tag' => 'hidden',
		'attributes' => [
			'id' => 'chart-date-range-first',
			'type' => 'hidden',
			'value' => formatLocalTimestamp($last_month, 'Y-m-d 00:00')
		]
	],
	[
		'tag' => 'hidden',
		'attributes' => [
			'id' => 'chart-date-range-second',
			'type' => 'hidden',
			'value' => formatLocalTimestamp(null, 'Y-m-d 23:59')
		]
	]
];

$fields_dateformat = [
	[
		'label' => __('forms.scope'),
		'tag' => 'select',
		'options' => $date_scopes,
		'selected' => 'Y-m-d',
		'attributes' => [
			'id' => 'chart-date-format',
			'class' => 'chart-date-format',
			'required' => true
		]
	]
];

$stats = [
	[
		'title' => $users_count,
		'subtitle' => __('users.title-m'),
		'icon' => 'fa fa-users',
		'color' => 'success',
		'route' => 'users.list'
	],
	[
		'title' => $notifications_count,
		'subtitle' => __('notifications.title-m'),
		'icon' => 'fa fa-broadcast-tower',
		'color' => 'primary',
		'route' => 'notifications.list'
	],
	[
		'title' => $devices_count,
		'subtitle' => __('notifications.devices'),
		'icon' => 'fa fa-mobile-alt',
		'color' => 'warning'
	]
];

$charts = [
	[
		'id' => 'chart-notifications',
		'title' => __('notifications.title-m'),
		'icon' => 'fa fa-broadcast-tower',
		'width' => 'col-sm-6',
		'api' => ['api.charts.notifications' => ['token' => $token]]
	],
	[
		'id' => 'chart-devices',
		'title' => __('notifications.devices'),
		'icon' => 'fa fa-mobile-alt',
		'width' => 'col-sm-6',
		'api' => ['api.charts.devices' => ['token' => $token]]
	]
];

?>

@extends('layouts.master')

@push('scripts')
	@include('layouts.chart_scripts')
@endpush

@section('content')
	@include('layouts.list_header', ['title' => __('dashboard.title'), 'icon' => 'fa fa-chart-line'])
	<div class="card-body">
		<ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-primary" role="tablist" data-active-tab="tab">
			<li class="nav-item">
				<a href="#btabs-basic" class="nav-link active" data-toggle="tab">
					<span class="nav-icon"><i class="fa fa-info"></i></span>
            		<span class="nav-text">{{ __('settings.menu-basic') }}</span>
				</a>
			</li>
			<li class="nav-item">
				<a href="#btabs-charts" class="nav-link update-charts" data-toggle="tab">
					<span class="nav-icon"><i class="fa fa-chart-line"></i></span>
            		<span class="nav-text">{{ __('dashboard.charts') }}</span>
				</a>
			</li>
		</ul>
		<div class="tab-content mt-5">
			<div class="tab-pane fade show active" id="btabs-basic" role="tabpanel">
				<div class="row">
					@foreach($stats as $stat)
						@include('layouts.stat', $stat)
					@endforeach
				</div>
			</div>
			<div class="tab-pane fade" id="btabs-charts" role="tabpanel">
				<form class="form">
					<div class="row">
						<div class="col-sm-6">
							@include('layouts.forms.generate_form_fields', ['fields' => $fields_daterange])
						</div>
						<div class="col-sm-6">
							@include('layouts.forms.generate_form_fields', ['fields' => $fields_dateformat])
						</div>
					</div>
				</form>

				<div class="row">
					@foreach($charts as $chart)
						@include('layouts.chart', $chart)
					@endforeach
				</div>
			</div>
		</div>
	</div>
@endsection