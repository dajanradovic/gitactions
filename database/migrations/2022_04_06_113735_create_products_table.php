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
		Schema::create('products', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->string('name', 100);
			$table->string('description', 500)->nullable();
			$table->float('price');
			$table->float('quantity')->default(0);
			$table->uuid('category_id')->nullable();
			$table->string('erp_id', 50)->nullable();
			$table->unsignedTinyInteger('type')->nullable();
			$table->string('variant_label', 50)->nullable();
			$table->float('weight');
			$table->boolean('active')->default(true);
			$table->json('piktograms')->nullable();
			$table->string('brand', 50)->nullable();
			$table->boolean('gratis')->default(false);
			$table->unsignedSmallInteger('sort_number')->default(0);
			$table->string('code', 30)->nullable();
			$table->string('harvest', 10)->nullable();
			$table->string('slug', 100)->unique();
			$table->unsignedTinyInteger('unit_of_measure')->nullable();
			$table->boolean('unavailable')->default(false);

			$table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('products');
	}
};
