<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassSection;
use App\Models\User;
use App\Models\Registration;
use App\Models\Announcement;
use App\Models\LogEntry;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class LecturerDashboardController extends Controller
{
    /**
     * UC4.1: Hiển thị thời khóa biểu giảng dạy (Dashboard)
     */
    public function index(Request $request)
    {
        $lecturer = Auth::user();
        // Required filters
        $academicYear = $request->query('academic_year', session('academic_year', '2024-2025'));
        $term = $request->query('term', session('term', 'HK1'));
        // View mode and base date
        $view = $request->query('view', 'week'); // week|month|list
        $baseDate = $request->query('date') ? \Carbon\Carbon::parse($request->query('date')) : now();
        $weekStart = (clone $baseDate)->startOfWeek();
        $weekEnd = (clone $weekStart)->copy()->endOfWeek();

        // Lấy tất cả lớp học phần được phân công
        $classSections = ClassSection::with(['course', 'room', 'shift'])
            ->withCount(['registrations as current_enrollment'])
            ->where('lecturer_id', $lecturer->id)
            ->where('academic_year', $academicYear)
            ->where('term', $term)
            ->get();

        // Tổ chức dữ liệu theo lịch tuần (7 ngày x các ca học)
        $schedule = [];
        for ($i = 0; $i < 7; $i++) {
            $schedule[$i] = [];
        }

        foreach ($classSections as $section) {
            $dayIndex = $section->day_of_week - 1; // 1=Thứ 2 -> index 0

            $schedule[$dayIndex][] = [
                'id' => $section->id,
                'course_name' => $section->course->name,
                'course_code' => $section->course->code,
                'section_code' => $section->section_code,
                'room' => $section->room ? $section->room->code : 'TBA',
                'shift' => $section->shift ? "Tiết {$section->shift->start_period}-{$section->shift->end_period}" : '',
                'time' => $section->shift ? $section->shift->start_time . ' - ' . $section->shift->end_time : '',
                'enrollment' => ($section->current_enrollment ?? 0) . '/' . $section->max_capacity,
            ];
        }

        $days = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];

        // Month matrix (6 weeks x 7 days), replicate weekly classes by matching day_of_week
        $monthWeeks = [];
        if ($view === 'month') {
            $monthStart = (clone $baseDate)->startOfMonth();
            $gridStart = (clone $monthStart)->startOfWeek();
            for ($w = 0; $w < 6; $w++) {
                $row = [];
                for ($d = 0; $d < 7; $d++) {
                    $date = (clone $gridStart)->addDays($w * 7 + $d);
                    $dow = $date->isoWeekday(); // 1..7
                    $classesForDay = $classSections->filter(fn($s) => (int)$s->day_of_week === (int)$dow)
                        ->values()
                        ->map(fn($s) => [
                            'id' => $s->id,
                            'course_code' => $s->course->code,
                            'course_name' => $s->course->name,
                            'section_code' => $s->section_code,
                            'room' => $s->room?->code ?? 'TBA',
                            'shift' => $s->shift ? "Tiết {$s->shift->start_period}-{$s->shift->end_period}" : '',
                        ]);
                    $row[] = [
                        'date' => $date->toDateString(),
                        'day' => $date->day,
                        'inMonth' => $date->month === $baseDate->month,
                        'classes' => $classesForDay,
                    ];
                }
                $monthWeeks[] = $row;
            }
        }

        // List view uses classSections sorted
        $listSections = $classSections->sortBy(['day_of_week', 'shift_id'])->values();

        // Years/terms for dropdowns
        // Lấy từ database + thêm các năm mặc định
        $yearsFromDB = ClassSection::where('lecturer_id', $lecturer->id)
            ->select('academic_year')->distinct()->pluck('academic_year');
        $currentYear = now()->year;
        $defaultYears = collect([
            ($currentYear - 2) . '-' . ($currentYear - 1),
            ($currentYear - 1) . '-' . $currentYear,
            $currentYear . '-' . ($currentYear + 1),
            ($currentYear + 1) . '-' . ($currentYear + 2),
        ]);
        $years = $yearsFromDB->merge($defaultYears)->unique()->sort()->values()->reverse();

        // Danh sách học kỳ đầy đủ
        $termsFromDB = ClassSection::where('lecturer_id', $lecturer->id)
            ->select('term')->distinct()->pluck('term');
        $defaultTerms = collect(['HK1', 'HK2', 'HKH']);
        $terms = $termsFromDB->merge($defaultTerms)->unique()->values();

        // Navigation dates based on current view
        if ($view === 'month') {
            $prevDate = (clone $baseDate)->subMonth()->toDateString();
            $nextDate = (clone $baseDate)->addMonth()->toDateString();
            $rangeLabel = $baseDate->translatedFormat('F Y');
        } else {
            $prevDate = (clone $baseDate)->subWeek()->toDateString();
            $nextDate = (clone $baseDate)->addWeek()->toDateString();
            $rangeLabel = $weekStart->format('d/m') . ' - ' . $weekEnd->format('d/m/Y');
        }

        return view('lecturer.dashboard', [
            'schedule' => $schedule,
            'days' => $days,
            'academicYear' => $academicYear,
            'term' => $term,
            'totalClasses' => $classSections->count(),
            'view' => $view,
            'baseDate' => $baseDate->toDateString(),
            'prevDate' => $prevDate,
            'nextDate' => $nextDate,
            'rangeLabel' => $rangeLabel,
            'monthWeeks' => $monthWeeks,
            'listSections' => $listSections,
            'years' => $years,
            'terms' => $terms,
        ]);
    }

    /**
     * UC2.8: Danh sách các lớp giảng dạy
     */
    public function myClasses(\Illuminate\Http\Request $request)
    {
        $lecturer = Auth::user();
        $academicYear = $request->query('academic_year', session('academic_year', '2024-2025'));
        $term = $request->query('term', session('term', 'HK1'));

        $query = ClassSection::with(['course', 'room', 'shift'])
            ->withCount(['registrations as current_enrollment'])
            ->where('lecturer_id', $lecturer->id)
            ->where('academic_year', $academicYear)
            ->where('term', $term);

        // Search by course code/name or section code
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('section_code', 'like', "%{$search}%")
                    ->orWhereHas('course', function ($qq) use ($search) {
                        $qq->where('code', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter (active, locked)
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        // Day of week filter (1..7)
        if ($day = $request->query('day_of_week')) {
            $query->where('day_of_week', (int) $day);
        }

        // Building filter via room
        if ($building = $request->query('building')) {
            $query->whereHas('room', function ($q) use ($building) {
                $q->where('building', $building);
            });
        }

        $classSections = $query->orderBy('day_of_week')->orderBy('shift_id')->get();

        // Dropdown data
        $yearsFromDB = ClassSection::where('lecturer_id', $lecturer->id)
            ->select('academic_year')->distinct()->pluck('academic_year');
        $currentYear = now()->year;
        $defaultYears = collect([
            ($currentYear - 2) . '-' . ($currentYear - 1),
            ($currentYear - 1) . '-' . $currentYear,
            $currentYear . '-' . ($currentYear + 1),
            ($currentYear + 1) . '-' . ($currentYear + 2),
        ]);
        $years = $yearsFromDB->merge($defaultYears)->unique()->sort()->values()->reverse();

        $termsFromDB = ClassSection::where('lecturer_id', $lecturer->id)
            ->select('term')->distinct()->pluck('term');
        $defaultTerms = collect(['HK1', 'HK2', 'HKH']);
        $terms = $termsFromDB->merge($defaultTerms)->unique()->values();

        $buildings = \App\Models\Room::select('building')->whereNotNull('building')->distinct()->orderBy('building')->pluck('building');

        return view('lecturer.classes.index', [
            'classSections' => $classSections,
            'academicYear' => $academicYear,
            'term' => $term,
            'years' => $years,
            'terms' => $terms,
            'buildings' => $buildings,
            'filters' => [
                'search' => $request->query('search'),
                'status' => $request->query('status'),
                'day_of_week' => $request->query('day_of_week'),
                'building' => $request->query('building'),
            ],
        ]);
    }

    /**
     * UC2.8: Xem chi tiết lớp và danh sách sinh viên
     */
    public function classDetail(ClassSection $classSection)
    {
        // Kiểm tra quyền: chỉ giảng viên được phân công mới được xem
        if ($classSection->lecturer_id !== Auth::id()) {
            return redirect()->route('lecturer.classes')->with('error', 'Bạn không có quyền xem lớp học này.');
        }

        $classSection->load(['course', 'room', 'shift']);

        // Danh sách sinh viên đã đăng ký (status = approved hoặc registered)
        $students = Registration::where('class_section_id', $classSection->id)
            ->with('student')
            ->orderBy('created_at')
            ->get()
            ->map(function ($registration, $index) {
                return [
                    'stt' => $index + 1,
                    'mssv' => $registration->student->code,
                    'name' => $registration->student->name,
                    'email' => $registration->student->email,
                    'phone' => $registration->student->phone ?? '--',
                    'registered_at' => $registration->created_at->format('d/m/Y H:i'),
                ];
            });

        return view('lecturer.classes.detail', [
            'classSection' => $classSection,
            'students' => $students,
        ]);
    }

    /**
     * JSON: Chi tiết lớp + danh sách SV (phục vụ modal)
     */
    public function classDetailJson(ClassSection $classSection)
    {
        if ($classSection->lecturer_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $classSection->load(['course.faculty', 'room', 'shift']);

        $students = Registration::where('class_section_id', $classSection->id)
            ->with('student')
            ->orderBy('created_at')
            ->get()
            ->map(function ($registration, $index) {
                return [
                    'stt' => $index + 1,
                    'mssv' => $registration->student->code,
                    'name' => $registration->student->name,
                    'email' => $registration->student->email,
                ];
            });

        $currentCount = Registration::where('class_section_id', $classSection->id)->count();

        $detail = [
            'id' => $classSection->id,
            'course_code' => $classSection->course->code,
            'course_name' => $classSection->course->name,
            'faculty' => $classSection->course->faculty->name ?? null,
            'section_code' => $classSection->section_code,
            'academic_year' => $classSection->academic_year,
            'term' => $classSection->term,
            'day_of_week' => $classSection->day_of_week,
            'shift_label' => $classSection->shift ? ("Tiết {$classSection->shift->start_period}-{$classSection->shift->end_period}") : 'TBA',
            'room' => $classSection->room?->code ?? 'Chưa xếp phòng',
            'enrollment' => [
                'current' => $currentCount,
                'max' => $classSection->max_capacity,
            ],
            'students' => $students,
        ];

        return response()->json($detail);
    }

    /**
     * CSV export: Danh sách sinh viên
     */
    public function exportStudentsCsv(ClassSection $classSection)
    {
        if ($classSection->lecturer_id !== Auth::id()) {
            abort(403);
        }

        $fileName = 'danh-sach-sinh-vien-' . $classSection->section_code . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($classSection) {
            $out = fopen('php://output', 'w');
            // BOM for UTF-8 Excel compatibility
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, ['STT', 'MSSV', 'Họ tên', 'Email']);

            $rows = Registration::where('class_section_id', $classSection->id)
                ->with('student')
                ->orderBy('created_at')
                ->get();

            foreach ($rows as $i => $registration) {
                $student = $registration->student;
                fputcsv($out, [
                    $i + 1,
                    $student->code,
                    $student->name,
                    $student->email,
                ]);
            }

            fclose($out);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }

    /**
     * Print-friendly page for class info/schedule (browser Print to PDF)
     */
    public function printSchedule(ClassSection $classSection)
    {
        if ($classSection->lecturer_id !== Auth::id()) {
            abort(403);
        }

        $classSection->load(['course.faculty', 'room', 'shift']);

        return view('lecturer.classes.print', [
            'classSection' => $classSection,
        ]);
    }

    /**
     * UC1.4: Hiển thị hồ sơ cá nhân
     */
    public function profile()
    {
        $lecturer = Auth::user();
        $lecturer->load('faculty');

        return view('lecturer.profile.show', [
            'lecturer' => $lecturer,
        ]);
    }

    /**
     * UC1.4: Cập nhật hồ sơ cá nhân
     */
    public function updateProfile(Request $request)
    {
        $lecturer = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $lecturer->id,
            'phone' => 'nullable|numeric',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:Nam,Nữ,Khác',
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:2048', // 2MB max
        ], [
            'name.required' => '⚠️ Vui lòng nhập họ và tên của bạn.',
            'name.string' => '⚠️ Họ và tên phải là chuỗi ký tự hợp lệ.',
            'name.max' => '⚠️ Họ và tên không được vượt quá 255 ký tự.',
            'email.required' => '⚠️ Vui lòng nhập địa chỉ email.',
            'email.email' => '⚠️ Địa chỉ email không đúng định dạng. Ví dụ: example@gmail.com',
            'email.unique' => '⚠️ Email này đã được sử dụng bởi tài khoản khác. Vui lòng sử dụng email khác.',
            'phone.numeric' => '⚠️ Số điện thoại chỉ được chứa các chữ số từ 0-9.',
            'dob.date' => '⚠️ Ngày sinh không đúng định dạng. Vui lòng chọn lại ngày sinh hợp lệ.',
            'gender.in' => '⚠️ Giới tính phải là Nam, Nữ hoặc Khác.',
            'avatar.image' => '⚠️ File tải lên phải là hình ảnh (không phải video hay tài liệu).',
            'avatar.mimes' => '⚠️ Ảnh đại diện chỉ chấp nhận các định dạng: PNG, JPG, JPEG, GIF.',
            'avatar.max' => '⚠️ Kích thước ảnh đại diện không được vượt quá 2MB. Vui lòng chọn ảnh nhỏ hơn.',
        ]);

        try {
            // Xử lý upload avatar
            if ($request->hasFile('avatar')) {
                // Xóa avatar cũ nếu có
                if ($lecturer->avatar_url && Storage::disk('public')->exists($lecturer->avatar_url)) {
                    Storage::disk('public')->delete($lecturer->avatar_url);
                }

                $path = $request->file('avatar')->store('avatars', 'public');
                $validated['avatar_url'] = $path;
            }

            $lecturer->update($validated);

            LogEntry::create([
                'user_id' => $lecturer->id,
                'action' => 'lecturer_profile_updated',
                'metadata' => json_encode([
                    'name' => $validated['name'],
                    'email' => $validated['email']
                ]),
            ]);

            return redirect()->route('lecturer.profile')->with('success', '✅ Cập nhật hồ sơ cá nhân thành công! Thông tin của bạn đã được lưu vào hệ thống.');
        } catch (\Exception $e) {
            return redirect()->route('lecturer.profile')->with('error', '❌ Đã xảy ra lỗi hệ thống, không thể cập nhật thông tin. Vui lòng thử lại sau hoặc liên hệ quản trị viên nếu lỗi vẫn tiếp diễn.');
        }
    }

    /**
     * UC1.3/UC1.6: Hiển thị form đổi mật khẩu
     */
    public function showChangePasswordForm()
    {
        return view('lecturer.profile.change-password');
    }

    /**
     * UC1.3/UC1.6: Xử lý đổi mật khẩu
     */
    public function changePassword(Request $request)
    {
        $lecturer = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($validated['current_password'], $lecturer->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $lecturer->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        LogEntry::create([
            'user_id' => $lecturer->id,
            'action' => 'lecturer_password_changed',
            'metadata' => json_encode(['timestamp' => now()]),
        ]);

        return redirect()->route('lecturer.profile')->with('success', 'Đổi mật khẩu thành công.');
    }

    /**
     * UC1.7: Xem thông báo hệ thống
     */
    public function notifications()
    {
        $lecturer = Auth::user();

        // Lấy thông báo dành cho giảng viên theo cấu trúc JSON 'audience'
        // audience: { roles: [..], faculties: [..], cohorts: [..] }
        // Hiển thị nếu: audience null (tất cả) OR roles chứa 'lecturers' OR faculties chứa khoa của giảng viên
        $announcements = Announcement::query()
            ->where(function ($q) use ($lecturer) {
                $q->whereNull('audience')
                    ->orWhereJsonContains('audience->roles', 'all')
                    ->orWhereJsonContains('audience->roles', 'lecturers')
                    ->orWhereJsonContains('audience->faculties', (int) $lecturer->faculty_id);
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('lecturer.notifications', [
            'announcements' => $announcements,
        ]);
    }

    // ==== Timetable exports for Lecturer ====
    public function exportIcs(Request $request)
    {
        $lecturer = Auth::user();
        $year = $request->query('academic_year', session('academic_year', '2024-2025'));
        $term = $request->query('term', session('term', 'HK1'));
        $sections = ClassSection::with(['course', 'room', 'shift'])
            ->where('lecturer_id', $lecturer->id)
            ->where('academic_year', $year)
            ->where('term', $term)
            ->get();

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//QLTC//Lecturer Schedule//VN'
        ];
        $baseMonday = now()->startOfWeek();
        foreach ($sections as $s) {
            $shift = $s->shift;
            if (!$shift) continue;
            $course = $s->course;
            $dayDate = (clone $baseMonday)->addDays(($s->day_of_week ?? 1) - 1);
            if ($shift->start_time && $shift->end_time) {
                $start = \Carbon\Carbon::parse($dayDate->format('Y-m-d') . ' ' . $shift->start_time);
                $end = \Carbon\Carbon::parse($dayDate->format('Y-m-d') . ' ' . $shift->end_time);
            } else {
                $start = (clone $dayDate)->setTime(7, 0)->addMinutes(($shift->start_period - 1) * 50);
                $end = (clone $dayDate)->setTime(7, 0)->addMinutes($shift->end_period * 50);
            }
            $lines[] = 'BEGIN:VEVENT';
            $lines[] = 'UID:' . uniqid('lec_', true);
            $lines[] = 'DTSTAMP:' . now()->utc()->format('Ymd\\THis\\Z');
            $lines[] = 'DTSTART:' . $start->format('Ymd\\THis');
            $lines[] = 'DTEND:' . $end->format('Ymd\\THis');
            $lines[] = 'SUMMARY:' . $course->code . ' - ' . $course->name . ' (' . $s->section_code . ')';
            $lines[] = 'LOCATION:' . ($s->room->code ?? '');
            $lines[] = 'END:VEVENT';
        }
        $lines[] = 'END:VCALENDAR';

        return response(implode("\r\n", $lines), 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="lecturer-timetable.ics"'
        ]);
    }

    public function exportTimetableCsv(Request $request)
    {
        $lecturer = Auth::user();
        $year = $request->query('academic_year', session('academic_year', '2024-2025'));
        $term = $request->query('term', session('term', 'HK1'));
        $sections = ClassSection::with(['course', 'room', 'shift'])
            ->where('lecturer_id', $lecturer->id)
            ->where('academic_year', $year)
            ->where('term', $term)
            ->orderBy('day_of_week')->orderBy('shift_id')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="lecturer-timetable.csv"',
        ];

        $callback = function () use ($sections) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, ['Mã HP', 'Tên HP', 'Mã lớp', 'Thứ', 'Ca (Tiết)', 'Giờ', 'Phòng']);
            foreach ($sections as $s) {
                $shift = $s->shift;
                $dayName = ['', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'][$s->day_of_week] ?? '';
                $period = $shift ? ('Tiết ' . $shift->start_period . '-' . $shift->end_period) : '';
                $time = $shift ? (($shift->start_time && $shift->end_time) ? ($shift->start_time . '-' . $shift->end_time) : '') : '';
                fputcsv($out, [
                    $s->course->code,
                    $s->course->name,
                    $s->section_code,
                    $dayName,
                    $period,
                    $time,
                    $s->room->code ?? '',
                ]);
            }
            fclose($out);
        };

        return response()->streamDownload($callback, 'lecturer-timetable.csv', $headers);
    }

    public function printTimetable(Request $request)
    {
        $lecturer = Auth::user();
        $year = $request->query('academic_year', session('academic_year', '2024-2025'));
        $term = $request->query('term', session('term', 'HK1'));
        $sections = ClassSection::with(['course', 'room', 'shift'])
            ->where('lecturer_id', $lecturer->id)
            ->where('academic_year', $year)
            ->where('term', $term)
            ->orderBy('day_of_week')->orderBy('shift_id')
            ->get();
        return view('lecturer.timetable.print', compact('sections', 'year', 'term'));
    }
}
