<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\ClassSection;
use App\Models\Faculty;
use App\Models\Room;
use App\Models\StudyShift;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ClassSectionsTestSeeder extends Seeder
{
    /**
     * Seeder để tạo dữ liệu mẫu test các trường hợp:
     * - Lớp đã đầy (hết slot)
     * - Môn có tiên quyết (để test thiếu tiên quyết)
     * - Lớp còn chỗ (để đăng ký thành công)
     * - Lớp trùng lịch
     */
    public function run(): void
    {
        // Lấy faculty
        $cntt = Faculty::where('code', 'CNTT')->first();
        $dtvt = Faculty::where('code', 'DTVT')->first();

        if (!$cntt || !$dtvt) {
            $this->command->error('Cần có faculty CNTT và DTVT. Chạy DatabaseSeeder trước!');
            return;
        }

        // Lấy giảng viên hoặc tạo mới nếu chưa có
        $lecturer1 = User::where('role', 'lecturer')->where('code', 'GV001')->first();
        if (!$lecturer1) {
            $lecturer1 = User::create([
                'code' => 'GV001',
                'name' => 'Nguyễn Văn A',
                'email' => 'gv001@university.edu.vn',
                'password' => bcrypt('123456'),
                'role' => 'lecturer',
                'faculty_id' => $cntt->id,
            ]);
            $this->command->info('✅ Đã tạo giảng viên GV001');
        }

        $lecturer2 = User::where('role', 'lecturer')->where('code', 'GV002')->first();
        if (!$lecturer2) {
            $lecturer2 = User::create([
                'code' => 'GV002',
                'name' => 'Trần Thị B',
                'email' => 'gv002@university.edu.vn',
                'password' => bcrypt('123456'),
                'role' => 'lecturer',
                'faculty_id' => $cntt->id,
            ]);
            $this->command->info('✅ Đã tạo giảng viên GV002');
        }

        // Lấy phòng học
        $rooms = Room::whereIn('code', ['B201', 'B202', 'B203', 'A101'])->get()->keyBy('code');

        // Lấy ca học
        $shifts = StudyShift::all()->keyBy('id');

        // ============================================
        // 1. Môn CƠ BẢN (không có tiên quyết)
        // ============================================

        // Lập trình cơ bản (IT101) - Không có tiên quyết
        $it101 = Course::updateOrCreate(
            ['code' => 'IT101'],
            [
                'name' => 'Lập trình cơ bản',
                'credits' => 3,
                'faculty_id' => $cntt->id,
                'description' => 'Môn học cơ bản về lập trình'
            ]
        );

        // Mạch điện tử (EC101) - Không có tiên quyết
        $ec101 = Course::updateOrCreate(
            ['code' => 'EC101'],
            [
                'name' => 'Mạch điện tử',
                'credits' => 3,
                'faculty_id' => $dtvt->id,
                'description' => 'Môn học về mạch điện tử cơ bản'
            ]
        );

        // Cấu trúc dữ liệu (IT102) - Không có tiên quyết
        $it102 = Course::updateOrCreate(
            ['code' => 'IT102'],
            [
                'name' => 'Cấu trúc dữ liệu',
                'credits' => 3,
                'faculty_id' => $cntt->id,
                'description' => 'Môn học về cấu trúc dữ liệu'
            ]
        );

        // ============================================
        // 2. MÔN CÓ TIÊN QUYẾT
        // ============================================

        // Lập trình hướng đối tượng (IT201) - Tiên quyết: IT101
        $it201 = Course::updateOrCreate(
            ['code' => 'IT201'],
            [
                'name' => 'Lập trình hướng đối tượng',
                'credits' => 3,
                'faculty_id' => $cntt->id,
                'description' => 'Môn học về OOP, yêu cầu hoàn thành IT101'
            ]
        );

        // Thêm tiên quyết
        DB::table('course_prerequisites')->updateOrInsert(
            ['course_id' => $it201->id, 'prerequisite_course_id' => $it101->id],
            []
        );

        // Cơ sở dữ liệu (IT202) - Tiên quyết: IT102
        $it202 = Course::updateOrCreate(
            ['code' => 'IT202'],
            [
                'name' => 'Cơ sở dữ liệu',
                'credits' => 3,
                'faculty_id' => $cntt->id,
                'description' => 'Môn học về database, yêu cầu hoàn thành IT102'
            ]
        );

        DB::table('course_prerequisites')->updateOrInsert(
            ['course_id' => $it202->id, 'prerequisite_course_id' => $it102->id],
            []
        );

        // ============================================
        // 3. TẠO CÁC LỚP HỌC PHẦN (HK1 2024-2025)
        // ============================================

        $this->command->info('Tạo lớp học phần cho HK1 2024-2025...');

        // --- EC101: Mạch điện tử ---
        // Lớp 1: CÒN CHỖ (45/60)
        ClassSection::updateOrCreate(
            ['section_code' => 'EC101.01', 'academic_year' => '2024-2025', 'term' => 'HK1'],
            [
                'course_id' => $ec101->id,
                'lecturer_id' => $lecturer1->id,
                'room_id' => $rooms['B201']->id,
                'shift_id' => 1, // Ca 1 (7:00-9:00)
                'day_of_week' => 5, // Thứ 5
                'max_capacity' => 60,
            ]
        );

        // --- IT101: Lập trình cơ bản ---
        // Lớp 1: ĐÃ ĐẦY (60/60)
        ClassSection::updateOrCreate(
            ['section_code' => 'IT101.01', 'academic_year' => '2024-2025', 'term' => 'HK1'],
            [
                'course_id' => $it101->id,
                'lecturer_id' => $lecturer1->id,
                'room_id' => $rooms['A101']->id,
                'shift_id' => 1, // Ca 1
                'day_of_week' => 3, // Thứ 3
                'max_capacity' => 60,
            ]
        );

        // Lớp 2: CÒN CHỖ (50/60)
        ClassSection::updateOrCreate(
            ['section_code' => 'IT101.02', 'academic_year' => '2024-2025', 'term' => 'HK1'],
            [
                'course_id' => $it101->id,
                'lecturer_id' => $lecturer2->id,
                'room_id' => $rooms['B202']->id,
                'shift_id' => 2, // Ca 2 (9:00-11:00)
                'day_of_week' => 4, // Thứ 4
                'max_capacity' => 60,
            ]
        );

        // --- IT102: Cấu trúc dữ liệu ---
        // Lớp 1: CÒN CHỖ (40/60)
        ClassSection::updateOrCreate(
            ['section_code' => 'IT102.01', 'academic_year' => '2024-2025', 'term' => 'HK1'],
            [
                'course_id' => $it102->id,
                'lecturer_id' => $lecturer1->id,
                'room_id' => $rooms['B203']->id,
                'shift_id' => 1, // Ca 1
                'day_of_week' => 6, // Thứ 6
                'max_capacity' => 60,
            ]
        );

        // --- IT201: Lập trình hướng đối tượng (CÓ TIÊN QUYẾT: IT101) ---
        // Lớp 1: CÒN CHỖ (35/60) - Nhưng cần hoàn thành IT101
        ClassSection::updateOrCreate(
            ['section_code' => 'IT201.01', 'academic_year' => '2024-2025', 'term' => 'HK1'],
            [
                'course_id' => $it201->id,
                'lecturer_id' => $lecturer2->id,
                'room_id' => $rooms['B201']->id,
                'shift_id' => 1, // Ca 1
                'day_of_week' => 7, // Thứ 7
                'max_capacity' => 60,
            ]
        );

        // Lớp 2: ĐÃ ĐẦY (60/60)
        ClassSection::updateOrCreate(
            ['section_code' => 'IT201.02', 'academic_year' => '2024-2025', 'term' => 'HK1'],
            [
                'course_id' => $it201->id,
                'lecturer_id' => $lecturer1->id,
                'room_id' => $rooms['B202']->id,
                'shift_id' => 2, // Ca 2
                'day_of_week' => 2, // Thứ 2
                'max_capacity' => 60,
            ]
        );

        // Lớp 3: TRÙNG LỊCH VỚI EC101.01 (cùng Thứ 5, Ca 1)
        ClassSection::updateOrCreate(
            ['section_code' => 'IT201.03', 'academic_year' => '2024-2025', 'term' => 'HK1'],
            [
                'course_id' => $it201->id,
                'lecturer_id' => $lecturer2->id,
                'room_id' => $rooms['B203']->id,
                'shift_id' => 1, // Ca 1
                'day_of_week' => 5, // Thứ 5 (giống EC101.01)
                'max_capacity' => 60,
            ]
        );

        // --- IT202: Cơ sở dữ liệu (CÓ TIÊN QUYẾT: IT102) ---
        // Lớp 1: CÒN CHỖ (25/50)
        ClassSection::updateOrCreate(
            ['section_code' => 'IT202.01', 'academic_year' => '2024-2025', 'term' => 'HK1'],
            [
                'course_id' => $it202->id,
                'lecturer_id' => $lecturer1->id,
                'room_id' => $rooms['A101']->id,
                'shift_id' => 2, // Ca 2
                'day_of_week' => 3, // Thứ 3
                'max_capacity' => 50,
            ]
        );

        // Tạo registrations để giả lập số sinh viên đã đăng ký
        $this->command->info('Tạo registrations giả lập...');

        $students = User::where('role', 'student')->limit(60)->get();
        if ($students->count() < 60) {
            $this->command->warn('Chưa đủ 60 sinh viên để fake registrations. Tạo thêm sinh viên...');
            // Tạo thêm sinh viên nếu cần
            for ($i = $students->count() + 1; $i <= 60; $i++) {
                User::create([
                    'code' => 'SV' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'name' => 'Sinh viên ' . $i,
                    'email' => 'sv' . $i . '@university.edu.vn',
                    'password' => bcrypt('123456'),
                    'role' => 'student',
                    'faculty_id' => $cntt->id,
                    'class_cohort' => 'K20',
                ]);
            }
            $students = User::where('role', 'student')->limit(60)->get();
        }

        // Helper function để tạo registrations
        $createRegistrations = function ($sectionCode, $count) use ($students) {
            $section = ClassSection::where('section_code', $sectionCode)
                ->where('academic_year', '2024-2025')
                ->where('term', 'HK1')
                ->first();
            if ($section) {
                foreach ($students->random(min($count, $students->count())) as $student) {
                    DB::table('registrations')->updateOrInsert(
                        [
                            'student_id' => $student->id,
                            'class_section_id' => $section->id
                        ],
                        [
                            'created_at' => now(),
                            'updated_at' => now()
                        ]
                    );
                }
            }
        };

        // Tạo registrations cho từng lớp
        $createRegistrations('EC101.01', 45);
        $createRegistrations('IT101.01', 60); // Đầy
        $createRegistrations('IT101.02', 50);
        $createRegistrations('IT102.01', 40);
        $createRegistrations('IT201.01', 35);
        $createRegistrations('IT201.02', 60); // Đầy
        $createRegistrations('IT201.03', 30);
        $createRegistrations('IT202.01', 25);

        $this->command->info('✅ Đã tạo dữ liệu mẫu thành công!');
        $this->command->newLine();
        $this->command->info('📋 DANH SÁCH CÁC LỚP ĐÃ TẠO:');
        $this->command->newLine();

        // Lấy thông tin thực tế từ database
        $sections = ClassSection::where('academic_year', '2024-2025')
            ->where('term', 'HK1')
            ->with(['course', 'registrations'])
            ->get();

        $tableData = [];
        foreach ($sections as $section) {
            $enrolled = $section->registrations->count();
            $status = $enrolled >= $section->max_capacity ? '❌ Đã đầy' : '✅ Còn chỗ';

            $prereq = DB::table('course_prerequisites')
                ->join('courses', 'course_prerequisites.prerequisite_course_id', '=', 'courses.id')
                ->where('course_prerequisites.course_id', $section->course_id)
                ->pluck('courses.code')
                ->implode(', ');

            $prereqText = $prereq ?: 'Không';

            $dayName = ['', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'CN'][$section->day_of_week] ?? '';
            $shift = StudyShift::find($section->shift_id);
            $shiftText = $shift ? "Ca {$shift->id}" : '';

            $tableData[] = [
                $section->section_code,
                $section->course->name,
                $prereqText,
                "{$enrolled}/{$section->max_capacity}",
                $status,
                "$dayName, $shiftText"
            ];
        }

        $this->command->table(
            ['Mã lớp', 'Môn học', 'Tiên quyết', 'Sĩ số', 'Trạng thái', 'Lịch'],
            $tableData
        );

        $this->command->newLine();
        $this->command->info('🧪 CÁC TRƯỜNG HỢP TEST:');
        $this->command->info('1. ❌ Hết slot: Thử đăng ký IT101.01 hoặc IT201.02');
        $this->command->info('2. ⚠️ Thiếu tiên quyết: Thử đăng ký IT201.xx hoặc IT202.01 (nếu chưa học IT101/IT102)');
        $this->command->info('3. ⚠️ Trùng lịch: Đăng ký EC101.01, sau đó thử đăng ký IT201.03');
        $this->command->info('4. ✅ Thành công: Đăng ký EC101.01, IT101.02, IT102.01');
    }
}
