<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\Faculty;
use Carbon\Carbon;

class AnnouncementsSeeder extends Seeder
{
    public function run(): void
    {
        // Resolve a couple faculties to target examples
        $faculties = Faculty::orderBy('id')->pluck('id', 'code');
        $anyFacultyId = Faculty::orderBy('id')->value('id');
        $cnttId = Faculty::where('code', 'CNTT')->value('id') ?? $anyFacultyId;

        // 1) Thông báo chung: nghỉ Tết
        Announcement::updateOrCreate([
            'title' => 'Thông báo nghỉ Tết Nguyên đán 2026',
        ], [
            'content' => "Nhà trường thông báo lịch nghỉ Tết Nguyên đán 2026 từ ngày 12/02 đến hết ngày 18/02. Chúc quý thầy cô năm mới an khang thịnh vượng.",
            'audience' => ['roles' => ['all']],
            'published_at' => Carbon::now()->subDays(10),
        ]);

        // 2) Bảo trì hệ thống đăng ký
        Announcement::updateOrCreate([
            'title' => 'Bảo trì hệ thống đăng ký tín chỉ',
        ], [
            'content' => "Hệ thống đăng ký tín chỉ sẽ bảo trì từ 00:00 đến 02:00 ngày 15/11 để nâng cấp hiệu năng. Quý thầy cô vui lòng sắp xếp thời gian phù hợp.",
            'audience' => ['roles' => ['all']],
            'published_at' => Carbon::now()->subDays(1),
        ]);

        // 3) Hạn cuối nhập điểm
        Announcement::updateOrCreate([
            'title' => 'Hạn cuối nhập điểm HK1 (2024-2025)',
        ], [
            'content' => "Nhắc nhở: Hạn chót nhập điểm học kỳ 1 là 23:59 ngày 30/11. Vui lòng hoàn tất việc nhập điểm đúng hạn.",
            'audience' => ['roles' => ['lecturers']],
            'published_at' => Carbon::now()->subDays(3),
        ]);

        // 4) Mở/đóng đợt đăng ký tín chỉ
        Announcement::updateOrCreate([
            'title' => 'Mở đợt đăng ký tín chỉ học kỳ 2',
        ], [
            'content' => "Phòng Đào tạo thông báo mở đợt đăng ký tín chỉ HK2 từ 20/11 đến 27/11. Giảng viên hỗ trợ cập nhật kế hoạch môn học đúng thời hạn.",
            'audience' => ['roles' => ['lecturers']],
            'published_at' => Carbon::now()->subDays(2),
        ]);

        // 5) Thông báo theo Khoa
        Announcement::updateOrCreate([
            'title' => 'Thông báo họp Khoa Công nghệ Thông tin',
        ], [
            'content' => "Trưởng khoa CNTT mời các giảng viên tham dự cuộc họp chuyên môn lúc 14:00 ngày 16/11 tại phòng A101.",
            'audience' => ['faculties' => [$cnttId]],
            'published_at' => Carbon::now()->subDay(),
        ]);

        // 6) Cập nhật quy chế giảng dạy
        Announcement::updateOrCreate([
            'title' => 'Cập nhật quy chế giảng dạy 2025',
        ], [
            'content' => "Ban Giám hiệu ban hành cập nhật một số quy định về thời lượng lên lớp và đánh giá quá trình. Vui lòng xem chi tiết trong văn bản đính kèm tại cổng thông tin nội bộ.",
            'audience' => ['roles' => ['lecturers']],
            'published_at' => Carbon::now()->subHours(6),
        ]);
    }
}
