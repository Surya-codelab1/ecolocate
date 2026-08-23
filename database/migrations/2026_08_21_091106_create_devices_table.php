<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model_name');
            $table->string('category');
            $table->text('description')->nullable();
            $table->text('materials')->nullable();
            $table->text('harmful_components')->nullable();
            $table->decimal('estimated_recycling_value', 10, 2)->nullable();
            $table->integer('eco_credits')->default(0);
            $table->text('recycling_information')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};