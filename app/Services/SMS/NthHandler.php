<?php

namespace App\Services\SMS;

use App\Services\Support\BaseApiHandler;

class NthHandler extends BaseApiHandler
{
	public const API_URL = 'https://msg.mobile-gw.com:9000/';

	public function __construct(?string $username = null, ?string $password = null)
	{
		parent::__construct(self::API_URL);

		$this->client->withBasicAuth($username ?? setting('nth_api_key'), $password ?? setting('nth_api_secret'));
	}

	public function sms(array $data): ?array
	{
		$data = $this->client->post('api/message', $data);

		return $this->returnResponse($data);
	}

	public function message(array $data): ?array
	{
		$data = $this->client->post('v1/omni-channel/message', $data);

		return $this->returnResponse($data);
	}
}
