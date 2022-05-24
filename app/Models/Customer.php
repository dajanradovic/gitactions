<?php

namespace App\Models;

use Notification;
use App\Traits\IsUser;
use Illuminate\Http\Request;
use App\Contracts\IsExtendedUser;
use Illuminate\Support\Collection;
use App\Observers\CustomerObserver;
use App\Notifications\ReferralAcceptedMail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model implements IsExtendedUser
{
	use IsUser;

	protected $casts = [
		'newsletter' => 'boolean',
		'club_card' => 'boolean',
		'date_of_birth' => 'date',
	];

	public function passwordResetTokenUrl(string $token): string
	{
		$attributes = [
			'token' => $token,
			'email' => $this->authParent->getEmailForPasswordReset(),
		];

		return config('custom.frontend_password_reset_form_url') . '?' . http_build_query($attributes);
	}

	public function verifyEmailRedirectUrl(): ?string
	{
		return config('custom.frontend_password_verification_redirect_url');
	}

	public function passwordResetRedirectUrl(): ?string
	{
		return config('custom.frontend_password_reset_redirect_url');
	}

	public function handleReferral(CheeseClub $cheeseClub): void
	{
		$referral = Referral::where('email', $this->authParent->email)->first();

		if ($referral) {
			$referral->update([
				'resolved_at' => formatTimestamp(),
			]);

			$referrer = $referral->customer;
			$referred = $this->getFullName();

			$cheeseClub->update([
				'points' => $cheeseClub->points + (setting('points_on_referral') ?? 0)
			]);

			if ($referrer->cheeseclub) {
				$referrer->cheeseclub->update([
					'points' => $referrer->cheeseclub->points + (setting('points_on_referral') ?? 0)
				]);
			}

			Notification::route('mail', $this->authParent->email)->notify(new ReferralAcceptedMail($referrer->getFullName(), $referred));
			Notification::route('mail', $referrer->authParent->email)->notify(new ReferralAcceptedMail($referrer->getFullName(), $referred));
		}
	}

	public function makeCheeseClubMember(): CheeseClub
	{
		$points = 0;

		if ($points = CheeseClub::where('email', $this->authParent->email)->first()) {
			$points = $points->points;
		}

		return CheeseClub::updateOrCreate(['email' => $this->authParent->email], [
			'name' => $this->authParent->name,
			'surname' => $this->surname,
			'date_of_birth' => $this->date_of_birth,
			'points' => setting('points_on_register') ?? $points
		]);
	}

	public function getUserExtraData(?Request $request = null): ?array
	{
		return [
			'oib' => $this->oib,
			'surname' => $this->surname,
			'date_of_birth' => $this->date_of_birth,
			'company_name' => $this->company_name,
			'newsletter' => $this->newsletter,
		];
	}

	public function getPublicUserExtraData(?Request $request = null): ?array
	{
		return [
			'surname' => $this->surname,
			'company_name' => $this->company_name,
		];
	}

	public function reviews(): HasMany
	{
		return $this->hasMany(Review::class);
	}

	public function hasAlreadyMadeGeneralReview(): bool
	{
		return $this->reviews()->generalReview()->count() > 0;
	}

	public function canReviewProduct(string $id): bool
	{
		return $this->reviews()->productReview()->where('product_id', $id)->exists();
	}

	public function getFullName(): string
	{
		return $this->authParent->name . ' ' . $this->surname;
	}

	public function addresses(): HasMany
	{
		return $this->hasMany(Address::class);
	}

	public function cheeseclub(): BelongsTo
	{
		return $this->belongsTo(CheeseClub::class, 'cheese_club_id');
	}

	public function getPoints(): int
	{
		return $this->cheeseclub->points;
	}

	public function likes(): HasMany
	{
		return $this->hasMany(Like::class);
	}

	public function getAddress(): Address|Collection
	{
		$addresses = $this->addresses;

		return $addresses->when($addresses->firstWhere('type', Address::DELIVERY_ADDRESS), function ($addresses) {
			return $addresses->firstWhere('type', Address::DELIVERY_ADDRESS);
		}, function ($addresses) {
			return $addresses->firstWhere('type', Address::INVOICE_ADDRESS) ?? null;
		});
	}

	public function getFullAddressByType(int $type): string|null
	{
		$address = $this->addresses->where('type', $type)->first();

		return $address->name ?? null;
	}

	public function referrals(): HasMany
	{
		return $this->hasMany(Referral::class, 'referrer_id');
	}

	public function createReferral(string $email): Referral
	{
		return $this->referrals()->create([
			'email' => $email,
		]);
	}

	public function orders(): HasMany
	{
		return $this->hasMany(Order::class);
	}

	protected static function initObservers(): ?string
	{
		return CustomerObserver::class;
	}
}
