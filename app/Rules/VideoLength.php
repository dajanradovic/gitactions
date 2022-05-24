<?php

namespace App\Rules;

use FFMpeg\FFProbe;
use Illuminate\Contracts\Validation\Rule;

class VideoLength implements Rule
{
	protected float $minLength;
	protected float $maxLength;

	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct(?float $maxLength = null, float $minLength = 0)
	{
		$this->minLength = $minLength < 0 ? 0 : $minLength;
		$this->maxLength = $maxLength ?? setting('max_video_length');
	}

	public function __toString(): string
	{
		return self::class;
	}

	/**
	 * Determine if the validation rule passes.
	 *
	 * @param string $attribute
	 */
	public function passes($attribute, $value): bool
	{
		$ffprobe = FFProbe::create([
			'ffprobe.binaries' => config('media-library.ffprobe_path')
		]);

		$value = $value->path();

		if (!$ffprobe->isValid($value)) {
			return false;
		}

		$value = floor($ffprobe->format($value)->get('duration'));

		return $value >= $this->minLength && $value <= $this->maxLength;
	}

	/**
	 * Get the validation error message.
	 */
	public function message(): string|array
	{
		return __('validation.custom.video-length', ['min' => $this->minLength, 'max' => $this->maxLength]);
	}
}
