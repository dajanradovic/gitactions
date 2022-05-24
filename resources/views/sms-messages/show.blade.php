<?php

$actions = [
	[
		'type' => 'remove',
		'action' => ['sms-messages.remove' => $sms_message->id]
	]
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.list_header', ['title' => __('sms-messages.title-s') . ' > ' . $sms_message->to, 'icon' => 'fa fa-comments', 'updated_at' => $sms_message->updated_at, 'actions' => $actions])
	<div class="card-body">
		<div class="form-group">
			<label>{{ __('sms-messages.status') }}</label>
			<p class="form-control-plaintext">
				@include('layouts.sms.status_badge', ['status' => $sms_message->status])
			</p>
		</div>
		<div class="form-group">
			<label>{{ __('sms-messages.provider') }}</label>
			<p class="form-control-plaintext">{{ __('sms-messages.providers.' . $sms_message->provider) }}</p>
		</div>
		<div class="form-group">
			<label>{{ __('sms-messages.from') }}</label>
			<p class="form-control-plaintext">
				<a href="tel:{{ $sms_message->from }}">{{ $sms_message->from }}</a>
			</p>
		</div>
		<div class="form-group">
			<label>{{ __('sms-messages.to') }}</label>
			<p class="form-control-plaintext">
				<a href="tel:{{ $sms_message->to }}">{{ $sms_message->to }}</a>
			</p>
		</div>
		<div class="form-group">
			<label>{{ __('sms-messages.message-count') }}</label>
			<p class="form-control-plaintext">{{ $sms_message->message_count }}</p>
		</div>
		<div class="form-group">
			<label>{{ __('sms-messages.price') }}</label>
			<p class="form-control-plaintext">{{ $sms_message->price ?? 0 }} {{ $sms_message->price_currency }}</p>
		</div>
		<div class="form-group">
			<label>{{ __('global.created-at') }}</label>
			<p class="form-control-plaintext">{{ formatLocalTimestamp($sms_message->created_at) }}</p>
		</div>
		<div class="form-group">
			<label>{{ __('forms.body') }}</label>
			<p class="form-control-plaintext">{{ $sms_message->body }}</p>
		</div>
		<div class="form-group">
			<label>{{ __('sms-messages.error-message') }}</label>
			<p class="form-control-plaintext">
				@include('layouts.sms.render_error', ['message' => $sms_message])
			</p>
		</div>
	</div>
@endsection