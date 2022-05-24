<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class TokenBlacklist extends Model
{
	public function user(): MorphTo
	{
		return $this->morphTo();
	}
}
