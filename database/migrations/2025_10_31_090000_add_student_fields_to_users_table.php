<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('code', 50)->nullable()->unique()->after('id'); // MSSV
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('avatar_url')->nullable()->after('phone');
            $table->foreignId('secondary_faculty_id')->nullable()->constrained('faculties')->nullOnDelete()->after('faculty_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('secondary_faculty_id');
            $table->dropColumn(['code', 'phone', 'avatar_url']);
        });
    }
};
