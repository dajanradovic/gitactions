@if ($message->request_error_message)
	@if (filter_var($message->request_error_message, FILTER_VALIDATE_URL))
		<a href="{{ $message->request_error_message }}" target="_blank">{{ $message->request_error_message }}</a>
	@else
		{{ $message->request_error_message }}
	@endif
@elseif ($message->error_code)
	{{ __('sms-messages.error-codes.' . $message->error_code) }}
@else
	-
@endif