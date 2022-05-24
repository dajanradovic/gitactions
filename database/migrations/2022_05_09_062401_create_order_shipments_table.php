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
		Schema::create('order_shipments', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->uuid('order_id');
			$table->string('shipment_number', 30)->nullable();

			$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('order_shipments');
	}
};
