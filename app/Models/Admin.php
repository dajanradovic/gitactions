<?php

namespace App\Models;

use App\Traits\IsUser;
use App\Contracts\IsExtendedUser;

class Admin extends Model implements IsExtendedUser
{
	use IsUser;

	public function profileRoute(?string $default = null): ?string
	{
		return $this->authParent->canViewRoute('users.profile', true) ? route('users.profile') : $default;
	}
}
