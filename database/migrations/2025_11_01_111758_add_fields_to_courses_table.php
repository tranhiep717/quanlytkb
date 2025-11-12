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
        Schema::table('courses', function (Blueprint $table) {
            $table->string('type', 100)->nullable()->after('faculty_id'); // Loại HP: Bắt buộc, Tự chọn, Đại cương, Chuyên ngành
            $table->text('description')->nullable()->after('type'); // Mô tả học phần
            $table->boolean('is_active')->default(true)->after('description'); // Trạng thái: Hoạt động/Không hoạt động
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['type', 'description', 'is_active']);
        });
    }
};
