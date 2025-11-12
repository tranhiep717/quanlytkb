<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faculty;
use App\Models\Course;
use App\Models\Room;
use App\Models\StudyShift;
use App\Models\ClassSection;
use App\Models\RegistrationWave;
use Illuminate\Support\Facades\DB;

class TrainingDataSeeder extends Seeder
{
    public function run()
    {
        DB::statement('PRAGMA foreign_keys = OFF');

        // Seed Rooms
        $rooms = [
            ['code' => 'A101', 'building' => 'Nhà A', 'capacity' => 50],
            ['code' => 'A102', 'building' => 'Nhà A', 'capacity' => 60],
            ['code' => 'A103', 'building' => 'Nhà A', 'capacity' => 40],
            ['code' => 'B201', 'building' => 'Nhà B', 'capacity' => 80],
            ['code' => 'B202', 'building' => 'Nhà B', 'capacity' => 70],
            ['code' => 'C301', 'building' => 'Nhà C', 'capacity' => 100],
            ['code' => 'LAB01', 'building' => 'Phòng Máy', 'capacity' => 30],
            ['code' => 'LAB02', 'building' => 'Phòng Máy', 'capacity' => 35],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }

        // Seed Study Shifts
        $shifts = [
            // Thứ 2
            ['day_of_week' => 2, 'start_period' => 1, 'end_period' => 3],   // Sáng: tiết 1-3
            ['day_of_week' => 2, 'start_period' => 4, 'end_period' => 6],   // Chiều: tiết 4-6
            ['day_of_week' => 2, 'start_period' => 7, 'end_period' => 9],   // Tối: tiết 7-9
            // Thứ 3
            ['day_of_week' => 3, 'start_period' => 1, 'end_period' => 3],
            ['day_of_week' => 3, 'start_period' => 4, 'end_period' => 6],
            ['day_of_week' => 3, 'start_period' => 7, 'end_period' => 9],
            // Thứ 4
            ['day_of_week' => 4, 'start_period' => 1, 'end_period' => 3],
            ['day_of_week' => 4, 'start_period' => 4, 'end_period' => 6],
            ['day_of_week' => 4, 'start_period' => 7, 'end_period' => 9],
            // Thứ 5
            ['day_of_week' => 5, 'start_period' => 1, 'end_period' => 3],
            ['day_of_week' => 5, 'start_period' => 4, 'end_period' => 6],
            ['day_of_week' => 5, 'start_period' => 7, 'end_period' => 9],
            // Thứ 6
            ['day_of_week' => 6, 'start_period' => 1, 'end_period' => 3],
            ['day_of_week' => 6, 'start_period' => 4, 'end_period' => 6],
            ['day_of_week' => 6, 'start_period' => 7, 'end_period' => 9],
        ];

        foreach ($shifts as $shift) {
            StudyShift::create($shift);
        }

        // Get faculties
        $cntt = Faculty::where('code', 'CNTT')->first();
        $kt = Faculty::where('code', 'KT')->first();

        if ($cntt && $kt) {
            // Seed Courses for CNTT
            $courses_cntt = [
                ['code' => 'IT001', 'name' => 'Nhập môn Lập trình', 'credits' => 4, 'faculty_id' => $cntt->id],
                ['code' => 'IT002', 'name' => 'Lập trình Hướng đối tượng', 'credits' => 4, 'faculty_id' => $cntt->id],
                ['code' => 'IT003', 'name' => 'Cấu trúc Dữ liệu và Giải thuật', 'credits' => 4, 'faculty_id' => $cntt->id],
                ['code' => 'IT004', 'name' => 'Cơ sở Dữ liệu', 'credits' => 4, 'faculty_id' => $cntt->id],
                ['code' => 'IT005', 'name' => 'Mạng Máy tính', 'credits' => 3, 'faculty_id' => $cntt->id],
                ['code' => 'IT006', 'name' => 'Hệ điều hành', 'credits' => 4, 'faculty_id' => $cntt->id],
                ['code' => 'IT007', 'name' => 'Phát triển Ứng dụng Web', 'credits' => 4, 'faculty_id' => $cntt->id],
                ['code' => 'IT008', 'name' => 'Trí tuệ Nhân tạo', 'credits' => 3, 'faculty_id' => $cntt->id],
            ];

            foreach ($courses_cntt as $course) {
                Course::create($course);
            }

            // Seed Courses for KT
            $courses_kt = [
                ['code' => 'EC001', 'name' => 'Kinh tế Vi mô', 'credits' => 3, 'faculty_id' => $kt->id],
                ['code' => 'EC002', 'name' => 'Kinh tế Vĩ mô', 'credits' => 3, 'faculty_id' => $kt->id],
                ['code' => 'EC003', 'name' => 'Quản trị Học', 'credits' => 3, 'faculty_id' => $kt->id],
                ['code' => 'EC004', 'name' => 'Marketing Căn bản', 'credits' => 3, 'faculty_id' => $kt->id],
                ['code' => 'EC005', 'name' => 'Kế toán Tài chính', 'credits' => 3, 'faculty_id' => $kt->id],
            ];

            foreach ($courses_kt as $course) {
                Course::create($course);
            }

            // Add prerequisites
            $it001 = Course::where('code', 'IT001')->first();
            $it002 = Course::where('code', 'IT002')->first();
            $it003 = Course::where('code', 'IT003')->first();
            $it004 = Course::where('code', 'IT004')->first();

            if ($it001 && $it002) {
                $it002->prerequisites()->attach($it001->id); // IT002 cần IT001
            }
            if ($it001 && $it003) {
                $it003->prerequisites()->attach($it001->id); // IT003 cần IT001
            }
            if ($it002 && $it004) {
                $it004->prerequisites()->attach($it002->id); // IT004 cần IT002
            }

            // Seed Class Sections for 2024-2025 HK1
            $giangvien1 = \App\Models\User::where('email', 'giangvien1@dangkytinchi.edu.vn')->first();
            $giangvien2 = \App\Models\User::where('email', 'giangvien2@dangkytinchi.edu.vn')->first();

            $room1 = Room::where('code', 'A101')->first();
            $room2 = Room::where('code', 'A102')->first();
            $room3 = Room::where('code', 'B201')->first();
            $room4 = Room::where('code', 'LAB01')->first();

            $shift1 = StudyShift::where('day_of_week', 2)->where('start_period', 1)->first();
            $shift2 = StudyShift::where('day_of_week', 2)->where('start_period', 4)->first();
            $shift3 = StudyShift::where('day_of_week', 3)->where('start_period', 1)->first();
            $shift4 = StudyShift::where('day_of_week', 4)->where('start_period', 7)->first();

            if ($it001 && $room1 && $shift1 && $giangvien1) {
                ClassSection::create([
                    'academic_year' => '2024-2025',
                    'term' => 'HK1',
                    'course_id' => $it001->id,
                    'section_code' => 'IT001.01',
                    'lecturer_id' => $giangvien1->id,
                    'day_of_week' => 2,
                    'shift_id' => $shift1->id,
                    'room_id' => $room1->id,
                    'max_capacity' => 50,
                ]);
            }

            if ($it002 && $room2 && $shift2 && $giangvien1) {
                ClassSection::create([
                    'academic_year' => '2024-2025',
                    'term' => 'HK1',
                    'course_id' => $it002->id,
                    'section_code' => 'IT002.01',
                    'lecturer_id' => $giangvien1->id,
                    'day_of_week' => 2,
                    'shift_id' => $shift2->id,
                    'room_id' => $room2->id,
                    'max_capacity' => 60,
                ]);
            }

            if ($it003 && $room3 && $shift3 && $giangvien2) {
                ClassSection::create([
                    'academic_year' => '2024-2025',
                    'term' => 'HK1',
                    'course_id' => $it003->id,
                    'section_code' => 'IT003.01',
                    'lecturer_id' => $giangvien2->id,
                    'day_of_week' => 3,
                    'shift_id' => $shift3->id,
                    'room_id' => $room3->id,
                    'max_capacity' => 80,
                ]);
            }

            // Seed Registration Wave
            RegistrationWave::create([
                'academic_year' => '2024-2025',
                'term' => 'HK1',
                'name' => 'Đợt 1 - Ưu tiên Khóa cũ',
                'audience' => json_encode([
                    'faculties' => [$cntt->id, $kt->id],
                    'cohorts' => ['K17', 'K18'],
                ]),
                'starts_at' => now()->addDays(1),
                'ends_at' => now()->addDays(7),
            ]);

            RegistrationWave::create([
                'academic_year' => '2024-2025',
                'term' => 'HK1',
                'name' => 'Đợt 2 - Tất cả sinh viên',
                'audience' => json_encode([
                    'faculties' => [$cntt->id, $kt->id],
                    'cohorts' => ['K17', 'K18', 'K19'],
                ]),
                'starts_at' => now()->addDays(8),
                'ends_at' => now()->addDays(14),
            ]);
        }

        DB::statement('PRAGMA foreign_keys = ON');

        $this->command->info('✅ Đã seed dữ liệu đào tạo thành công!');
    }
}
