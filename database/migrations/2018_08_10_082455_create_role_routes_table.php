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
		Schema::dropIfExists('role_routes');
	}

	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('role_routes', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->uuid('role_id');
			$table->string('route', 250);
			$table->timestamps();

			$table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
		});
	}
};
