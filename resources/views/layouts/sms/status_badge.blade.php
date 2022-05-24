@switch ($status)
	@case ('accepted')
	@case ('delivered')
	@case ('read')
		<?php $state = 'success'; ?>
		@break
	@case ('buffered')
	@case ('queued')
	@case ('pending')
	@case ('concat_wait')
	@case ('enroute')
		<?php $state = 'warning'; ?>
		@break
	@case ('sending')
	@case ('sent')
		<?php $state = 'primary'; ?>
		@break
	@case ('receiving')
	@case ('received')
	@case ('created')
		<?php $state = 'info'; ?>
		@break
	@case ('expired')
	@case ('failed')
	@case ('rejected')
	@case ('undelivered')
	@case ('undeliverable')
	@case ('deleted')
	@case ('throttling')
	@case ('no_route')
	@case ('error')
		<?php $state = 'danger'; ?>
		@break
	@case ('unknown')
	@case ('none')
	@default
		<?php

		$state = 'secondary';
		$status = 'unknown';

		?>
		@break
@endswitch

<span class="label label-{{ $state }} label-inline">{{ strtoupper(__('sms-messages.statuses.' . $status)) }}</span>