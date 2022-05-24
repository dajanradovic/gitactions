<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleRoute extends Model
{
	public function role(): BelongsTo
	{
		return $this->belongsTo(Role::class);
	}
}
