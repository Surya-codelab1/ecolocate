<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eco_credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pickup_request_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('device_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('credits');
            $table->string('type'); // earned|adjusted
            $table->string('description')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eco_credit_transactions');
    }
};