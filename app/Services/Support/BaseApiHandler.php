<?php

namespace App\Services\Support;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

abstract class BaseApiHandler
{
	protected PendingRequest $client;

	protected function __construct(?string $baseUrl = null, array $headers = [])
	{
		$this->client = Http::acceptJson()->withHeaders($headers)->baseUrl($baseUrl ?? '');
	}

	protected function returnResponse(Response $data, ?array $default = null): ?array
	{
		return $data->successful() ? $data->json() : $default;
	}
}
