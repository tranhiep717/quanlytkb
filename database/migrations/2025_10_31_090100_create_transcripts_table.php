<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transcripts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('academic_year', 20)->nullable();
            $table->string('term', 10)->nullable();
            $table->unsignedTinyInteger('credits');
            $table->string('grade_letter', 2)->nullable();
            $table->decimal('grade_point', 3, 2)->nullable();
            $table->boolean('passed')->default(false);
            $table->timestamps();
            $table->unique(['student_id', 'course_id', 'academic_year', 'term'], 'uniq_transcript');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transcripts');
    }
};
