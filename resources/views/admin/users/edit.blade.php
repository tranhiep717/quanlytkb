@extends('admin.layout')

@section('title', 'Chỉnh sửa người dùng')

@section('content')
<h2>Chỉnh sửa người dùng: {{ $user->name }}</h2>

<div class="card" style="max-width:600px;">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Họ tên <span style="color:#ef4444;">*</span></label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="width:100%;">
            @error('name')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Email <span style="color:#ef4444;">*</span></label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required style="width:100%;">
            @error('email')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Mã người dùng</label>
            <input type="text" name="code" value="{{ old('code', $user->code) }}" style="width:100%;">
            @error('code')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Vai trò <span style="color:#ef4444;">*</span></label>
            <select name="role" required style="width:100%;">
                <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Sinh viên</option>
                <option value="lecturer" {{ old('role', $user->role) === 'lecturer' ? 'selected' : '' }}>Giảng viên</option>
                <option value="faculty_admin" {{ old('role', $user->role) === 'faculty_admin' ? 'selected' : '' }}>Quản trị khoa</option>
                <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Quản trị hệ thống</option>
            </select>
            @error('role')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Khoa</label>
            <select name="faculty_id" style="width:100%;">
                <option value="">-- Không thuộc khoa nào --</option>
                @foreach($faculties as $faculty)
                <option value="{{ $faculty->id }}" {{ old('faculty_id', $user->faculty_id) == $faculty->id ? 'selected' : '' }}>
                    {{ $faculty->name }}
                </option>
                @endforeach
            </select>
            @error('faculty_id')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Khóa học (chỉ dành cho sinh viên)</label>
            <select name="class_cohort" style="width:100%;">
                <option value="">-- Chọn khóa học --</option>
                @foreach($cohorts as $cohort)
                <option value="{{ $cohort }}" {{ old('class_cohort', $user->class_cohort) === $cohort ? 'selected' : '' }}>{{ $cohort }}</option>
                @endforeach
            </select>
            <div style="color:#64748b;font-size:12px;margin-top:4px;">Nếu khóa học chưa có trong danh sách, hãy tạo sinh viên khóa đó trước</div>
            @error('class_cohort')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="display:flex;gap:12px;margin-top:24px;">
            <button type="submit" style="background:#0ea5e9;color:#fff;padding:10px 20px;border-radius:8px;cursor:pointer;border:none;">Cập nhật</button>
            <a href="{{ route('admin.users') }}" style="background:#475569;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;display:inline-block;">Hủy</a>
        </div>
    </form>
</div>
@endsection