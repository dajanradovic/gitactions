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
			$table->float('total_price');
			$table->float('total_price_minus_discounts');
			$table->unsignedSmallInteger('quantity');
			$table->string('tax_rate', 20)->nullable();
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
			$table->dropColumn('total_price');
			$table->dropColumn('total_price_minus_discounts');
			$table->dropColumn('quantity');
			$table->dropColumn('tax_rate');
		});
	}
};
