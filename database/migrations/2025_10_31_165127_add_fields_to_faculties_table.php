<?php

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
        Schema::table('faculties', function (Blueprint $table) {
            $table->foreignId('dean_id')->nullable()->after('name')->constrained('users')->nullOnDelete();
            $table->date('founding_date')->nullable()->after('dean_id');
            $table->text('description')->nullable()->after('founding_date');
            $table->boolean('is_active')->default(true)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faculties', function (Blueprint $table) {
            $table->dropForeign(['dean_id']);
            $table->dropColumn(['dean_id', 'founding_date', 'description', 'is_active']);
        });
    }
};
