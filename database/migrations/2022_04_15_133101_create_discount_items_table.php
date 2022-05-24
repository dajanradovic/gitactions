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
		Schema::create('discount_items', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->uuid('discount_id');
			$table->uuidMorphs('item');

			$table->foreign('discount_id')->references('id')->on('discounts')->cascadeOnDelete();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('discount_items');
	}
};
