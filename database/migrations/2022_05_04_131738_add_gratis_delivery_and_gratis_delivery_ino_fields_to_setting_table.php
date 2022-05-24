<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
	public function up(): void
	{
		$this->migrator->inGroup('general', function (SettingsBlueprint $blueprint): void {
			$blueprint->add('gratis_delivery');
			$blueprint->add('gratis_delivery_ino');
		});
	}

	public function down(): void
	{
		$this->migrator->inGroup('general', function (SettingsBlueprint $blueprint): void {
			$blueprint->delete('gratis_delivery');
			$blueprint->delete('gratis_delivery_ino');
		});
	}
};
