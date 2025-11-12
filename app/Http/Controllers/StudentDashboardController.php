<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\RegistrationWave;
use App\Models\Announcement;
use App\Models\ClassSection;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Required filters
        $academicYear = $request->query('academic_year', session('academic_year', '2024-2025'));
        $term = $request->query('term', session('term', 'HK1'));

        // View mode and base date
        $view = $request->query('view', 'week'); // week|month|list
        $baseDate = $request->query('date') ? Carbon::parse($request->query('date')) : now();
        $weekStart = (clone $baseDate)->startOfWeek();
        $weekEnd = (clone $weekStart)->copy()->endOfWeek();

        // Lấy danh sách đăng ký của sinh viên
        $registrations = Registration::with([
            'classSection.course',
            'classSection.room',
            'classSection.shift',
            'classSection.lecturer'
        ])
            ->where('student_id', $user->id)
            ->whereHas('classSection', function ($q) use ($academicYear, $term) {
                $q->where('academic_year', $academicYear)->where('term', $term);
            })
            ->get();

        // Tính tổng tín chỉ
        $totalCredits = $registrations->sum(fn($r) => $r->classSection->course->credits);
        // Lấy danh sách các lớp học phần
        $classSections = $registrations->map(fn($r) => $r->classSection);

        // Tổ chức dữ liệu theo lịch tuần (7 ngày x các ca học)
        $schedule = [];
        for ($i = 0; $i < 7; $i++) {
            $schedule[$i] = [];
        }

        foreach ($classSections as $section) {
            if (!$section) continue;

            $dayIndex = $section->day_of_week - 1; // 1=Thứ 2 -> index 0

            $schedule[$dayIndex][] = [
                'id' => $section->id,
                'course_name' => $section->course->name,
                'course_code' => $section->course->code,
                'section_code' => $section->section_code,
                'room' => $section->room ? $section->room->code : 'TBA',
                'shift' => $section->shift ? "Tiết {$section->shift->start_period}-{$section->shift->end_period}" : '',
                'time' => $section->shift ? $section->shift->start_time . ' - ' . $section->shift->end_time : '',
                'lecturer' => $section->lecturer ? $section->lecturer->name : 'N/A',
            ];
        }

        $days = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];

        // Month matrix (6 weeks x 7 days)
        $monthWeeks = [];
        if ($view === 'month') {
            $monthStart = (clone $baseDate)->startOfMonth();
            $gridStart = (clone $monthStart)->startOfWeek();
            for ($w = 0; $w < 6; $w++) {
                $row = [];
                for ($d = 0; $d < 7; $d++) {
                    $date = (clone $gridStart)->addDays($w * 7 + $d);
                    $dow = $date->isoWeekday(); // 1..7

                    // Lọc các lớp của sinh viên cho ngày này
                    $classesForDay = $classSections->filter(fn($s) => (int)$s->day_of_week === (int)$dow)
                        ->values()
                        ->map(fn($s) => [
                            'id' => $s->id,
                            'course_code' => $s->course->code,
                            'course_name' => $s->course->name,
                            'section_code' => $s->section_code,
                            'room' => $s->room?->code ?? 'TBA',
                            'shift' => $s->shift ? "Tiết {$s->shift->start_period}-{$s->shift->end_period}" : '',
                            'lecturer' => $s->lecturer ? $s->lecturer->name : 'N/A',
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
        $listSections = $registrations->sortBy(function ($reg) {
            return $reg->classSection->day_of_week . '_' . $reg->classSection->shift_id;
        })->values();

        // Years/terms for dropdowns
        // Lấy từ database + thêm các năm mặc định
        $yearsFromDB = ClassSection::select('academic_year')->distinct()->pluck('academic_year');
        $currentYear = now()->year;
        $defaultYears = collect([
            ($currentYear - 2) . '-' . ($currentYear - 1),
            ($currentYear - 1) . '-' . $currentYear,
            $currentYear . '-' . ($currentYear + 1),
            ($currentYear + 1) . '-' . ($currentYear + 2),
        ]);
        $years = $yearsFromDB->merge($defaultYears)->unique()->sort()->values()->reverse();

        // Danh sách học kỳ đầy đủ
        $termsFromDB = ClassSection::select('term')->distinct()->pluck('term');
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

        return view('student.dashboard', [
            'schedule' => $schedule,
            'days' => $days,
            'academicYear' => $academicYear,
            'term' => $term,
            'totalCredits' => $totalCredits,
            'currentRegs' => $listSections,
            'view' => $view,
            'baseDate' => $baseDate->toDateString(),
            'prevDate' => $prevDate,
            'nextDate' => $nextDate,
            'rangeLabel' => $rangeLabel,
            'monthWeeks' => $monthWeeks,
            'years' => $years,
            'terms' => $terms,
        ]);
    }
}
