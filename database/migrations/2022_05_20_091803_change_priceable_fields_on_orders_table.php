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
		Schema::table('orders', function (Blueprint $table) {
			$table->decimal('total_price')->change();
			$table->decimal('shipping_price')->nullable()->change();
			$table->decimal('total_discounts')->nullable()->change();
			$table->decimal('tax_total')->nullable()->change();
			$table->decimal('final_price')->change();
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
		Schema::table('orders', function (Blueprint $table) {
			$table->float('total_price')->change();
			$table->float('shipping_price')->nullable()->change();
			$table->float('total_discounts')->nullable()->change();
			$table->float('tax_total')->nullable()->change();
			$table->float('final_price')->change();
			$table->float('total_price_minus_discounts')->change();
		});
	}
};
