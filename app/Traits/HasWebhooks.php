<?php

namespace App\Traits;

use App\Models\Webhook;
use App\Services\WebhookSchedulerHandler;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasWebhooks
{
	public function webhooks(): MorphMany
	{
		return $this->morphMany(Webhook::class, 'item');
	}

	public static function getRepetitiveUnitOff(): int
	{
		return Webhook::REPETITIVE_OFF;
	}

	public static function getRepetitiveUnitMinutes(): int
	{
		return Webhook::REPETITIVE_MINUTES;
	}

	public static function getRepetitiveUnitHours(): int
	{
		return Webhook::REPETITIVE_HOURS;
	}

	public static function getRepetitiveUnitDays(): int
	{
		return Webhook::REPETITIVE_DAYS;
	}

	public static function getRepetitiveUnitWeeks(): int
	{
		return Webhook::REPETITIVE_WEEKS;
	}

	public static function getRepetitiveUnitMonths(): int
	{
		return Webhook::REPETITIVE_MONTHS;
	}

	public static function getRepetitiveUnitQuarters(): int
	{
		return Webhook::REPETITIVE_QUARTERS;
	}

	public static function getRepetitiveUnitYears(): int
	{
		return Webhook::REPETITIVE_YEARS;
	}

	public function createWebhook(array $data): ?Webhook
	{
		$webhookScheduler = new WebhookSchedulerHandler;

		$data = $webhookScheduler->createWebhook($data);

		return $data ? $this->webhooks()->create(['external_id' => $data['data']['id']]) : null;
	}

	public function updateWebhook(string $id, array $data)
	{
		$webhookScheduler = new WebhookSchedulerHandler;

		return $webhookScheduler->updateWebhook($id, $data);
	}

	protected static function bootHasWebhooks(): void
	{
		static::deleting(function ($model): void {
			$model->webhooks->each(function ($item): void {
				$item->delete();
			});
		});
	}
}
