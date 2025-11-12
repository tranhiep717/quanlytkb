@extends('admin.layout')

@section('title', 'Quản lý Giảng viên')

@section('content')
<style>
.lecturer-name-link {
    color: #1976d2;
    text-decoration: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}
.lecturer-name-link:hover {
    text-decoration: underline;
    color: #1565c0;
}
.btn-action {
    border-radius: 6px;
    padding: 6px 12px;
    text-decoration: none;
    color: white;
    display: inline-block;
    font-weight: 500;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
}
.btn-primary {
    background: #1976d2;
}
.btn-primary:hover {
    background: #1565c0;
}
.btn-edit {
    background: #f59e0b;
}
.btn-edit:hover {
    background: #d97706;
}
.btn-delete {
    background: #ef4444;
}
.btn-delete:hover {
    background: #dc2626;
}
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5);
}
.modal-content {
    background-color: #fefefe;
    margin: 2% auto;
    padding: 0;
    border: 1px solid #888;
    border-radius: 12px;
    width: 90%;
    max-width: 900px;
    max-height: 90vh;
    overflow-y: auto;
}
.modal-header {
    padding: 20px;
    background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
    color: white;
    border-radius: 12px 12px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-body {
    padding: 24px;
}
.close {
    color: white;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    background: none;
    border: none;
    transition: transform 0.2s;
}
.close:hover {
    transform: scale(1.2);
}
.detail-section {
    margin-bottom: 24px;
}
.detail-section h4 {
    margin: 0 0 12px;
    font-size: 16px;
    font-weight: 600;
    color: #1e293b;
    border-bottom: 2px solid #e5e7eb;
    padding-bottom: 8px;
}
.detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}
.detail-item {
    padding: 8px;
    background: #f9fafb;
    border-radius: 6px;
}
.detail-label {
    font-weight: 600;
    color: #475569;
    margin-right: 8px;
}
.detail-value {
    color: #1e293b;
}
.detail-table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #e5e7eb;
    font-size: 14px;
}
.detail-table thead {
    background: #f9fafb;
}
.detail-table th,
.detail-table td {
    padding: 8px;
    text-align: left;
    border: 1px solid #e5e7eb;
}
.no-data {
    color: #6b7280;
    font-style: italic;
    padding: 12px;
    text-align: center;
    background: #f9fafb;
    border-radius: 6px;
}
</style>

<div style="background:white; padding:24px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2 style="margin:0; font-size:20px; font-weight:600; color:#1e293b;">Danh sách Giảng viên</h2>
        <a href="{{ route('lecturers.create') }}" class="btn-action btn-primary">
            ➕ Thêm Giảng viên
        </a>
    </div>

    <!-- Search & Filter -->
    <form method="GET" style="display:flex; gap:12px; margin-bottom:20px; align-items:end; flex-wrap:wrap;">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Tìm theo mã, tên, email..."
            style="flex:1; padding:10px; border:1px solid #cbd5e0; border-radius:6px;">
        <select name="faculty_id" style="padding:10px; border:1px solid #cbd5e0; border-radius:6px; min-width:150px;">
            <option value="">-- Tất cả Khoa --</option>
            @foreach($faculties as $fac)
                <option value="{{ $fac->id }}" {{ request('faculty_id') == $fac->id ? 'selected' : '' }}>
                    {{ $fac->name }}
                </option>
            @endforeach
        </select>
        <select name="degree" style="padding:10px; border:1px solid #cbd5e0; border-radius:6px; min-width:120px;">
            <option value="">-- Học vị --</option>
            @foreach($degrees as $deg)
                <option value="{{ $deg }}" {{ request('degree') == $deg ? 'selected' : '' }}>
                    {{ $deg }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn-action btn-primary">🔍 Tìm kiếm</button>
        <a href="{{ route('lecturers.index') }}" class="btn-action" style="background:#64748b;">🔄 Xóa lọc</a>
    </form>

    @if(session('success'))
        <div style="background:#dcfce7; border-left:4px solid #22c55e; padding:12px; margin-bottom:20px; border-radius:4px; color:#166534;">
            ✓ {{ session('success') }}
        </div>
    @endif

    <!-- Lecturers Table -->
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; border:1px solid #e2e8f0;">
            <thead>
                <tr style="background:linear-gradient(135deg, #1976d2 0%, #1565c0 100%); color:white;">
                    <th style="padding:12px; text-align:left;">Mã GV</th>
                    <th style="padding:12px; text-align:left;">Họ tên</th>
                    <th style="padding:12px; text-align:left;">Email</th>
                    <th style="padding:12px; text-align:left;">Khoa</th>
                    <th style="padding:12px; text-align:left;">Học vị</th>
                    <th style="padding:12px; text-align:center;">Số lớp</th>
                    <th style="padding:12px; text-align:center;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
            @forelse($lecturers as $lecturer)
                <tr style="border-bottom:1px solid #e2e8f0;">
                    <td style="padding:12px;">{{ $lecturer->code }}</td>
                    <td style="padding:12px; font-weight:500;">
                        <a href="javascript:void(0)" onclick="openLecturerDetail({{ $lecturer->id }})" class="lecturer-name-link">
                            {{ $lecturer->name }}
                        </a>
                    </td>
                    <td style="padding:12px; color:#475569;">{{ $lecturer->email ?? '-' }}</td>
                    <td style="padding:12px;">{{ $lecturer->faculty->name ?? '-' }}</td>
                    <td style="padding:12px;">{{ $lecturer->degree ?? '-' }}</td>
                    <td style="padding:12px; text-align:center; font-weight:600;">{{ $classCounts[$lecturer->id] ?? 0 }}</td>
                    <td style="padding:12px; text-align:center;">
                        <div style="display:flex; gap:8px; justify-content:center; flex-wrap:wrap;">
                            <a href="{{ route('lecturers.edit', $lecturer) }}" class="btn-action btn-edit">📝 Sửa</a>
                            <form action="{{ route('lecturers.destroy', $lecturer) }}" method="POST" style="display:inline;" onsubmit="return confirm('Xác nhận xóa giảng viên này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete">🗑️ Xóa</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="padding:24px; text-align:center; color:#64748b; font-style:italic;">
                        Không tìm thấy giảng viên nào
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top:20px;">
        {{ $lecturers->appends(request()->except('page'))->links() }}
    </div>
</div>

<!-- Detail Modal -->
<div id="lecturerDetailModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 style="margin:0; font-size:20px; font-weight:600;">🧑‍🏫 Chi tiết Giảng viên</h3>
            <button class="close" onclick="closeDetailModal()">&times;</button>
        </div>
        <div class="modal-body" id="lecturerDetailBody">
            <div style="text-align:center; padding:40px; color:#64748b;">
                <div style="font-size:24px; margin-bottom:12px;">⏳</div>
                <div>Đang tải thông tin...</div>
            </div>
        </div>
    </div>
</div>

<script>
function openLecturerDetail(lecId) {
    const modal = document.getElementById('lecturerDetailModal');
    const body = document.getElementById('lecturerDetailBody');
    modal.style.display = 'block';
    
    // Reset loading state
    body.innerHTML = `
        <div style="text-align:center; padding:40px; color:#64748b;">
            <div style="font-size:24px; margin-bottom:12px;">⏳</div>
            <div>Đang tải thông tin...</div>
        </div>
    `;
    
    // Fetch data
    fetch(`/admin/lecturers/${lecId}/detail-json`)
        .then(res => {
            if (!res.ok) throw new Error('Network error');
            return res.json();
        })
        .then(data => {
            const lec = data.lecturer || {};
            const quals = data.qualifications || [];
            const hist = data.history || [];
            
            let html = '';
            
            // Profile section
            html += '<div class="detail-section">';
            html += '<h4>👤 Hồ sơ cá nhân</h4>';
            html += '<div class="detail-grid">';
            html += `<div class="detail-item"><span class="detail-label">Mã GV:</span><span class="detail-value">${lec.code || '-'}</span></div>`;
            html += `<div class="detail-item"><span class="detail-label">Họ tên:</span><span class="detail-value">${lec.name || '-'}</span></div>`;
            html += `<div class="detail-item"><span class="detail-label">Email:</span><span class="detail-value">${lec.email || '-'}</span></div>`;
            html += `<div class="detail-item"><span class="detail-label">SĐT:</span><span class="detail-value">${lec.phone || '-'}</span></div>`;
            html += `<div class="detail-item"><span class="detail-label">Khoa:</span><span class="detail-value">${lec.faculty || '-'}</span></div>`;
            html += `<div class="detail-item"><span class="detail-label">Học vị:</span><span class="detail-value">${lec.degree || '-'}</span></div>`;
            html += '</div></div>';
            
            // Qualifications section
            html += '<div class="detail-section">';
            html += '<h4>📚 Chuyên môn giảng dạy</h4>';
            if (quals.length === 0) {
                html += '<div class="no-data">Chưa có thông tin chuyên môn</div>';
            } else {
                html += '<table class="detail-table">';
                html += '<thead><tr><th>Mã HP</th><th>Tên học phần</th><th>Trình độ</th></tr></thead>';
                html += '<tbody>';
                quals.forEach(q => {
                    html += `<tr>
                        <td>${q.course_code || '-'}</td>
                        <td>${q.course_name || '-'}</td>
                        <td>${q.level || 'Qualified'}</td>
                    </tr>`;
                });
                html += '</tbody></table>';
            }
            html += '</div>';
            
            // Teaching history section
            html += '<div class="detail-section">';
            html += '<h4>📖 Lịch sử giảng dạy (10 lớp gần nhất)</h4>';
            if (hist.length === 0) {
                html += '<div class="no-data">Chưa có lịch sử giảng dạy</div>';
            } else {
                html += '<table class="detail-table">';
                html += '<thead><tr><th>Năm học</th><th>Học kỳ</th><th>Học phần</th><th>Lớp</th><th>Phòng</th><th>Thứ</th><th>Ca</th></tr></thead>';
                html += '<tbody>';
                hist.forEach(h => {
                    html += `<tr>
                        <td>${h.academic_year || '-'}</td>
                        <td>${h.term || '-'}</td>
                        <td>${h.course || '-'}</td>
                        <td>${h.section || '-'}</td>
                        <td>${h.room || '-'}</td>
                        <td>${h.day || '-'}</td>
                        <td>${h.shift || '-'}</td>
                    </tr>`;
                });
                html += '</tbody></table>';
            }
            html += '</div>';
            
            body.innerHTML = html;
        })
        .catch(err => {
            console.error('Error loading lecturer details:', err);
            body.innerHTML = `
                <div style="text-align:center; padding:40px; color:#dc2626;">
                    <div style="font-size:24px; margin-bottom:12px;">⚠️</div>
                    <div>Không thể tải thông tin giảng viên</div>
                    <div style="font-size:14px; margin-top:8px; color:#64748b;">Vui lòng thử lại sau</div>
                </div>
            `;
        });
}

function closeDetailModal() {
    document.getElementById('lecturerDetailModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('lecturerDetailModal');
    if (event.target === modal) {
        closeDetailModal();
    }
}

// Close modal with ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDetailModal();
    }
});
</script>
@endsection
