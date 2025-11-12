<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 50)->default('student')->after('email');
            $table->foreignId('faculty_id')->nullable()->constrained('faculties')->nullOnDelete()->after('role');
            $table->string('class_cohort', 20)->nullable()->after('faculty_id'); // e.g., K17
            $table->boolean('is_locked')->default(false)->after('class_cohort');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('faculty_id');
            $table->dropColumn(['role', 'class_cohort', 'is_locked']);
        });
    }
};
