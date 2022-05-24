<?php

namespace App\Services\Location;

use App\Services\Support\BaseApiHandler;

class RestCountriesHandler extends BaseApiHandler
{
	public const API_URL = 'https://restcountries.com/v3.1/';

	public function __construct()
	{
		parent::__construct(self::API_URL);
	}

	public function all(?array $fields = []): ?array
	{
		$data = $this->client->get('all', [
			'fields' => implode(',', $fields ?? [])
		]);

		return $this->returnResponse($data);
	}
}
