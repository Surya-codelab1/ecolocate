<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->string('facility_name');
            $table->string('contact_person');
            $table->string('email');
            $table->string('area');
            $table->text('full_address');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('accepted_ewaste');
            $table->string('pickup_availability');
            $table->string('working_hours');
            $table->string('status')->default('pending'); // pending|approved|rejected|inactive
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};