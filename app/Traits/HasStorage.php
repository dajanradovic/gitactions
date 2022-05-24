<?php

namespace App\Traits;

use App\Services\MediaStorage;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasStorage
{
	use InteractsWithMedia;

	public function registerMediaConversions(?Media $media = null): void
	{
		$thumbWidth = $this->getStorageThumbWidth();

		$this->addMediaConversion(MediaStorage::THUMB_KEY)
			->withResponsiveImages()
			->extractVideoFrameAtSecond($this->getVideoThumbAtSecond())
			->keepOriginalImageFormat()
			->width($thumbWidth)
			->height($thumbWidth);
	}

	public function getStorageConfig(): array
	{
		return config('filesystems.disks');
	}

	public function getStorageDisk(): string
	{
		return setting('media_storage');
	}

	public function getStorageMaxUploadSize(): int
	{
		return setting('max_upload_size');
	}

	public function getStorageThumbWidth(): int
	{
		return setting('thumb_width');
	}

	public function shouldQueueMediaConversions(): bool
	{
		return setting('queue_media_conversions');
	}

	public function getProtectedMediaExpirationTime(object $file): ?Carbon
	{
		return now()->addMinutes(setting('protected_media_token_valid_until'));
	}

	public function getVideoThumbAtSecond(): int
	{
		return 0;
	}

	public function getDefaultMediaPrefix(): string
	{
		return config('media-library.prefix');
	}

	public function mediaConfig(): array
	{
		return [];
	}

	public function storage(): MediaStorage
	{
		return new MediaStorage($this);
	}
}
