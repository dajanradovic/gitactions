<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface HasLimitedScope
{
	public function scopeUserScope(Builder $query): Builder;
}
