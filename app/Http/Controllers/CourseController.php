<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Faculty;
use App\Models\LogEntry;

class CourseController extends Controller
{
    // A-2: Danh sách học phần
    public function index(Request $request)
    {
        $query = Course::with('faculty', 'prerequisites');

        if ($facultyId = $request->query('faculty_id')) {
            $query->where('faculty_id', $facultyId);
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $courses = $query->orderBy('code')->paginate(20);
        $faculties = Faculty::orderBy('name')->get();
        $allCourses = Course::orderBy('code')->get();

        return view('admin.courses.index', [
            'courses' => $courses,
            'faculties' => $faculties,
            'allCourses' => $allCourses,
            'filters' => $request->query(),
        ]);
    }

    // A-2: Hiển thị form tạo học phần mới
    public function create()
    {
        $faculties = Faculty::orderBy('name')->get();
        $allCourses = Course::orderBy('code')->get();
        return view('admin.courses.create', [
            'faculties' => $faculties,
            'allCourses' => $allCourses,
        ]);
    }

    // A-2: Lưu học phần mới
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:50|unique:courses,code',
                'name' => 'required|string|max:255',
                'credits' => 'required|integer|min:1|max:10',
                'faculty_id' => 'required|exists:faculties,id',
                'type' => 'nullable|string|max:100',
                'description' => 'nullable|string',
                'is_active' => 'nullable|boolean',
            ], [
                // Validation Errors (Lỗi nhập liệu - Luồng 4b)
                'code.required' => 'Vui lòng nhập mã học phần.',
                'code.max' => 'Mã học phần không được vượt quá 50 ký tự.',
                'code.unique' => 'Mã học phần "' . $request->code . '" đã tồn tại trong hệ thống. Vui lòng chọn mã khác.',
                'name.required' => 'Vui lòng nhập tên học phần.',
                'name.max' => 'Tên học phần không được vượt quá 255 ký tự.',
                'credits.required' => 'Vui lòng nhập số tín chỉ.',
                'credits.integer' => 'Số tín chỉ phải là số nguyên.',
                'credits.min' => 'Số tín chỉ phải lớn hơn 0.',
                'credits.max' => 'Số tín chỉ không được vượt quá 10.',
                'faculty_id.required' => 'Vui lòng chọn khoa quản lý.',
                'faculty_id.exists' => 'Khoa được chọn không tồn tại.',
            ]);

            $course = Course::create([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'credits' => $validated['credits'],
                'faculty_id' => $validated['faculty_id'],
                'type' => $validated['type'] ?? null,
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            LogEntry::create([
                'user_id' => auth()->id(),
                'action' => 'course_created',
                'metadata' => json_encode(['course_id' => $course->id, 'code' => $course->code]),
            ]);

            return redirect()->route('courses.index')->with('success', 'Tạo học phần thành công.');
        } catch (\Illuminate\Database\QueryException $e) {
            // System Errors (Lỗi hệ thống - Luồng 5a)
            \Log::error('Database error while creating course: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Lỗi hệ thống: Không thể lưu học phần. Vui lòng thử lại sau hoặc liên hệ quản trị viên.');
        } catch (\Exception $e) {
            // System Errors (Lỗi hệ thống - Luồng 5a)
            \Log::error('Unexpected error while creating course: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Lỗi hệ thống: Đã xảy ra lỗi không mong muốn. Vui lòng thử lại.');
        }
    }

    // A-2: Hiển thị form chỉnh sửa học phần
    public function edit(Course $course)
    {
        $faculties = Faculty::orderBy('name')->get();
        $allCourses = Course::where('id', '!=', $course->id)->orderBy('code')->get();
        return view('admin.courses.edit', [
            'course' => $course,
            'faculties' => $faculties,
            'allCourses' => $allCourses,
        ]);
    }

    // A-2: Cập nhật học phần
    public function update(Request $request, Course $course)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:50|unique:courses,code,' . $course->id,
                'name' => 'required|string|max:255',
                'credits' => 'required|integer|min:1|max:10',
                'faculty_id' => 'required|exists:faculties,id',
                'type' => 'nullable|string|max:100',
                'description' => 'nullable|string',
                'is_active' => 'nullable|boolean',
                'prerequisites' => 'nullable|array',
                'prerequisites.*' => 'exists:courses,id',
            ], [
                // Validation Errors (Lỗi nhập liệu - Luồng 4b)
                'code.required' => 'Vui lòng nhập mã học phần.',
                'code.unique' => 'Mã học phần "' . $request->code . '" đã tồn tại trong hệ thống. Vui lòng chọn mã khác.',
                'name.required' => 'Vui lòng nhập tên học phần.',
                'credits.required' => 'Vui lòng nhập số tín chỉ.',
                'credits.min' => 'Số tín chỉ phải lớn hơn 0.',
                'credits.max' => 'Số tín chỉ không được vượt quá 10.',
                'faculty_id.required' => 'Vui lòng chọn khoa quản lý.',
            ]);

            // Business Logic Error (Lỗi nghiệp vụ - Luồng 4a)
            // Check if changing faculty is allowed
            if ($course->faculty_id != $validated['faculty_id']) {
                // Check if course has class sections
                if ($course->classSections()->exists()) {
                    return back()
                        ->withInput()
                        ->withErrors(['faculty_id' => 'Không thể thay đổi Khoa quản lý vì môn học này đang có lớp học phần liên kết. Vui lòng xóa hoặc chuyển các lớp học phần trước.']);
                }

                // Check if course is prerequisite for other courses
                if ($course->isPrerequisiteFor()->exists()) {
                    return back()
                        ->withInput()
                        ->withErrors(['faculty_id' => 'Không thể thay đổi Khoa quản lý vì môn học này đang là điều kiện tiên quyết cho các môn học khác. Vui lòng gỡ bỏ liên kết tiên quyết trước.']);
                }
            }

            $course->update([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'credits' => $validated['credits'],
                'faculty_id' => $validated['faculty_id'],
                'type' => $validated['type'] ?? null,
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'] ?? $course->is_active,
            ]);

            // Đồng bộ học phần tiên quyết
            if (isset($validated['prerequisites'])) {
                $course->prerequisites()->sync($validated['prerequisites']);
            }

            LogEntry::create([
                'user_id' => auth()->id(),
                'action' => 'course_updated',
                'metadata' => json_encode(['course_id' => $course->id, 'code' => $course->code]),
            ]);

            return redirect()->route('courses.index')->with('success', 'Cập nhật học phần thành công.');
        } catch (\Illuminate\Database\QueryException $e) {
            // System Errors (Lỗi hệ thống - Luồng 5a)
            \Log::error('Database error while updating course: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Lỗi hệ thống: Không thể cập nhật học phần. Vui lòng thử lại sau.');
        } catch (\Exception $e) {
            // System Errors (Lỗi hệ thống - Luồng 5a)
            \Log::error('Unexpected error while updating course: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Lỗi hệ thống: Đã xảy ra lỗi không mong muốn. Vui lòng thử lại.');
        }
    }

    // A-2: Xóa học phần
    public function destroy(Course $course)
    {
        try {
            // Business Logic Error (Lỗi nghiệp vụ - Luồng 4 Xóa)
            // Check if course has class sections
            if ($course->classSections()->exists()) {
                return back()->with(
                    'error',
                    'Không thể xóa môn học "' . $course->code . '" vì đang có ' .
                        $course->classSections()->count() . ' lớp học phần liên kết. ' .
                        'Gợi ý: Thay vì xóa, bạn có thể chuyển trạng thái thành "Ngưng hoạt động".'
                );
            }

            // Check if course is prerequisite for other courses
            if ($course->isPrerequisiteFor()->exists()) {
                $dependentCourses = $course->isPrerequisiteFor()->pluck('code')->toArray();
                return back()->with(
                    'error',
                    'Không thể xóa môn học "' . $course->code . '" vì đang là điều kiện tiên quyết cho các môn: ' .
                        implode(', ', $dependentCourses) . '. ' .
                        'Vui lòng gỡ bỏ các liên kết tiên quyết trước.'
                );
            }

            $courseCode = $course->code;

            $course->prerequisites()->detach();
            $course->delete();

            LogEntry::create([
                'user_id' => auth()->id(),
                'action' => 'course_deleted',
                'metadata' => json_encode(['course_code' => $courseCode]),
            ]);

            return redirect()->route('courses.index')->with('success', 'Xóa học phần "' . $courseCode . '" thành công.');
        } catch (\Illuminate\Database\QueryException $e) {
            // System Errors (Lỗi hệ thống - Luồng 4a Xóa)
            \Log::error('Database error while deleting course: ' . $e->getMessage());
            return back()->with('error', 'Lỗi hệ thống: Không thể xóa học phần. Vui lòng thử lại sau.');
        } catch (\Exception $e) {
            // System Errors (Lỗi hệ thống)
            \Log::error('Unexpected error while deleting course: ' . $e->getMessage());
            return back()->with('error', 'Lỗi hệ thống: Đã xảy ra lỗi không mong muốn. Vui lòng thử lại.');
        }
    }

    /**
     * Get detailed information for a course (API endpoint for modal)
     */
    public function getDetail(Course $course)
    {
        // Load relationships
        $course->load(['faculty', 'prerequisites']);

        return response()->json([
            'id' => $course->id,
            'code' => $course->code,
            'name' => $course->name,
            'credits' => $course->credits,
            'type' => $course->type,
            'description' => $course->description,
            'is_active' => $course->is_active,
            'faculty' => [
                'id' => $course->faculty->id,
                'name' => $course->faculty->name,
                'code' => $course->faculty->code,
            ],
            'prerequisites' => $course->prerequisites->map(function ($prereq) {
                return [
                    'id' => $prereq->id,
                    'code' => $prereq->code,
                    'name' => $prereq->name,
                ];
            }),
        ]);
    }

    /**
     * Get prerequisites for a course (API endpoint)
     */
    public function getPrerequisites(Course $course)
    {
        // Load prerequisites relationship
        $prerequisites = $course->prerequisites()->pluck('courses.id')->toArray();

        return response()->json([
            'prerequisites' => $prerequisites
        ]);
    }

    /**
     * Update prerequisites for a course
     */
    public function updatePrerequisites(Request $request, Course $course)
    {
        $request->validate([
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => 'exists:courses,id|different:' . $course->id,
        ], [
            'prerequisites.*.exists' => 'Một trong các học phần tiên quyết không tồn tại.',
            'prerequisites.*.different' => 'Học phần không thể là tiên quyết của chính nó.',
        ]);

        $prerequisites = $request->input('prerequisites', []);

        // Check for circular dependencies
        if ($this->hasCircularDependency($course->id, $prerequisites)) {
            return response()->json([
                'success' => false,
                'message' => 'Phát hiện phụ thuộc vòng. Không thể thiết lập điều kiện tiên quyết này.'
            ], 422);
        }

        // Sync prerequisites
        $course->prerequisites()->sync($prerequisites);

        // Log the action
        LogEntry::create([
            'user_id' => auth()->id(),
            'action' => 'prerequisites_updated',
            'metadata' => json_encode([
                'course_code' => $course->code,
                'prerequisites_count' => count($prerequisites)
            ]),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật điều kiện tiên quyết thành công.'
        ]);
    }

    /**
     * Check for circular dependencies in prerequisites
     */
    private function hasCircularDependency($courseId, $prerequisites)
    {
        foreach ($prerequisites as $prereqId) {
            // Check if any of the prerequisites has the current course as its prerequisite
            if ($this->isPrerequisiteOf($courseId, $prereqId)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Recursively check if courseA is a prerequisite of courseB
     */
    private function isPrerequisiteOf($courseA, $courseB)
    {
        $course = Course::find($courseB);
        if (!$course) {
            return false;
        }

        $prerequisites = $course->prerequisites()->pluck('courses.id')->toArray();

        if (in_array($courseA, $prerequisites)) {
            return true;
        }

        foreach ($prerequisites as $prereqId) {
            if ($this->isPrerequisiteOf($courseA, $prereqId)) {
                return true;
            }
        }

        return false;
    }
}
