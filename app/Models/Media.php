<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Spatie\MediaLibrary\MediaCollections\Models\Media as Model;

class Media extends Model
{
	use UsesUuid;

	protected $guarded = ['id', self::CREATED_AT, self::UPDATED_AT];

	protected $with = ['model'];

	public function setNameAttribute(string $value): void
	{
		$this->attributes['name'] = preg_replace('/\s+/', ' ', $value);
	}
}
