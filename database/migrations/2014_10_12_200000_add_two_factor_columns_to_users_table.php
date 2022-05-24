<?php

use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::table('users', function (Blueprint $table): void {
			$table->text('two_factor_secret')
					->after('password')
					->nullable();

			$table->text('two_factor_recovery_codes')
					->after('two_factor_secret')
					->nullable();

			if (Fortify::confirmsTwoFactorAuthentication()) {
				$table->timestamp('two_factor_confirmed_at')
					->after('two_factor_recovery_codes')
					->nullable();
			}
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('users', function (Blueprint $table): void {
			$table->dropColumn(['two_factor_secret', 'two_factor_recovery_codes']);

			if (Fortify::confirmsTwoFactorAuthentication()) {
				$table->dropColumn('two_factor_confirmed_at');
			}
		});
	}
};
