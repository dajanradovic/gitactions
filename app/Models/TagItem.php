<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TagItem extends Model
{
	public function tag(): BelongsTo
	{
		return $this->belongsTo(Tag::class);
	}

	public function item(): MorphTo
	{
		return $this->morphTo();
	}
}
