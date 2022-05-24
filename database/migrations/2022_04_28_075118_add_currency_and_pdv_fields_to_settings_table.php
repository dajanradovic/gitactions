<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
	public function up(): void
	{
		$this->migrator->inGroup('general', function (SettingsBlueprint $blueprint): void {
			$blueprint->add('pdv_default');
			$blueprint->add('main_currency');
			$blueprint->add('currency_exchange_rate');
		});
	}

	public function down(): void
	{
		$this->migrator->inGroup('general', function (SettingsBlueprint $blueprint): void {
			$blueprint->delete('pdv_default');
			$blueprint->delete('main_currency');
			$blueprint->delete('currency_exhange_rate');
		});
	}
};
