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
		Schema::create('stores', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->string('code', 10);
			$table->string('department', 50);
			$table->string('webshop_name', 50)->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('stores');
	}
};
