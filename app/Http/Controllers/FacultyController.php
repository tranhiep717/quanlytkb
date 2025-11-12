<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faculty;
use App\Models\User;
use App\Models\LogEntry;

class FacultyController extends Controller
{
    // A-1: Danh sách các khoa với tìm kiếm và bộ lọc
    public function index(Request $request)
    {
        $query = Faculty::with('dean')->withCount(['users', 'courses']);

        // Tìm kiếm theo mã hoặc tên
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->has('status')) {
            $status = $request->query('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Lọc theo trưởng khoa
        if ($deanId = $request->query('dean_id')) {
            $query->where('dean_id', $deanId);
        }

        $faculties = $query->orderBy('name')->paginate(20);

        // Danh sách trưởng khoa cho bộ lọc
        $deans = User::where('role', 'lecturer')
            ->orderBy('name')
            ->get();

        return view('admin.faculties.index', [
            'faculties' => $faculties,
            'deans' => $deans,
            'filters' => $request->query(),
        ]);
    }

    // A-1: Xem chi tiết khoa
    public function show(Faculty $faculty)
    {
        $faculty->load(['dean', 'courses', 'users' => function ($q) {
            $q->where('role', 'lecturer')->orderBy('name');
        }]);

        return view('admin.faculties.show', ['faculty' => $faculty]);
    }

    // A-1: Hiển thị form tạo khoa mới
    public function create()
    {
        $lecturers = User::where('role', 'lecturer')->orderBy('name')->get();
        return view('admin.faculties.create', ['lecturers' => $lecturers]);
    }

    // A-1: Lưu khoa mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:faculties,code',
            'name' => 'required|string|max:150',
            'dean_id' => 'nullable|exists:users,id',
            'founding_date' => 'nullable|date',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ], [
            'code.required' => 'Mã khoa là bắt buộc.',
            'code.unique' => 'Mã khoa đã tồn tại.',
            'name.required' => 'Tên khoa là bắt buộc.',
            'dean_id.exists' => 'Trưởng khoa không hợp lệ.',
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool)$request->is_active : true;

        $faculty = Faculty::create($validated);

        LogEntry::create([
            'user_id' => auth()->id(),
            'action' => 'faculty_created',
            'metadata' => json_encode(['faculty_id' => $faculty->id, 'code' => $faculty->code]),
        ]);

        return redirect()->route('faculties.index')->with('success', 'Tạo khoa thành công.');
    }

    // A-1: Hiển thị form chỉnh sửa khoa
    public function edit(Faculty $faculty)
    {
        $lecturers = User::where('role', 'lecturer')->orderBy('name')->get();
        return view('admin.faculties.edit', ['faculty' => $faculty, 'lecturers' => $lecturers]);
    }

    // A-1: Cập nhật khoa
    public function update(Request $request, Faculty $faculty)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:faculties,code,' . $faculty->id,
            'name' => 'required|string|max:150',
            'dean_id' => 'nullable|exists:users,id',
            'founding_date' => 'nullable|date',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ], [
            'code.required' => 'Mã khoa là bắt buộc.',
            'code.unique' => 'Mã khoa đã tồn tại.',
            'name.required' => 'Tên khoa là bắt buộc.',
            'dean_id.exists' => 'Trưởng khoa không hợp lệ.',
        ]);

        $validated['is_active'] = $request->has('is_active') ? (bool)$request->is_active : false;

        $faculty->update($validated);

        LogEntry::create([
            'user_id' => auth()->id(),
            'action' => 'faculty_updated',
            'metadata' => json_encode(['faculty_id' => $faculty->id, 'code' => $faculty->code]),
        ]);

        return redirect()->route('faculties.index')->with('success', 'Cập nhật khoa thành công.');
    }

    // A-1: Xóa khoa với kiểm tra ràng buộc
    public function destroy(Request $request, Faculty $faculty)
    {
        // Kiểm tra ràng buộc
        if ($faculty->users()->exists() || $faculty->courses()->exists()) {
            $msg = 'Không thể xóa khoa vì đang có ' .
                ($faculty->users_count > 0 ? $faculty->users_count . ' người dùng' : '') .
                ($faculty->users_count > 0 && $faculty->courses_count > 0 ? ' và ' : '') .
                ($faculty->courses_count > 0 ? $faculty->courses_count . ' học phần' : '') .
                ' liên kết.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $msg], 400);
            }
            return back()->with('error', $msg);
        }

        try {
            $code = $faculty->code;
            $faculty->delete();

            LogEntry::create([
                'user_id' => auth()->id(),
                'action' => 'faculty_deleted',
                'metadata' => json_encode(['faculty_code' => $code]),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Xóa khoa thành công']);
            }
            return redirect()->route('faculties.index')->with('success', 'Xóa khoa thành công.');
        } catch (\Throwable $e) {
            LogEntry::create([
                'user_id' => auth()->id(),
                'action' => 'faculty_delete_failed',
                'metadata' => json_encode([
                    'faculty_id' => $faculty->id,
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
}
