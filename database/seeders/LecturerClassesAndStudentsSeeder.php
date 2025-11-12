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

class LecturerClassesAndStudentsSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Lấy giảng viên Nguyễn Văn Giảng
        $lecturer = User::where('email', 'giang@dktc.edu.vn')->first();
        if (!$lecturer) {
            $this->command->error('❌ Không tìm thấy giảng viên giang@dktc.edu.vn');
            return;
        }

        $this->command->info("👨‍🏫 Đang thêm lớp cho giảng viên: {$lecturer->name}");

        // Lấy khoa CNTT
        $cntt = Faculty::where('code', 'CNTT')->first();

        // Tạo thêm courses nếu chưa có
        $newCourses = [
            ['code' => 'IT105', 'name' => 'Hệ điều hành', 'credits' => 4, 'faculty_id' => $cntt->id],
            ['code' => 'IT106', 'name' => 'Kiến trúc Máy tính', 'credits' => 3, 'faculty_id' => $cntt->id],
            ['code' => 'IT205', 'name' => 'Công nghệ Phần mềm', 'credits' => 4, 'faculty_id' => $cntt->id],
            ['code' => 'IT206', 'name' => 'Lập trình Java', 'credits' => 4, 'faculty_id' => $cntt->id],
            ['code' => 'IT207', 'name' => 'Lập trình Python', 'credits' => 3, 'faculty_id' => $cntt->id],
        ];

        foreach ($newCourses as $courseData) {
            Course::firstOrCreate(
                ['code' => $courseData['code']],
                $courseData
            );
        }

        // Tạo thêm phòng học nếu chưa có
        $newRooms = [
            ['code' => 'LAB01', 'building' => 'Phòng Máy', 'capacity' => 30],
            ['code' => 'LAB02', 'building' => 'Phòng Máy', 'capacity' => 35],
            ['code' => 'LAB03', 'building' => 'Phòng Máy', 'capacity' => 30],
            ['code' => 'B202', 'building' => 'Nhà B', 'capacity' => 70],
            ['code' => 'C301', 'building' => 'Nhà C', 'capacity' => 100],
        ];

        foreach ($newRooms as $roomData) {
            Room::firstOrCreate(
                ['code' => $roomData['code']],
                $roomData
            );
        }

        // Lấy các phòng và shifts có sẵn
        $rooms = Room::all();
        $shifts = StudyShift::all();

        // Tạo các lớp cho giảng viên này
        $classesToCreate = [
            // Thứ 2
            ['course' => 'IT201', 'section' => '01', 'day' => 2, 'shift_start' => 1, 'shift_end' => 3, 'room' => 'A102'],
            ['course' => 'IT105', 'section' => '01', 'day' => 2, 'shift_start' => 4, 'shift_end' => 6, 'room' => 'B201'],

            // Thứ 3
            ['course' => 'IT106', 'section' => '01', 'day' => 3, 'shift_start' => 1, 'shift_end' => 3, 'room' => 'A101'],
            ['course' => 'IT205', 'section' => '01', 'day' => 3, 'shift_start' => 7, 'shift_end' => 9, 'room' => 'C301'],

            // Thứ 4
            ['course' => 'IT201', 'section' => '02', 'day' => 4, 'shift_start' => 1, 'shift_end' => 3, 'room' => 'A103'],
            ['course' => 'IT206', 'section' => '01', 'day' => 4, 'shift_start' => 4, 'shift_end' => 6, 'room' => 'LAB01'],

            // Thứ 5
            ['course' => 'IT207', 'section' => '01', 'day' => 5, 'shift_start' => 1, 'shift_end' => 3, 'room' => 'LAB02'],
            ['course' => 'IT105', 'section' => '02', 'day' => 5, 'shift_start' => 4, 'shift_end' => 6, 'room' => 'B202'],

            // Thứ 6
            ['course' => 'IT206', 'section' => '02', 'day' => 6, 'shift_start' => 1, 'shift_end' => 3, 'room' => 'LAB01'],
            ['course' => 'IT207', 'section' => '02', 'day' => 6, 'shift_start' => 4, 'shift_end' => 6, 'room' => 'LAB03'],
        ];

        $createdSections = [];

        foreach ($classesToCreate as $data) {
            $course = Course::where('code', $data['course'])->first();
            if (!$course) {
                $this->command->warn("⚠️  Không tìm thấy môn {$data['course']}");
                continue;
            }

            $room = Room::where('code', $data['room'])->first();
            if (!$room) {
                $this->command->warn("⚠️  Không tìm thấy phòng {$data['room']}");
                continue;
            }

            $shift = StudyShift::where('day_of_week', $data['day'])
                ->where('start_period', $data['shift_start'])
                ->where('end_period', $data['shift_end'])
                ->first();

            if (!$shift) {
                $this->command->warn("⚠️  Không tìm thấy ca học ngày {$data['day']}, tiết {$data['shift_start']}-{$data['shift_end']}");
                continue;
            }

            $section = ClassSection::firstOrCreate(
                [
                    'academic_year' => '2024-2025',
                    'term' => 'HK1',
                    'section_code' => $data['course'] . '.' . $data['section'],
                ],
                [
                    'course_id' => $course->id,
                    'lecturer_id' => $lecturer->id,
                    'day_of_week' => $data['day'],
                    'shift_id' => $shift->id,
                    'room_id' => $room->id,
                    'max_capacity' => $room->capacity,
                ]
            );

            $createdSections[] = $section;
            $this->command->info("✅ Tạo lớp: {$section->section_code} - {$course->name}");
        }

        // Tạo sinh viên mẫu
        $students = [
            ['email' => 'sv001@dktc.edu.vn', 'name' => 'Nguyễn Văn An'],
            ['email' => 'sv002@dktc.edu.vn', 'name' => 'Trần Thị Bình'],
            ['email' => 'sv003@dktc.edu.vn', 'name' => 'Lê Văn Cường'],
            ['email' => 'sv004@dktc.edu.vn', 'name' => 'Phạm Thị Dung'],
            ['email' => 'sv005@dktc.edu.vn', 'name' => 'Hoàng Văn Em'],
            ['email' => 'sv006@dktc.edu.vn', 'name' => 'Võ Thị Phương'],
            ['email' => 'sv007@dktc.edu.vn', 'name' => 'Đỗ Văn Giang'],
            ['email' => 'sv008@dktc.edu.vn', 'name' => 'Bùi Thị Hoa'],
            ['email' => 'sv009@dktc.edu.vn', 'name' => 'Mai Văn Khoa'],
            ['email' => 'sv010@dktc.edu.vn', 'name' => 'Đinh Thị Lan'],
        ];

        $this->command->info("\n👥 Đang tạo sinh viên...");

        $createdStudents = [];
        foreach ($students as $studentData) {
            $student = User::firstOrCreate(
                ['email' => $studentData['email']],
                [
                    'password' => bcrypt('password'),
                    'name' => $studentData['name'],
                    'role' => 'student',
                    'faculty_id' => $cntt->id,
                ]
            );
            $createdStudents[] = $student;
            $this->command->info("✅ Sinh viên: {$student->name} ({$student->email})");
        }

        // Đăng ký sinh viên vào các lớp (mỗi sinh viên đăng ký 3-5 lớp ngẫu nhiên)
        $this->command->info("\n📝 Đang đăng ký sinh viên vào lớp...");

        $totalRegistrations = 0;
        foreach ($createdStudents as $student) {
            // Mỗi sinh viên đăng ký 3-5 lớp ngẫu nhiên
            $numClasses = rand(3, 5);
            $selectedSections = collect($createdSections)->random(min($numClasses, count($createdSections)));

            foreach ($selectedSections as $section) {
                Registration::firstOrCreate([
                    'student_id' => $student->id,
                    'class_section_id' => $section->id,
                ]);
                $totalRegistrations++;
            }

            $this->command->info("   → {$student->name}: đăng ký {$selectedSections->count()} lớp");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Thống kê
        $lecturerClasses = ClassSection::where('lecturer_id', $lecturer->id)
            ->where('academic_year', '2024-2025')
            ->where('term', 'HK1')
            ->count();

        $this->command->info("\n" . str_repeat('=', 60));
        $this->command->info("✅ HOÀN TẤT!");
        $this->command->info("👨‍🏫 Giảng viên {$lecturer->name} giờ có: {$lecturerClasses} lớp");
        $this->command->info("👥 Đã tạo: " . count($createdStudents) . " sinh viên");
        $this->command->info("📝 Tổng số đăng ký: {$totalRegistrations} lượt");
        $this->command->info(str_repeat('=', 60));
    }
}
