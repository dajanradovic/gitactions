<?php

$actions = [
	[
		'type' => 'cancel',
		'action' => ['notifications.cancel' => $notification->id]
	],
	[
		'type' => 'remove',
		'action' => ['notifications.remove' => $notification->id]
	]
];

$countries = $notification->countries ?? [];

$stats_sent = $successful_perc = $failed_perc = $errored_perc = $sent_perc = $converted_perc = 0;
$stats_total = $notification->successful + $notification->failed + $notification->errored + $notification->remaining;

if ($stats_total) {
	$stats_sent = $stats_total - $notification->remaining;
	$successful_perc = round((100 * $notification->successful) / $stats_total);
	$failed_perc = round((100 * $notification->failed) / $stats_total);
	$errored_perc = round((100 * $notification->errored) / $stats_total);
	$sent_perc = round((100 * $stats_sent) / $stats_total);
	$converted_perc = round((100 * $notification->converted) / $stats_total);
}

?>

@extends('layouts.master')

@section('content')
	@include('layouts.list_header', ['title' => __('notifications.title-s') . ' > ' . $notification->title, 'icon' => 'fa fa-broadcast-tower', 'updated_at' => $notification->updated_at, 'actions' => $actions])
	<div class="card-body">
		<ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-primary" role="tablist" data-active-tab="tab">
			<li class="nav-item">
				<a href="#btabs-basic" class="nav-link active" data-toggle="tab">
					<span class="nav-icon"><i class="fa fa-info"></i></span>
					<span class="nav-text">{{ __('settings.menu-basic') }}</span>
				</a>
			</li>
			<li class="nav-item">
				<a href="#btabs-location" class="nav-link" data-toggle="tab">
					<span class="nav-icon"><i class="fa fa-map-marker-alt"></i></span>
					<span class="nav-text">{{ __('notifications.location') }}</span>
				</a>
			</li>
			<li class="nav-item">
				<a href="#btabs-stats" class="nav-link" data-toggle="tab">
					<span class="nav-icon"><i class="fa fa-mobile-alt"></i></span>
					<span class="nav-text">{{ __('notifications.menu-stats') }}</span>
				</a>
			</li>
		</ul>
		<div class="tab-content mt-5">
			<div class="tab-pane fade show active" id="btabs-basic" role="tabpanel">
				<div class="form-group">
					<label>{{ __('forms.title') }}</label>
					<p class="form-control-plaintext">{{ $notification->title }}</p>
				</div>
				<div class="form-group">
					<label>{{ __('global.created-at') }}</label>
					<p class="form-control-plaintext">{{ formatLocalTimestamp($notification->created_at) }}</p>
				</div>
				<div class="form-group">
					<label>{{ __('notifications.scheduled-at') }}</label>
					<p class="form-control-plaintext">{{ $notification->scheduled_at ? formatLocalTimestamp($notification->scheduled_at) : '-' }}</p>
				</div>
				<div class="form-group">
					<label>{{ __('forms.url') }}</label>
					<p class="form-control-plaintext">
						<a href="{{ $notification->url ?? '#' }}" target="_blank">{{ $notification->url ?? '-' }}</a>
					</p>
				</div>
				<div class="form-group">
					<label>{{ __('notifications.canceled') }}</label>
					<p class="form-control-plaintext">
						@include('layouts.bool_badge', ['value' => $notification->canceled, 'inverted' => true])
					</p>
				</div>
				<div class="form-group">
					<label>{{ __('forms.body') }}</label>
					<p class="form-control-plaintext">{{ $notification->body }}</p>
				</div>
			</div>
			<div class="tab-pane fade" id="btabs-location" role="tabpanel">
				@if(!empty($countries))
					<div class="form-group">
						<label>{{ __('notifications.countries') }}</label>
						<p class="form-control-plaintext">
							@foreach($countries as $country)
								<span class="label label-light-primary label-rounded label-xl">{{ $country }}</span>
							@endforeach
						</p>
					</div>
				@endif

				@if($notification->radius)
					<div class="form-group">
						<label>{{ __('notifications.radius-location') }}</label>
						<p class="form-control-plaintext">{{ __('notifications.radius') }}: {{ $notification->radius }} {{ __('notifications.meters') }}</p>
						<iframe class="map-area" frameborder="0" loading="lazy" allowfullscreen src="https://google.com/maps/embed/v1/place?q={{ $notification->location_lat }},{{ $notification->location_lng }}&zoom=10&key={{ setting('google_api_key') }}"></iframe>
					</div>
				@endif
			</div>
			<div class="tab-pane fade" id="btabs-stats" role="tabpanel">
				<div class="form-group">
					<label>{{ __('notifications.sent') }}</label>
					<p class="form-control-plaintext"><span class="label label-info label-dot"></span> {{ $stats_sent }} / {{ $stats_total }} ({{ $sent_perc }}%)</p>
				</div>
				<div class="form-group">
					<label>{{ __('notifications.successful') }}</label>
					<p class="form-control-plaintext"><span class="label label-success label-dot"></span> {{ $notification->successful }} / {{ $stats_total }} ({{ $successful_perc }}%)</p>
				</div>
				<div class="form-group">
					<label>{{ __('notifications.failed') }}</label>
					<p class="form-control-plaintext"><span class="label label-danger label-dot"></span> {{ $notification->failed }} / {{ $stats_total }} ({{ $failed_perc }}%)</p>
				</div>
				<div class="form-group">
					<label>{{ __('notifications.errored') }}</label>
					<p class="form-control-plaintext"><span class="label label-danger label-dot"></span> {{ $notification->errored }} / {{ $stats_total }} ({{ $errored_perc }}%)</p>
				</div>
				<div class="form-group">
					<label>{{ __('notifications.opened') }}</label>
					<p class="form-control-plaintext"><span class="label label-warning label-dot"></span> {{ $notification->converted }} / {{ $stats_total }} ({{ $converted_perc }}%)</p>
				</div>
				<div class="form-group">
					<label>{{ __('notifications.completed-at') }}</label>
					<p class="form-control-plaintext">{{ $notification->completed_at ? formatLocalTimestamp($notification->completed_at) : '-' }}</p>
				</div>
				<div class="form-group">
					<label>{{ __('notifications.last-check') }}</label>
					<p class="form-control-plaintext">{{ $notification->updated_at ? formatLocalTimestamp($notification->updated_at) : '-' }}</p>
				</div>
			</div>
		</div>
	</div>
@endsection
