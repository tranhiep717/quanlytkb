<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassSection;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RegistrationsForReportSeeder extends Seeder
{
    /**
     * Seeder để tạo dữ liệu đăng ký đầy đủ cho báo cáo
     * Tạo đủ sinh viên và đăng ký vào các lớp để test các trường hợp
     */
    public function run(): void
    {
        $this->command->info('Bắt đầu tạo dữ liệu đăng ký cho báo cáo...');

        // Lấy các lớp học phần
        $sections = ClassSection::where('academic_year', '2024-2025')
            ->where('term', 'HK1')
            ->with('course')
            ->get();

        if ($sections->isEmpty()) {
            $this->command->error('Không tìm thấy lớp học phần. Chạy ClassSectionsTestSeeder trước!');
            return;
        }

        // Đảm bảo có đủ sinh viên
        $this->ensureEnoughStudents(100);

        // Lấy danh sách sinh viên
        $students = User::where('role', 'student')->get();
        $this->command->info("Có {$students->count()} sinh viên trong hệ thống");

        // Đăng ký sinh viên vào các lớp
        foreach ($sections as $section) {
            $this->registerStudentsToSection($section, $students);
        }

        $this->command->newLine();
        $this->command->info('✅ Hoàn tất! Thống kê đăng ký:');
        $this->showRegistrationStats();
    }

    /**
     * Đảm bảo có đủ sinh viên trong hệ thống
     */
    private function ensureEnoughStudents(int $count): void
    {
        $currentCount = User::where('role', 'student')->count();

        if ($currentCount >= $count) {
            $this->command->info("✓ Đã có {$currentCount} sinh viên");
            return;
        }

        $needed = $count - $currentCount;
        $this->command->info("Đang tạo thêm {$needed} sinh viên...");

        $faculties = DB::table('faculties')->pluck('id')->toArray();

        for ($i = $currentCount + 1; $i <= $count; $i++) {
            User::create([
                'code' => 'SV' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => 'Sinh viên Test ' . $i,
                'email' => 'svtest' . $i . '@university.edu.vn',
                'password' => bcrypt('123456'),
                'role' => 'student',
                'faculty_id' => $faculties[array_rand($faculties)],
                'class_cohort' => 'K20',
            ]);
        }

        $this->command->info("✓ Đã tạo {$needed} sinh viên mới");
    }

    /**
     * Đăng ký sinh viên vào một lớp học phần
     */
    private function registerStudentsToSection(ClassSection $section, $students): void
    {
        $sectionCode = $section->section_code;
        $maxCapacity = $section->max_capacity;

        // Xác định số lượng sinh viên cần đăng ký
        $targetEnrolled = match ($sectionCode) {
            'IT101.01' => 60,  // Đầy hoàn toàn
            'IT201.02' => 60,  // Đầy hoàn toàn
            'EC101.01' => 58,  // Gần đầy (để test đăng ký còn 2 chỗ)
            'IT101.02' => 55,  // Gần đầy
            'IT102.01' => 45,  // Trung bình
            'IT201.01' => 40,  // Trung bình
            'IT201.03' => 35,  // Trung bình
            'IT202.01' => 30,  // Ít
            default => min(floor($maxCapacity * 0.6), $maxCapacity), // 60% capacity cho các lớp khác
        };

        // Lấy số lượng đã đăng ký
        $currentEnrolled = DB::table('registrations')
            ->where('class_section_id', $section->id)
            ->count();

        if ($currentEnrolled >= $targetEnrolled) {
            $this->command->info("  ✓ {$sectionCode}: Đã có {$currentEnrolled}/{$maxCapacity} sinh viên");
            return;
        }

        $needed = $targetEnrolled - $currentEnrolled;

        // Lấy sinh viên chưa đăng ký lớp này
        $registeredStudentIds = DB::table('registrations')
            ->where('class_section_id', $section->id)
            ->pluck('student_id')
            ->toArray();

        $availableStudents = $students->whereNotIn('id', $registeredStudentIds);

        if ($availableStudents->isEmpty()) {
            $this->command->warn("  ⚠ {$sectionCode}: Không đủ sinh viên để đăng ký");
            return;
        }

        // Đăng ký sinh viên
        $toRegister = $availableStudents->random(min($needed, $availableStudents->count()));

        foreach ($toRegister as $student) {
            DB::table('registrations')->insert([
                'student_id' => $student->id,
                'class_section_id' => $section->id,
                'created_at' => now()->subDays(rand(1, 20)),
                'updated_at' => now()->subDays(rand(1, 20)),
            ]);
        }

        $newTotal = $currentEnrolled + $toRegister->count();
        $status = $newTotal >= $maxCapacity ? '🔴 ĐẦY' : ($newTotal >= $maxCapacity * 0.8 ? '🟡 GẦN ĐẦY' : '🟢 CÒN CHỖ');

        $this->command->info("  ✓ {$sectionCode}: {$currentEnrolled} → {$newTotal}/{$maxCapacity} {$status}");
    }

    /**
     * Hiển thị thống kê đăng ký
     */
    private function showRegistrationStats(): void
    {
        $sections = ClassSection::where('academic_year', '2024-2025')
            ->where('term', 'HK1')
            ->with(['course', 'registrations'])
            ->get();

        $tableData = [];
        foreach ($sections as $section) {
            $enrolled = $section->registrations->count();
            $percent = round(($enrolled / $section->max_capacity) * 100, 1);

            $status = match (true) {
                $enrolled >= $section->max_capacity => '🔴 Đầy',
                $enrolled >= $section->max_capacity * 0.8 => '🟡 Gần đầy',
                default => '🟢 Còn chỗ'
            };

            // Lấy thông tin tiên quyết
            $prereq = DB::table('course_prerequisites')
                ->join('courses', 'course_prerequisites.prerequisite_course_id', '=', 'courses.id')
                ->where('course_prerequisites.course_id', $section->course_id)
                ->pluck('courses.code')
                ->implode(', ');

            $tableData[] = [
                $section->section_code,
                $section->course->name,
                $prereq ?: '-',
                "{$enrolled}/{$section->max_capacity}",
                "{$percent}%",
                $status,
            ];
        }

        $this->command->newLine();
        $this->command->table(
            ['Mã lớp', 'Môn học', 'Tiên quyết', 'Sĩ số', '%', 'Trạng thái'],
            $tableData
        );

        $this->command->newLine();
        $this->command->info('📊 TỔNG QUAN:');
        $totalSections = $sections->count();
        $fullSections = $sections->filter(function ($s) {
            return $s->registrations->count() >= $s->max_capacity;
        })->count();
        $nearFullSections = $sections->filter(function ($s) {
            $count = $s->registrations->count();
            return $count >= $s->max_capacity * 0.8 && $count < $s->max_capacity;
        })->count();
        $totalRegistrations = DB::table('registrations')
            ->whereIn('class_section_id', $sections->pluck('id'))
            ->count();

        $this->command->info("  • Tổng số lớp: {$totalSections}");
        $this->command->info("  • Lớp đã đầy: {$fullSections}");
        $this->command->info("  • Lớp gần đầy: {$nearFullSections}");
        $this->command->info("  • Tổng số đăng ký: {$totalRegistrations}");

        $this->command->newLine();
        $this->command->info('🎯 CÁC TRƯỜNG HỢP TEST:');
        $this->command->info('  1. 🔴 Hết slot: IT101.01, IT201.02');
        $this->command->info('  2. 🟡 Gần đầy (test đăng ký còn vài chỗ): EC101.01 (58/60)');
        $this->command->info('  3. ⚠️ Thiếu tiên quyết: IT201.01, IT201.02, IT201.03, IT202.01');
        $this->command->info('  4. ⚠️ Trùng lịch: EC101.01 + IT201.03 (cùng Thứ 6, Ca 1)');
        $this->command->info('  5. 🟢 Đăng ký thành công: IT101.02, IT102.01');
    }
}
