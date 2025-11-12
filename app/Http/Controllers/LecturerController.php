<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Faculty;
use App\Models\ClassSection;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class LecturerController extends Controller
{
    /**
     * Display a listing of lecturers (UC2.2)
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'lecturer')->with('faculty');

        // Search filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Faculty filter
        if ($facultyId = $request->input('faculty_id')) {
            $query->where('faculty_id', $facultyId);
        }

        // UC2.2 S1: Degree filter
        if ($degree = $request->input('degree')) {
            $query->where('degree', $degree);
        }

        $lecturers = $query->orderBy('code')->paginate(20);
        $faculties = Faculty::orderBy('name')->get();

        // UC2.2: compute per-lecturer metrics for selected AY/Term (for status display only)
        $academicYear = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');

        $ids = $lecturers->pluck('id')->all();
        $counts = [];
        if (!empty($ids)) {
            // Number of classes per lecturer
            $counts = ClassSection::whereIn('lecturer_id', $ids)
                ->where('academic_year', $academicYear)
                ->where('term', $term)
                ->selectRaw('lecturer_id, COUNT(*) as cnt')
                ->groupBy('lecturer_id')
                ->pluck('cnt', 'lecturer_id')
                ->toArray();
        }

        // Unique degrees for filter dropdown
        $degrees = User::where('role', 'lecturer')->whereNotNull('degree')->distinct()->pluck('degree')->filter()->sort()->values();

        return view('admin.lecturers.index', [
            'lecturers' => $lecturers,
            'faculties' => $faculties,
            'degrees' => $degrees,
            'academicYear' => $academicYear,
            'term' => $term,
            'classCounts' => $counts,
        ]);
    }

    /**
     * Show the form for creating a new lecturer
     */
    public function create()
    {
        $faculties = Faculty::orderBy('name')->get();
        return view('admin.lecturers.create', compact('faculties'));
    }

    /**
     * Store a newly created lecturer in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:users,code',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'faculty_id' => 'required|exists:faculties,id',
            'phone' => 'nullable|string|max:20',
            'degree' => 'nullable|string|max:100',
            'password' => 'required|string|min:6',
        ]);

        $validated['role'] = 'lecturer';
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_locked'] = false;

        User::create($validated);

        return redirect()->route('lecturers.index')
            ->with('success', 'Thêm giảng viên thành công!');
    }

    /**
     * Show the form for editing the specified lecturer
     */
    public function edit(User $lecturer)
    {
        if ($lecturer->role !== 'lecturer') {
            abort(404);
        }

        $faculties = Faculty::orderBy('name')->get();
        return view('admin.lecturers.edit', compact('lecturer', 'faculties'));
    }

    /**
     * Update the specified lecturer in storage
     */
    public function update(Request $request, User $lecturer)
    {
        if ($lecturer->role !== 'lecturer') {
            abort(404);
        }

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($lecturer->id)],
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($lecturer->id)],
            'faculty_id' => 'required|exists:faculties,id',
            'phone' => 'nullable|string|max:20',
            'degree' => 'nullable|string|max:100',
            'password' => 'nullable|string|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $lecturer->update($validated);

        return redirect()->route('lecturers.index')
            ->with('success', 'Cập nhật giảng viên thành công!');
    }

    /**
     * Remove the specified lecturer from storage
     */
    public function destroy(User $lecturer)
    {
        if ($lecturer->role !== 'lecturer') {
            abort(404);
        }

        // Check if lecturer is assigned to any class sections
        if ($lecturer->classSections()->exists()) {
            return back()->with('error', 'Không thể xóa giảng viên đang phụ trách lớp học phần!');
        }

        $lecturer->delete();

        return redirect()->route('lecturers.index')
            ->with('success', 'Xóa giảng viên thành công!');
    }

    /**
     * UC2.2 R4: Return lecturer detail JSON (read-only view).
     */
    public function detailJson(User $lecturer)
    {
        if ($lecturer->role !== 'lecturer') abort(404);

        $lecturer->load(['faculty', 'qualifications']);

        // Recent teaching history (last 10 class sections across all terms)
        $history = ClassSection::with(['course', 'room', 'shift'])
            ->where('lecturer_id', $lecturer->id)
            ->orderBy('academic_year', 'desc')
            ->orderBy('term', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($cs) {
                return [
                    'academic_year' => $cs->academic_year,
                    'term' => $cs->term,
                    'course' => $cs->course->code . ' - ' . $cs->course->name,
                    'section' => $cs->section_code,
                    'room' => $cs->room->code ?? 'TBA',
                    'day' => ['', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'CN'][$cs->day_of_week] ?? '',
                    'shift' => $cs->shift ? 'Tiết ' . $cs->shift->start_period . '-' . $cs->shift->end_period : '',
                ];
            });

        return response()->json([
            'lecturer' => [
                'code' => $lecturer->code,
                'name' => $lecturer->name,
                'email' => $lecturer->email,
                'phone' => $lecturer->phone ?? 'N/A',
                'faculty' => $lecturer->faculty->name ?? 'N/A',
                'degree' => $lecturer->degree ?? 'N/A',
            ],
            'qualifications' => $lecturer->qualifications->map(fn($c) => [
                'course_code' => $c->code,
                'course_name' => $c->name,
                'level' => $c->pivot->level ?? 'qualified',
            ]),
            'history' => $history,
        ]);
    }

    /**
     * UC2.8: Return timetable JSON for a lecturer in the selected term.
     */
    public function timetableJson(Request $request, User $lecturer)
    {
        if ($lecturer->role !== 'lecturer') abort(404);
        $academicYear = $request->query('academic_year', session('academic_year', '2024-2025'));
        $term = $request->query('term', session('term', 'HK1'));

        $sections = ClassSection::with(['course', 'room', 'shift'])
            ->where('academic_year', $academicYear)
            ->where('term', $term)
            ->where('lecturer_id', $lecturer->id)
            ->orderBy('day_of_week')
            ->orderByRaw('(select start_period from study_shifts where study_shifts.id = class_sections.shift_id) asc')
            ->get();

        $days = [1 => 'Thứ 2', 2 => 'Thứ 3', 3 => 'Thứ 4', 4 => 'Thứ 5', 5 => 'Thứ 6', 6 => 'Thứ 7', 7 => 'CN'];
        $schedule = [];
        foreach ($sections as $s) {
            $d = (int) $s->day_of_week;
            $schedule[$d] = $schedule[$d] ?? [];
            $schedule[$d][] = [
                'id' => $s->id,
                'course_code' => $s->course->code ?? 'N/A',
                'course_name' => $s->course->name ?? 'N/A',
                'section_code' => $s->section_code,
                'room' => $s->room->code ?? 'TBA',
                'shift' => $s->shift ? ('Tiết ' . $s->shift->start_period . '-' . $s->shift->end_period) : '',
                'start' => $s->shift->start_period ?? null,
                'end' => $s->shift->end_period ?? null,
            ];
        }

        return response()->json([
            'academic_year' => $academicYear,
            'term' => $term,
            'days' => $days,
            'schedule' => $schedule,
        ]);
    }

    /**
     * UC2.8: Quick-assign candidates for a lecturer (unassigned LHP in same faculty).
     */
    public function quickAssignCandidates(Request $request, User $lecturer)
    {
        if ($lecturer->role !== 'lecturer') abort(404);
        $academicYear = $request->query('academic_year', session('academic_year', '2024-2025'));
        $term = $request->query('term', session('term', 'HK1'));
        $search = trim((string)$request->query('q', ''));

        // Lecturer existing time slots for conflict detection
        $occupied = ClassSection::where('academic_year', $academicYear)
            ->where('term', $term)
            ->where('lecturer_id', $lecturer->id)
            ->get(['day_of_week', 'shift_id'])
            ->map(fn($r) => $r->day_of_week . '-' . $r->shift_id)
            ->toArray();
        $occupied = array_flip($occupied);

        $query = ClassSection::with(['course.faculty', 'room', 'shift'])
            ->withCount('registrations')
            ->whereNull('lecturer_id')
            ->where('academic_year', $academicYear)
            ->where('term', $term)
            ->whereHas('course', function ($q) use ($lecturer) {
                $q->where('faculty_id', $lecturer->faculty_id);
            });

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('section_code', 'like', "%{$search}%")
                    ->orWhereHas('course', function ($qq) use ($search) {
                        $qq->where('code', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                    });
            });
        }

        $list = $query->orderBy('section_code')->limit(50)->get();

        $days = [1 => 'Thứ 2', 2 => 'Thứ 3', 3 => 'Thứ 4', 4 => 'Thứ 5', 5 => 'Thứ 6', 6 => 'Thứ 7', 7 => 'CN'];
        $items = $list->map(function ($cs) use ($days, $occupied) {
            $key = $cs->day_of_week . '-' . $cs->shift_id;
            return [
                'id' => $cs->id,
                'code' => ($cs->course->code ?? 'N/A') . '-' . $cs->section_code,
                'course' => $cs->course->name ?? 'N/A',
                'day' => $days[$cs->day_of_week] ?? '',
                'shift' => $cs->shift ? ('Tiết ' . $cs->shift->start_period . '-' . $cs->shift->end_period) : '',
                'room' => $cs->room->code ?? 'TBA',
                'enrolled' => $cs->registrations_count,
                'capacity' => (int)($cs->max_capacity ?? 0),
                'has_conflict' => isset($occupied[$key]),
            ];
        });

        return response()->json([
            'academic_year' => $academicYear,
            'term' => $term,
            'items' => $items,
        ]);
    }
}
