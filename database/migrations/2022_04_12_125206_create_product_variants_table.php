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
		Schema::create('product_variants', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->string('name', 80);
			$table->float('measure');
			$table->float('price');
			$table->uuid('product_id');
			$table->string('product_code')->nullable();

			$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('product_variants');
	}
};
