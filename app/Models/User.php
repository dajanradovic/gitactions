<?php

namespace App\Models;

use App\Traits\HasRole;
use App\Traits\UsesUuid;
use App\Contracts\HasJwt;
use App\Traits\HasStorage;
use App\Contracts\HasMedia;
use Illuminate\Http\Request;
use App\Traits\HasPushDevice;
use Illuminate\Support\Carbon;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Login;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Prunable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Translation\HasLocalePreference;

class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference, HasMedia, HasJwt
{
	use Prunable, HasPushDevice, HasRole, HasStorage, Notifiable, UsesUuid, TwoFactorAuthenticatable, HasFactory;

	protected $casts = [
		'active' => 'boolean',
		'allow_push_notifications' => 'boolean',
		'email_verified_at' => 'datetime',
	];

	protected $guarded = ['id', self::CREATED_AT, self::UPDATED_AT];

	protected $hidden = [
		'password', 'remember_token',
	];

	protected $attributes = [
		'active' => true,
		'allow_push_notifications' => true,
	];

	protected $touches = ['user'];

	protected $with = ['user', 'role'];

	public function setEmailAttribute(string $value): void
	{
		$this->attributes['email'] = strtolower($value);
	}

	public function setNameAttribute(string $value): void
	{
		$this->attributes['name'] = preg_replace('/\s+/', ' ', $value);
	}

	public function setPasswordAttribute(?string $value): void
	{
		$this->attributes['password'] = $value ? bcrypt($value) : null;
	}

	public function setTimezoneAttribute(?string $value): void
	{
		$this->attributes['timezone'] = $value ?? setting('timezone');
	}

	public function setLocaleAttribute(?string $value): void
	{
		$this->attributes['locale'] = $value ?? config('app.locale');
	}

	public function user(): MorphTo
	{
		return $this->morphTo();
	}

	public function getModelResource(): JsonResource
	{
		return $this->user->getModelResource($this);
	}

	public function getJwtValidFromTime(): ?Carbon
	{
		return $this->user->getJwtValidFromTime();
	}

	public function getJwtValidUntilTime(): ?Carbon
	{
		return $this->user->getJwtValidUntilTime();
	}

	public function getJwtCustomClaims(): array
	{
		return $this->user->getJwtCustomClaims();
	}

	public function getSilentLoginUrl(int $expiration = 0): string
	{
		return $expiration ? url()->temporarySignedRoute('login.silent', now()->addMinutes($expiration), ['user' => $this->id]) : url()->signedRoute('login.silent', ['user' => $this->id]);
	}

	public function handleShouldVerifyEmail(): self
	{
		if ($this->hasVerifiedEmail()) {
			return $this;
		}

		if ($this->user->mustVerifyEmail()) {
			$this->sendEmailVerificationNotification();
		} else {
			$this->markEmailAsVerified();
		}

		return $this;
	}

	public function detectIfEmailUpdate(): self
	{
		if ($this->isDirty('email') && $this->user->mustVerifyEmail()) {
			$this->email_verified_at = null;
		}

		return $this;
	}

	public function handleEmailUpdate(): self
	{
		if ($this->isDirty('email') && $this->user->mustVerifyEmail()) {
			$this->sendEmailVerificationNotification();
		}

		return $this;
	}

	public function getDefaultMediaPrefix(): string
	{
		return 'users';
	}

	public function mediaConfig(): array
	{
		return [
			'avatar' => [
				'max' => 1
			],
		];
	}

	public function getAvatar(): ?string
	{
		return $this->avatar ?? $this->storage()->getFirstThumb('avatar');
	}

	public function preferredLocale(): string
	{
		return $this->locale ?? config('app.locale');
	}

	public function activities(): HasMany
	{
		return $this->hasMany(UserActivity::class);
	}

	public function sessions(): HasMany
	{
		return $this->hasMany(Session::class);
	}

	public function fireLoginEvent(?string $guard = null, bool $remember = false): self
	{
		event(new Login($guard ?? config('auth.defaults.guard'), $this, $remember));

		return $this;
	}

	public function sendEmailVerificationNotification(): self
	{
		$this->notify($this->user->verifyEmailNotification());

		return $this;
	}

	public function sendPasswordResetNotification($token): self
	{
		$this->notify($this->user->passwordResetNotification($token));

		return $this;
	}

	public function scopeIncludes(Builder $query): Builder
	{
		return $query->with('media');
	}

	public function scopeAvailable(Builder $query): Builder
	{
		return $query->where('active', true)->whereNotNull('email_verified_at')->has('user');
	}

	public function scopeUserScope(Builder $query): Builder
	{
		return $query->where('id', auth()->user()->id);
	}

	public function scopeLoginLogic(Builder $query, Request $request): Builder
	{
		return $query->where('active', true)->whereHas('user', function ($query) use ($request): void {
			$query->loginLogic($request);
		});
	}

	public function isAvailable(): bool
	{
		return $this->active && $this->hasVerifiedEmail() && $this->user && $this->user->isAvailable();
	}

	public function scopeSearch(Builder $query, ?string $search = null): Builder
	{
		$search = preg_replace('/\s+/', '%', $search ?? '');
		$search = empty($search) ? null : '%' . $search . '%';

		return !$search ? $query : $query->where(function ($query) use ($search): void {
			$query->orWhere('name', 'like', $search)->orWhere('email', 'like', $search);
		});
	}

	public function prunable(): Builder
	{
		return static::doesntHave('user')->orWhere(function ($query): void {
			$query->whereNull('email_verified_at')->where('created_at', '<', formatTimestamp(now()->subMinutes(setting('email_verification_timeout'))));
		});
	}

	protected static function boot(): void
	{
		parent::boot();

		static::observe(UserObserver::class);
	}
}
