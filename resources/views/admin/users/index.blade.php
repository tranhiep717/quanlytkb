@extends('admin.layout')

@section('title', 'Quản lý người dùng')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
    <h2 style="margin:0;">Quản lý người dùng</h2>
    <a href="{{ route('admin.users.create') }}" style="background:#10b981;color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none;">+ Tạo người dùng mới</a>
</div>

<!-- U-2: Bộ lọc và tìm kiếm -->
<div class="card" style="margin-bottom:16px;">
    <form method="GET" action="{{ route('admin.users') }}">
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:12px;">
            <div>
                <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Tìm kiếm</label>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Tên, email, mã..." style="width:100%;">
            </div>
            <div>
                <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Vai trò</label>
                <select name="role" style="width:100%;">
                    <option value="">-- Tất cả --</option>
                    <option value="student" {{ ($filters['role'] ?? '') === 'student' ? 'selected' : '' }}>Sinh viên</option>
                    <option value="lecturer" {{ ($filters['role'] ?? '') === 'lecturer' ? 'selected' : '' }}>Giảng viên</option>
                    <option value="faculty_admin" {{ ($filters['role'] ?? '') === 'faculty_admin' ? 'selected' : '' }}>Quản trị khoa</option>
                    <option value="super_admin" {{ ($filters['role'] ?? '') === 'super_admin' ? 'selected' : '' }}>Quản trị hệ thống</option>
                </select>
            </div>
            <div>
                <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Trạng thái</label>
                <select name="status" style="width:100%;">
                    <option value="">-- Tất cả --</option>
                    <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="locked" {{ ($filters['status'] ?? '') === 'locked' ? 'selected' : '' }}>Đã khóa</option>
                </select>
            </div>
            <div>
                <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Khoa</label>
                <select name="faculty_id" style="width:100%;">
                    <option value="">-- Tất cả --</option>
                    @foreach($faculties as $faculty)
                    <option value="{{ $faculty->id }}" {{ ($filters['faculty_id'] ?? '') == $faculty->id ? 'selected' : '' }}>
                        {{ $faculty->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Khóa học</label>
                <input type="text" name="cohort" value="{{ $filters['cohort'] ?? '' }}" placeholder="VD: K17" style="width:100%;">
            </div>
            <div style="display:flex;align-items:flex-end;">
                <button type="submit" style="background:#0ea5e9;color:#fff;cursor:pointer;width:100%;">Lọc</button>
            </div>
        </div>
    </form>
</div>

<!-- U-1: Bảng danh sách người dùng -->
<div class="card">
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="border-bottom:1px solid rgba(148,163,184,.2);">
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Mã</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Họ tên</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Email</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Vai trò</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Khoa</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Khóa</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Trạng thái</th>
                <th style="text-align:right;padding:12px;color:#94a3b8;font-weight:500;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr style="border-bottom:1px solid rgba(148,163,184,.1);">
                <td style="padding:12px;">
                    @if($user->code)
                    <span style="background:#eef2ff;color:#4f46e5;padding:2px 8px;border-radius:999px;font-size:12px;font-weight:600;">{{ $user->code }}</span>
                    @else
                    <span style="color:#94a3b8;">-</span>
                    @endif
                </td>
                <td style="padding:12px;">{{ $user->name }}</td>
                <td style="padding:12px;">{{ $user->email }}</td>
                <td style="padding:12px;">
                    @if($user->role === 'student') Sinh viên
                    @elseif($user->role === 'lecturer') Giảng viên
                    @elseif($user->role === 'faculty_admin') Quản trị khoa
                    @elseif($user->role === 'super_admin') Quản trị hệ thống
                    @else {{ $user->role }}
                    @endif
                </td>
                <td style="padding:12px;">{{ $user->faculty->name ?? '-' }}</td>
                <td style="padding:12px;">{{ $user->class_cohort ?? '-' }}</td>
                <td style="padding:12px;">
                    @if($user->is_locked)
                    <span style="background:#fee2e2;color:#b91c1c;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;">Đã khóa</span>
                    @else
                    <span style="background:#dcfce7;color:#166534;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;">Hoạt động</span>
                    @endif
                </td>
                <td style="padding:12px;text-align:right;">
                    <div style="display:flex;gap:8px;justify-content:flex-end;">
                        <!-- Xem chi tiết -->
                        <a href="{{ route('admin.users.show', $user) }}" title="Xem chi tiết" style="color:#334155;display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1px solid #e2e8f0;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                <path fill="currentColor" d="M12,6A10.91,10.91,0,0,1,23,12a10.91,10.91,0,0,1-11,6A10.91,10.91,0,0,1,1,12,10.91,10.91,0,0,1,12,6m0,2C8.13,8,4.7,10.06,3,12c1.7,1.94,5.13,4,9,4s7.3-2.06,9-4C19.3,10.06,15.87,8,12,8m0,2a2,2,0,1,1-2,2,2,2,0,0,1,2-2Z" />
                            </svg>
                        </a>

                        <!-- Sửa -->
                        <a href="{{ route('admin.users.edit', $user) }}" title="Sửa" style="color:#1d4ed8;display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1px solid #bfdbfe;background:#eff6ff;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                <path fill="currentColor" d="M5,18.08V21h2.92L17.81,11.11l-2.92-2.92ZM20.71,7a1,1,0,0,0,0-1.41L18.37,3.29a1,1,0,0,0-1.41,0L15,5.25l2.92,2.92Z" />
                            </svg>
                        </a>

                        <!-- Khóa/Mở khóa -->
                        @if($user->is_locked)
                        <form action="{{ route('admin.users.unlock', $user) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" title="Mở khóa" style="color:#166534;display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1px solid #86efac;background:#dcfce7;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                    <path fill="currentColor" d="M12,17a2,2,0,1,1,2-2A2,2,0,0,1,12,17Zm6-5H18V10a6,6,0,0,0-12,0h2a4,4,0,0,1,8,0v2H6a2,2,0,0,0-2,2v6a2,2,0,0,0,2,2H18a2,2,0,0,0,2-2V14A2,2,0,0,0,18,12Z" />
                                </svg>
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.users.lock', $user) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" title="Khóa" style="color:#991b1b;display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1px solid #fecaca;background:#fee2e2;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                    <path fill="currentColor" d="M12,17a2,2,0,1,1,2-2A2,2,0,0,1,12,17Zm6-5H18V10a6,6,0,0,0-12,0H4a8,8,0,0,1,16,0v2h0a2,2,0,0,1,2,2v6a2,2,0,0,1-2,2H6a2,2,0,0,1-2-2V14a2,2,0,0,1,2-2Z" />
                                </svg>
                            </button>
                        </form>
                        @endif

                        <!-- Gửi reset mật khẩu -->
                        <form action="{{ route('admin.users.reset_password', $user) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" title="Gửi đặt lại mật khẩu" style="color:#92400e;display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1px solid #fde68a;background:#fef3c7;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                    <path fill="currentColor" d="M7,14A3,3 0 0,1 10,11A3,3 0 0,1 13,14A3,3 0 0,1 10,17A3,3 0 0,1 7,14M10,5A9,9 0 0,1 19,14C19,16.39 18.05,18.56 16.5,20.22L14.95,18.67C16.09,17.41 16.8,15.78 16.8,14A6.8,6.8 0 0,0 10,7.2A6.8,6.8 0 0,0 3.2,14C3.2,15.78 3.91,17.41 5.05,18.67L3.5,20.22C1.95,18.56 1,16.39 1,14A9,9 0 0,1 10,5Z" />
                                </svg>
                            </button>
                        </form>

                        <!-- Xóa -->
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;" class="js-delete-form">
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
                <td colspan="8" style="padding:24px;text-align:center;color:#64748b;">Không tìm thấy người dùng nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:16px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
        <div style="color:#64748b; font-size:14px;">
            @if($users->total() > 0)
            Hiển thị {{ $users->firstItem() }}–{{ $users->lastItem() }} của {{ $users->total() }}
            @else
            Không có dữ liệu
            @endif
        </div>
        <div>
            {{ $users->withQueryString()->links() }}
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
                if (!confirm('Xác nhận xóa người dùng này? Thao tác không thể hoàn tác.')) return;
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
                        showFlash('success', (data && data.message) ? data.message : 'Xóa thành công');
                    } else {
                        showFlash('error', (data && data.message) ? data.message : 'Xóa thất bại (lỗi DB/kết nối). Vui lòng thử lại.');
                    }
                } catch (err) {
                    showFlash('error', 'Xóa thất bại (lỗi DB/kết nối). Vui lòng thử lại.');
                }
            });
        });
    });
</script>
@endsection