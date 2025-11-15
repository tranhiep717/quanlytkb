<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Faculty;
use App\Models\Course;
use App\Models\Room;
use App\Models\StudyShift;
use App\Models\ClassSection;
use App\Models\Registration;
use App\Models\RegistrationWave;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Tạo Khoa
        $cntt = Faculty::create([
            'code' => 'CNTT',
            'name' => 'Công nghệ Thông tin',
            'description' => 'Khoa Công nghệ Thông tin',
            'is_active' => true,
        ]);

        $dtvt = Faculty::create([
            'code' => 'DTVT',
            'name' => 'Điện tử - Viễn thông',
            'description' => 'Khoa Điện tử - Viễn thông',
            'is_active' => true,
        ]);

        // 2. Tạo tài khoản ADMIN
        $admin = User::create([
            'name' => 'Admin Hệ thống',
            'email' => 'admin@dktc.edu.vn',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'faculty_id' => null,
            'is_locked' => false,
        ]);

        // 3. Tạo tài khoản GIẢNG VIÊN
        $lecturer1 = User::create([
            'name' => 'Nguyễn Văn Giảng',
            'email' => 'giang@dktc.edu.vn',
            'password' => Hash::make('giang123'),
            'role' => 'lecturer',
            'faculty_id' => $cntt->id,
            'degree' => 'Tiến sĩ',
            'is_locked' => false,
        ]);

        $lecturer2 = User::create([
            'name' => 'Trần Thị Hương',
            'email' => 'huong@dktc.edu.vn',
            'password' => Hash::make('huong123'),
            'role' => 'lecturer',
            'faculty_id' => $dtvt->id,
            'degree' => 'Thạc sĩ',
            'is_locked' => false,
        ]);

        // 4. Tạo tài khoản SINH VIÊN
        $student1 = User::create([
            'name' => 'Hoàng Văn Cường',
            'email' => 'cuong@student.dktc.edu.vn',
            'password' => Hash::make('cuong123'),
            'role' => 'student',
            'faculty_id' => $cntt->id,
            'code' => '12345',
            'class_cohort' => '2024',
            'is_locked' => false,
        ]);

        $student2 = User::create([
            'name' => 'Lê Thị Mai',
            'email' => 'mai@student.dktc.edu.vn',
            'password' => Hash::make('mai123'),
            'role' => 'student',
            'faculty_id' => $cntt->id,
            'code' => '12346',
            'class_cohort' => '2024',
            'is_locked' => false,
        ]);

        $student3 = User::create([
            'name' => 'Phạm Minh Tuấn',
            'email' => 'tuan@student.dktc.edu.vn',
            'password' => Hash::make('tuan123'),
            'role' => 'student',
            'faculty_id' => $dtvt->id,
            'code' => '12347',
            'class_cohort' => '2024',
            'is_locked' => false,
        ]);

        // 5. Tạo Học phần
        $lthdt = Course::create([
            'code' => 'IT101',
            'name' => 'Lập trình hướng đối tượng',
            'credits' => 3,
            'faculty_id' => $cntt->id,
            'type' => 'mandatory',
            'is_active' => true,
        ]);

        $ctdl = Course::create([
            'code' => 'IT201',
            'name' => 'Cấu trúc dữ liệu và Giải thuật',
            'credits' => 4,
            'faculty_id' => $cntt->id,
            'type' => 'mandatory',
            'is_active' => true,
        ]);

        $mdt = Course::create([
            'code' => 'EC101',
            'name' => 'Mạch điện tử',
            'credits' => 3,
            'faculty_id' => $dtvt->id,
            'type' => 'mandatory',
            'is_active' => true,
        ]);

        // 6. Tạo Phòng học
        $room1 = Room::create(['code' => 'A101', 'name' => 'Phòng A101', 'building' => 'A', 'capacity' => 50, 'status' => 'active']);
        $room2 = Room::create(['code' => 'A102', 'name' => 'Phòng A102', 'building' => 'A', 'capacity' => 45, 'status' => 'active']);
        $room3 = Room::create(['code' => 'B201', 'name' => 'Phòng B201', 'building' => 'B', 'capacity' => 40, 'status' => 'active']);

        // 7. Tạo Ca học
        $shift1 = StudyShift::create(['name' => 'Ca 1', 'start_period' => 1, 'end_period' => 3, 'start_time' => '07:00', 'end_time' => '09:30']);
        $shift2 = StudyShift::create(['name' => 'Ca 2', 'start_period' => 4, 'end_period' => 6, 'start_time' => '09:45', 'end_time' => '12:15']);
        $shift3 = StudyShift::create(['name' => 'Ca 3', 'start_period' => 7, 'end_period' => 9, 'start_time' => '13:00', 'end_time' => '15:30']);

        // 8. Tạo Lớp học phần
        $class1 = ClassSection::create([
            'course_id' => $lthdt->id,
            'section_code' => '01',
            'academic_year' => '2024-2025',
            'term' => 'HK1',
            'lecturer_id' => $lecturer1->id,
            'room_id' => $room1->id,
            'shift_id' => $shift1->id,
            'day_of_week' => 2, // Thứ 2
            'max_capacity' => 50,
            'status' => 'active',
        ]);

        $class2 = ClassSection::create([
            'course_id' => $ctdl->id,
            'section_code' => '01',
            'academic_year' => '2024-2025',
            'term' => 'HK1',
            'lecturer_id' => $lecturer1->id,
            'room_id' => $room2->id,
            'shift_id' => $shift2->id,
            'day_of_week' => 3, // Thứ 3
            'max_capacity' => 45,
            'status' => 'active',
        ]);

        $class3 = ClassSection::create([
            'course_id' => $mdt->id,
            'section_code' => '01',
            'academic_year' => '2024-2025',
            'term' => 'HK1',
            'lecturer_id' => $lecturer2->id,
            'room_id' => $room3->id,
            'shift_id' => $shift1->id,
            'day_of_week' => 4, // Thứ 4
            'max_capacity' => 40,
            'status' => 'active',
        ]);

        // 9. Tạo Đợt đăng ký
        $wave = RegistrationWave::create([
            'academic_year' => '2024-2025',
            'term' => 'HK1',
            'name' => 'Đợt đăng ký chính Học kỳ 1',
            'audience' => json_encode(['faculties' => [], 'cohorts' => []]),
            'starts_at' => now()->subDays(5),
            'ends_at' => now()->addDays(10),
        ]);

        // 10. Đăng ký lớp cho sinh viên
        Registration::create(['student_id' => $student1->id, 'class_section_id' => $class1->id]);
        Registration::create(['student_id' => $student1->id, 'class_section_id' => $class2->id]);
        Registration::create(['student_id' => $student2->id, 'class_section_id' => $class1->id]);
        Registration::create(['student_id' => $student3->id, 'class_section_id' => $class3->id]);

        // 11. Thông báo mẫu dành cho giảng viên
        $this->call(AnnouncementsSeeder::class);

        $this->command->info('✅ Đã tạo dữ liệu mẫu thành công!');
        $this->command->newLine();
        $this->command->info('📋 THÔNG TIN TÀI KHOẢN:');
        $this->command->newLine();
        $this->command->info('👨‍💼 ADMIN:');
        $this->command->info('   Email: admin@dktc.edu.vn');
        $this->command->info('   Password: admin123');
        $this->command->newLine();
        $this->command->info('👨‍🏫 GIẢNG VIÊN:');
        $this->command->info('   1. Email: giang@dktc.edu.vn | Password: giang123 (Khoa CNTT)');
        $this->command->info('   2. Email: huong@dktc.edu.vn | Password: huong123 (Khoa ĐTVT)');
        $this->command->newLine();
        $this->command->info('🎓 SINH VIÊN:');
        $this->command->info('   1. Email: cuong@student.dktc.edu.vn | Password: cuong123 | Mã SV: 12345');
        $this->command->info('   2. Email: mai@student.dktc.edu.vn   | Password: mai123   | Mã SV: 12346');
        $this->command->info('   3. Email: tuan@student.dktc.edu.vn  | Password: tuan123  | Mã SV: 12347');
        $this->command->newLine();
        $this->command->info('📚 Đã tạo: 2 Khoa, 3 Học phần, 3 Phòng học, 3 Ca học, 3 Lớp học phần');
    }
}
