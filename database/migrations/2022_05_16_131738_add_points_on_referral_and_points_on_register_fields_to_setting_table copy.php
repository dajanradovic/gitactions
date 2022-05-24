<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
	public function up(): void
	{
		$this->migrator->inGroup('general', function (SettingsBlueprint $blueprint): void {
			$blueprint->add('points_on_referral', 0);
			$blueprint->add('points_on_register', 0);
		});
	}

	public function down(): void
	{
		$this->migrator->inGroup('general', function (SettingsBlueprint $blueprint): void {
			$blueprint->delete('points_on_referral');
			$blueprint->delete('points_on_register');
		});
	}
};
