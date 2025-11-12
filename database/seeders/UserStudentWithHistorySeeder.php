<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Course;
use App\Models\Room;
use App\Models\StudyShift;
use App\Models\ClassSection;
use App\Models\Registration;

class UserStudentWithHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy sẵn một khoa và một học phần; nếu chưa có thì tạo tối thiểu
        $faculty = Faculty::first() ?? Faculty::create([
            'code' => 'TEST',
            'name' => 'Khoa Thử nghiệm',
            'is_active' => true,
        ]);

        $course = Course::first() ?? Course::create([
            'code' => 'TST101',
            'name' => 'Học phần Thử nghiệm',
            'credits' => 3,
            'faculty_id' => $faculty->id,
        ]);

        // Phòng học và ca học
        $room = Room::first() ?? Room::create([
            'code' => 'A101',
            'building' => 'A',
            'capacity' => 60,
        ]);

        $shift = StudyShift::first() ?? StudyShift::create([
            'day_of_week' => 1,
            'start_period' => 1,
            'end_period' => 3,
        ]);

        // Tạo lớp học phần mẫu
        $class = ClassSection::first() ?? ClassSection::create([
            'academic_year' => '2024-2025',
            'term' => 'HK1',
            'course_id' => $course->id,
            'section_code' => 'L01',
            'lecturer_id' => null,
            'day_of_week' => $shift->day_of_week,
            'shift_id' => $shift->id,
            'room_id' => $room->id,
            'max_capacity' => 80,
        ]);

        // Tạo sinh viên mẫu có ràng buộc lịch sử đăng ký
        $student = User::firstOrCreate(
            ['email' => 'sv.test@university.edu.vn'],
            [
                'name' => 'Sinh viên Test Ràng Buộc',
                'code' => 'SVTEST01',
                'password' => bcrypt('password'),
                'role' => 'student',
                'faculty_id' => $faculty->id,
                'class_cohort' => 'K17'
            ]
        );

        Registration::firstOrCreate([
            'student_id' => $student->id,
            'class_section_id' => $class->id,
        ]);

        $this->command?->info('✅ Đã tạo SV mẫu có đăng ký: email sv.test@university.edu.vn / code SVTEST01');
    }
}
