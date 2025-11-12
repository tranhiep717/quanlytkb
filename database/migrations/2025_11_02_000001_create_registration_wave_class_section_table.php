<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('registration_wave_class_section', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_wave_id')->constrained('registration_waves')->cascadeOnDelete();
            $table->foreignId('class_section_id')->constrained('class_sections')->cascadeOnDelete();
            $table->unique(['registration_wave_id', 'class_section_id'], 'wave_section_unique');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_wave_class_section');
    }
};
