<?php

namespace App\Contracts;

use App\Services\MediaStorage;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia as HasMediaBase;

interface HasMedia extends HasMediaBase
{
	public function getStorageConfig(): array;

	public function getStorageDisk(): string;

	public function getStorageMaxUploadSize(): int;

	public function getStorageThumbWidth(): int;

	public function shouldQueueMediaConversions(): bool;

	public function getProtectedMediaExpirationTime(object $file): ?Carbon;

	public function getVideoThumbAtSecond(): int;

	public function getDefaultMediaPrefix(): string;

	public function mediaConfig(): array;

	public function storage(): MediaStorage;
}
