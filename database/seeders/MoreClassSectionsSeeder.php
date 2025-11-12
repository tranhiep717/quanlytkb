<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faculty;
use App\Models\Course;
use App\Models\Room;
use App\Models\StudyShift;
use App\Models\ClassSection;
use App\Models\User;
use App\Models\Registration;
use Illuminate\Support\Facades\DB;

class MoreClassSectionsSeeder extends Seeder
{
    public function run()
    {
        // Tạm thời tắt foreign key checks (MySQL)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Lấy các khóa ngoại
        $cntt = Faculty::where('code', 'CNTT')->first();

        // Tạo thêm courses nếu chưa có
        $courses = [
            ['code' => 'IT101', 'name' => 'Lập trình Hướng đối tượng', 'credits' => 4, 'faculty_id' => $cntt->id],
            ['code' => 'IT102', 'name' => 'Cấu trúc Dữ liệu', 'credits' => 3, 'faculty_id' => $cntt->id],
            ['code' => 'IT103', 'name' => 'Cơ sở Dữ liệu', 'credits' => 4, 'faculty_id' => $cntt->id],
            ['code' => 'IT104', 'name' => 'Mạng Máy tính', 'credits' => 3, 'faculty_id' => $cntt->id],
            ['code' => 'IT201', 'name' => 'Cấu trúc dữ liệu và Giải thuật', 'credits' => 4, 'faculty_id' => $cntt->id],
            ['code' => 'IT202', 'name' => 'Phân tích Thiết kế Hệ thống', 'credits' => 3, 'faculty_id' => $cntt->id],
            ['code' => 'IT203', 'name' => 'Phát triển Ứng dụng Web', 'credits' => 4, 'faculty_id' => $cntt->id],
            ['code' => 'IT204', 'name' => 'Lập trình Di động', 'credits' => 3, 'faculty_id' => $cntt->id],
            ['code' => 'IT301', 'name' => 'Trí tuệ Nhân tạo', 'credits' => 4, 'faculty_id' => $cntt->id],
            ['code' => 'IT302', 'name' => 'Machine Learning', 'credits' => 3, 'faculty_id' => $cntt->id],
            ['code' => 'IT303', 'name' => 'Bảo mật Thông tin', 'credits' => 3, 'faculty_id' => $cntt->id],
            ['code' => 'IT304', 'name' => 'Điện toán Đám mây', 'credits' => 3, 'faculty_id' => $cntt->id],
        ];

        foreach ($courses as $courseData) {
            Course::firstOrCreate(
                ['code' => $courseData['code']],
                $courseData
            );
        }

        // Tạo thêm phòng học nếu cần
        $rooms = [
            ['code' => 'A103', 'building' => 'Nhà A', 'capacity' => 40],
            ['code' => 'A104', 'building' => 'Nhà A', 'capacity' => 45],
            ['code' => 'B203', 'building' => 'Nhà B', 'capacity' => 60],
            ['code' => 'C302', 'building' => 'Nhà C', 'capacity' => 50],
            ['code' => 'LAB03', 'building' => 'Phòng Máy', 'capacity' => 30],
        ];

        foreach ($rooms as $roomData) {
            Room::firstOrCreate(
                ['code' => $roomData['code']],
                $roomData
            );
        }

        // Tạo đầy đủ các ca học (shifts) cho các ngày trong tuần
        $shiftDefinitions = [];
        for ($day = 2; $day <= 6; $day++) { // Thứ 2 đến Thứ 6
            $shiftDefinitions[] = ['day_of_week' => $day, 'start_period' => 1, 'end_period' => 3];   // Sáng
            $shiftDefinitions[] = ['day_of_week' => $day, 'start_period' => 4, 'end_period' => 6];   // Chiều
            $shiftDefinitions[] = ['day_of_week' => $day, 'start_period' => 7, 'end_period' => 9];   // Tối
        }

        foreach ($shiftDefinitions as $shiftData) {
            StudyShift::firstOrCreate($shiftData);
        }

        $this->command->info('📅 Đã tạo ' . StudyShift::count() . ' ca học');

        // Lấy giảng viên
        $giangvien1 = User::where('email', 'giangvien1@dangkytinchi.edu.vn')->first();
        $giangvien2 = User::where('email', 'giangvien2@dangkytinchi.edu.vn')->first();

        // Nếu không có giảng viên mẫu, tạo mới
        if (!$giangvien1) {
            $giangvien1 = User::create([
                'email' => 'giangvien1@dangkytinchi.edu.vn',
                'password' => bcrypt('password'),
                'name' => 'Nguyễn Văn A',
                'role' => 'lecturer',
                'faculty_id' => $cntt->id,
            ]);
        }

        if (!$giangvien2) {
            $giangvien2 = User::create([
                'email' => 'giangvien2@dangkytinchi.edu.vn',
                'password' => bcrypt('password'),
                'name' => 'Trần Thị B',
                'role' => 'lecturer',
                'faculty_id' => $cntt->id,
            ]);
        }

        // Lấy các ca học đã có sẵn
        $shifts = StudyShift::all();

        // Tạo các lớp học phần cho HK1 2024-2025
        $classSections = [
            // Thứ 2
            ['code' => 'IT101', 'section' => '01', 'day' => 2, 'shift_start' => 1, 'shift_end' => 3, 'room' => 'A101', 'lecturer' => $giangvien1],
            ['code' => 'IT102', 'section' => '01', 'day' => 2, 'shift_start' => 4, 'shift_end' => 6, 'room' => 'A102', 'lecturer' => $giangvien1],
            ['code' => 'IT103', 'section' => '01', 'day' => 2, 'shift_start' => 7, 'shift_end' => 9, 'room' => 'B201', 'lecturer' => $giangvien2],

            // Thứ 3
            ['code' => 'IT104', 'section' => '01', 'day' => 3, 'shift_start' => 1, 'shift_end' => 3, 'room' => 'A103', 'lecturer' => $giangvien1],
            ['code' => 'IT201', 'section' => '01', 'day' => 3, 'shift_start' => 4, 'shift_end' => 6, 'room' => 'A104', 'lecturer' => $giangvien2],
            ['code' => 'IT202', 'section' => '01', 'day' => 3, 'shift_start' => 7, 'shift_end' => 9, 'room' => 'B203', 'lecturer' => $giangvien1],

            // Thứ 4
            ['code' => 'IT203', 'section' => '01', 'day' => 4, 'shift_start' => 1, 'shift_end' => 3, 'room' => 'LAB01', 'lecturer' => $giangvien2],
            ['code' => 'IT204', 'section' => '01', 'day' => 4, 'shift_start' => 4, 'shift_end' => 6, 'room' => 'LAB02', 'lecturer' => $giangvien1],
            ['code' => 'IT301', 'section' => '01', 'day' => 4, 'shift_start' => 7, 'shift_end' => 9, 'room' => 'C301', 'lecturer' => $giangvien2],

            // Thứ 5
            ['code' => 'IT302', 'section' => '01', 'day' => 5, 'shift_start' => 1, 'shift_end' => 3, 'room' => 'A101', 'lecturer' => $giangvien1],
            ['code' => 'IT303', 'section' => '01', 'day' => 5, 'shift_start' => 4, 'shift_end' => 6, 'room' => 'A102', 'lecturer' => $giangvien2],
            ['code' => 'IT304', 'section' => '01', 'day' => 5, 'shift_start' => 7, 'shift_end' => 9, 'room' => 'B201', 'lecturer' => $giangvien1],

            // Thứ 6
            ['code' => 'IT101', 'section' => '02', 'day' => 6, 'shift_start' => 1, 'shift_end' => 3, 'room' => 'A103', 'lecturer' => $giangvien2],
            ['code' => 'IT102', 'section' => '02', 'day' => 6, 'shift_start' => 4, 'shift_end' => 6, 'room' => 'A104', 'lecturer' => $giangvien1],
            ['code' => 'IT201', 'section' => '02', 'day' => 6, 'shift_start' => 7, 'shift_end' => 9, 'room' => 'B203', 'lecturer' => $giangvien2],
        ];

        foreach ($classSections as $data) {
            $course = Course::where('code', $data['code'])->first();
            if (!$course) continue;

            $room = Room::where('code', $data['room'])->first();
            if (!$room) continue;

            $shift = StudyShift::where('day_of_week', $data['day'])
                ->where('start_period', $data['shift_start'])
                ->where('end_period', $data['shift_end'])
                ->first();

            if (!$shift) continue;

            $section = ClassSection::firstOrCreate(
                [
                    'academic_year' => '2024-2025',
                    'term' => 'HK1',
                    'section_code' => $data['code'] . '.' . $data['section'],
                ],
                [
                    'course_id' => $course->id,
                    'lecturer_id' => $data['lecturer']->id,
                    'day_of_week' => $data['day'],
                    'shift_id' => $shift->id,
                    'room_id' => $room->id,
                    'max_capacity' => $room->capacity,
                ]
            );
        }

        // Đăng ký một số lớp cho sinh viên mẫu
        $student = User::where('email', 'sinhvien1@dangkytinchi.edu.vn')->first();
        if ($student) {
            $sectionsToRegister = ClassSection::where('academic_year', '2024-2025')
                ->where('term', 'HK1')
                ->whereIn('section_code', [
                    'IT101.01',
                    'IT102.01',
                    'IT201.01',
                    'IT203.01',
                    'IT302.01'
                ])
                ->get();

            foreach ($sectionsToRegister as $section) {
                Registration::firstOrCreate([
                    'student_id' => $student->id,
                    'class_section_id' => $section->id,
                ]);
            }

            $this->command->info("✅ Đã đăng ký {$sectionsToRegister->count()} lớp cho sinh viên {$student->email}");
        }

        $this->command->info('✅ Đã tạo thành công các lớp học phần mẫu!');
        $this->command->info('📊 Tổng số lớp: ' . ClassSection::where('academic_year', '2024-2025')->where('term', 'HK1')->count());

        // Bật lại foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
