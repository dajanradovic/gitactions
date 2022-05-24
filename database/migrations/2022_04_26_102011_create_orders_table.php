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
		Schema::create('orders', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->uuid('customer_id')->nullable();
			$table->unsignedTinyInteger('status')->nullable();
			$table->unsignedTinyInteger('payment_type')->nullable();
			$table->string('payment_id', 50)->nullable();
			$table->timestamp('payment_created_at')->nullable();
			$table->boolean('ready_for_pickup')->default(false);
			$table->unsignedTinyInteger('number_of_packages')->nullable();
			$table->float('total_price');
			$table->float('shipping_price')->nullable();
			$table->float('total_discounts')->nullable();
			$table->float('tax_total')->nullable();
			$table->uuid('store_id')->nullable();
			$table->json('order_details');
			$table->json('shipping_number')->nullable();
			$table->unsignedTinyInteger('currency');

			$table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
			$table->foreign('store_id')->references('id')->on('stores')->onDelete('set null');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('orders');
	}
};
