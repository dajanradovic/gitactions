<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('token_blacklists', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->uuidMorphs('user');
			$table->string('token', 500)->unique();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('token_blacklists');
	}
};
