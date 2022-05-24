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
		Schema::dropIfExists('push_devices');
	}

	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('push_devices', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->uuidMorphs('user');
			$table->string('device_id', 128);
			$table->string('app_version', 20)->nullable();
			$table->unsignedTinyInteger('device_type')->nullable();
			$table->string('device_model', 50)->nullable();
			$table->string('device_os', 20)->nullable();
			$table->string('timezone', 50)->nullable();
			$table->timestamps();
		});
	}
};
