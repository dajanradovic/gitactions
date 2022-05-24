<?php

namespace App\Services\SMS;

use Vonage\SMS\Collection;
use Vonage\Client as VonageClient;
use Vonage\SMS\Message\SMS as VonageSMS;
use Vonage\Client\Credentials\Basic as VonageAuth;

class VonageHandler
{
	protected VonageClient $client;
	protected string $from;

	public function __construct(?string $api_key = null, ?string $api_secret = null, ?string $from = null)
	{
		$this->client = new VonageClient(new VonageAuth($api_key ?? setting('vonage_api_key'), $api_secret ?? setting('vonage_api_secret')));
		$this->setFrom($from);
	}

	public function setFrom(?string $from = null): self
	{
		$this->from = $from ?? setting('vonage_from_number');

		return $this;
	}

	public function sms(string $to, string $body, array $data = []): Collection
	{
		$message = new VonageSMS($to, $data['from'] ?? $this->from, $body);

		if (isset($data['reference'])) {
			$message->setClientRef($data['reference']);
		}

		if (isset($data['webhook'])) {
			$message->setDeliveryReceiptCallback($data['webhook']);
		}

		return $this->client->sms()->send($message);
	}
}
