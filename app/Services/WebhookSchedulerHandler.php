<?php

namespace App\Services;

use App\Services\Support\BaseApiHandler;

class WebhookSchedulerHandler extends BaseApiHandler
{
	public function __construct(?string $token = null)
	{
		parent::__construct(setting('webhook_scheduler_url'));

		$this->client->withToken($token ?? setting('webhook_scheduler_token'));
	}

	public function getConfig(): ?array
	{
		$data = $this->client->get('config');

		return $this->returnResponse($data);
	}

	public function getWebhooks(array $data = []): ?array
	{
		$data = $this->client->get('webhooks', $data);

		return $this->returnResponse($data);
	}

	public function getWebhook(string $id): ?array
	{
		$data = $this->client->get('webhooks/' . $id);

		return $this->returnResponse($data);
	}

	public function createWebhook(array $data): ?array
	{
		$data = $this->client->post('webhooks', $data);

		return $this->returnResponse($data);
	}

	public function updateWebhook(string $id, array $data): ?array
	{
		$data = $this->client->post('webhooks/' . $id, $data);

		return $this->returnResponse($data);
	}

	public function deleteWebhook(string $id): ?array
	{
		$data = $this->client->delete('webhooks/' . $id);

		return $this->returnResponse($data);
	}

	public function deleteWebhooks(): ?array
	{
		$data = $this->client->delete('webhooks');

		return $this->returnResponse($data);
	}
}
