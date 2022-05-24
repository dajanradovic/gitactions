<?php

namespace App\Services\Push;

use OneSignal\Config;
use GuzzleHttp\Client;
use OneSignal\OneSignal;
use Nyholm\Psr7\Factory\Psr17Factory;
use OneSignal\Exception\OneSignalExceptionInterface;

class OneSignalHandler
{
	protected OneSignal $api;

	public function __construct(?string $app_id = null, ?string $rest_api_key = null, ?string $user_auth_key = null)
	{
		$config = new Config($app_id ?? setting('onesignal_app_id'), $rest_api_key ?? setting('onesignal_rest_api_key'), $user_auth_key ?? setting('onesignal_user_auth_key'));
		$requestFactory = $streamFactory = new Psr17Factory;

		$this->api = new OneSignal($config, new Client, $requestFactory, $streamFactory);
	}

	public function addApp(array $data): array
	{
		return $this->api->apps()->add($data);
	}

	public function addNotification(array $data): array
	{
		return $this->api->notifications()->add($data);
	}

	public function cancelNotification(string $notification_id): ?array
	{
		try {
			$data = $this->api->notifications()->cancel($notification_id);
		} catch (OneSignalExceptionInterface $e) {
			$data = null;
		}

		return $data;
	}

	public function deleteDevice(string $device_id): array
	{
		return $this->api->devices()->delete($device_id);
	}

	public function getApps(?string $app_id = null): array
	{
		return $app_id ? $this->api->apps()->getOne($app_id) : $this->api->apps()->getAll();
	}

	public function getDevices(?string $device_id = null): array
	{
		return $device_id ? $this->api->devices()->getOne($device_id) : $this->api->devices()->getAll();
	}

	public function getNotifications(?string $notification_id = null): ?array
	{
		try {
			$data = $notification_id ? $this->api->notifications()->getOne($notification_id) : $this->api->notifications()->getAll();
		} catch (OneSignalExceptionInterface $e) {
			$data = null;
		}

		return $data;
	}

	public function updateApp(string $app_id, array $data): array
	{
		return $this->api->apps()->update($app_id, $data);
	}

	public function updateOrAddApp(string $app_id, array $data): ?array
	{
		if (!$app_id) {
			return $this->addApp($data);
		}

		try {
			$data = $this->updateApp($app_id, $data);
		} catch (OneSignalExceptionInterface $e) {
			$data = $this->addApp($data);
		}

		return $data;
	}
}
