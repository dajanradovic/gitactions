<?php

namespace App\Services\SMS;

use Twilio\Rest\Client as TwilioClient;
use Twilio\Rest\Api\V2010\Account\MessageInstance;

class TwilioHandler
{
	protected TwilioClient $client;
	protected string $from;

	public function __construct(?string $account_sid = null, ?string $account_token = null, ?string $from = null)
	{
		$this->client = new TwilioClient($account_sid ?? setting('twilio_api_key'), $account_token ?? setting('twilio_api_secret'));
		$this->setFrom($from);
	}

	public function setFrom(?string $from = null): self
	{
		$this->from = $from ?? setting('twilio_from_number');

		return $this;
	}

	public function sms(string $to, string $body, array $data = []): MessageInstance
	{
		return $this->client->messages->create($to, [
			'body' => $body,
			'from' => $data['from'] ?? $this->from,
			'statusCallback' => $data['webhook'] ?? null
		]);
	}
}
