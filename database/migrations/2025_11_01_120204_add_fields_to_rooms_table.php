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
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('name')->after('code')->nullable(); // Tên phòng
            $table->string('floor', 50)->after('building')->nullable(); // Tầng
            $table->text('equipment')->after('capacity')->nullable(); // Trang thiết bị (JSON)
            $table->enum('status', ['active', 'inactive'])->after('equipment')->default('active'); // Trạng thái
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['name', 'floor', 'equipment', 'status']);
        });
    }
};
