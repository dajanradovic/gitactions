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
		Schema::create('reviews', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->uuid('product_id')->nullable();
			$table->uuid('customer_id');
			$table->unsignedTinyInteger('rating')->nullable();
			$table->string('description', 300)->nullable();
			$table->boolean('active')->default(false);

			$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
			$table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('reviews');
	}
};
