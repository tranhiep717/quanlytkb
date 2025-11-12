@extends('admin.layout')

@section('title', 'Chi tiết người dùng')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
    <h2 style="margin:0;">Chi tiết người dùng</h2>
    <div style="display:flex; gap:8px;">
        <a href="{{ route('admin.users.edit', $user) }}" style="background:#eff6ff;color:#1d4ed8;padding:8px 12px;border-radius:8px;text-decoration:none;border:1px solid #bfdbfe;">Sửa</a>
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Xác nhận xóa người dùng này? Thao tác không thể hoàn tác.');">
            @csrf
            @method('DELETE')
            <button type="submit" style="background:#fee2e2;color:#991b1b;padding:8px 12px;border-radius:8px;border:1px solid #fecaca;cursor:pointer;">Xóa</button>
        </form>
        <a href="{{ route('admin.users') }}" style="background:#e2e8f0;color:#334155;padding:8px 12px;border-radius:8px;text-decoration:none;border:1px solid #cbd5e0;">Quay lại</a>
    </div>
</div>

<div class="card">
    <div style="display:grid; grid-template-columns: 1fr 2fr; gap:12px;">
        <div style="color:#94a3b8;">Mã</div>
        <div>
            @if($user->code)
            <span style="background:#eef2ff;color:#4f46e5;padding:2px 8px;border-radius:999px;font-size:12px;font-weight:600;">{{ $user->code }}</span>
            @else
            <span style="color:#94a3b8;">-</span>
            @endif
        </div>

        <div style="color:#94a3b8;">Họ tên</div>
        <div>{{ $user->name }}</div>

        <div style="color:#94a3b8;">Email</div>
        <div>{{ $user->email }}</div>

        <div style="color:#94a3b8;">Vai trò</div>
        <div>
            @if($user->role === 'student') Sinh viên
            @elseif($user->role === 'lecturer') Giảng viên
            @elseif($user->role === 'faculty_admin') Quản trị khoa
            @elseif($user->role === 'super_admin') Quản trị hệ thống
            @else {{ $user->role }}
            @endif
        </div>

        <div style="color:#94a3b8;">Khoa</div>
        <div>{{ $user->faculty->name ?? '-' }}</div>

        <div style="color:#94a3b8;">Khóa</div>
        <div>{{ $user->class_cohort ?? '-' }}</div>

        <div style="color:#94a3b8;">Trạng thái</div>
        <div>
            @if($user->is_locked)
            <span style="background:#fee2e2;color:#b91c1c;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;">Đã khóa</span>
            @else
            <span style="background:#dcfce7;color:#166534;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;">Hoạt động</span>
            @endif
        </div>

        <div style="color:#94a3b8;">Ngày tạo</div>
        <div>{{ $user->created_at?->format('d/m/Y H:i') }}</div>

        <div style="color:#94a3b8;">Cập nhật</div>
        <div>{{ $user->updated_at?->format('d/m/Y H:i') }}</div>
    </div>
</div>

<div class="card" style="display:flex; gap:12px; align-items:center;">
    @if($user->is_locked)
    <form action="{{ route('admin.users.unlock', $user) }}" method="POST">
        @csrf
        <button type="submit" style="background:#dcfce7;color:#166534;padding:8px 12px;border-radius:8px;border:1px solid #86efac;cursor:pointer;">Mở khóa</button>
    </form>
    @else
    <form action="{{ route('admin.users.lock', $user) }}" method="POST">
        @csrf
        <button type="submit" style="background:#fee2e2;color:#991b1b;padding:8px 12px;border-radius:8px;border:1px solid #fecaca;cursor:pointer;">Khóa</button>
    </form>
    @endif

    <form action="{{ route('admin.users.reset_password', $user) }}" method="POST">
        @csrf
        <button type="submit" style="background:#fef3c7;color:#92400e;padding:8px 12px;border-radius:8px;border:1px solid #fde68a;cursor:pointer;">Gửi đặt lại mật khẩu</button>
    </form>
</div>
@endsection