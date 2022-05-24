<?php

namespace App\Services\Push;

use App\Services\Support\BaseApiHandler;

class ExpoPushHandler extends BaseApiHandler
{
	public const API_URL = 'https://exp.host/--/api/v2/push/';

	public function __construct()
	{
		parent::__construct(self::API_URL);
	}

	public function send(array $data): ?array
	{
		$data = $this->client->post('send', $data);

		return $this->returnResponse($data);
	}
}
