<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('class_sections', function (Blueprint $table) {
            $table->id();
            $table->string('academic_year', 20); // e.g., 2024-2025
            $table->string('term', 20); // e.g., HK1, HK2, HE
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('section_code', 10); // e.g., L01
            $table->foreignId('lecturer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedTinyInteger('day_of_week');
            $table->foreignId('shift_id')->constrained('study_shifts')->cascadeOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->unsignedInteger('max_capacity');
            $table->timestamps();
            $table->unique(['academic_year', 'term', 'course_id', 'section_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_sections');
    }
};
