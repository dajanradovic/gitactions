<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class Translation extends Model
{
	public function item(): MorphTo
	{
		return $this->morphTo();
	}
}
