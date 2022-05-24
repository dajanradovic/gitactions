<?php

namespace App\Services\Support;

use Illuminate\Support\Collection;
use Spatie\MediaLibrary\ResponsiveImages\WidthCalculator\FileSizeOptimizedWidthCalculator;

class FixedWidthCalculator extends FileSizeOptimizedWidthCalculator
{
	public function calculateWidths(int $fileSize, int $width, int $height): Collection
	{
		$breakpoints = (array) setting('responsive_images_breakpoints', []);

		return empty($breakpoints)
			? parent::calculateWidths($fileSize, $width, $height)
			: collect($breakpoints)
				->filter(function (int $value) use ($width) {
					return $value < $width;
				})
				->sort();
	}
}
