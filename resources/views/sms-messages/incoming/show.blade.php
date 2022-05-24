<?php

$actions = [
	[
		'type' => 'remove',
		'action' => ['sms-messages.incoming.remove' => $sms_message->id]
	]
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.list_header', ['title' => __('sms-messages.incoming.title-s') . ' > ' . $sms_message->from, 'icon' => 'fa fa-comments', 'updated_at' => $sms_message->updated_at, 'actions' => $actions])
	<div class="card-body">
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
			<label>{{ __('forms.body') }}</label>
			<p class="form-control-plaintext">{{ $sms_message->body }}</p>
		</div>
	</div>
@endsection