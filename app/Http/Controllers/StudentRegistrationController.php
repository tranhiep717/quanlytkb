<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ClassSection;
use App\Models\Course;
use App\Models\Registration;
use App\Models\Transcript;
use App\Models\RegistrationWave;

class StudentRegistrationController extends Controller
{
    private int $minCredits = 12;
    private int $maxCredits = 22;

    public function offerings(Request $request)
    {
        $user = Auth::user();
        $year = $request->input('academic_year', session('academic_year', '2024-2025'));
        $term = $request->input('term', session('term', 'HK1'));

        // Base query - GROUP BY course instead of sections
        $query = Course::with(['faculty'])
            ->whereHas('classSections', function ($q) use ($year, $term) {
                $q->where('academic_year', $year)->where('term', $term);
            });

        // Search by course code/name
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search%");
            });
        }

        // Filter by faculty
        if ($facultyId = $request->input('faculty_id')) {
            $query->where('faculty_id', $facultyId);
        }

        $courses = $query->orderBy('code')->paginate(20)->withQueryString();

        // For each course, count total sections and available sections
        foreach ($courses as $course) {
            $sections = ClassSection::where('course_id', $course->id)
                ->where('academic_year', $year)
                ->where('term', $term);

            if ($shiftId = $request->input('shift_id')) {
                $sections->where('shift_id', $shiftId);
            }
            if ($roomId = $request->input('room_id')) {
                $sections->where('room_id', $roomId);
            }

            $course->total_sections = $sections->count();

            // Count available sections (not full)
            $course->available_sections = $sections->get()->filter(function ($s) {
                $enrolled = Registration::where('class_section_id', $s->id)->count();
                return $enrolled < $s->max_capacity;
            })->count();
        }

        // Current registrations
        $currentRegs = $this->currentRegistrations($year, $term)
            ->load('classSection.course', 'classSection.shift', 'classSection.room');
        $registeredCourseIds = $currentRegs->pluck('classSection.course_id')->unique()->toArray();

        // Current open wave info
        $wave = RegistrationWave::where('academic_year', $year)->where('term', $term)
            ->where('starts_at', '<=', now())->where('ends_at', '>=', now())
            ->orderBy('starts_at', 'desc')->first();
        $openForUser = $this->isRegistrationOpenFor($user, $year, $term);

        // Data for filters
        $faculties = \App\Models\Faculty::orderBy('name')->get(['id', 'name']);
        $shifts = \App\Models\StudyShift::orderBy('start_period')->get(['id', 'start_period', 'end_period']);
        $rooms = \App\Models\Room::orderBy('code')->get(['id', 'code']);

        return view('student.registrations.offerings', compact(
            'courses',
            'year',
            'term',
            'registeredCourseIds',
            'currentRegs',
            'wave',
            'openForUser',
            'faculties',
            'shifts',
            'rooms'
        ));
    }

    /**
     * API: Get sections for a specific course with status
     */
    public function courseSections(Request $request, Course $course)
    {
        $user = Auth::user();
        $year = $request->query('academic_year', session('academic_year', '2024-2025'));
        $term = $request->query('term', session('term', 'HK1'));

        // Get sections for this course
        $query = ClassSection::with(['room', 'shift', 'lecturer'])
            ->where('course_id', $course->id)
            ->where('academic_year', $year)
            ->where('term', $term);

        // Apply filters if provided
        if ($shiftId = $request->query('shift_id')) {
            $query->where('shift_id', $shiftId);
        }
        if ($roomId = $request->query('room_id')) {
            $query->where('room_id', $roomId);
        }

        $sections = $query->orderBy('section_code')->get();

        // Get current registrations for status check
        $currentRegs = $this->currentRegistrations($year, $term);
        $registeredSectionIds = $currentRegs->pluck('class_section_id')->toArray();
        $registeredCourseIds = $currentRegs->pluck('classSection.course_id')->unique()->toArray();

        // Compute status for each section
        $sectionsData = [];
        foreach ($sections as $s) {
            $enrolled = Registration::where('class_section_id', $s->id)->count();
            $isFull = $enrolled >= $s->max_capacity;

            $status = 'available';
            $reason = null;
            $buttonClass = 'btn-primary';
            $buttonText = 'Đăng ký';
            $disabled = false;

            // Check already registered this exact section
            if (in_array($s->id, $registeredSectionIds)) {
                $status = 'already_registered';
                $buttonText = '✅ Đã đăng ký';
                $buttonClass = 'btn-success';
                $disabled = true; // Chỉ disable nếu đã đăng ký chính xác lớp này
            }
            // Check already registered same course (different section)
            elseif (in_array($course->id, $registeredCourseIds)) {
                $status = 'swap_available';
                $buttonText = 'Đổi lớp';
                $buttonClass = 'btn-warning';
                $disabled = false; // Cho phép đổi lớp
            }
            // Check prerequisites - KHÔNG disable, chỉ hiển thị cảnh báo
            else {
                $prereqCheck = $this->checkPrerequisites($user->id, $course->id);
                if (!$prereqCheck['satisfied']) {
                    $status = 'prereq_missing';
                    $reason = 'Thiếu tiên quyết: ' . implode(', ', $prereqCheck['missing']);
                    $buttonText = 'Đăng ký';
                    $buttonClass = 'btn-primary';
                    $disabled = false; // CHO PHÉP click, sẽ báo lỗi khi submit
                } else {
                    // Check schedule conflict - KHÔNG disable
                    $conflictCheck = $this->checkScheduleConflict($user->id, $s);
                    if ($conflictCheck['hasConflict']) {
                        $status = 'conflict';
                        $reason = 'Trùng lịch với ' . $conflictCheck['conflictWith'];
                        $buttonText = 'Đăng ký';
                        $buttonClass = 'btn-primary';
                        $disabled = false; // CHO PHÉP click, sẽ báo lỗi khi submit
                    } elseif ($isFull) {
                        $status = 'full';
                        $reason = 'Lớp đã đầy';
                        $buttonText = 'Đăng ký';
                        $buttonClass = 'btn-primary';
                        $disabled = false; // CHO PHÉP click, sẽ báo lỗi khi submit
                    }
                }
            }

            $dayNames = ['', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];

            $sectionsData[] = [
                'id' => $s->id,
                'section_code' => $s->section_code,
                'lecturer' => $s->lecturer?->name ?? ($s->lecturer_name ?? 'Chưa có'),
                'room' => $s->room?->code ?? 'TBA',
                'day_name' => $dayNames[$s->day_of_week] ?? '',
                'shift' => $s->shift ? "Tiết {$s->shift->start_period}-{$s->shift->end_period}" : '',
                'enrolled' => $enrolled,
                'max_capacity' => $s->max_capacity,
                'status' => $status,
                'reason' => $reason,
                'button_text' => $buttonText,
                'button_class' => $buttonClass,
                'disabled' => $disabled,
            ];
        }

        return response()->json([
            'success' => true,
            'sections' => $sectionsData
        ]);
    }

    public function my()
    {
        $year = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');
        $regs = $this->currentRegistrations($year, $term)->load('classSection.course', 'classSection.room', 'classSection.shift', 'classSection.lecturer');
        $credits = $regs->sum(fn($r) => $r->classSection->course->credits);
        return view('student.registrations.my', compact('regs', 'year', 'term', 'credits'));
    }

    public function register(Request $request, ClassSection $classSection)
    {
        $user = Auth::user();
        $isAjax = $request->wantsJson() || $request->expectsJson();

        // Re-validate context
        $year = $classSection->academic_year;
        $term = $classSection->term;

        // Check if student is locked (UC3.1 - 2)
        if ($user->is_locked) {
            $msg = 'Tài khoản của bạn đã bị khóa học vụ. Vui lòng liên hệ phòng đào tạo.';
            return $isAjax ? response()->json(['success' => false, 'message' => $msg], 403) : back()->with('error', $msg);
        }

        // Check wave open and audience (UC3.1 - 2)
        if (!$this->isRegistrationOpenFor($user, $year, $term)) {
            $msg = 'Hiện không trong thời gian đăng ký cho đối tượng của bạn.';
            return $isAjax ? response()->json(['success' => false, 'message' => $msg], 400) : back()->with('error', $msg);
        }

        // Check if already registered for this section
        $alreadyRegistered = Registration::where('student_id', $user->id)
            ->where('class_section_id', $classSection->id)
            ->exists();
        if ($alreadyRegistered) {
            $msg = 'Bạn đã đăng ký lớp học phần này rồi.';
            return $isAjax ? response()->json(['success' => false, 'message' => $msg], 400) : back()->with('error', $msg);
        }

        // Prerequisites check with detailed error (UC3.1 - 2a)
        $prereqCheck = $this->checkPrerequisites($user->id, $classSection->course_id);
        if (!$prereqCheck['satisfied']) {
            $missing = implode(', ', $prereqCheck['missing']);
            $msg = 'Chưa đủ điều kiện tiên quyết. Bạn cần hoàn thành các học phần: ' . $missing;
            return $isAjax ? response()->json(['success' => false, 'message' => $msg], 400) : back()->with('error', $msg);
        }

        // Schedule conflict with suggestion (UC3.1 - 2b)
        $conflictCheck = $this->checkScheduleConflict($user->id, $classSection);
        if ($conflictCheck['hasConflict']) {
            $conflictWith = $conflictCheck['conflictWith'];
            $errorMsg = 'Xung đột lịch học với lớp ' . $conflictWith . '.';

            // Suggest alternative sections
            $alternatives = ClassSection::with('course')
                ->where('course_id', $classSection->course_id)
                ->where('academic_year', $year)
                ->where('term', $term)
                ->where('id', '!=', $classSection->id)
                ->get();

            if ($alternatives->count() > 0) {
                $errorMsg .= ' Gợi ý: Bạn có thể đăng ký các lớp khác cùng môn học.';
            }

            return $isAjax ? response()->json(['success' => false, 'message' => $errorMsg], 400) : back()->with('error', $errorMsg);
        }

        // Credit limit check (UC3.1 - 2c)
        $currentCredits = $this->currentRegistrations($year, $term)->sum(fn($r) => $r->classSection->course->credits);
        $newTotal = $currentCredits + $classSection->course->credits;
        if ($newTotal > $this->maxCredits) {
            $msg = 'Vượt giới hạn tín chỉ tối đa (' . $this->maxCredits . ' TC). Hiện tại: ' . $currentCredits . ' TC, sau khi thêm: ' . $newTotal . ' TC.';
            return $isAjax ? response()->json(['success' => false, 'message' => $msg], 400) : back()->with('error', $msg);
        }

        // Check for equivalent courses (UC3.1 - 2d)
        $equivalentCheck = $this->hasEquivalentCourse($user->id, $classSection->course_id);
        if ($equivalentCheck['hasEquivalent']) {
            $msg = 'Bạn đã đăng ký/đạt học phần tương đương: ' . $equivalentCheck['courseName'];
            return $isAjax ? response()->json(['success' => false, 'message' => $msg], 400) : back()->with('error', $msg);
        }

        // Capacity check (UC3.1 - 2e)
        $enrolled = Registration::where('class_section_id', $classSection->id)->count();
        if ($enrolled >= $classSection->max_capacity) {
            $msg = 'Lớp đã hết chỗ (' . $enrolled . '/' . $classSection->max_capacity . ').';
            return $isAjax ? response()->json(['success' => false, 'message' => $msg], 400) : back()->with('error', $msg);
        }

        // All checks passed - create registration (UC3.1 - 3)
        try {
            Registration::create([
                'student_id' => $user->id,
                'class_section_id' => $classSection->id,
            ]);

            // Get updated enrolled count
            $newEnrolled = Registration::where('class_section_id', $classSection->id)->count();

            // UC3.1 - 4: Success notification
            $successMsg = 'Đăng ký thành công môn ' . $classSection->course->code . ' - ' . $classSection->course->name . ', lớp ' . $classSection->section_code . '.';

            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => $successMsg,
                    'enrolled' => $newEnrolled,
                    'max_capacity' => $classSection->max_capacity
                ]);
            }

            return back()->with('success', $successMsg);
        } catch (\Exception $e) {
            // UC3.1 - 2f: System error
            $msg = 'Có lỗi xảy ra. Vui lòng thử lại sau.';
            return $isAjax ? response()->json(['success' => false, 'message' => $msg], 500) : back()->with('error', $msg);
        }
    }

    public function cancel(Request $request, Registration $registration)
    {
        $user = Auth::user();
        if ($registration->student_id !== $user->id) {
            abort(403);
        }

        $classSection = $registration->classSection;
        if (!$this->isRegistrationOpenFor($user, $classSection->academic_year, $classSection->term)) {
            return back()->with('error', 'Đợt đăng ký đã đóng.');
        }

        $registration->delete();
        return back()->with('success', 'Hủy đăng ký thành công.');
    }

    public function swap(Request $request)
    {
        $request->validate([
            'from_registration_id' => 'required|exists:registrations,id',
            'to_section_id' => 'required|exists:class_sections,id',
        ]);
        $user = Auth::user();
        $from = Registration::with('classSection')->findOrFail($request->from_registration_id);
        if ($from->student_id !== $user->id) abort(403);
        $to = ClassSection::with(['course', 'room', 'shift'])->findOrFail($request->to_section_id);

        // same course constraint for swap
        if ($from->classSection->course_id !== $to->course_id) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ được đổi giữa các lớp cùng một môn học.'
            ], 400);
        }
        // Wave open
        if (!$this->isRegistrationOpenFor($user, $to->academic_year, $to->term)) {
            return response()->json([
                'success' => false,
                'message' => 'Đợt đăng ký đã đóng.'
            ], 400);
        }
        // Capacity
        $enrolled = Registration::where('class_section_id', $to->id)->count();
        if ($enrolled >= $to->max_capacity) {
            return response()->json([
                'success' => false,
                'message' => 'Lớp chuyển đến đã đủ.'
            ], 400);
        }
        // Schedule conflict (excluding the from section)
        $conflictCheck = $this->checkScheduleConflictForSwap($user->id, $to, $from->id);
        if ($conflictCheck['hasConflict']) {
            return response()->json([
                'success' => false,
                'message' => 'Xung đột lịch học với lớp ' . $conflictCheck['conflictWith'] . '.'
            ], 400);
        }

        DB::transaction(function () use ($from, $to) {
            $from->delete();
            Registration::create([
                'student_id' => $from->student_id,
                'class_section_id' => $to->id,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Đổi lớp thành công.'
        ]);
    }

    public function timetable(Request $request)
    {
        // Required filters
        $academicYear = $request->query('academic_year', session('academic_year', '2024-2025'));
        $term = $request->query('term', session('term', 'HK1'));
        // View mode and base date
        $view = $request->query('view', 'week'); // week|month|list
        $baseDate = $request->query('date') ? \Carbon\Carbon::parse($request->query('date')) : now();
        $weekStart = (clone $baseDate)->startOfWeek();
        $weekEnd = (clone $weekStart)->copy()->endOfWeek();

        // Current registrations with sections
        $regs = $this->currentRegistrations($academicYear, $term)->load('classSection.course', 'classSection.room', 'classSection.shift', 'classSection.lecturer');
        $sections = $regs->map(fn($r) => $r->classSection);

        // Weekly schedule grid by day (0-based array kept for backward-compat but we'll also build a 2D grid by day/period)
        $schedule = [];
        for ($i = 0; $i < 7; $i++) {
            $schedule[$i] = [];
        }
        foreach ($sections as $s) {
            $dayIndex = max(0, (int)($s->day_of_week ?? 1) - 1);
            $schedule[$dayIndex][] = [
                'id' => $s->id,
                'course_name' => $s->course->name,
                'course_code' => $s->course->code,
                'section_code' => $s->section_code,
                'room' => $s->room?->code ?? 'TBA',
                'shift' => $s->shift ? ("Tiết {$s->shift->start_period}-{$s->shift->end_period}") : '',
                'time' => $s->shift ? (($s->shift->start_time && $s->shift->end_time) ? ($s->shift->start_time . ' - ' . $s->shift->end_time) : '') : '',
                'lecturer' => $s->lecturer?->name ?? ($s->lecturer_name ?? ''),
            ];
        }

        // Build a 2D schedule grid with rowspan information: $scheduleGrid[day][period]
        // day: 1..7 (Thứ 2 .. Chủ nhật), period: 1..15 (default)
        $scheduleGrid = [];
        for ($day = 1; $day <= 7; $day++) {
            $scheduleGrid[$day] = [];
        }
        $maxPeriods = 15; // default number of periods per day
        foreach ($sections as $s) {
            if (!$s->shift) continue;
            $day = (int)($s->day_of_week ?? 1); // 1..7
            $start = (int)$s->shift->start_period;
            $end = (int)$s->shift->end_period;
            if ($end < $start) {
                [$start, $end] = [$end, $start];
            }
            $rowspan = max(1, $end - $start + 1);
            $maxPeriods = max($maxPeriods, $end);
            // Place the main cell
            $scheduleGrid[$day][$start] = [
                'class' => $s,
                'rowspan' => $rowspan,
            ];
            // Mark covered periods under the rowspan
            for ($p = $start + 1; $p <= $end; $p++) {
                $scheduleGrid[$day][$p] = 'covered';
            }
        }

        $days = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];

        // Month matrix view: replicate by weekday
        $monthWeeks = [];
        if ($view === 'month') {
            $monthStart = (clone $baseDate)->startOfMonth();
            $gridStart = (clone $monthStart)->startOfWeek();
            for ($w = 0; $w < 6; $w++) {
                $row = [];
                for ($d = 0; $d < 7; $d++) {
                    $date = (clone $gridStart)->addDays($w * 7 + $d);
                    $dow = $date->isoWeekday(); // 1..7
                    $classesForDay = $sections
                        ->filter(fn($s) => (int)$s->day_of_week === (int)$dow)
                        ->values()
                        ->map(fn($s) => [
                            'id' => $s->id,
                            'course_code' => $s->course->code,
                            'course_name' => $s->course->name,
                            'section_code' => $s->section_code,
                            'room' => $s->room?->code ?? 'TBA',
                            'shift' => $s->shift ? ("Tiết {$s->shift->start_period}-{$s->shift->end_period}") : '',
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

        // List view
        $listSections = $sections->sortBy(['day_of_week', 'shift_id'])->values();

        // Distinct years/terms for dropdown
        $years = ClassSection::whereHas('registrations', function ($q) {
            $q->where('student_id', Auth::id());
        })
            ->select('academic_year')->distinct()->orderBy('academic_year', 'desc')->pluck('academic_year');
        $terms = ClassSection::whereHas('registrations', function ($q) {
            $q->where('student_id', Auth::id());
        })
            ->select('term')->distinct()->pluck('term');

        // Navigation dates and label
        if ($view === 'month') {
            $prevDate = (clone $baseDate)->subMonth()->toDateString();
            $nextDate = (clone $baseDate)->addMonth()->toDateString();
            $rangeLabel = $baseDate->translatedFormat('F Y');
        } else {
            $prevDate = (clone $baseDate)->subWeek()->toDateString();
            $nextDate = (clone $baseDate)->addWeek()->toDateString();
            $rangeLabel = $weekStart->format('d/m') . ' - ' . $weekEnd->format('d/m/Y');
        }

        return view('student.timetable.index', [
            'regs' => $regs,
            'schedule' => $schedule,
            'scheduleGrid' => $scheduleGrid,
            'days' => $days,
            'academicYear' => $academicYear,
            'year' => $academicYear, // backward-compat in view
            'term' => $term,
            'totalClasses' => $sections->count(),
            'view' => $view,
            'baseDate' => $baseDate->toDateString(),
            'prevDate' => $prevDate,
            'nextDate' => $nextDate,
            'rangeLabel' => $rangeLabel,
            'monthWeeks' => $monthWeeks,
            'listSections' => $listSections,
            'years' => $years,
            'terms' => $terms,
            'maxPeriods' => $maxPeriods,
        ]);
    }

    public function exportIcs()
    {
        $year = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');
        $regs = $this->currentRegistrations($year, $term)->load('classSection.course', 'classSection.room', 'classSection.shift');

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//DKTC//Student Schedule//VN'
        ];
        foreach ($regs as $r) {
            $shift = $r->classSection->shift;
            $course = $r->classSection->course;
            // Placeholder date mapping: assume week start today for demo
            $date = now()->startOfWeek()->addDays(($shift->day_of_week ?? 2) - 1);
            $start = $date->copy()->setTime(7, 0)->addMinutes(($shift->start_period - 1) * 50);
            $end = $date->copy()->setTime(7, 0)->addMinutes(($shift->end_period) * 50);
            $lines[] = 'BEGIN:VEVENT';
            $lines[] = 'UID:' . uniqid();
            $lines[] = 'DTSTAMP:' . now()->format('Ymd\THis\Z');
            $lines[] = 'DTSTART:' . $start->format('Ymd\THis');
            $lines[] = 'DTEND:' . $end->format('Ymd\THis');
            $lines[] = 'SUMMARY:' . $course->code . ' - ' . $course->name;
            $lines[] = 'LOCATION:' . ($r->classSection->room->code ?? '');
            $lines[] = 'END:VEVENT';
        }
        $lines[] = 'END:VCALENDAR';

        return response(implode("\r\n", $lines), 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="timetable.ics"'
        ]);
    }

    public function exportTimetableCsv(Request $request)
    {
        $year = $request->query('academic_year', session('academic_year', '2024-2025'));
        $term = $request->query('term', session('term', 'HK1'));
        $regs = $this->currentRegistrations($year, $term)->load('classSection.course', 'classSection.room', 'classSection.shift');
        $sections = $regs->map(fn($r) => $r->classSection)->sortBy(['day_of_week', 'shift_id']);

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="student-timetable.csv"',
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

        return response()->streamDownload($callback, 'student-timetable.csv', $headers);
    }

    public function printTimetable(Request $request)
    {
        $year = $request->query('academic_year', session('academic_year', '2024-2025'));
        $term = $request->query('term', session('term', 'HK1'));
        $regs = $this->currentRegistrations($year, $term)->load('classSection.course', 'classSection.room', 'classSection.shift');
        $sections = $regs->map(fn($r) => $r->classSection)->sortBy(['day_of_week', 'shift_id']);
        return view('student.timetable.print', compact('sections', 'year', 'term'));
    }

    public function classDetailJson(ClassSection $classSection)
    {
        // Only allow if the student is registered to this section
        $userId = Auth::id();
        $isRegistered = Registration::where('student_id', $userId)->where('class_section_id', $classSection->id)->exists();
        if (!$isRegistered) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $classSection->load(['course.faculty', 'room', 'shift', 'lecturer']);
        $currentCount = Registration::where('class_section_id', $classSection->id)->count();

        return response()->json([
            'id' => $classSection->id,
            'course_code' => $classSection->course->code,
            'course_name' => $classSection->course->name,
            'faculty' => $classSection->course->faculty->name ?? null,
            'section_code' => $classSection->section_code,
            'academic_year' => $classSection->academic_year,
            'term' => $classSection->term,
            'day_of_week' => $classSection->day_of_week,
            'shift_label' => $classSection->shift ? ("Tiết {$classSection->shift->start_period}-{$classSection->shift->end_period}") : 'TBA',
            'time' => ($classSection->shift && $classSection->shift->start_time && $classSection->shift->end_time) ? ($classSection->shift->start_time . ' - ' . $classSection->shift->end_time) : null,
            'room' => $classSection->room?->code ?? 'Chưa xếp phòng',
            'lecturer' => $classSection->lecturer?->name ?? ($classSection->lecturer_name ?? null),
            'enrollment' => [
                'current' => $currentCount,
                'max' => $classSection->max_capacity,
            ],
        ]);
    }

    // --- Registration Cart (Shopping Cart) ---
    public function cart()
    {
        $year = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');
        $ids = session('reg_cart', []);
        $sections = ClassSection::with(['course', 'room', 'shift'])->whereIn('id', $ids)->get();
        $existingRegs = $this->currentRegistrations($year, $term)->load('classSection.course', 'classSection.shift');
        $issues = $this->evaluateCart($sections, $existingRegs);
        $totalCredits = $existingRegs->sum(fn($r) => $r->classSection->course->credits) + $sections->sum(fn($s) => $s->course->credits);
        return view('student.registrations.cart', compact('sections', 'issues', 'totalCredits', 'year', 'term'));
    }

    public function cartAdd(ClassSection $classSection)
    {
        $user = Auth::user();
        // Basic wave check (quick feedback; full checks on checkout)
        if (!$this->isRegistrationOpenFor($user, $classSection->academic_year, $classSection->term)) {
            return back()->with('error', 'Hiện không trong thời gian đăng ký.');
        }
        $cart = session('reg_cart', []);
        if (!in_array($classSection->id, $cart)) {
            $cart[] = $classSection->id;
            session(['reg_cart' => $cart]);
        }
        return back()->with('status', 'Đã thêm vào giỏ.');
    }

    public function cartRemove(ClassSection $classSection)
    {
        $cart = array_values(array_filter(session('reg_cart', []), fn($id) => (int)$id !== (int)$classSection->id));
        session(['reg_cart' => $cart]);
        return back()->with('status', 'Đã bỏ khỏi giỏ.');
    }

    public function cartCheckout(Request $request)
    {
        $user = Auth::user();
        $year = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');
        $ids = session('reg_cart', []);
        if (empty($ids)) return redirect()->route('student.cart')->with('error', 'Giỏ trống.');
        $sections = ClassSection::with(['course', 'room', 'shift'])->whereIn('id', $ids)->get();
        $existingRegs = $this->currentRegistrations($year, $term)->load('classSection.course', 'classSection.shift');
        $issues = $this->evaluateCart($sections, $existingRegs);

        if (!empty($issues)) {
            return redirect()->route('student.cart')->with('error', 'Giỏ có lỗi, vui lòng kiểm tra.');
        }

        DB::transaction(function () use ($sections, $user) {
            foreach ($sections as $s) {
                // Final guards per item
                $enrolled = Registration::where('class_section_id', $s->id)->count();
                if ($enrolled >= $s->max_capacity) {
                    throw new \RuntimeException('Lớp ' . $s->section_code . ' đã đủ.');
                }
                Registration::firstOrCreate([
                    'student_id' => $user->id,
                    'class_section_id' => $s->id,
                ]);
            }
        });

        session()->forget('reg_cart');
        return redirect()->route('student.my')->with('status', 'Đăng ký thành công các môn trong giỏ.');
    }

    private function evaluateCart($sections, $existingRegs): array
    {
        $user = Auth::user();
        $year = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');
        $issues = [];

        // Wave for each
        foreach ($sections as $s) {
            if (!$this->isRegistrationOpenFor($user, $s->academic_year, $s->term)) {
                $issues[$s->id][] = 'Đợt đăng ký đã đóng.';
            }
            // Already registered
            if ($existingRegs->firstWhere('class_section_id', $s->id)) {
                $issues[$s->id][] = 'Đã đăng ký lớp này.';
            }
            // Capacity
            $enrolled = Registration::where('class_section_id', $s->id)->count();
            if ($enrolled >= $s->max_capacity) {
                $issues[$s->id][] = 'Lớp đã đủ.';
            }
            // Prereq
            if (!$this->satisfyPrerequisites($user->id, $s->course_id)) {
                $issues[$s->id][] = 'Chưa thỏa điều kiện tiên quyết.';
            }
        }

        // Schedule conflicts (with existing regs)
        foreach ($sections as $s) {
            if ($this->hasScheduleConflict($user->id, $s)) {
                $issues[$s->id][] = 'Xung đột với lớp đã đăng ký.';
            }
        }

        // Conflicts within cart and duplicate courses
        for ($i = 0; $i < count($sections); $i++) {
            for ($j = $i + 1; $j < count($sections); $j++) {
                $a = $sections[$i];
                $b = $sections[$j];
                if ($a->course_id === $b->course_id) {
                    $issues[$a->id][] = 'Trùng môn với lớp khác trong giỏ.';
                    $issues[$b->id][] = 'Trùng môn với lớp khác trong giỏ.';
                }
                if ($a->day_of_week === $b->day_of_week && $a->shift_id === $b->shift_id) {
                    $issues[$a->id][] = 'Trùng lịch với lớp trong giỏ.';
                    $issues[$b->id][] = 'Trùng lịch với lớp trong giỏ.';
                }
            }
        }

        // Credit limit check
        $currentCredits = $existingRegs->sum(fn($r) => $r->classSection->course->credits);
        $cartCredits = $sections->sum(fn($s) => $s->course->credits);
        if ($currentCredits + $cartCredits > $this->maxCredits) {
            foreach ($sections as $s) {
                $issues[$s->id][] = 'Vượt giới hạn tín chỉ.';
            }
        }

        return array_map('array_unique', $issues);
    }

    private function currentRegistrations(string $year, string $term)
    {
        $userId = Auth::id();
        return Registration::with(['classSection.course'])
            ->where('student_id', $userId)
            ->whereHas('classSection', function ($q) use ($year, $term) {
                $q->where('academic_year', $year)->where('term', $term);
            })->get();
    }

    private function hasScheduleConflict(int $studentId, ClassSection $section, ?int $excludeRegistrationId = null): bool
    {
        $query = Registration::where('student_id', $studentId)
            ->whereHas('classSection', function ($q) use ($section) {
                $q->where('academic_year', $section->academic_year)
                    ->where('term', $section->term)
                    ->where('day_of_week', $section->day_of_week)
                    ->where('shift_id', $section->shift_id);
            });
        if ($excludeRegistrationId) {
            $query->where('id', '!=', $excludeRegistrationId);
        }
        return $query->exists();
    }

    private function satisfyPrerequisites(int $studentId, int $courseId): bool
    {
        $check = $this->checkPrerequisites($studentId, $courseId);
        return $check['satisfied'];
    }

    private function checkPrerequisites(int $studentId, int $courseId): array
    {
        $course = Course::with('prerequisites')->find($courseId);
        if (!$course) {
            return ['satisfied' => false, 'missing' => ['Môn học không tồn tại']];
        }

        if ($course->prerequisites->isEmpty()) {
            return ['satisfied' => true, 'missing' => []];
        }

        $passedCourseIds = Transcript::where('student_id', $studentId)
            ->where('passed', true)
            ->pluck('course_id')
            ->toArray();

        $missing = [];
        foreach ($course->prerequisites as $pr) {
            if (!in_array($pr->id, $passedCourseIds)) {
                $missing[] = $pr->code . ' - ' . $pr->name;
            }
        }

        return [
            'satisfied' => empty($missing),
            'missing' => $missing
        ];
    }

    private function checkScheduleConflict(int $studentId, ClassSection $section): array
    {
        // Load target shift periods
        $targetStart = $section->shift?->start_period ?? null;
        $targetEnd = $section->shift?->end_period ?? null;

        $regsSameDay = Registration::with(['classSection.course', 'classSection.shift'])
            ->where('student_id', $studentId)
            ->whereHas('classSection', function ($q) use ($section) {
                $q->where('academic_year', $section->academic_year)
                    ->where('term', $section->term)
                    ->where('day_of_week', $section->day_of_week);
            })
            ->get();

        foreach ($regsSameDay as $reg) {
            $s = $reg->classSection;
            $start = $s->shift?->start_period ?? null;
            $end = $s->shift?->end_period ?? null;
            if ($start !== null && $end !== null && $targetStart !== null && $targetEnd !== null) {
                // Overlap if ranges intersect
                $overlap = !($targetEnd < $start || $end < $targetStart);
                if ($overlap) {
                    return [
                        'hasConflict' => true,
                        'conflictWith' => $s->course->code . ' (' . $s->section_code . ')'
                    ];
                }
            } else {
                // Fallback to shift_id equality
                if ($s->shift_id == $section->shift_id) {
                    return [
                        'hasConflict' => true,
                        'conflictWith' => $s->course->code . ' (' . $s->section_code . ')'
                    ];
                }
            }
        }

        return ['hasConflict' => false, 'conflictWith' => null];
    }

    private function checkScheduleConflictForSwap(int $studentId, ClassSection $section, int $excludeRegistrationId): array
    {
        $targetStart = $section->shift?->start_period ?? null;
        $targetEnd = $section->shift?->end_period ?? null;

        $regsSameDay = Registration::with(['classSection.course', 'classSection.shift'])
            ->where('student_id', $studentId)
            ->where('id', '!=', $excludeRegistrationId)
            ->whereHas('classSection', function ($q) use ($section) {
                $q->where('academic_year', $section->academic_year)
                    ->where('term', $section->term)
                    ->where('day_of_week', $section->day_of_week);
            })
            ->get();

        foreach ($regsSameDay as $reg) {
            $s = $reg->classSection;
            $start = $s->shift?->start_period ?? null;
            $end = $s->shift?->end_period ?? null;
            if ($start !== null && $end !== null && $targetStart !== null && $targetEnd !== null) {
                $overlap = !($targetEnd < $start || $end < $targetStart);
                if ($overlap) {
                    return [
                        'hasConflict' => true,
                        'conflictWith' => $s->course->code . ' (' . $s->section_code . ')'
                    ];
                }
            } else {
                if ($s->shift_id == $section->shift_id) {
                    return [
                        'hasConflict' => true,
                        'conflictWith' => $s->course->code . ' (' . $s->section_code . ')'
                    ];
                }
            }
        }

        return ['hasConflict' => false, 'conflictWith' => null];
    }

    private function hasEquivalentCourse(int $studentId, int $courseId): array
    {
        // Check if student has already registered or passed equivalent courses
        // For now, we'll check if the same course is already in their transcript
        $existingInTranscript = Transcript::with('course')
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->where('passed', true)
            ->first();

        if ($existingInTranscript) {
            return [
                'hasEquivalent' => true,
                'courseName' => $existingInTranscript->course->code . ' - ' . $existingInTranscript->course->name
            ];
        }

        // Check if already registered for current term
        $year = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');

        $alreadyRegistered = Registration::with('classSection.course')
            ->where('student_id', $studentId)
            ->whereHas('classSection', function ($q) use ($courseId, $year, $term) {
                $q->where('course_id', $courseId)
                    ->where('academic_year', $year)
                    ->where('term', $term);
            })
            ->first();

        if ($alreadyRegistered) {
            return [
                'hasEquivalent' => true,
                'courseName' => $alreadyRegistered->classSection->course->code . ' (đã đăng ký lớp ' . $alreadyRegistered->classSection->section_code . ')'
            ];
        }

        return ['hasEquivalent' => false, 'courseName' => null];
    }

    private function isRegistrationOpenFor($user, string $year, string $term): bool
    {
        $now = now();
        $waves = RegistrationWave::where('academic_year', $year)->where('term', $term)
            ->where('starts_at', '<=', $now)->where('ends_at', '>=', $now)->get();
        if ($waves->isEmpty()) return false;
        foreach ($waves as $w) {
            $aud = json_decode($w->audience, true) ?? [];
            $facOk = empty($aud['faculties']) || in_array($user->faculty_id, $aud['faculties']);
            $cohOk = empty($aud['cohorts']) || in_array($user->class_cohort, $aud['cohorts']);
            if ($facOk && $cohOk) return true;
        }
        return false;
    }

    public function print()
    {
        $year = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');
        $regs = $this->currentRegistrations($year, $term)->load('classSection.course', 'classSection.room', 'classSection.shift');
        $credits = $regs->sum(fn($r) => $r->classSection->course->credits);
        return view('student.registrations.print', compact('regs', 'year', 'term', 'credits'));
    }

    /**
     * API: Lấy danh sách các lớp có thể đổi cho một môn học
     */
    public function availableSectionsForSwap(Request $request)
    {
        $user = Auth::user();
        $courseId = $request->query('course_id');
        $currentSectionId = $request->query('current_section_id');

        $year = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');

        // Lấy danh sách lớp cùng môn học (trừ lớp hiện tại)
        $sections = ClassSection::with(['course', 'room', 'shift', 'lecturer'])
            ->where('course_id', $courseId)
            ->where('academic_year', $year)
            ->where('term', $term)
            ->where('id', '!=', $currentSectionId)
            ->get();

        // Lấy lịch học hiện tại của sinh viên (để check conflict)
        $mySchedule = Registration::with('classSection')
            ->where('student_id', $user->id)
            ->whereHas('classSection', function ($q) use ($year, $term) {
                $q->where('academic_year', $year)->where('term', $term);
            })
            ->get()
            ->map(function ($reg) {
                $s = $reg->classSection;
                return [
                    'section_id' => $s->id,
                    'day_of_week' => $s->day_of_week,
                    'shift_id' => $s->shift_id,
                ];
            });

        // Format sections data với status và lý do
        $sectionsData = $sections->map(function ($s) use ($mySchedule, $currentSectionId) {
            $enrolled = Registration::where('class_section_id', $s->id)->count();
            $dayNames = ['', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];

            // Kiểm tra trạng thái
            $status = 'available';
            $reason = '';

            // Check trùng lịch (loại trừ lớp hiện tại)
            $hasConflict = $mySchedule->first(function ($schedule) use ($s, $currentSectionId) {
                return $schedule['section_id'] != $currentSectionId
                    && $schedule['day_of_week'] == $s->day_of_week
                    && $schedule['shift_id'] == $s->shift_id;
            });

            if ($hasConflict) {
                $status = 'conflict';
                $reason = 'Trùng lịch với lớp đã đăng ký';
            } else if ($enrolled >= $s->max_capacity) {
                $status = 'full';
                $reason = 'Lớp đã hết chỗ';
            }

            return [
                'id' => $s->id,
                'section_code' => $s->section_code,
                'day_of_week' => $s->day_of_week,
                'day_name' => $dayNames[$s->day_of_week] ?? '',
                'shift_id' => $s->shift_id,
                'shift' => $s->shift ? "{$s->shift->start_period}-{$s->shift->end_period}" : '',
                'room' => $s->room?->code ?? 'TBA',
                'lecturer' => $s->lecturer?->name ?? 'N/A',
                'enrolled' => $enrolled,
                'max_capacity' => $s->max_capacity,
                'status' => $status,
                'reason' => $reason,
            ];
        });

        return response()->json([
            'sections' => $sectionsData,
            'mySchedule' => $mySchedule,
        ]);
    }
}
