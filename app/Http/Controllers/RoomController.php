<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\LogEntry;

class RoomController extends Controller
{
    // A-1: Danh sách phòng học với tìm kiếm và lọc
    public function index(Request $request)
    {
        $query = Room::query();

        // Tìm kiếm theo từ khóa (mã hoặc tên phòng)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Lọc theo tòa nhà
        if ($building = $request->input('building')) {
            $query->where('building', $building);
        }

        // Lọc theo sức chứa tối thiểu
        if ($minCapacity = $request->input('min_capacity')) {
            $query->where('capacity', '>=', $minCapacity);
        }

        // Lọc theo trang thiết bị
        if ($equipment = $request->input('equipment')) {
            $query->whereJsonContains('equipment', $equipment);
        }

        // Lọc theo trạng thái
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $rooms = $query->orderBy('code')->paginate(15);

        // Lấy danh sách tòa nhà để hiển thị trong dropdown
        $buildings = Room::select('building')
            ->whereNotNull('building')
            ->distinct()
            ->orderBy('building')
            ->pluck('building');

        // Danh sách thiết bị phổ biến
        $equipmentOptions = [
            'Máy chiếu',
            'Bảng thông minh',
            'Điều hòa',
            'Micro',
            'Loa',
            'Máy tính',
            'Bảng viết'
        ];

        return view('admin.rooms.index', [
            'rooms' => $rooms,
            'buildings' => $buildings,
            'equipmentOptions' => $equipmentOptions,
            'filters' => $request->all()
        ]);
    }

    // A-1: Hiển thị form tạo phòng học mới
    public function create()
    {
        return view('admin.rooms.create');
    }

    // A-1: Lưu phòng học mới
    public function store(Request $request)
    {
        try {
            // UC2.5-C-4: Kiểm tra hợp lệ
            $validated = $request->validate([
                'code' => [
                    'required',
                    'string',
                    'max:50',
                    'unique:rooms,code',
                    'regex:/^[A-Z0-9\-\_]+$/i' // Chỉ chấp nhận chữ, số, gạch ngang, gạch dưới
                ],
                'name' => 'required|string|max:255',
                'building' => 'nullable|string|max:100',
                'floor' => 'nullable|string|max:50',
                'capacity' => 'required|integer|min:1|max:10000',
                'equipment' => 'nullable|array',
                'equipment.*' => 'string|max:100',
                'status' => 'nullable|in:active,inactive',
            ], [
                // UC2.5-C-4a: Trùng mã
                'code.required' => 'Mã phòng là trường bắt buộc.',
                'code.unique' => 'Mã phòng "' . $request->code . '" đã tồn tại trong hệ thống. Vui lòng chọn mã khác.',
                'code.max' => 'Mã phòng không được vượt quá 50 ký tự.',
                'code.regex' => 'Mã phòng chỉ được chứa chữ cái, số, dấu gạch ngang (-) và gạch dưới (_).',

                // Tên phòng
                'name.required' => 'Tên phòng là trường bắt buộc.',
                'name.max' => 'Tên phòng không được vượt quá 255 ký tự.',

                // UC2.5-C-4b: Sức chứa không hợp lệ
                'capacity.required' => 'Sức chứa là trường bắt buộc.',
                'capacity.integer' => 'Sức chứa phải là số nguyên.',
                'capacity.min' => 'Sức chứa phải lớn hơn 0.',
                'capacity.max' => 'Sức chứa không được vượt quá 10,000 người.',

                // Tòa nhà, tầng
                'building.max' => 'Tên tòa nhà không được vượt quá 100 ký tự.',
                'floor.max' => 'Số tầng không được vượt quá 50 ký tự.',

                // Thiết bị
                'equipment.array' => 'Danh sách trang thiết bị không hợp lệ.',
                'equipment.*.string' => 'Tên thiết bị phải là chuỗi ký tự.',
                'equipment.*.max' => 'Tên thiết bị không được vượt quá 100 ký tự.',

                // Trạng thái
                'status.in' => 'Trạng thái phải là "Hoạt động" hoặc "Tạm ngưng".',
            ]);

            // UC2.5-C-5: Lưu bản ghi
            $room = Room::create([
                'code' => strtoupper(trim($validated['code'])), // Chuẩn hóa mã phòng
                'name' => trim($validated['name']),
                'building' => isset($validated['building']) ? trim($validated['building']) : null,
                'floor' => isset($validated['floor']) ? trim($validated['floor']) : null,
                'capacity' => $validated['capacity'],
                'equipment' => $validated['equipment'] ?? [],
                'status' => $validated['status'] ?? 'active',
            ]);

            // UC2.5-C-5: Ghi log
            LogEntry::create([
                'user_id' => auth()->id(),
                'action' => 'room_created',
                'metadata' => json_encode([
                    'room_id' => $room->id,
                    'code' => $room->code,
                    'name' => $room->name,
                    'capacity' => $room->capacity,
                    'timestamp' => now()->toDateTimeString()
                ]),
            ]);

            // UC2.5-C-5: Thông báo "Thêm mới thành công"
            return redirect()
                ->route('rooms.index')
                ->with('success', '✓ Thêm mới phòng học "' . $room->code . '" thành công.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Trả về form với lỗi validation
            return back()->withErrors($e->errors())->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            // UC2.5-C-5a: Lưu thất bại (DB)
            \Log::error('Database error creating room: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', '❌ Lỗi cơ sở dữ liệu: Không thể lưu phòng học. Vui lòng kiểm tra kết nối và thử lại.');
        } catch (\Exception $e) {
            // UC2.5-C-5a: Lưu thất bại (hệ thống)
            \Log::error('System error creating room: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', '❌ Lỗi hệ thống: Không thể tạo phòng học. Vui lòng liên hệ quản trị viên hoặc thử lại sau.');
        }
    }

    // A-1: Hiển thị form chỉnh sửa phòng học
    public function edit(Room $room)
    {
        return view('admin.rooms.edit', ['room' => $room]);
    }

    // A-1: Cập nhật phòng học
    public function update(Request $request, Room $room)
    {
        try {
            // UC2.5-U-4: Kiểm tra hợp lệ
            $validated = $request->validate([
                'code' => [
                    'required',
                    'string',
                    'max:50',
                    'unique:rooms,code,' . $room->id,
                    'regex:/^[A-Z0-9\-\_]+$/i'
                ],
                'name' => 'required|string|max:255',
                'building' => 'nullable|string|max:100',
                'floor' => 'nullable|string|max:50',
                'capacity' => 'required|integer|min:1|max:10000',
                'equipment' => 'nullable|array',
                'equipment.*' => 'string|max:100',
                'status' => 'nullable|in:active,inactive',
            ], [
                // Mã phòng
                'code.required' => 'Mã phòng là trường bắt buộc.',
                'code.unique' => 'Mã phòng "' . $request->code . '" đã tồn tại trong hệ thống. Vui lòng chọn mã khác.',
                'code.max' => 'Mã phòng không được vượt quá 50 ký tự.',
                'code.regex' => 'Mã phòng chỉ được chứa chữ cái, số, dấu gạch ngang (-) và gạch dưới (_).',

                // UC2.5-U-4a: Dữ liệu thiếu/sai
                'name.required' => 'Tên phòng là trường bắt buộc.',
                'name.max' => 'Tên phòng không được vượt quá 255 ký tự.',

                // Sức chứa
                'capacity.required' => 'Sức chứa là trường bắt buộc.',
                'capacity.integer' => 'Sức chứa phải là số nguyên.',
                'capacity.min' => 'Sức chứa phải lớn hơn 0.',
                'capacity.max' => 'Sức chứa không được vượt quá 10,000 người.',

                'building.max' => 'Tên tòa nhà không được vượt quá 100 ký tự.',
                'floor.max' => 'Số tầng không được vượt quá 50 ký tự.',
                'equipment.array' => 'Danh sách trang thiết bị không hợp lệ.',
                'status.in' => 'Trạng thái phải là "Hoạt động" hoặc "Tạm ngưng".',
            ]);

            // UC2.5-U-4: Cảnh báo nếu giảm sức chứa
            $capacityWarning = null;
            if ($validated['capacity'] < $room->capacity) {
                // Kiểm tra có lớp nào đang sử dụng phòng không
                $activeClasses = $room->classSections()
                    ->where('status', 'active')
                    ->count();

                if ($activeClasses > 0) {
                    $capacityWarning = "⚠️ Cảnh báo: Bạn đang giảm sức chứa từ {$room->capacity} xuống {$validated['capacity']} người. " .
                        "Hiện có {$activeClasses} lớp học phần đang sử dụng phòng này. " .
                        "Vui lòng kiểm tra để tránh vượt tải.";
                }
            }

            // UC2.5-U-5: Lưu thay đổi
            $oldData = $room->toArray(); // Lưu dữ liệu cũ để ghi log

            $room->update([
                'code' => strtoupper(trim($validated['code'])),
                'name' => trim($validated['name']),
                'building' => isset($validated['building']) ? trim($validated['building']) : null,
                'floor' => isset($validated['floor']) ? trim($validated['floor']) : null,
                'capacity' => $validated['capacity'],
                'equipment' => $validated['equipment'] ?? $room->equipment,
                'status' => $validated['status'] ?? $room->status,
            ]);

            // UC2.5-U-5: Ghi log thay đổi
            $changes = [];
            foreach (['code', 'name', 'building', 'floor', 'capacity', 'equipment', 'status'] as $field) {
                if ($room->$field != $oldData[$field]) {
                    $changes[$field] = [
                        'old' => $oldData[$field],
                        'new' => $room->$field
                    ];
                }
            }

            LogEntry::create([
                'user_id' => auth()->id(),
                'action' => 'room_updated',
                'metadata' => json_encode([
                    'room_id' => $room->id,
                    'code' => $room->code,
                    'changes' => $changes,
                    'timestamp' => now()->toDateTimeString()
                ]),
            ]);

            // UC2.5-U-5: Thông báo "Cập nhật thành công"
            $successMessage = '✓ Cập nhật phòng học "' . $room->code . '" thành công.';

            return redirect()
                ->route('rooms.index')
                ->with('success', $successMessage)
                ->with('warning', $capacityWarning); // Thêm cảnh báo nếu có

        } catch (\Illuminate\Validation\ValidationException $e) {
            // UC2.5-U-4a: Dữ liệu thiếu/sai
            return back()->withErrors($e->errors())->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            // UC2.5-U-4b: Lưu thất bại (DB)
            \Log::error('Database error updating room: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', '❌ Lỗi cơ sở dữ liệu: Không thể cập nhật phòng học. Vui lòng kiểm tra kết nối và thử lại.');
        } catch (\Exception $e) {
            // UC2.5-U-4b: Lưu thất bại (hệ thống)
            \Log::error('System error updating room: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', '❌ Lỗi hệ thống: Không thể cập nhật phòng học. Vui lòng liên hệ quản trị viên hoặc thử lại sau.');
        }
    }

    // A-1: Xóa phòng học
    public function destroy(Room $room)
    {
        try {
            // UC2.5-D-2: Kiểm tra ràng buộc - Phòng đang được tham chiếu trong LOP_HOC_PHAN
            // Lấy năm học và kỳ hiện tại từ session hoặc xác định dựa vào ngày hiện tại
            $currentYear = session('academic_year');
            $currentTerm = session('term');

            // Nếu không có trong session, tự động xác định dựa vào ngày hiện tại
            if (!$currentYear || !$currentTerm) {
                $now = now();
                $month = $now->month;
                $year = $now->year;

                // Xác định năm học (VD: tháng 9/2024 -> 8/2025 là năm học 2024-2025)
                if ($month >= 9) {
                    $currentYear = $year . '-' . ($year + 1);
                    $currentTerm = 'HK1'; // Tháng 9-12: Học kỳ 1
                } else if ($month >= 1 && $month <= 5) {
                    $currentYear = ($year - 1) . '-' . $year;
                    $currentTerm = 'HK2'; // Tháng 1-5: Học kỳ 2
                } else {
                    $currentYear = ($year - 1) . '-' . $year;
                    $currentTerm = 'HE'; // Tháng 6-8: Học kỳ hè
                }
            }

            // Lấy các lớp học phần đang sử dụng phòng (kỳ hiện tại hoặc tương lai)
            $activeClasses = $room->classSections()
                ->where(function ($query) use ($currentYear, $currentTerm) {
                    // Lấy lớp có năm học lớn hơn năm hiện tại
                    $query->where('academic_year', '>', $currentYear)
                        // HOẶC cùng năm học nhưng kỳ >= kỳ hiện tại
                        ->orWhere(function ($q) use ($currentYear, $currentTerm) {
                            $q->where('academic_year', '=', $currentYear);

                            // So sánh kỳ học: HK1 < HK2 < HE
                            $termOrder = ['HK1' => 1, 'HK2' => 2, 'HE' => 3];
                            $currentTermOrder = $termOrder[$currentTerm] ?? 1;

                            $q->where(function ($q2) use ($termOrder, $currentTermOrder) {
                                foreach ($termOrder as $term => $order) {
                                    if ($order >= $currentTermOrder) {
                                        $q2->orWhere('term', $term);
                                    }
                                }
                            });
                        });
                })
                ->with(['course', 'shift']) // Eager load để hiển thị thông tin
                ->get();

            // UC2.5-D-2a: Có ràng buộc → đề xuất Tạm ngưng thay vì xóa
            if ($activeClasses->count() > 0) {
                $classList = $activeClasses->map(function ($class) {
                    $courseName = $class->course->name ?? 'N/A';
                    $sectionCode = $class->section_code ?? '';
                    return "- {$courseName} - Nhóm {$sectionCode} ({$class->term} - {$class->academic_year})";
                })->implode("\n");

                return back()->with(
                    'error',
                    "❌ Không thể xóa phòng \"{$room->code}\" vì đang được sử dụng bởi {$activeClasses->count()} lớp học phần:\n\n" .
                        $classList . "\n\n" .
                        "💡 GỢI Ý: Thay vì xóa, bạn có thể:\n" .
                        "1. Chuyển trạng thái phòng thành \"Tạm ngưng\" (phòng vẫn giữ dữ liệu lịch sử)\n" .
                        "2. Chuyển các lớp sang phòng khác trước khi xóa\n" .
                        "3. Đợi đến khi các lớp học kết thúc"
                );
            }

            // UC2.5-D-3: Hiển thị xác nhận (đã xử lý ở frontend với confirm())

            // UC2.5-D-5: Xóa mềm (soft delete) hoặc chuyển Tạm ngưng
            $roomCode = $room->code;
            $roomName = $room->name;

            // Lưu thông tin trước khi xóa để ghi log
            $roomInfo = [
                'id' => $room->id,
                'code' => $room->code,
                'name' => $room->name,
                'building' => $room->building,
                'capacity' => $room->capacity,
                'total_classes_hosted' => $room->classSections()->count(),
                'deleted_at' => now()->toDateTimeString(),
                'deleted_by' => auth()->user()->name ?? auth()->user()->email
            ];

            // Thực hiện xóa
            $room->delete();

            // UC2.5-D-5: Ghi log
            LogEntry::create([
                'user_id' => auth()->id(),
                'action' => 'room_deleted',
                'metadata' => json_encode($roomInfo),
            ]);

            // UC2.5-D-5: Cập nhật danh sách, thông báo thành công
            return redirect()
                ->route('rooms.index')
                ->with('success', "✓ Xóa phòng học \"{$roomCode}\" ({$roomName}) thành công.");
        } catch (\Illuminate\Database\QueryException $e) {
            // UC2.5-D-5a: Lỗi xóa (DB constraint)
            \Log::error('Database error deleting room: ' . $e->getMessage());

            // Kiểm tra lỗi foreign key constraint
            if (strpos($e->getMessage(), 'foreign key constraint') !== false) {
                return back()->with(
                    'error',
                    "❌ Không thể xóa phòng \"{$room->code}\" do vi phạm ràng buộc dữ liệu.\n\n" .
                        "Phòng này đang được tham chiếu bởi các bảng khác trong hệ thống.\n\n" .
                        "💡 GỢI Ý: Chuyển trạng thái thành \"Tạm ngưng\" thay vì xóa."
                );
            }

            return back()->with(
                'error',
                '❌ Lỗi cơ sở dữ liệu: Không thể xóa phòng học. Vui lòng kiểm tra kết nối và thử lại.'
            );
        } catch (\Exception $e) {
            // UC2.5-D-5a: Lỗi xóa (hệ thống)
            \Log::error('System error deleting room: ' . $e->getMessage());
            return back()->with(
                'error',
                '❌ Lỗi hệ thống: Không thể xóa phòng học. Vui lòng liên hệ quản trị viên hoặc thử lại sau.'
            );
        }
    }

    /**
     * Get room detail (API endpoint for modal)
     */
    public function getDetail(Room $room)
    {
        // Load recent usage (last 10 class sections)
        $recentUsage = $room->classSections()
            ->with(['course', 'shift'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($section) {
                return [
                    'class_name' => $section->section_name,
                    'course_code' => $section->course->code ?? 'N/A',
                    'course_name' => $section->course->name ?? 'N/A',
                    'shift' => $section->shift->name ?? 'N/A',
                    'semester' => $section->semester ?? 'N/A',
                    'year' => $section->academic_year ?? 'N/A',
                ];
            });

        return response()->json([
            'code' => $room->code,
            'name' => $room->name,
            'building' => $room->building,
            'floor' => $room->floor,
            'capacity' => $room->capacity,
            'equipment' => $room->equipment ?? [],
            'status' => $room->status,
            'status_label' => $room->status_label,
            'recent_usage' => $recentUsage,
            'total_usage' => $room->classSections()->count(),
        ]);
    }
}
