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
			$table->boolean('guest_mode')->default(true);
			$table->json('order_details')->nullable()->change();
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
			$table->dropColumn('guest_mode');
			$table->json('order_details');
		});
	}
};
