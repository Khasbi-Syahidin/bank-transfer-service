<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transfer_logs', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_id')->unique();
            $table->string('source_account');
            $table->string('destination_account');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3);
            $table->string('bank_code')->nullable();
            $table->enum('status', ['SUCCESS', 'FAILED', 'PENDING']);
            $table->string('message')->nullable();
            $table->time('scheduled_at')->nullable();
            $table->timestamp('requested_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_logs');
    }
};
