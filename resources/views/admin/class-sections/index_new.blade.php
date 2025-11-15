@extends('admin.layout')

@section('title', 'Quản lý Lớp học phần')

@section('content')
<div class="container-fluid py-4">
    <h2>📚 Quản lý Lớp học phần</h2>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('class-sections.index') }}" method="GET">
                <!-- Row 1 -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Năm học</label>
                        <input type="text" name="academic_year" class="form-control" value="{{ $filters['academic_year'] ?? $academicYear }}">
                    </div>
                    <div class="col-md-3">
                        <label>Học kỳ</label>
                        <select name="term" class="form-control">
                            <option value="">-- Tất cả --</option>
                            <option value="HK1" {{ ($filters['term'] ?? $term) == 'HK1' ? 'selected' : '' }}>Học kỳ 1</option>
                            <option value="HK2" {{ ($filters['term'] ?? $term) == 'HK2' ? 'selected' : '' }}>Học kỳ 2</option>
                            <option value="HE" {{ ($filters['term'] ?? $term) == 'HE' ? 'selected' : '' }}>Học kỳ Hè</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Khoa</label>
                        <select name="faculty_id" class="form-control">
                            <option value="">-- Tất cả --</option>
                            @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ ($filters['faculty_id'] ?? '') == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" placeholder="Mã lớp, môn học..." value="{{ $filters['search'] ?? '' }}">
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="row">
                    <div class="col-md-2">
                        <label>Trạng thái</label>
                        <select name="status" class="form-control">
                            <option value="">-- Tất cả --</option>
                            <option value="active" {{ ($filters['status'] ?? '') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="locked" {{ ($filters['status'] ?? '') == 'locked' ? 'selected' : '' }}>Tạm khóa</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Phòng học</label>
                        <select name="room_id" class="form-control">
                            <option value="">-- Tất cả --</option>
                            @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ ($filters['room_id'] ?? '') == $room->id ? 'selected' : '' }}>
                                {{ $room->code }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Ca học</label>
                        <select name="shift_id" class="form-control">
                            <option value="">-- Tất cả --</option>
                            @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}" {{ ($filters['shift_id'] ?? '') == $shift->id ? 'selected' : '' }}>
                                Ca {{ $shift->start_period }}-{{ $shift->end_period }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>&nbsp;</label><br>
                        <div class="form-check d-inline-block me-3">
                            <input type="checkbox" name="unassigned_lecturer" id="unassigned" value="1" {{ ($filters['unassigned_lecturer'] ?? '') == '1' ? 'checked' : '' }} class="form-check-input">
                            <label for="unassigned" class="form-check-label">Chưa phân công GV</label>
                        </div>
                        <button type="submit" class="btn btn-primary">🔎 Lọc</button>
                        <a href="{{ route('class-sections.index') }}" class="btn btn-secondary">Xóa lọc</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Data Table -->
    <div class="card">
        <div class="card-body">
            @if($classSections->count() > 0)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã LHP</th>
                        <th>Môn học</th>
                        <th>Khoa HP</th>
                        <th>Giảng viên</th>
                        <th>Lịch & Phòng</th>
                        <th>Sĩ số</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classSections as $cs)
                    <tr>
                        <td><strong>{{ $cs->course->code ?? 'N/A' }}-{{ $cs->section_code }}</strong></td>
                        <td>{{ $cs->course->name ?? 'N/A' }}</td>
                        <td>{{ $cs->course->faculty->code ?? 'N/A' }}</td>
                        <td>{{ $cs->lecturer->name ?? 'Chưa phân công' }}</td>
                        <td>
                            @php
                            $days = ['', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'];
                            @endphp
                            {{ $days[$cs->day_of_week] ?? '' }}
                            @if($cs->shift)
                            (Ca {{ $cs->shift->start_period }}-{{ $cs->shift->end_period }})
                            @endif
                            <br>
                            <small>Phòng: {{ $cs->room->code ?? 'N/A' }}</small>
                        </td>
                        <td>{{ $cs->registrations->count() }} / {{ $cs->max_capacity }}</td>
                        <td>
                            @if($cs->status == 'active')
                            <span class="badge bg-success">Hoạt động</span>
                            @else
                            <span class="badge bg-secondary">Tạm khóa</span>
                            @endif
                        </td>
                        <td>
                            <button onclick="viewDetail({{ $cs->id }})" class="btn btn-sm btn-info" title="Xem chi tiết">👁️</button>
                            <a href="{{ route('class-sections.edit', $cs) }}" class="btn btn-sm btn-primary" title="Sửa">✏️</a>
                            <form action="{{ route('class-sections.destroy', $cs) }}" method="POST" style="display:inline;" onsubmit="return confirm('Xóa lớp {{ $cs->section_code }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $classSections->links() }}
            @else
            <p class="text-center text-muted">Không tìm thấy lớp học phần nào</p>
            @endif
        </div>
    </div>
</div>

<!-- Modal Chi tiết -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết Lớp học phần</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailBody">
                <p class="text-center">Đang tải...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const ADMIN_CLASS_SECTIONS_BASE = "{{ url('admin/class-sections') }}";

    function viewDetail(id) {
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        const body = document.getElementById('detailBody');

        modal.show();
        body.innerHTML = '<p class="text-center">Đang tải...</p>';

        fetch(`${ADMIN_CLASS_SECTIONS_BASE}/${id}/detail`, {
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                const cs = data.class_section;
                body.innerHTML = `
                <h6>Thông tin cơ bản</h6>
                <table class="table table-sm">
                    <tr><th>Mã LHP:</th><td>${cs.course.code}-${cs.section_code}</td></tr>
                    <tr><th>Môn học:</th><td>${cs.course.name}</td></tr>
                    <tr><th>Giảng viên:</th><td>${cs.lecturer ? cs.lecturer.name : 'Chưa phân công'}</td></tr>
                    <tr><th>Phòng:</th><td>${cs.room ? cs.room.code : 'N/A'}</td></tr>
                    <tr><th>Sĩ số:</th><td>${cs.current_enrollment} / ${cs.max_capacity}</td></tr>
                </table>
                
                <h6 class="mt-3">Danh sách sinh viên (${data.students.length})</h6>
                ${data.students.length > 0 ? `
                    <table class="table table-sm table-bordered">
                        <thead><tr><th>MSSV</th><th>Họ tên</th><th>Trạng thái</th></tr></thead>
                        <tbody>
                            ${data.students.map(s => `<tr><td>${s.student_id}</td><td>${s.name}</td><td>${s.status}</td></tr>`).join('')}
                        </tbody>
                    </table>
                ` : '<p class="text-muted">Chưa có sinh viên</p>'}
            `;
            })
            .catch(err => {
                body.innerHTML = '<p class="text-danger">Lỗi tải dữ liệu</p>';
            });
    }
</script>
@endsection