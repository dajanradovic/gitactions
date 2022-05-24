<?php

namespace App\Models;

use App\Observers\CheeseClubObserver;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CheeseClub extends Model
{
	public const TYPE_1 = 1;

	protected $casts = [
		'club_type' => 'integer',
		'date_of_birth' => 'date',
	];

	public static function getTypes(): array
	{
		return [
			self::TYPE_1,
		];
	}

	public static function getTypeName(int $type): string
	{
		$club_type = [
			self::TYPE_1 => __('cheese-club.type-1'),
		];

		return $club_type[$type];
	}

	public function customer(): HasOne
	{
		return $this->hasOne(Customer::class, 'cheese_club_id');
	}

	public function assignIfCustomerExists(): void
	{
		$user = User::where('email', $this->email)->first();

		if ($user) {
			$user->user->update([
				'cheese_club_id' => $this->id,
			]);
		}
	}

	protected static function initObservers(): ?string
	{
		return CheeseClubObserver::class;
	}
}
