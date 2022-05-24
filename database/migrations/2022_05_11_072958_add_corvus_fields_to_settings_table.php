<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
	public function up(): void
	{
		$this->migrator->inGroup('general', function (SettingsBlueprint $blueprint): void {
			$blueprint->add('corvus_version', '1.3');
			$blueprint->add('corvus_store_id', '20426');
			$blueprint->add('corvus_language', 'hr');
			$blueprint->add('corvus_currency', 'HRK');
			$blueprint->add('corvus_secret_key', 'DkIY9nMk41TK9hmwdvGU69FYI');
			$blueprint->add('corvus_require_complete', false);
			$blueprint->add('corvus_success_url');
			$blueprint->add('corvus_cancel_url');
		});
	}

	public function down(): void
	{
		$this->migrator->inGroup('general', function (SettingsBlueprint $blueprint): void {
			$blueprint->delete('corvus_version');
			$blueprint->delete('corvus_store_id');
			$blueprint->delete('corvus_language');
			$blueprint->delete('corvus_currency');
			$blueprint->delete('corvus_secret_key');
			$blueprint->delete('corvus_require_complete');
			$blueprint->delete('corvus_success_url');
			$blueprint->delete('corvus_cancel_url');
		});
	}
};
