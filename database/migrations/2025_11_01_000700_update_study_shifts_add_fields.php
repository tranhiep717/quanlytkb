<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('study_shifts', function (Blueprint $table) {
            $table->string('code', 20)->nullable()->after('id');
            $table->string('name', 100)->nullable()->after('code');
            $table->time('start_time')->nullable()->after('end_period');
            $table->time('end_time')->nullable()->after('start_time');
            $table->string('status', 20)->default('active')->after('end_time');
        });
    }

    public function down(): void
    {
        Schema::table('study_shifts', function (Blueprint $table) {
            $table->dropColumn(['code', 'name', 'start_time', 'end_time', 'status']);
        });
    }
};
