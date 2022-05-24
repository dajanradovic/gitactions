<?php

namespace App\Contracts;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notification;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Relations\MorphOne;

interface IsExtendedUser extends HasJwt, HasLimitedScope
{
	public function authParent(): MorphOne;

	public function profileRoute(?string $default = null): ?string;

	public function getUserType(): string;

	public function verifyEmailNotification(): Notification;

	public function passwordResetNotification(string $token): Notification;

	public function passwordResetTokenUrl(string $token): string;

	public function verifyEmailRedirectUrl(): ?string;

	public function passwordResetRedirectUrl(): ?string;

	public function getModelResource(User $authParent): JsonResource;

	public function getUserExtraData(?Request $request = null): ?array;

	public function getPublicUserExtraData(?Request $request = null): ?array;

	public function mustVerifyEmail(): bool;

	public function isAvailable(): bool;

	public function hasLimitedScope(HasLimitedScope $model): bool;

	public function updateProfileValidationRules(?Request $request = null): array;

	public function updateProfileLogic(Request $request): array;

	public function scopeAvailable(Builder $query): Builder;

	public function scopeNotifiable(Builder $query): Builder;

	public function scopeCanViewRoute(Builder $query, ?string $route = null): Builder;

	public function scopeLoginLogic(Builder $query, Request $request): Builder;
}
