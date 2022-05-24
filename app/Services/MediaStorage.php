<?php

namespace App\Services;

use App\Contracts\HasMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\Support\MediaStream;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaStorage
{
	public const FILENAME_GLUE = '-';
	public const MEDIA_PREFIX = 'media';
	public const THUMB_KEY = 'thumb';
	public const IDS_KEY = 'ids';
	public const NEW_NAMES_KEY = 'new_names';
	public const TITLE_KEY = 'titles';
	public const ORDER_KEY = 'order';
	public const MARKED_REMOVAL_KEY = 'marked_removal';

	protected object $model;
	protected Request $request;

	public function __construct(HasMedia $model)
	{
		$this->setModel($model);
		$this->setRequest(request());
	}

	public static function getValidationRules(?string $prefix = null): array
	{
		$prefix ??= self::MEDIA_PREFIX;
		$prefix = empty($prefix) ? '' : $prefix . '.';

		return [
			$prefix . self::IDS_KEY => ['array'],
			$prefix . self::IDS_KEY . '.*' => ['required', 'uuid'],
			$prefix . self::MARKED_REMOVAL_KEY => ['array'],
			$prefix . self::MARKED_REMOVAL_KEY . '.*' => ['required', 'uuid'],
			$prefix . self::NEW_NAMES_KEY => ['array'],
			$prefix . self::NEW_NAMES_KEY . '.*' => ['required', 'string', 'max:250'],
			$prefix . self::TITLE_KEY => ['array'],
			$prefix . self::TITLE_KEY . '.*' => ['nullable', 'string', 'max:250'],
			$prefix . self::ORDER_KEY => ['array'],
			$prefix . self::ORDER_KEY . '.*' => ['nullable', 'integer', 'min:0'],
		];
	}

	public static function mergeValidationRules(array $rules = [], ?string $prefix = null): array
	{
		return array_replace(static::getValidationRules($prefix), $rules);
	}

	public function setModel(HasMedia $model): self
	{
		$this->model = $model;

		return $this->setDisk();
	}

	public function setRequest(Request $request): self
	{
		$this->request = $request;

		return $this;
	}

	public function setDisk(?string $disk = null): self
	{
		config([
			'filesystems.disks' => $this->model->getStorageConfig(),
			'media-library.queue_conversions_by_default' => $this->model->shouldQueueMediaConversions(),
			'media-library.disk_name' => $disk ?? $this->model->getStorageDisk(),
			'media-library.max_file_size' => $this->model->getStorageMaxUploadSize(),
			'media-library.prefix' => $this->model->getDefaultMediaPrefix()
		]);

		return $this;
	}

	public function handle(?array $media = [], array $pairs = []): self
	{
		$pairs = $this->resolveMediaConfig($pairs);

		return $this
			->deleteFromRequest($media)
			->renameAndReorderFromRequest($media)
			->registerMediaCollections($pairs)
			->saveFromRequest($pairs);
	}

	public function handleFromNestedArray(array $inputArray, ?array $media = [], array $pairs = []): self
	{
		return $this->setRequest($this->request->merge($inputArray))->handle($media, $pairs);
	}

	public function files(string $collection = ''): Collection
	{
		return $this->model->getMedia($collection);
	}

	public function deleteFiles(string $collection = ''): self
	{
		$files = $this->files($collection);

		foreach ($files as $file) {
			$file->delete();
		}

		return $this;
	}

	public function isAudio(object $file): bool
	{
		return $this->isMime($file, 'audio');
	}

	public function isImage(object $file): bool
	{
		return $this->isMime($file, 'image');
	}

	public function isVideo(object $file): bool
	{
		return $this->isMime($file, 'video');
	}

	public function isMime(object $file, string $mime): bool
	{
		return str_contains($this->getMime($file), $mime);
	}

	public function getMime(object $file): string
	{
		return $file->mime_type;
	}

	public function url(?object $file = null, ?string $conversion = null): ?string
	{
		if (empty($file)) {
			return null;
		}

		return !empty($conversion) && $file->hasGeneratedConversion($conversion) ? $file->getFullUrl($conversion) : $file->getFullUrl();
	}

	public function getProtectedFirst(string $collection = '', ?string $conversion = null): ?string
	{
		return $this->protectedUrl($this->model->getFirstMedia($collection), $conversion);
	}

	public function getProtectedFirstThumb(string $collection = ''): ?string
	{
		return $this->getProtectedFirst($collection, self::THUMB_KEY);
	}

	public function getProtectedThumb(object $file): ?string
	{
		return $this->protectedUrl($file, self::THUMB_KEY);
	}

	public function protectedUrl(?object $file = null, ?string $conversion = null): ?string
	{
		if (empty($file)) {
			return null;
		}

		return $this->getProtectedMediaRoute($file, ['id' => $file->id, 'conversion' => $conversion]);
	}

	public function getProtectedAll(string $collection = '', ?string $conversion = null): array
	{
		$allFiles = [];

		foreach ($this->files($collection) as $file) {
			$allFiles[] = $this->protectedUrl($file, $conversion);
		}

		return $allFiles;
	}

	public function getProtectedAllThumbs(string $collection = ''): array
	{
		return $this->getProtectedAll($collection, self::THUMB_KEY);
	}

	public function getAll(string $collection = '', ?string $conversion = null): array
	{
		$allFiles = [];

		foreach ($this->files($collection) as $file) {
			$allFiles[] = $this->url($file, $conversion);
		}

		return $allFiles;
	}

	public function getAllThumbs(string $collection = ''): array
	{
		return $this->getAll($collection, self::THUMB_KEY);
	}

	public function getThumb(object $file): ?string
	{
		return $this->url($file, self::THUMB_KEY);
	}

	public function getFirst(string $collection = '', ?string $conversion = null): ?string
	{
		return $this->url($this->model->getFirstMedia($collection), $conversion);
	}

	public function getFirstThumb(string $collection = ''): ?string
	{
		return $this->getFirst($collection, self::THUMB_KEY);
	}

	public function getFirstFile(string $collection = ''): ?object
	{
		return $this->model->getFirstMedia($collection);
	}

	public function getResponsiveImages(object $file, ?string $conversion = null, bool $protected = false): array
	{
		$pairs = [];
		$conversion ??= self::THUMB_KEY;

		$images = $file->responsiveImages($conversion)->files ?? [];

		foreach ($images as $image) {
			$pairs[] = [
				'width' => $image->width(),
				'height' => $image->height(),
				'url' => $protected
					? $this->getProtectedMediaRoute($file, ['id' => $file->id, 'width' => $image->width(), 'conversion' => $conversion])
					: $image->url()
			];
		}

		return $pairs;
	}

	public function getResponsiveImage(string|object $file, int $width, ?string $conversion = null, bool $protected = false): ?string
	{
		if (is_string($file)) {
			$file = $this->getFirstFile($file);
		}

		if (empty($file)) {
			return null;
		}

		$images = $this->getResponsiveImages($file, $conversion, $protected);

		foreach ($images as $image) {
			if ($image['width'] == $width) {
				return $image['url'];
			}
		}

		return null;
	}

	public function size(object $file): int
	{
		return $file->size;
	}

	public function getFileId(object $file): string
	{
		return $file->id;
	}

	public function getOrder(object $file): ?int
	{
		return $file->order_column;
	}

	public function getPath(object $file, ?string $conversion = null): string
	{
		return $file->getPath($conversion);
	}

	public function getName(object $file): string
	{
		return pathinfo($file->file_name, PATHINFO_FILENAME);
	}

	public function getTitle(object $file): string
	{
		return $file->name;
	}

	public function getExt(object $file): string
	{
		return $file->extension;
	}

	public function lastModified(object $file): Carbon
	{
		return $file->updated_at;
	}

	public function createdAt(object $file): Carbon
	{
		return $file->created_at;
	}

	public function generateFileName(string $filename): string
	{
		return preg_replace('/[\s' . self::FILENAME_GLUE . ']+/', self::FILENAME_GLUE, basename(trim($filename)));
	}

	public function zip(string $zip, array $collections = []): MediaStream
	{
		$zip = MediaStream::create($this->generateFileName($zip));

		$collections = empty($collections) ? $this->resolveMediaConfig() : $collections;

		foreach ($collections as $collection) {
			$collection = is_string($collection) ? $collection : $collection['collection'];

			$zip->addMedia($this->files($collection));
		}

		return $zip;
	}

	public function saveFromFileUpload(object $file, array $options): ?Media
	{
		if (!$file->isValid()) {
			return null;
		}

		$name = $file->getClientOriginalName();

		return $this->model->addMedia($file)
			->usingFileName($options['name'] ?? $this->generateFileName($name))
			->usingName(pathinfo($options['name'] ?? $name, PATHINFO_FILENAME))
			->toMediaCollection($options['collection']);
	}

	public function saveFromUrl(string $url, array $options): Media
	{
		$media = $this->model->addMediaFromUrl($url);

		if ($options['name'] ?? null) {
			$media = $media->usingFileName($options['name'])->usingName(pathinfo($options['name'], PATHINFO_FILENAME));
		}

		return $media->toMediaCollection($options['collection']);
	}

	public function saveFromString(string $string, array $options): Media
	{
		$media = $this->model->addMediaFromString($string);

		if ($options['name'] ?? null) {
			$media = $media->usingFileName($options['name'])->usingName(pathinfo($options['name'], PATHINFO_FILENAME));
		}

		return $media->toMediaCollection($options['collection']);
	}

	public function saveFromBase64(string $string, array $options): Media
	{
		$media = $this->model->addMediaFromBase64($string);

		if ($options['name'] ?? null) {
			$media = $media->usingFileName($options['name'])->usingName(pathinfo($options['name'], PATHINFO_FILENAME));
		}

		return $media->toMediaCollection($options['collection']);
	}

	public function saveFromStream(mixed $stream, array $options): Media
	{
		$media = $this->model->addMediaFromStream($stream);

		if ($options['name'] ?? null) {
			$media = $media->usingFileName($options['name'])->usingName(pathinfo($options['name'], PATHINFO_FILENAME));
		}

		return $media->toMediaCollection($options['collection']);
	}

	protected function getProtectedMediaRoute(object $file, array $params = []): string
	{
		$expiration = $this->model->getProtectedMediaExpirationTime($file);

		return $expiration ? url()->temporarySignedRoute('api.media.url', $expiration, $params) : url()->signedRoute('api.media.url', $params);
	}

	protected function resolveMediaConfig(array $pairs = []): array
	{
		$resolvedPairs = [];
		$pairs = empty($pairs) ? $this->model->mediaConfig() : $pairs;

		foreach ($pairs as $key => $options) {
			if (is_string($key)) {
				$collection = $options['collection'] ?? $key;
			} else {
				$key = $collection = $options['collection'] ?? $options;
			}

			$disk = $options['disk'] ?? $this->model->getStorageDisk();

			$resolvedPairs[$key] = [
				'collection' => $collection,
				'disk' => $disk,
				'conversions_disk' => $options['conversions_disk'] ?? $disk,
				'max' => $options['max'] ?? 0,
				'name' => isset($options['name']) ? $this->generateFileName($options['name']) : null
			];
		}

		return $resolvedPairs;
	}

	protected function deleteFromRequest(?array $media = []): self
	{
		$media = $this->model->media()->whereIn('id', $media[self::MARKED_REMOVAL_KEY] ?? [])->get();

		foreach ($media as $file) {
			$this->model->deleteMedia($file);
		}

		return $this;
	}

	protected function renameAndReorderFromRequest(?array $media = []): self
	{
		$fileIds = $media[self::IDS_KEY] ?? [];

		if (empty($fileIds)) {
			return $this;
		}

		$newNames = $media[self::NEW_NAMES_KEY] ?? [];
		$titles = $media[self::TITLE_KEY] ?? [];
		$orders = $media[self::ORDER_KEY] ?? [];

		$modelMedia = $this->model->media()->whereIn('id', $fileIds)->get();

		foreach ($fileIds as $id) {
			$file = $modelMedia->firstWhere('id', $id);

			if (!$file) {
				continue;
			}

			$filename = isset($newNames[$id]) ? $this->generateFileName($newNames[$id]) . '.' . $this->getExt($file) : null;

			$file->update([
				'file_name' => $filename ?? $file->file_name,
				'name' => $titles[$id] ?? null,
				'order_column' => $orders[$id] ?? null
			]);
		}

		return $this;
	}

	protected function registerMediaCollections(array $pairs = []): self
	{
		foreach ($pairs as $options) {
			$collection = $this->model->addMediaCollection($options['collection'])->useDisk($options['disk'])->storeConversionsOnDisk($options['conversions_disk']);

			if ($options['max']) {
				$collection->onlyKeepLatest($options['max']);
			}
		}

		return $this;
	}

	protected function saveFromRequest(array $pairs = []): self
	{
		foreach ($pairs as $key => $options) {
			$inputs = $this->request->hasFile($key) ? $this->request->file($key) : $this->request->input($key);

			if (empty($inputs)) {
				continue;
			}

			if (!is_array($inputs)) {
				$inputs = [$inputs];
			}

			foreach ($inputs as $input) {
				if (is_object($input)) {
					$this->saveFromFileUpload($input, $options);
				} elseif (is_resource($input)) {
					$this->saveFromStream($input, $options);
				} elseif (is_string($input)) {
					if (filter_var($input, FILTER_VALIDATE_URL)) {
						$this->saveFromUrl($input, $options);
					} elseif (base64_decode($input)) {
						$this->saveFromBase64($input, $options);
					} else {
						$this->saveFromString($input, $options);
					}
				}
			}
		}

		$this->model->unsetRelation('media');

		return $this;
	}
}
