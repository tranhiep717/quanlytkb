@extends('admin.layout')

@section('title', 'Tạo người dùng mới')

@section('content')
<h2>Tạo người dùng mới</h2>

<div class="card" style="max-width:600px;">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Họ tên <span style="color:#ef4444;">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" required style="width:100%;">
            @error('name')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Email <span style="color:#ef4444;">*</span></label>
            <input type="email" name="email" value="{{ old('email') }}" required style="width:100%;">
            @error('email')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Mã người dùng</label>
            <input type="text" name="code" value="{{ old('code') }}" style="width:100%;">
            @error('code')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Vai trò <span style="color:#ef4444;">*</span></label>
            <select name="role" required style="width:100%;">
                <option value="">-- Chọn vai trò --</option>
                <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Sinh viên</option>
                <option value="lecturer" {{ old('role') === 'lecturer' ? 'selected' : '' }}>Giảng viên</option>
                <option value="faculty_admin" {{ old('role') === 'faculty_admin' ? 'selected' : '' }}>Quản trị khoa</option>
                <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Quản trị hệ thống</option>
            </select>
            @error('role')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Khoa</label>
            <select name="faculty_id" style="width:100%;">
                <option value="">-- Chọn khoa --</option>
                @foreach($faculties as $faculty)
                <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>{{ $faculty->name }}</option>
                @endforeach
            </select>
            @error('faculty_id')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Khóa học (chỉ dành cho sinh viên)</label>
            <select name="class_cohort" style="width:100%;">
                <option value="">-- Chọn khóa học --</option>
                @foreach($cohorts as $cohort)
                <option value="{{ $cohort }}" {{ old('class_cohort') === $cohort ? 'selected' : '' }}>{{ $cohort }}</option>
                @endforeach
            </select>
            <div style="color:#64748b;font-size:12px;margin-top:4px;">Nếu khóa học chưa có trong danh sách, hãy tạo sinh viên khóa đó trước</div>
            @error('class_cohort')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Mật khẩu <span style="color:#ef4444;">*</span></label>
            <input type="password" name="password" required style="width:100%;">
            <div style="color:#64748b;font-size:12px;margin-top:4px;">Tối thiểu 8 ký tự</div>
            @error('password')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="display:flex;gap:12px;margin-top:24px;">
            <button type="submit" style="background:#10b981;color:#fff;padding:10px 20px;border-radius:8px;cursor:pointer;border:none;">Tạo người dùng</button>
            <a href="{{ route('admin.users') }}" style="background:#475569;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;display:inline-block;">Hủy</a>
        </div>
    </form>
</div>
@endsection