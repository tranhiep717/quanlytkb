@extends('admin.layout')

@section('title', 'Quản lý Khoa')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
    <h2 style="margin:0;">Quản lý Khoa</h2>
    <a href="{{ route('faculties.create') }}" style="background:#10b981;color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none;">+ Tạo khoa mới</a>
</div>

<!-- Bộ lọc và tìm kiếm -->
<div class="card" style="margin-bottom:16px;">
    <form method="GET" action="{{ route('faculties.index') }}">
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:12px;">
            <div>
                <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Tìm kiếm</label>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Mã khoa, tên khoa..." style="width:100%;">
            </div>
            <div>
                <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Trạng thái</label>
                <select name="status" style="width:100%;">
                    <option value="">-- Tất cả --</option>
                    <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Ngưng hoạt động</option>
                </select>
            </div>
            <div>
                <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Trưởng khoa</label>
                <select name="dean_id" style="width:100%;">
                    <option value="">-- Tất cả --</option>
                    @foreach($deans as $dean)
                    <option value="{{ $dean->id }}" {{ ($filters['dean_id'] ?? '') == $dean->id ? 'selected' : '' }}>
                        {{ $dean->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;align-items:flex-end;">
                <button type="submit" style="background:#0ea5e9;color:#fff;cursor:pointer;width:100%;">Lọc</button>
            </div>
        </div>
    </form>
</div>

<!-- Bảng danh sách khoa -->
<div class="card">
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="border-bottom:1px solid rgba(148,163,184,.2);">
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Mã khoa</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Tên khoa</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Trưởng khoa</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Trạng thái</th>
                <th style="text-align:center;padding:12px;color:#94a3b8;font-weight:500;">Người dùng</th>
                <th style="text-align:center;padding:12px;color:#94a3b8;font-weight:500;">Học phần</th>
                <th style="text-align:right;padding:12px;color:#94a3b8;font-weight:500;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($faculties as $faculty)
            <tr style="border-bottom:1px solid rgba(148,163,184,.1);">
                <td style="padding:12px;">
                    <span style="background:#eef2ff;color:#4f46e5;padding:2px 8px;border-radius:999px;font-size:12px;font-weight:600;">{{ $faculty->code }}</span>
                </td>
                <td style="padding:12px;">{{ $faculty->name }}</td>
                <td style="padding:12px;">{{ $faculty->dean->name ?? '-' }}</td>
                <td style="padding:12px;">
                    @if($faculty->is_active)
                    <span style="background:#dcfce7;color:#166534;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;">Hoạt động</span>
                    @else
                    <span style="background:#f3f4f6;color:#4b5563;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;">Ngưng hoạt động</span>
                    @endif
                </td>
                <td style="padding:12px;text-align:center;">{{ $faculty->users_count ?? 0 }}</td>
                <td style="padding:12px;text-align:center;">{{ $faculty->courses_count ?? 0 }}</td>
                <td style="padding:12px;text-align:right;">
                    <div style="display:flex;gap:8px;justify-content:flex-end;">
                        <!-- Xem chi tiết -->
                        <a href="{{ route('faculties.show', $faculty) }}" title="Xem chi tiết" style="color:#334155;display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1px solid #e2e8f0;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                <path fill="currentColor" d="M12,6A10.91,10.91,0,0,1,23,12a10.91,10.91,0,0,1-11,6A10.91,10.91,0,0,1,1,12,10.91,10.91,0,0,1,12,6m0,2C8.13,8,4.7,10.06,3,12c1.7,1.94,5.13,4,9,4s7.3-2.06,9-4C19.3,10.06,15.87,8,12,8m0,2a2,2,0,1,1-2,2,2,2,0,0,1,2-2Z" />
                            </svg>
                        </a>

                        <!-- Sửa -->
                        <a href="{{ route('faculties.edit', $faculty) }}" title="Sửa" style="color:#1d4ed8;display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1px solid #bfdbfe;background:#eff6ff;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                <path fill="currentColor" d="M5,18.08V21h2.92L17.81,11.11l-2.92-2.92ZM20.71,7a1,1,0,0,0,0-1.41L18.37,3.29a1,1,0,0,0-1.41,0L15,5.25l2.92,2.92Z" />
                            </svg>
                        </a>

                        <!-- Xóa -->
                        <form action="{{ route('faculties.destroy', $faculty) }}" method="POST" style="display:inline;" class="js-delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Xóa" style="color:#991b1b;display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1px solid #fecaca;background:#fee2e2;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                    <path fill="currentColor" d="M9,3V4H4V6H5V19A2,2 0 0,0 7,21H17A2,2 0 0,0 19,19V6H20V4H15V3H9M7,6H17V19H7V6Z" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding:24px;text-align:center;color:#64748b;">Chưa có khoa nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:16px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
        <div style="color:#64748b; font-size:14px;">
            @if($faculties->total() > 0)
            Hiển thị {{ $faculties->firstItem() }}–{{ $faculties->lastItem() }} của {{ $faculties->total() }}
            @else
            Không có dữ liệu
            @endif
        </div>
        <div>
            {{ $faculties->withQueryString()->links() }}
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function showFlash(type, text) {
            const container = document.querySelector('.content');
            if (!container) return;
            const div = document.createElement('div');
            div.className = 'flash' + (type === 'error' ? ' error' : '');
            div.textContent = text;
            container.prepend(div);
            setTimeout(() => {
                div.remove();
            }, 4000);
        }

        document.querySelectorAll('form.js-delete-form').forEach(function(form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                if (!confirm('Xác nhận xóa khoa này? Thao tác không thể hoàn tác.')) return;
                const url = form.getAttribute('action');
                const token = form.querySelector('input[name="_token"]').value;
                const row = form.closest('tr');
                try {
                    const res = await fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    let data = {};
                    try {
                        data = await res.json();
                    } catch (_) {}
                    if (res.ok) {
                        if (row) row.remove();
                        showFlash('success', (data && data.message) ? data.message : 'Xóa khoa thành công');
                    } else {
                        showFlash('error', (data && data.message) ? data.message : 'Xóa thất bại. Vui lòng thử lại.');
                    }
                } catch (err) {
                    showFlash('error', 'Xóa thất bại (lỗi DB/kết nối). Vui lòng thử lại.');
                }
            });
        });
    });
</script>
@endsection