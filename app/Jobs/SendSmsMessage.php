<?php

namespace App\Jobs;

use Exception;
use App\Models\SmsMessage;
use Vonage\SMS\Collection;
use Illuminate\Bus\Queueable;
use App\Services\SMS\NthHandler;
use App\Services\SMS\ElksHandler;
use App\Services\SMS\TwilioHandler;
use App\Services\SMS\VonageHandler;
use App\Jobs\Middleware\BindConfigs;
use App\Services\SMS\InfoBipHandler;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Exceptions\RestException as TwilioException;

class SendSmsMessage implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public bool $deleteWhenMissingModels = true;

	protected SmsMessage $message;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(SmsMessage $message)
	{
		$this->message = $message;
	}

	public function middleware(): array
	{
		return [new BindConfigs];
	}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{
		switch ($this->message->provider) {
			case $this->message::PROVIDER_VONAGE:
				$this->sendSmsVonage();

				break;

			case $this->message::PROVIDER_TWILIO:
				$this->sendSmsTwilio();

				break;

			case $this->message::PROVIDER_INFOBIP:
				$this->sendSmsInfoBip();

				break;

			case $this->message::PROVIDER_NTH:
				$this->sendSmsNth();

				break;

			case $this->message::PROVIDER_ELKS:
				$this->sendSmsElks();

				break;
		}
	}

	protected function sendSmsVonage(): ?Collection
	{
		$vonage = new VonageHandler;

		try {
			$vonage = $vonage->sms($this->message->to, $this->message->body, [
				'from' => $this->message->from,
				'reference' => $this->message->id,
				'webhook' => route('api.webhooks.sms.vonage.report', $this->message->id)
			]);
		} catch (Exception $e) {
			$this->message->update([
				'request_error_code' => $e->getCode(),
				'request_error_message' => $e->getMessage()
			]);

			return null;
		}

		$price = 0;

		foreach ($vonage as $message) {
			$price += $message->getMessagePrice();
		}

		$this->message->update([
			'message_count' => $vonage->count(),
			'price' => $price
		]);

		return $vonage;
	}

	protected function sendSmsTwilio(): ?MessageInstance
	{
		$twilio = new TwilioHandler;

		try {
			$twilio = $twilio->sms($this->message->to, $this->message->body, [
				'from' => $this->message->from,
				'webhook' => route('api.webhooks.sms.twilio.report', $this->message->id)
			]);
		} catch (TwilioException $e) {
			$this->message->update([
				'request_error_code' => $e->getStatusCode(),
				'request_error_message' => $e->getMoreInfo()
			]);

			return null;
		}

		$this->message->update([
			'external_id' => $twilio->sid,
			'from' => $twilio->from,
			'to' => $twilio->to,
			'message_count' => $twilio->numSegments,
			'price' => $twilio->price ?? 0,
			'price_currency' => $twilio->priceUnit,
			'status' => $twilio->status,
			'error_code' => $twilio->errorCode ?? 0,
			'request_error_message' => $twilio->errorMessage
		]);

		return $twilio;
	}

	protected function sendSmsInfoBip(): ?array
	{
		$infobip = new InfoBipHandler;

		$infobip = $infobip->sms([
			'messages' => [
				[
					'intermediateReport' => true,
					'notifyContentType' => 'application/json',
					'notifyUrl' => route('api.webhooks.sms.infobip.report'),
					'from' => $this->message->from,
					'text' => $this->message->body,
					'destinations' => [
						[
							'messageId' => $this->message->id,
							'to' => $this->message->to,
						]
					]
				]
			]
		]);

		if (!$infobip) {
			return null;
		}

		foreach ($infobip['messages'] as $message) {
			$smsMessage = SmsMessage::find($message['messageId']);

			if (!$smsMessage) {
				continue;
			}

			$smsMessage->update([
				'external_id' => $message['messageId'],
				'to' => $message['to'],
				'status' => $message['status']['groupName']
			]);
		}

		return $infobip;
	}

	protected function sendSmsNth(): ?array
	{
		$nth = new NthHandler;

		$nth = $nth->message([
			'channels' => ['SMS'],
			'dlr' => true,
			'dlrUrl' => route('api.webhooks.sms.nth.report', $this->message->id),
			'requestId' => $this->message->id,
			'transactionId' => $this->message->id,
			'destinations' => [
				[
					'phoneNumber' => $this->message->to,
				]
			],
			'sms' => [
				'sender' => $this->message->from,
				'text' => $this->message->body,
			]
		]);

		if (!isset($nth['messages'])) {
			return null;
		}

		$message = $nth['messages'][0];

		$this->message->update([
			'external_id' => $message['messageId'],
			'to' => $message['destination']['phoneNumber'],
			'status' => $message['status']['code'],
			'message_count' => $nth['smsCount']
		]);

		return $nth;
	}

	protected function sendSmsElks(): ?array
	{
		$elks = new ElksHandler;

		$elks = $elks->sms([
			'from' => $this->message->from,
			'to' => $this->message->to,
			'message' => $this->message->body,
			'whendelivered' => route('api.webhooks.sms.elks.report', $this->message->id),
		]);

		if (!$elks) {
			return null;
		}

		$this->message->update([
			'external_id' => $elks['id'],
			'from' => $elks['from'],
			'to' => $elks['to'],
			'status' => $elks['status'],
			'message_count' => $elks['parts'],
			'price' => $elks['cost'] / 10000
		]);

		return $elks;
	}
}
