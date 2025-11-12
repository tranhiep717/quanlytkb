<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('study_shifts', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('day_of_week')->nullable(); // 1=Mon .. 7=Sun
            $table->unsignedTinyInteger('start_period');
            $table->unsignedTinyInteger('end_period');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_shifts');
    }
};
