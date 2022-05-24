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
		Schema::create('banners', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->string('title', 50);
			$table->unsignedTinyInteger('type');
			$table->string('subtitle', 50)->nullable();
			$table->unsignedTinyInteger('order_column')->default(0);
			$table->string('url', 256)->nullable();
			$table->boolean('active')->default(false);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('banners');
	}
};
