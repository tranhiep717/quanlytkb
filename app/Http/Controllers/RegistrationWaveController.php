<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationWave;
use App\Models\Faculty;
use App\Models\ClassSection;
use App\Models\LogEntry;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RegistrationWaveController extends Controller
{
    // S-1: Danh sách đợt đăng ký
    public function index()
    {
        $waves = RegistrationWave::orderBy('starts_at', 'desc')->paginate(20);
        // Needed by index view to render audience faculties badges
        $faculties = Faculty::select('id', 'code', 'name')->orderBy('name')->get();
        // Distinct cohorts (e.g., K17, K18) from users/students
        $cohorts = User::whereNotNull('class_cohort')
            ->select('class_cohort')
            ->distinct()
            ->orderBy('class_cohort')
            ->pluck('class_cohort');
        // Distinct academic years and terms from existing class sections
        $years = ClassSection::select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');
        $terms = ClassSection::select('term')
            ->distinct()
            ->orderBy('term')
            ->pluck('term');
        return view('admin.registration-waves.index', [
            'waves' => $waves,
            'faculties' => $faculties,
            'cohorts' => $cohorts,
            'years' => $years,
            'terms' => $terms,
        ]);
    }

    // S-1: Hiển thị form tạo đợt đăng ký mới
    public function create()
    {
        $faculties = Faculty::orderBy('name')->get();
        return view('admin.registration-waves.create', ['faculties' => $faculties]);
    }

    // S-1: Lưu đợt đăng ký mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string|max:20',
            'term' => 'required|string|max:20',
            'name' => 'required|string|max:150',
            'faculties' => 'nullable|array',
            'faculties.*' => 'exists:faculties,id',
            'cohorts' => 'nullable|array',
            'cohorts.*' => 'string|max:50',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'class_section_ids' => 'nullable|array',
            'class_section_ids.*' => 'integer|exists:class_sections,id',
        ], [
            'academic_year.required' => 'Năm học là bắt buộc.',
            'term.required' => 'Học kỳ là bắt buộc.',
            'name.required' => 'Tên đợt đăng ký là bắt buộc.',
            'starts_at.required' => 'Thời gian bắt đầu là bắt buộc.',
            'ends_at.required' => 'Thời gian kết thúc là bắt buộc.',
            'ends_at.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
        ]);
        // Business validations: duplicate name & time overlap within same year/term
        $dup = RegistrationWave::where('name', $validated['name'])->exists();
        if ($dup) {
            return back()->withInput()
                ->with('business_error', 'Tên đợt đăng ký đã tồn tại. Vui lòng chọn tên khác.')
                ->withErrors(['name' => 'Tên đợt đăng ký đã tồn tại.']);
        }
        $conflict = RegistrationWave::where('academic_year', $validated['academic_year'])
            ->where('term', $validated['term'])
            ->where(function ($q) use ($validated) {
                $q->where('starts_at', '<', $validated['ends_at'])
                    ->where('ends_at', '>', $validated['starts_at']);
            })
            ->first();
        if ($conflict) {
            return back()->withInput()
                ->with('business_error', "Lỗi: Thời gian này bị trùng với '{$conflict->name}'. Vui lòng kiểm tra lại.")
                ->withErrors(['starts_at' => 'Khoảng thời gian bị trùng', 'ends_at' => 'Khoảng thời gian bị trùng']);
        }

        try {
            // S-1: Lưu audience dưới dạng JSON
            $audience = [
                'faculties' => $validated['faculties'] ?? [],
                'cohorts' => $validated['cohorts'] ?? [],
            ];

            $wave = RegistrationWave::create([
                'academic_year' => $validated['academic_year'],
                'term' => $validated['term'],
                'name' => $validated['name'],
                'audience' => json_encode($audience),
                'starts_at' => $validated['starts_at'],
                'ends_at' => $validated['ends_at'],
            ]);

            // Sync selected offerings if provided
            if (!empty($validated['class_section_ids'])) {
                $wave->classSections()->sync($validated['class_section_ids']);
            }

            LogEntry::create([
                'user_id' => auth()->id(),
                'action' => 'registration_wave_created',
                'metadata' => json_encode(['wave_id' => $wave->id, 'name' => $wave->name]),
            ]);

            return redirect()->route('registration-waves.index')->with('success', 'Tạo đợt đăng ký thành công.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Không thể lưu (lỗi DB/kết nối). Vui lòng thử lại.');
        }
    }

    // S-1: Hiển thị form chỉnh sửa đợt đăng ký
    public function edit(RegistrationWave $registrationWave)
    {
        $faculties = Faculty::orderBy('name')->get();
        $audience = json_decode($registrationWave->audience, true) ?? [];

        return view('admin.registration-waves.edit', [
            'wave' => $registrationWave,
            'faculties' => $faculties,
            'audience' => $audience,
        ]);
    }

    // S-1: Cập nhật đợt đăng ký
    public function update(Request $request, RegistrationWave $registrationWave)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string|max:20',
            'term' => 'required|string|max:20',
            'name' => 'required|string|max:150',
            'faculties' => 'nullable|array',
            'faculties.*' => 'exists:faculties,id',
            'cohorts' => 'nullable|array',
            'cohorts.*' => 'string|max:50',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'class_section_ids' => 'nullable|array',
            'class_section_ids.*' => 'integer|exists:class_sections,id',
        ], [
            'academic_year.required' => 'Năm học là bắt buộc.',
            'term.required' => 'Học kỳ là bắt buộc.',
            'name.required' => 'Tên đợt đăng ký là bắt buộc.',
            'starts_at.required' => 'Thời gian bắt đầu là bắt buộc.',
            'ends_at.required' => 'Thời gian kết thúc là bắt buộc.',
            'ends_at.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
        ]);

        $audience = [
            'faculties' => $validated['faculties'] ?? [],
            'cohorts' => $validated['cohorts'] ?? [],
        ];

        // Business validations for update
        $dup = RegistrationWave::where('name', $validated['name'])
            ->where('id', '!=', $registrationWave->id)
            ->exists();
        if ($dup) {
            return back()->withInput()
                ->with('business_error', 'Tên đợt đăng ký đã tồn tại. Vui lòng chọn tên khác.')
                ->withErrors(['name' => 'Tên đợt đăng ký đã tồn tại.']);
        }
        $conflict = RegistrationWave::where('academic_year', $validated['academic_year'])
            ->where('term', $validated['term'])
            ->where('id', '!=', $registrationWave->id)
            ->where(function ($q) use ($validated) {
                $q->where('starts_at', '<', $validated['ends_at'])
                    ->where('ends_at', '>', $validated['starts_at']);
            })
            ->first();
        if ($conflict) {
            return back()->withInput()
                ->with('business_error', "Lỗi: Thời gian này bị trùng với '{$conflict->name}'. Vui lòng kiểm tra lại.")
                ->withErrors(['starts_at' => 'Khoảng thời gian bị trùng', 'ends_at' => 'Khoảng thời gian bị trùng']);
        }

        try {
            $registrationWave->update([
                'academic_year' => $validated['academic_year'],
                'term' => $validated['term'],
                'name' => $validated['name'],
                'audience' => json_encode($audience),
                'starts_at' => $validated['starts_at'],
                'ends_at' => $validated['ends_at'],
            ]);

            // Sync selected offerings if provided (or clear when empty array supplied)
            $registrationWave->classSections()->sync($validated['class_section_ids'] ?? []);

            LogEntry::create([
                'user_id' => auth()->id(),
                'action' => 'registration_wave_updated',
                'metadata' => json_encode(['wave_id' => $registrationWave->id, 'name' => $registrationWave->name]),
            ]);

            return redirect()->route('registration-waves.index')->with('success', 'Cập nhật đợt đăng ký thành công.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Không thể lưu (lỗi DB/kết nối). Vui lòng thử lại.');
        }
    }

    // S-1: Xóa đợt đăng ký
    public function destroy(Request $request, RegistrationWave $registrationWave)
    {
        try {
            DB::transaction(function () use ($registrationWave) {
                // Detach offerings explicitly to avoid FK issues on some drivers
                $registrationWave->classSections()->detach();
                $registrationWave->delete();
            });

            LogEntry::create([
                'user_id' => auth()->id(),
                'action' => 'registration_wave_deleted',
                'metadata' => json_encode(['wave_name' => $registrationWave->name]),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['status' => 'ok']);
            }
            return redirect()->route('registration-waves.index')->with('success', 'Xóa đợt đăng ký thành công.');
        } catch (\Throwable $e) {
            report($e);
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Không thể xóa đợt đăng ký do ràng buộc dữ liệu.'], 422);
            }
            return redirect()->route('registration-waves.index')->with('error', 'Không thể xóa đợt đăng ký do ràng buộc dữ liệu. Vui lòng gỡ liên kết lớp học phần hoặc thử lại sau.');
        }
    }

    // JSON: List offerings (class sections) filtered by academic year/term and optional faculty/q
    public function offerings(Request $request)
    {
        $year = $request->query('academic_year');
        $term = $request->query('term');
        $facultyId = $request->query('faculty_id');
        $q = $request->query('q');

        if (!$year || !$term) {
            return response()->json(['data' => []]);
        }

        $query = ClassSection::with(['course.faculty', 'lecturer', 'room', 'shift'])
            ->where('academic_year', $year)
            ->where('term', $term);

        if ($facultyId) {
            $query->whereHas('course', function ($qq) use ($facultyId) {
                $qq->where('faculty_id', $facultyId);
            });
        }

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('section_code', 'like', "%$q%")
                    ->orWhereHas('course', function ($qc) use ($q) {
                        $qc->where('code', 'like', "%$q%")
                            ->orWhere('name', 'like', "%$q%");
                    });
            });
        }

        $sections = $query->orderBy('section_code')->limit(300)->get();

        $data = $sections->map(function ($s) {
            return [
                'id' => $s->id,
                'section_code' => $s->section_code,
                'course_code' => $s->course?->code,
                'course_name' => $s->course?->name,
                'faculty' => $s->course?->faculty?->only(['id', 'code', 'name']),
                'lecturer' => $s->lecturer?->name,
                'room' => $s->room?->code ?? $s->room?->name,
                'day_of_week' => $s->day_of_week,
                'shift' => $s->shift ? [
                    'name' => $s->shift->name,
                    'start_time' => $s->shift->start_time,
                    'end_time' => $s->shift->end_time,
                ] : null,
                'max_capacity' => $s->max_capacity,
                'status' => $s->status,
            ];
        });

        return response()->json(['data' => $data]);
    }

    // JSON: Detail of a wave including selected offerings
    public function detail(RegistrationWave $registrationWave)
    {
        $faculties = Faculty::select('id', 'code', 'name')->get()->keyBy('id');
        $aud = $registrationWave->audience ?? [];
        $facultyIds = $aud['faculties'] ?? [];
        $cohorts = $aud['cohorts'] ?? [];

        $registrationWave->load(['classSections.course.faculty', 'classSections.lecturer', 'classSections.room', 'classSections.shift']);

        $offerings = $registrationWave->classSections->map(function ($s) {
            return [
                'id' => $s->id,
                'section_code' => $s->section_code,
                'course_code' => $s->course?->code,
                'course_name' => $s->course?->name,
                'faculty' => $s->course?->faculty?->only(['id', 'code', 'name']),
                'lecturer' => $s->lecturer?->name,
                'room' => $s->room?->code ?? $s->room?->name,
                'day_of_week' => $s->day_of_week,
                'shift' => $s->shift ? [
                    'name' => $s->shift->name,
                    'start_time' => $s->shift->start_time,
                    'end_time' => $s->shift->end_time,
                ] : null,
                'max_capacity' => $s->max_capacity,
                'status' => $s->status,
            ];
        });

        return response()->json([
            'wave' => [
                'id' => $registrationWave->id,
                'name' => $registrationWave->name,
                'academic_year' => $registrationWave->academic_year,
                'term' => $registrationWave->term,
                'starts_at' => optional($registrationWave->starts_at)->toDateTimeString(),
                'ends_at' => optional($registrationWave->ends_at)->toDateTimeString(),
                'faculties' => collect($facultyIds)->map(fn($id) => $faculties[$id] ?? null)->filter()->values()->all(),
                'cohorts' => array_values($cohorts),
            ],
            'offerings' => $offerings,
        ]);
    }
}
