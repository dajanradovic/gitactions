<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('roles');
	}

	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('roles', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->string('name', 50);
			$table->boolean('protected')->default(false);
			$table->unsignedTinyInteger('mode');
			$table->unsignedTinyInteger('api_rate_limit')->default(60);
			$table->unsignedTinyInteger('api_rate_limit_backoff_minutes')->default(1);
			$table->json('disallowed_methods')->nullable();
			$table->timestamps();
		});
	}
};
