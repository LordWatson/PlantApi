<?php

use App\Enums\WaterUnitEnum;
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
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->integer('perenual_id')->nullable();
            $table->string('name');
            $table->string('scientific_name');
            $table->integer('watering');
            $table->integer('watering_period');
            $table->integer('sunlight');
            $table->integer('humidity');
            $table->integer('care_level');
            $table->timestamps();
        });


        Schema::create('user_plants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('last_watered')->nullable();
            $table->string('last_watered_unit')->default(WaterUnitEnum::Days);
            $table->foreignId('plant_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plants');
        Schema::dropIfExists('user_plants');
    }
};
