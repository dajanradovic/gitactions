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
		Schema::create('product_filters', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->string('value', 500);
			$table->uuid('product_id');
			$table->uuid('filter_categories_id');

			$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
			$table->foreign('filter_categories_id')->references('id')->on('category_filters')->onDelete('cascade');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('product_filters');
	}
};
