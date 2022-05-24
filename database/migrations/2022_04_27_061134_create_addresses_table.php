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
		Schema::create('addresses', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->string('name', 150);
			$table->string('country_code', 10);
			$table->uuid('customer_id');
			$table->string('street', 150);
			$table->string('city', 50);
			$table->string('zip_code', 10);
			$table->unsignedTinyInteger('type');
			$table->string('phone', 20)->nullable();

			$table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('addresses');
	}
};
