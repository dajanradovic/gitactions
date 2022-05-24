<?php

namespace App\Traits;

use App\Models\UserActivity;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasUserActivity
{
	public function activities(): MorphMany
	{
		return $this->morphMany(UserActivity::class, 'item');
	}

	public function recordReadActivity(?bool $every = null, ?bool $update = null): self
	{
		$user = auth()->user();

		if (!$user || !$this->shouldRecordUserActivity(UserActivity::TYPE_READ)) {
			return $this;
		}

		$every ??= $this->recordEveryReadActivity();
		$update ??= $this->updateReadActivity();

		if ($every) {
			$this->activities()->create([
				'user_id' => $user->id,
				'type' => UserActivity::TYPE_READ,
				'ip_address' => request()->ip() ?? null
			]);
		} elseif ($update) {
			$this->activities()->updateOrCreate([
				'user_id' => $user->id,
				'type' => UserActivity::TYPE_READ
			], [
				'ip_address' => request()->ip() ?? null
			]);
		} else {
			$this->activities()->firstOrCreate([
				'user_id' => $user->id,
				'type' => UserActivity::TYPE_READ
			], [
				'ip_address' => request()->ip() ?? null
			]);
		}

		return $this;
	}

	public function recordEveryReadActivity(): bool
	{
		return true;
	}

	public function updateReadActivity(): bool
	{
		return true;
	}

	public function getUserActivityTitle(): ?string
	{
		return null;
	}

	public function getUserActivityUrl(): ?string
	{
		return null;
	}

	public function shouldRecordUserActivity(int $type): bool
	{
		return true;
	}

	protected static function bootHasUserActivity(): void
	{
		static::deleting(function ($model): void {
			$model->activities->each(function ($item): void {
				$item->delete();
			});
		});

		static::created(function ($model): void {
			$user = auth()->user();

			if (!$user || !$model->shouldRecordUserActivity(UserActivity::TYPE_CREATE)) {
				return;
			}

			$model->activities()->create([
				'user_id' => $user->id,
				'ip_address' => request()->ip() ?? null,
				'type' => UserActivity::TYPE_CREATE
			]);
		});

		static::updated(function ($model): void {
			$user = auth()->user();

			if (!$user || !$model->shouldRecordUserActivity(UserActivity::TYPE_UPDATE)) {
				return;
			}

			$fields = $model->getChanges();
			unset($fields[$model->getUpdatedAtColumn()]);
			$fields = array_keys($fields);
			sort($fields);

			$model->activities()->create([
				'user_id' => $user->id,
				'ip_address' => request()->ip() ?? null,
				'type' => UserActivity::TYPE_UPDATE,
				'updated_fields' => $fields
			]);
		});
	}
}
