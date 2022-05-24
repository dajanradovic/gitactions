<?php

namespace App\Services\SMS;

use App\Services\Support\BaseApiHandler;

class ElksHandler extends BaseApiHandler
{
	public const API_URL = 'https://api.46elks.com/a1/';

	public function __construct(?string $username = null, ?string $password = null)
	{
		parent::__construct(self::API_URL);

		$this->client->asForm()->withBasicAuth($username ?? setting('elks_api_key'), $password ?? setting('elks_api_secret'));
	}

	public function sms(array $data): ?array
	{
		$data = $this->client->post('sms', $data);

		return $this->returnResponse($data);
	}
}
