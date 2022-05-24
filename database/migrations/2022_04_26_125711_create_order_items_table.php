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
		Schema::create('order_items', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->uuid('order_id');
			$table->uuid('product_id')->nullable();
			$table->uuid('product_variant_id')->nullable();
			$table->float('price');
			$table->float('tax')->nullable();
			$table->float('discount_amount')->nullable();
			$table->json('discounts_applied')->nullable();
			$table->json('order_item_details');

			$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
			$table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
			$table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('set null');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('order_items');
	}
};
