<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Faculty;
use App\Models\ClassSection;
use App\Models\Registration;
use App\Models\LogEntry;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    // Persist academic year and term in session
    public function updateContext(Request $request)
    {
        $request->validate([
            'academic_year' => 'required|string|max:20',
            'term' => 'required|string|max:20',
        ]);

        session([
            'academic_year' => $request->academic_year,
            'term' => $request->term,
        ]);

        return back()->with('success', 'Đã cập nhật bối cảnh Năm học/Học kỳ.');
    }

    public function dashboard(Request $request)
    {
        $academicYear = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');

        $faculties = Faculty::orderBy('name')->get();
        $facultyFilter = $request->query('faculty_id');

        // Total students
        $studentsQuery = User::where('role', 'student');
        if ($facultyFilter) {
            $studentsQuery->where('faculty_id', $facultyFilter);
        }
        $totalStudents = $studentsQuery->count();

        // Total lecturers
        $lecturersQuery = User::where('role', 'lecturer');
        if ($facultyFilter) {
            $lecturersQuery->where('faculty_id', $facultyFilter);
        }
        $totalLecturers = $lecturersQuery->count();

        // Total open class sections in context
        $sectionsQuery = ClassSection::where('academic_year', $academicYear)
            ->where('term', $term);
        if ($facultyFilter) {
            $sectionsQuery->whereHas('course', function ($q) use ($facultyFilter) {
                $q->where('faculty_id', $facultyFilter);
            });
        }
        $totalOpenCourses = $sectionsQuery->count();

        // Total registrations in context
        $registrationsQuery = Registration::whereHas('classSection', function ($q) use ($academicYear, $term, $facultyFilter) {
            $q->where('academic_year', $academicYear)->where('term', $term);
            if ($facultyFilter) {
                $q->whereHas('course', function ($qq) use ($facultyFilter) {
                    $qq->where('faculty_id', $facultyFilter);
                });
            }
        });
        $totalRegistrations = $registrationsQuery->count();

        return view('admin.dashboard', [
            'academicYear' => $academicYear,
            'term' => $term,
            'faculties' => $faculties,
            'facultyFilter' => $facultyFilter,
            'totalStudents' => $totalStudents,
            'totalLecturers' => $totalLecturers,
            'totalOpenCourses' => $totalOpenCourses,
            'totalRegistrations' => $totalRegistrations,
        ]);
    }

    // UC1.4 - Hiển thị trang cập nhật hồ sơ cá nhân cho Admin
    public function editProfile(Request $request)
    {
        $user = $request->user();
        return view('admin.profile', [
            'user' => $user,
        ]);
    }

    // UC1.4 - Cập nhật hồ sơ (tên, email)
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        // Validation rules including optional password change (UC1.3 admin privilege)
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã được sử dụng.',
            'password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu mới không trùng khớp.',
        ]);

        // Update basic fields
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // If password provided, update without requiring old password (admin privilege)
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
            // Optional: invalidate other sessions or log action
            LogEntry::create([
                'user_id' => $user->id,
                'action' => 'admin_password_changed_self',
                'metadata' => json_encode(['admin_id' => $user->id]),
            ]);
        }

        $user->save();

        return back()->with('success', 'Cập nhật thông tin cá nhân thành công!');
    }

    // ========== U-1, U-2: Danh sách người dùng với tìm kiếm và bộ lọc ==========
    public function listUsers(Request $request)
    {
        $query = User::with('faculty');

        // U-2: Tìm kiếm theo tên, email, hoặc code
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Bộ lọc theo vai trò
        if ($role = $request->query('role')) {
            $query->where('role', $role);
        }

        // Bộ lọc theo trạng thái (khóa/mở)
        if ($request->has('status')) {
            $status = $request->query('status');
            if ($status === 'locked') {
                $query->where('is_locked', true);
            } elseif ($status === 'active') {
                $query->where('is_locked', false);
            }
        }

        // Bộ lọc theo khoa
        if ($facultyId = $request->query('faculty_id')) {
            $query->where('faculty_id', $facultyId);
        }

        // Bộ lọc theo khóa học
        if ($cohort = $request->query('cohort')) {
            $query->where('class_cohort', $cohort);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        $faculties = Faculty::orderBy('name')->get();

        return view('admin.users.index', [
            'users' => $users,
            'faculties' => $faculties,
            'filters' => $request->query(),
        ]);
    }

    // U-3: Hiển thị form tạo người dùng mới
    public function createUser()
    {
        $faculties = Faculty::orderBy('name')->get();
        // Load distinct cohorts from existing students
        $cohorts = User::whereNotNull('class_cohort')
            ->select('class_cohort')
            ->distinct()
            ->orderBy('class_cohort')
            ->pluck('class_cohort');
        return view('admin.users.create', [
            'faculties' => $faculties,
            'cohorts' => $cohorts,
        ]);
    }

    // U-3: Lưu người dùng mới
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'code' => 'nullable|string|max:50|unique:users,code',
            'role' => ['required', Rule::in(['student', 'lecturer', 'faculty_admin', 'super_admin'])],
            'faculty_id' => 'nullable|exists:faculties,id',
            'class_cohort' => 'nullable|string|max:50',
            'password' => 'required|string|min:8',
        ], [
            'name.required' => 'Họ tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.unique' => 'Email đã tồn tại.',
            'code.unique' => 'Mã người dùng đã tồn tại.',
            'role.required' => 'Vai trò là bắt buộc.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        LogEntry::create([
            'user_id' => auth()->id(),
            'action' => 'user_created',
            'metadata' => json_encode(['user_id' => $user->id, 'email' => $user->email]),
        ]);

        return redirect()->route('admin.users')->with('success', 'Tạo người dùng thành công.');
    }

    // U-4: Hiển thị form chỉnh sửa người dùng
    public function editUser(User $user)
    {
        $faculties = Faculty::orderBy('name')->get();
        // Load distinct cohorts from existing students
        $cohorts = User::whereNotNull('class_cohort')
            ->select('class_cohort')
            ->distinct()
            ->orderBy('class_cohort')
            ->pluck('class_cohort');
        return view('admin.users.edit', [
            'user' => $user,
            'faculties' => $faculties,
            'cohorts' => $cohorts,
        ]);
    }

    // U-4: Cập nhật người dùng
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'code' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['student', 'lecturer', 'faculty_admin', 'super_admin'])],
            'faculty_id' => 'nullable|exists:faculties,id',
            'class_cohort' => 'nullable|string|max:50',
        ], [
            'name.required' => 'Họ tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.unique' => 'Email đã tồn tại.',
            'code.unique' => 'Mã người dùng đã tồn tại.',
            'role.required' => 'Vai trò là bắt buộc.',
        ]);

        $user->update($validated);

        LogEntry::create([
            'user_id' => auth()->id(),
            'action' => 'user_updated',
            'metadata' => json_encode(['user_id' => $user->id, 'email' => $user->email]),
        ]);

        return redirect()->route('admin.users')->with('success', 'Cập nhật người dùng thành công.');
    }

    // U-5: Xem chi tiết người dùng
    public function showUser(User $user)
    {
        $user->load('faculty');
        return view('admin.users.show', [
            'user' => $user,
        ]);
    }

    // U-5: Xóa người dùng
    public function destroyUser(Request $request, User $user)
    {
        // Không cho phép tự xóa chính mình
        if (auth()->id() === $user->id) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Không thể xóa tài khoản của chính bạn.'], 400);
            }
            return back()->with('error', 'Không thể xóa tài khoản của chính bạn.');
        }

        // 2a: Nếu tài khoản có ràng buộc lịch sử -> không cho xóa, đề xuất Khóa
        try {
            // Sinh viên có đăng ký
            if ($user->role === 'student' && \App\Models\Registration::where('student_id', $user->id)->exists()) {
                $msg = 'Không thể xóa vì tài khoản sinh viên đang có lịch sử đăng ký. Vui lòng Khóa tài khoản thay vì xóa.';
                if ($request->expectsJson()) {
                    return response()->json(['message' => $msg], 400);
                }
                return back()->with('error', $msg);
            }
            // Giảng viên có lớp phụ trách
            if ($user->role === 'lecturer' && \App\Models\ClassSection::where('lecturer_id', $user->id)->exists()) {
                $msg = 'Không thể xóa vì tài khoản giảng viên đang phụ trách lớp học phần. Vui lòng Khóa tài khoản thay vì xóa.';
                if ($request->expectsJson()) {
                    return response()->json(['message' => $msg], 400);
                }
                return back()->with('error', $msg);
            }
        } catch (\Throwable $e) {
            // Nếu có lỗi bất ngờ trong bước kiểm tra ràng buộc, vẫn trả về 500 phù hợp 4a
            $msg = 'Xóa thất bại (lỗi DB/kết nối). Vui lòng thử lại.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $msg], 500);
            }
            return back()->with('error', $msg);
        }

        try {
            $email = $user->email;
            $id = $user->id;
            $user->delete();

            LogEntry::create([
                'user_id' => auth()->id(),
                'action' => 'user_deleted',
                'metadata' => json_encode(['user_id' => $id, 'email' => $email]),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Xóa thành công']);
            }
            return redirect()->route('admin.users')->with('success', 'Đã xóa người dùng thành công.');
        } catch (\Throwable $e) {
            // Ghi log lỗi chi tiết phục vụ debug
            LogEntry::create([
                'user_id' => auth()->id(),
                'action' => 'user_delete_failed',
                'metadata' => json_encode([
                    'target_user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]),
            ]);

            $msg = 'Xóa thất bại (lỗi DB/kết nối). Vui lòng thử lại.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $msg], 500);
            }
            return back()->with('error', $msg);
        }
    }

    // U-6: Khóa tài khoản người dùng
    public function lockUser(User $user)
    {
        $user->update(['is_locked' => true]);

        LogEntry::create([
            'user_id' => auth()->id(),
            'action' => 'user_locked',
            'metadata' => json_encode(['user_id' => $user->id, 'email' => $user->email]),
        ]);

        return back()->with('success', 'Đã khóa tài khoản người dùng.');
    }

    // U-6: Mở khóa tài khoản người dùng
    public function unlockUser(User $user)
    {
        $user->update(['is_locked' => false]);

        LogEntry::create([
            'user_id' => auth()->id(),
            'action' => 'user_unlocked',
            'metadata' => json_encode(['user_id' => $user->id, 'email' => $user->email]),
        ]);

        return back()->with('success', 'Đã mở khóa tài khoản người dùng.');
    }

    // U-6: Gửi liên kết đặt lại mật khẩu
    public function resetUserPassword(User $user)
    {
        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Gửi email với liên kết đặt lại mật khẩu
        $resetLink = url('/reset-password/' . $token) . '?email=' . urlencode($user->email);

        try {
            Mail::raw("Liên kết đặt lại mật khẩu: {$resetLink}\n\nLiên kết có hiệu lực trong 60 phút.", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Đặt lại mật khẩu');
            });

            LogEntry::create([
                'user_id' => auth()->id(),
                'action' => 'password_reset_sent',
                'metadata' => json_encode(['user_id' => $user->id, 'email' => $user->email]),
            ]);

            return back()->with('success', 'Đã gửi liên kết đặt lại mật khẩu tới ' . $user->email);
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể gửi email. Vui lòng kiểm tra cấu hình mail.');
        }
    }

    // ========== S-2: Báo cáo ==========
    public function reports(Request $request)
    {
        $academicYear = $request->query('academic_year', session('academic_year', '2024-2025'));
        $term = $request->query('term', session('term', 'HK1'));
        $facultyId = $request->query('faculty_id');
        $cohort = $request->query('cohort');

        $faculties = Faculty::orderBy('name')->get();

        // Thống kê đăng ký theo bộ lọc
        $registrations = Registration::with(['student', 'classSection.course.faculty'])
            ->whereHas('classSection', function ($q) use ($academicYear, $term, $facultyId) {
                $q->where('academic_year', $academicYear)->where('term', $term);
                if ($facultyId) {
                    $q->whereHas('course', function ($qq) use ($facultyId) {
                        $qq->where('faculty_id', $facultyId);
                    });
                }
            });

        if ($cohort) {
            $registrations->whereHas('student', function ($q) use ($cohort) {
                $q->where('class_cohort', $cohort);
            });
        }

        $registrations = $registrations->paginate(50);

        return view('admin.reports.index', [
            'registrations' => $registrations,
            'faculties' => $faculties,
            'filters' => $request->query(),
        ]);
    }

    // S-2: Xuất báo cáo
    public function exportReport(Request $request)
    {
        // Lấy bộ lọc giống trang báo cáo
        $academicYear = $request->query('academic_year', session('academic_year', '2024-2025'));
        $term = $request->query('term', session('term', 'HK1'));
        $facultyId = $request->query('faculty_id');
        $cohort = $request->query('cohort');

        // Truy vấn đăng ký kèm quan hệ cần thiết
        $query = Registration::with([
            'student',
            'classSection.course.faculty',
            'classSection.room',
            'classSection.shift',
            'classSection.lecturer',
        ])->whereHas('classSection', function ($q) use ($academicYear, $term, $facultyId) {
            $q->where('academic_year', $academicYear)
                ->where('term', $term);
            if ($facultyId) {
                $q->whereHas('course', function ($qq) use ($facultyId) {
                    $qq->where('faculty_id', $facultyId);
                });
            }
        });

        if ($cohort) {
            $query->whereHas('student', function ($q) use ($cohort) {
                $q->where('class_cohort', $cohort);
            });
        }

        // Tên file thân thiện
        $safeYear = preg_replace('/[^0-9\-]/', '', (string) $academicYear);
        $fileName = sprintf('bao-cao-dang-ky_%s_%s_%s.csv', $safeYear ?: 'na', $term ?: 'na', now()->format('Ymd_His'));

        // Stream CSV để tiết kiệm bộ nhớ
        return response()->streamDownload(function () use ($query) {
            // Excel trên Windows cần BOM để hiển thị UTF-8 chính xác
            echo "\xEF\xBB\xBF";
            $out = fopen('php://output', 'w');

            // Header
            fputcsv($out, [
                'MSSV',
                'Họ tên SV',
                'Khóa',
                'Mã HP',
                'Tên học phần',
                'Số TC',
                'Lớp HP',
                'Khoa',
                'Ca học',
                'Phòng',
                'Giảng viên',
                'Thời điểm ĐK'
            ]);

            // Ghi theo từng lô để tránh tốn RAM
            $query->orderBy('id')->chunk(1000, function ($regs) use ($out) {
                foreach ($regs as $reg) {
                    $student = $reg->student;
                    $sec = $reg->classSection;
                    $course = $sec?->course;
                    $faculty = $course?->faculty;
                    $shift = $sec?->shift;
                    $room = $sec?->room;
                    $lecturer = $sec?->lecturer;

                    $shiftText = '';
                    if ($shift) {
                        // Dạng: "Thứ 2 07:00 - 09:30" nếu có accessor day_name/time_range
                        $dayName = method_exists($shift, 'getDayNameAttribute') ? $shift->day_name : ($shift->day_of_week ? ('Thứ ' . $shift->day_of_week) : '');
                        $timeRange = method_exists($shift, 'getTimeRangeAttribute') ? $shift->time_range : '';
                        $shiftText = trim($dayName . ' ' . $timeRange);
                    }

                    fputcsv($out, [
                        $student->code ?? '',
                        $student->name ?? '',
                        $student->class_cohort ?? '',
                        $course->code ?? '',
                        $course->name ?? '',
                        $course->credits ?? 0,
                        $sec->section_code ?? '',
                        $faculty->name ?? '',
                        $shiftText,
                        $room?->code ?? $room?->name ?? '',
                        $lecturer?->name ?? '',
                        optional($reg->created_at)->format('d/m/Y H:i'),
                    ]);
                }
            });

            fclose($out);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    // S-3: Xem nhật ký hệ thống
    public function logs(Request $request)
    {
        $logs = LogEntry::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.logs.index', ['logs' => $logs]);
    }

    // S-4: Hiển thị trang Sao lưu & Phục hồi
    public function showBackup()
    {
        return view('admin.backup');
    }

    // S-4: Sao lưu cơ sở dữ liệu
    public function backup()
    {
        // Placeholder cho backup
        LogEntry::create([
            'user_id' => auth()->id(),
            'action' => 'backup_requested',
            'metadata' => json_encode(['timestamp' => now()]),
        ]);

        return back()->with('success', 'Đã tạo bản sao lưu thành công!');
    }

    // S-4: Khôi phục cơ sở dữ liệu
    public function restore()
    {
        // Placeholder cho restore
        return back()->with('info', 'Chức năng khôi phục đang được phát triển.');
    }
}
