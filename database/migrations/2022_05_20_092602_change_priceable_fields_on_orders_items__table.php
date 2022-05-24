<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_items', function (Blueprint $table) {
			$table->decimal('price')->change();
			$table->decimal('tax')->nullable()->change();
			$table->decimal('discount_amount')->nullable()->change();
			$table->decimal('total_price')->change();
			$table->decimal('total_price_minus_discounts')->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_items', function (Blueprint $table) {
			$table->float('price')->change();
			$table->float('tax')->nullable()->change();
			$table->float('discount_amount')->nullable()->change();
			$table->float('total_price')->change();
			$table->float('total_price_minus_discounts')->change();
		});
	}
};
