<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('failed_jobs');
	}

	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('failed_jobs', function (Blueprint $table): void {
			$table->bigIncrements('id');
			$table->string('uuid')->unique();
			$table->text('connection');
			$table->text('queue');
			$table->longText('payload');
			$table->longText('exception');
			$table->timestamp('failed_at')->useCurrent();
		});
	}
};
