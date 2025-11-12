@extends('admin.layout')

@section('title', 'Quản lý Giảng viên')

@section('content')
<div style="background:white; padding:24px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2 style="margin:0; font-size:20px; font-weight:600; color:#1e293b;">Danh sách Giảng viên</h2>
        <a href="{{ route('lecturers.create') }}"
            style="background:#1976d2; color:white; padding:10px 20px; border-radius:6px; text-decoration:none; font-weight:500;">
            + Thêm Giảng viên
        </a>
    </div>

    <!-- Search & Filter -->
    <form method="GET" style="display:flex; gap:12px; margin-bottom:20px; align-items:end; flex-wrap:wrap;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo mã, tên, email..."
            style="flex:1; padding:10px; border:1px solid #cbd5e0; border-radius:6px;">
        <select name="faculty_id" style="padding:10px; border:1px solid #cbd5e0; border-radius:6px; min-width:200px;">
            <option value="">-- Tất cả Khoa --</option>
            @foreach($faculties as $faculty)
            <option value="{{ $faculty->id }}" {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>
                {{ $faculty->name }}
            </option>
            @endforeach
        </select>
        <select name="degree" style="padding:10px; border:1px solid #cbd5e0; border-radius:6px; min-width:160px;">
            <option value="">-- Tất cả Học vị --</option>
            @foreach($degrees as $deg)
            <option value="{{ $deg }}" {{ request('degree') == $deg ? 'selected' : '' }}>{{ $deg }}</option>
            @endforeach
        </select>
        <button type="submit"
            style="background:#1976d2; color:white; padding:10px 20px; border:none; border-radius:6px; cursor:pointer;">
            Tìm kiếm
        </button>
        @if(request('search') || request('faculty_id') || request('degree'))
        <a href="{{ route('lecturers.index') }}"
            style="padding:10px 20px; border:1px solid #cbd5e0; border-radius:6px; text-decoration:none; color:#475569;">
            Xóa bộ lọc
        </a>
        @endif
    </form>

    @if($lecturers->isEmpty())
    <p style="text-align:center; color:#64748b; padding:40px;">Không có giảng viên nào.</p>
    @else
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                <th style="padding:12px; text-align:left; font-weight:600; color:#475569;">Mã GV</th>
                <th style="padding:12px; text-align:left; font-weight:600; color:#475569;">Họ tên</th>
                <th style="padding:12px; text-align:left; font-weight:600; color:#475569;">Email</th>
                <th style="padding:12px; text-align:left; font-weight:600; color:#475569;">Khoa</th>
                <th style="padding:12px; text-align:left; font-weight:600; color:#475569;">Học vị</th>
                <th style="padding:12px; text-align:center; font-weight:600; color:#475569;">Số lớp</th>
                <th style="padding:12px; text-align:center; font-weight:600; color:#475569;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lecturers as $lecturer)
            <tr style="border-bottom:1px solid #e2e8f0;">
                <td style="padding:12px;">{{ $lecturer->code }}</td>
                <td style="padding:12px; font-weight:500;">
                    <a href="javascript:void(0)" onclick="openLecturerDetail({{ $lecturer->id }})"
                        style="color:#1976d2; text-decoration:none; font-weight:600;">
                        {{ $lecturer->name }}
                    </a>
                </td>
                <td style="padding:12px; color:#475569;">{{ $lecturer->email ?? '-' }}</td>
                <td style="padding:12px;">{{ $lecturer->faculty->name ?? '-' }}</td>
                <td style="padding:12px;">{{ $lecturer->degree ?? '-' }}</td>
                <td style="padding:12px; text-align:center; font-weight:600;">{{ $classCounts[$lecturer->id] ?? 0 }}
                </td>
                <td style="padding:12px; text-align:center;">
                    <div style="display:flex; gap:8px; justify-content:center; flex-wrap:wrap;">
                        <button onclick="openLecturerDetail({{ $lecturer->id }})"
                            style="background:#1976d2; color:white; border:none; border-radius:6px; padding:6px 12px; cursor:pointer; font-size:14px;">
                            👁️ Xem
                        </button>
                        <a href="{{ route('lecturers.edit', $lecturer) }}"
                            style="background:#f59e0b; color:white; border-radius:6px; padding:6px 12px; text-decoration:none; font-size:14px;">
                            📝 Sửa
                        </a>
                        <form action="{{ route('lecturers.destroy', $lecturer) }}" method="POST" style="display:inline;"
                            onsubmit="return confirm('Xác nhận xóa giảng viên này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                style="background:#dc2626; color:white; border:none; border-radius:6px; padding:6px 12px; cursor:pointer; font-size:14px;">
                                🗑️ Xóa
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top:20px;">
        {{ $lecturers->links() }}
    </div>
    @endif
</div>

<!-- UC2.2 R4: Detail Modal -->
<div id="lecturerDetailModal"
    style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:9999; align-items:center; justify-content:center;">
    <div
        style="background:#fff; border-radius:12px; width:95%; max-width:900px; max-height:85vh; overflow:auto; box-shadow:0 10px 25px rgba(0,0,0,0.3);">
        <div
            style="padding:16px 20px; border-bottom:1px solid #e5e7eb; display:flex; justify-content:space-between; align-items:center; background:#f8fafc;">
            <h3 style="margin:0; font-size:18px; font-weight:600; color:#1e293b;">📋 Thông tin Giảng viên</h3>
            <button onclick="closeDetailModal()"
                style="background:#ef4444; border:none; font-size:20px; cursor:pointer; color:white; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold;">&times;</button>
        </div>
        <div id="detailBody" style="padding:20px;">
            <div style="text-align:center; color:#6b7280; padding:40px;">
                <div style="font-size:24px; margin-bottom:10px;">⏳</div>
                <div>Đang tải...</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openLecturerDetail(lecId) {
    const modal = document.getElementById('lecturerDetailModal');
    modal.style.display = 'flex';
    const body = document.getElementById('detailBody');
    body.innerHTML =
        '<div style="text-align:center; color:#6b7280; padding:40px;"><div style="font-size:24px; margin-bottom:10px;">⏳</div><div>Đang tải...</div></div>';

    fetch(`/admin/lecturers/${lecId}/detail-json`)
        .then(r => {
            if (!r.ok) throw new Error('Network error');
            return r.json();
        })
        .then(data => {
            const lec = data.lecturer || {};
            const quals = data.qualifications || [];
            const hist = data.history || [];
            let html = '';

            // Profile section
            html += '<div style="margin-bottom:28px; padding:20px; background:#f8fafc; border-radius:8px;">';
            html +=
                '<h4 style="margin:0 0 16px; font-size:17px; font-weight:600; color:#1e293b; border-bottom:2px solid #1976d2; padding-bottom:8px;">👤 Hồ sơ</h4>';
            html += '<div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">';
            html +=
                `<div style="padding:8px; background:white; border-radius:4px;"><span style="font-weight:600; color:#475569;">Mã GV:</span> <span style="color:#1e293b;">${lec.code || '-'}</span></div>`;
            html +=
                `<div style="padding:8px; background:white; border-radius:4px;"><span style="font-weight:600; color:#475569;">Họ tên:</span> <span style="color:#1e293b;">${lec.name || '-'}</span></div>`;
            html +=
                `<div style="padding:8px; background:white; border-radius:4px;"><span style="font-weight:600; color:#475569;">Email:</span> <span style="color:#1e293b;">${lec.email || '-'}</span></div>`;
            html +=
                `<div style="padding:8px; background:white; border-radius:4px;"><span style="font-weight:600; color:#475569;">Điện thoại:</span> <span style="color:#1e293b;">${lec.phone || '-'}</span></div>`;
            html +=
                `<div style="padding:8px; background:white; border-radius:4px;"><span style="font-weight:600; color:#475569;">Khoa:</span> <span style="color:#1e293b;">${lec.faculty || '-'}</span></div>`;
            html +=
                `<div style="padding:8px; background:white; border-radius:4px;"><span style="font-weight:600; color:#475569;">Học vị:</span> <span style="color:#1e293b;">${lec.degree || '-'}</span></div>`;
            html += '</div></div>';

            // Qualifications section
            html += '<div style="margin-bottom:28px;">';
            html +=
                '<h4 style="margin:0 0 16px; font-size:17px; font-weight:600; color:#1e293b; border-bottom:2px solid #10b981; padding-bottom:8px;">📚 Chuyên môn giảng dạy</h4>';
            if (quals.length === 0) {
                html +=
                    '<div style="color:#6b7280; font-style:italic; padding:20px; background:#f9fafb; border-radius:6px; text-align:center;">Chưa có thông tin chuyên môn</div>';
            } else {
                html += '<div style="overflow-x:auto;">';
                html += '<table style="width:100%; border-collapse:collapse; border:1px solid #e5e7eb;">';
                html +=
                    '<thead><tr style="background:#f0fdf4;"><th style="padding:10px; text-align:left; border:1px solid #e5e7eb; font-weight:600; color:#166534;">Mã HP</th><th style="padding:10px; text-align:left; border:1px solid #e5e7eb; font-weight:600; color:#166534;">Tên học phần</th><th style="padding:10px; text-align:left; border:1px solid #e5e7eb; font-weight:600; color:#166534;">Trình độ</th></tr></thead>';
                html += '<tbody>';
                quals.forEach((q, idx) => {
                    const bg = idx % 2 === 0 ? '#ffffff' : '#f9fafb';
                    html +=
                        `<tr style="background:${bg};"><td style="padding:10px; border:1px solid #e5e7eb;">${q.course_code || '-'}</td><td style="padding:10px; border:1px solid #e5e7eb;">${q.course_name || '-'}</td><td style="padding:10px; border:1px solid #e5e7eb;">${q.level || '-'}</td></tr>`;
                });
                html += '</tbody></table>';
                html += '</div>';
            }
            html += '</div>';

            // Teaching history section
            html += '<div>';
            html +=
                '<h4 style="margin:0 0 16px; font-size:17px; font-weight:600; color:#1e293b; border-bottom:2px solid #f59e0b; padding-bottom:8px;">📖 Lịch sử giảng dạy (10 lớp gần nhất)</h4>';
            if (hist.length === 0) {
                html +=
                    '<div style="color:#6b7280; font-style:italic; padding:20px; background:#f9fafb; border-radius:6px; text-align:center;">Chưa có lịch sử giảng dạy</div>';
            } else {
                html += '<div style="overflow-x:auto;">';
                html +=
                    '<table style="width:100%; border-collapse:collapse; border:1px solid #e5e7eb; font-size:14px;">';
                html +=
                    '<thead><tr style="background:#fffbeb;"><th style="padding:10px; text-align:left; border:1px solid #e5e7eb; font-weight:600; color:#92400e;">Năm học</th><th style="padding:10px; text-align:left; border:1px solid #e5e7eb; font-weight:600; color:#92400e;">Học kỳ</th><th style="padding:10px; text-align:left; border:1px solid #e5e7eb; font-weight:600; color:#92400e;">Học phần</th><th style="padding:10px; text-align:left; border:1px solid #e5e7eb; font-weight:600; color:#92400e;">Lớp</th><th style="padding:10px; text-align:left; border:1px solid #e5e7eb; font-weight:600; color:#92400e;">Phòng</th><th style="padding:10px; text-align:left; border:1px solid #e5e7eb; font-weight:600; color:#92400e;">Thứ</th><th style="padding:10px; text-align:left; border:1px solid #e5e7eb; font-weight:600; color:#92400e;">Ca</th></tr></thead>';
                html += '<tbody>';
                hist.forEach((h, idx) => {
                    const bg = idx % 2 === 0 ? '#ffffff' : '#f9fafb';
                    html +=
                        `<tr style="background:${bg};"><td style="padding:10px; border:1px solid #e5e7eb;">${h.academic_year || '-'}</td><td style="padding:10px; border:1px solid #e5e7eb;">${h.term || '-'}</td><td style="padding:10px; border:1px solid #e5e7eb;">${h.course || '-'}</td><td style="padding:10px; border:1px solid #e5e7eb;">${h.section || '-'}</td><td style="padding:10px; border:1px solid #e5e7eb;">${h.room || '-'}</td><td style="padding:10px; border:1px solid #e5e7eb;">${h.day || '-'}</td><td style="padding:10px; border:1px solid #e5e7eb;">${h.shift || '-'}</td></tr>`;
                });
                html += '</tbody></table>';
                html += '</div>';
            }
            html += '</div>';

            body.innerHTML = html;
        })
        .catch(() => {
            body.innerHTML =
                '<div style="text-align:center; color:#dc2626; padding:40px;"><div style="font-size:24px; margin-bottom:10px;">❌</div><div style="font-weight:600;">Không thể tải thông tin giảng viên</div><div style="margin-top:8px; color:#6b7280; font-size:14px;">Vui lòng thử lại sau</div></div>';
        });
}

function closeDetailModal() {
    document.getElementById('lecturerDetailModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('lecturerDetailModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDetailModal();
    }
});
</script>
@endsection