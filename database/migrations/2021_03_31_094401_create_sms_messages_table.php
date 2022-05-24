<?php

use App\Models\SmsMessage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('sms_messages', function (Blueprint $table): void {
			$table->uuid('id')->primary();
			$table->nullableUuidMorphs('parent');
			$table->unsignedTinyInteger('provider');
			$table->string('from', 20);
			$table->string('to', 20);
			$table->string('body', 1600);
			$table->string('external_id', 50)->nullable();
			$table->unsignedTinyInteger('message_count')->default(0);
			$table->double('price')->unsigned()->default(0);
			$table->string('price_currency', 3)->default(SmsMessage::DEFAULT_CURRENCY);
			$table->string('status', 20)->nullable();
			$table->unsignedInteger('error_code')->default(0);
			$table->unsignedInteger('request_error_code')->nullable();
			$table->string('request_error_message', 100)->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('sms_messages');
	}
};
