<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pickup_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->text('pickup_address');
            $table->date('preferred_date');
            $table->string('preferred_time');
            $table->text('additional_note')->nullable();
            $table->string('status')->default('Requested'); // Requested|Accepted|Scheduled|Collected|Completed|Cancelled
            $table->string('certificate_path')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickup_requests');
    }
};