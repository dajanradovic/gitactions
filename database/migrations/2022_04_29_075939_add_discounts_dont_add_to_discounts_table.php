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
		Schema::table('discounts', function (Blueprint $table) {
			$table->boolean('add_up_with_other_discounts')->default(false);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('discounts', function (Blueprint $table) {
			$table->dropColumn('add_up_with_other_discounts');
		});
	}
};
