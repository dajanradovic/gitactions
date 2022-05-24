<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
	public function up(): void
	{
		$this->migrator->inGroup('general', function (SettingsBlueprint $blueprint): void {
			$blueprint->add('iban');
			$blueprint->add('model');
			$blueprint->add('sifra_namjene');
			$blueprint->add('company_name');
			$blueprint->add('company_address');
			$blueprint->add('company_town');
			$blueprint->add('company_zip_code');
			$blueprint->add('company_additional_address_info');
		});
	}

	public function down(): void
	{
		$this->migrator->inGroup('general', function (SettingsBlueprint $blueprint): void {
			$blueprint->delete('iban');
			$blueprint->delete('model');
			$blueprint->delete('sifra_namjene');
			$blueprint->delete('company_name');
			$blueprint->delete('company_address');
			$blueprint->delete('company_town');
			$blueprint->delete('company_zip_code');
			$blueprint->delete('company_additional_address_info');
		});
	}
};
