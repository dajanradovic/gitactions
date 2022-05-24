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
		Schema::dropIfExists('admins');
	}

	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('admins', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->timestamps();
		});
	}
};
