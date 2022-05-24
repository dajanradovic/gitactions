<?php

$actions = [
	[
		'type' => 'remove',
		'action' => 'sms-messages.incoming.remove-multi',
		'condition' => $sms_messages->isNotEmpty()
	]
];

$search = [
	'value' => $search ?? null,
	'route' => 'sms-messages.incoming.search'
];

$providers = [
	'' => __('global.all'),
	\App\Models\SmsMessage::PROVIDER_VONAGE => __('sms-messages.providers.' . \App\Models\SmsMessage::PROVIDER_VONAGE),
	\App\Models\SmsMessage::PROVIDER_TWILIO => __('sms-messages.providers.' . \App\Models\SmsMessage::PROVIDER_TWILIO),
	\App\Models\SmsMessage::PROVIDER_INFOBIP => __('sms-messages.providers.' . \App\Models\SmsMessage::PROVIDER_INFOBIP),
	\App\Models\SmsMessage::PROVIDER_NTH => __('sms-messages.providers.' . \App\Models\SmsMessage::PROVIDER_NTH),
	\App\Models\SmsMessage::PROVIDER_ELKS => __('sms-messages.providers.' . \App\Models\SmsMessage::PROVIDER_ELKS),
];

$fields_providers = [
	[
		'label' => __('sms-messages.provider'),
		'tag' => 'select',
		'options' => $providers,
		'selected' => $provider,
		'attributes' => [
			'id' => 'provider',
			'name' => 'provider',
			'onchange' => 'this.form.submit()'
		]
	],
	[
		'tag' => 'hidden',
		'attributes' => [
			'name' => 'search',
			'type' => 'hidden',
			'value' => $search['value']
		]
	],
];

$fields_daterange = !isset($start_date) || !isset($end_date) ? [] : [
	[
		'label' => __('forms.daterange'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'daterangepicker',
			'type' => 'text',
			'value' => formatLocalTimestamp($start_date, 'd/m/Y H:i') . ' - ' . formatLocalTimestamp($end_date, 'd/m/Y H:i'),
			'class' => 'js-search-datetimerangepicker',
			'readonly' => true
		]
	],
	[
		'tag' => 'hidden',
		'attributes' => [
			'id' => 'daterangepicker-first',
			'name' => 'start_date',
			'type' => 'hidden',
			'value' => $start_date
		]
	],
	[
		'tag' => 'hidden',
		'attributes' => [
			'id' => 'daterangepicker-second',
			'name' => 'end_date',
			'type' => 'hidden',
			'value' => $end_date
		]
	]
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.list_header', ['title' => __('sms-messages.incoming.list'), 'icon' => 'fa fa-comments', 'actions' => $actions, 'search' => $search])
	<div class="card-body">
		<form class="form">
			<div class="form__heading">
				<div class="row">
					<div class="col-sm-6">
						@include('layouts.forms.generate_form_fields', ['fields' => $fields_providers])
					</div>
					<div class="col-sm-6">
						@include('layouts.forms.generate_form_fields', ['fields' => $fields_daterange])
					</div>
				</div>
			</div>
		</form>

		<table width="100%" class="table table-head-custom js-datatable">
			<thead>
				<tr>
					<th data-datatable-searchable="0" data-datatable-orderable="0" data-datatable-exclude-export></th>
					<th>{{ __('sms-messages.provider') }}</th>
					<th>{{ __('sms-messages.from') }}</th>
					<th>{{ __('sms-messages.to') }}</th>
					<th>{{ __('forms.body') }}</th>
					<th>{{ __('global.created-at') }}</th>
					@include('layouts.options_column_header')
				</tr>
			</thead>
			<tbody>
				@foreach($sms_messages as $row)
					<tr>
						<td><a href="{{ route('sms-messages.incoming.show', $row->id) }}">{{ __('global.view') }}</a></td>
						<td>{{ __('sms-messages.providers.' . $row->provider) }}</td>
						<td><a href="tel:{{ $row->from }}">{{ $row->from }}</a></td>
						<td><a href="tel:{{ $row->to }}">{{ $row->to }}</a></td>
						<td>{{ $row->body }}</td>
						<td data-order="{{ formatTimestamp($row->created_at, 'U') }}">{{ formatLocalTimestamp($row->created_at) }}</td>
						<td>@include('layouts.option_buttons', ['value' => $row->id])</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection
