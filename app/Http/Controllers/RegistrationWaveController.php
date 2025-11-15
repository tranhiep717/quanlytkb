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

        // Distinct cohorts from users/students + predefined common cohorts
        $dbCohorts = User::whereNotNull('class_cohort')
            ->where('class_cohort', '!=', '')
            ->select('class_cohort')
            ->distinct()
            ->pluck('class_cohort')
            ->toArray();

        // Merge with common cohort options to ensure we always have choices
        $commonCohorts = ['K15', 'K16', 'K17', 'K18', 'K19', 'K20', 'K21'];
        $cohorts = collect(array_merge($commonCohorts, $dbCohorts))
            ->unique()
            ->sort()
            ->values();

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
        // Rule: If any student has registrations in class sections of this wave, do NOT allow deletion.
        // Otherwise, allow deletion (currently soft delete for safety).
        try {
            // If wave has not started yet, allow deletion regardless of existing registrations in those class sections
            $now = now();
            $starts = \Carbon\Carbon::parse($registrationWave->starts_at);

            if ($now->lt($starts)) {
                $deleted = (bool) $registrationWave->delete();

                LogEntry::create([
                    'user_id' => auth()->id(),
                    'action' => 'registration_wave_deleted',
                    'metadata' => json_encode(['wave_name' => $registrationWave->name, 'soft' => true, 'reason' => 'pre_start']),
                ]);

                if ($request->expectsJson()) {
                    return response()->json(['status' => $deleted ? 'ok' : 'error']);
                }
                return redirect()->route('registration-waves.index')->with($deleted ? 'success' : 'error', $deleted ? 'Xóa đợt đăng ký thành công.' : 'Xóa đợt đăng ký không thành công.');
            }

            $regCount = \App\Models\Registration::whereIn('class_section_id', function ($q) use ($registrationWave) {
                $q->from('registration_wave_class_section')
                    ->select('class_section_id')
                    ->where('registration_wave_id', $registrationWave->id);
            })->count();

            if ($regCount > 0) {
                $msg = "Không thể xóa vì có {$regCount} lượt đăng ký của sinh viên trong các lớp thuộc đợt này.";
                return $request->expectsJson()
                    ? response()->json(['status' => 'error', 'message' => $msg], 422)
                    : redirect()->route('registration-waves.index')->with('error', $msg);
            }

            // No registrations => proceed to delete (soft delete to keep audit). If you prefer permanent, switch to forceDelete().
            $deleted = (bool) $registrationWave->delete();

            if (!$deleted) {
                $msg = 'Xóa đợt đăng ký không thành công. Đợt có thể đã bị xóa hoặc có lỗi hệ thống.';
                return $request->expectsJson()
                    ? response()->json(['status' => 'error', 'message' => $msg], 422)
                    : redirect()->route('registration-waves.index')->with('error', $msg);
            }

            LogEntry::create([
                'user_id' => auth()->id(),
                'action' => 'registration_wave_deleted',
                'metadata' => json_encode(['wave_name' => $registrationWave->name, 'soft' => true]),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['status' => 'ok']);
            }
            return redirect()->route('registration-waves.index')->with('success', 'Xóa đợt đăng ký thành công.');
        } catch (\Throwable $e) {
            report($e);
            $msg = 'Không thể xóa do lỗi hệ thống. Vui lòng thử lại sau.';
            return $request->expectsJson()
                ? response()->json(['status' => 'error', 'message' => $msg], 500)
                : redirect()->route('registration-waves.index')->with('error', $msg);
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

    // List soft-deleted waves (archive)
    public function trashed()
    {
        $waves = RegistrationWave::onlyTrashed()->orderByDesc('deleted_at')->paginate(20);
        return view('admin.registration-waves.trashed', [
            'waves' => $waves,
        ]);
    }

    // Restore a soft-deleted wave
    public function restore($id)
    {
        $wave = RegistrationWave::withTrashed()->findOrFail($id);
        $wave->restore();
        LogEntry::create([
            'user_id' => auth()->id(),
            'action' => 'registration_wave_restored',
            'metadata' => json_encode(['wave_id' => $wave->id, 'name' => $wave->name]),
        ]);
        return redirect()->route('registration-waves.trashed')->with('success', 'Khôi phục đợt đăng ký thành công.');
    }

    // Permanently delete a wave (only when no registrations exist)
    public function forceDelete($id)
    {
        $wave = RegistrationWave::withTrashed()->findOrFail($id);
        try {
            // Safety: ensure no registrations exist in any linked class sections
            $regCount = \App\Models\Registration::whereIn('class_section_id', function ($q) use ($wave) {
                $q->from('registration_wave_class_section')
                    ->select('class_section_id')
                    ->where('registration_wave_id', $wave->id);
            })->count();
            if ($regCount > 0) {
                return redirect()->route('registration-waves.trashed')
                    ->with('error', 'Không thể xóa vĩnh viễn vì có đăng ký của sinh viên liên quan.');
            }

            \DB::transaction(function () use ($wave) {
                // Remove pivot entries explicitly then force delete
                $wave->classSections()->detach();
                $wave->forceDelete();
            });

            LogEntry::create([
                'user_id' => auth()->id(),
                'action' => 'registration_wave_force_deleted',
                'metadata' => json_encode(['wave_name' => $wave->name]),
            ]);

            return redirect()->route('registration-waves.trashed')->with('success', 'Đã xóa vĩnh viễn đợt đăng ký.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('registration-waves.trashed')->with('error', 'Không thể xóa vĩnh viễn. Vui lòng thử lại sau.');
        }
    }

    // Bulk delete waves (soft delete). Rules:
    // - If wave has not started yet => delete directly
    // - Otherwise, delete only when no registrations exist in its class sections
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids'); // optional list; if omitted, apply to all waves
        $query = RegistrationWave::query();
        if (is_array($ids) && !empty($ids)) {
            $query->whereIn('id', $ids);
        }
        $waves = $query->get();

        $now = now();
        $deleted = [];
        $skipped = [];

        foreach ($waves as $wave) {
            $starts = \Carbon\Carbon::parse($wave->starts_at);
            if ($now->lt($starts)) {
                $wave->delete();
                $deleted[] = $wave->name;
                continue;
            }

            $regCount = \App\Models\Registration::whereIn('class_section_id', function ($q) use ($wave) {
                $q->from('registration_wave_class_section')
                    ->select('class_section_id')
                    ->where('registration_wave_id', $wave->id);
            })->count();

            if ($regCount === 0) {
                $wave->delete();
                $deleted[] = $wave->name;
            } else {
                $skipped[] = $wave->name . " (" . $regCount . " đăng ký)";
            }
        }

        $msgOk = count($deleted) ? ('Đã xóa ' . count($deleted) . ' đợt: ' . implode(', ', $deleted)) : null;
        $msgSkip = count($skipped) ? ('Không thể xóa ' . count($skipped) . ' đợt: ' . implode('; ', $skipped)) : null;

        $redir = redirect()->route('registration-waves.index');
        if ($msgOk) $redir = $redir->with('success', $msgOk);
        if ($msgSkip) $redir = $redir->with('error', $msgSkip);
        return $redir;
    }
}
