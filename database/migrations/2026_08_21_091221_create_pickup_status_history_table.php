<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pickup_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pickup_request_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->string('changed_by')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickup_status_history');
    }
};