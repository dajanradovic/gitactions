<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
	public function up(): void
	{
		$this->migrator->inGroup('general', function (SettingsBlueprint $blueprint): void {
			$blueprint->add('erp_client_id');
			$blueprint->add('erp_client_secret');
			$blueprint->add('erp_api_key');
			$blueprint->add('erp_base_url');
		});
	}

	public function down(): void
	{
		$this->migrator->inGroup('general', function (SettingsBlueprint $blueprint): void {
			$blueprint->delete('erp_client_id');
			$blueprint->delete('erp_client_secret');
			$blueprint->delete('erp_api_key');
			$blueprint->delete('erp_base_url');
		});
	}
};
