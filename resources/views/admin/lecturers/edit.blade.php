@extends('admin.layout')

@section('title', 'Sửa Giảng viên')

@section('content')
<div style="background:white; padding:24px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1); max-width:800px;">
    <h2 style="margin:0 0 24px 0; font-size:20px; font-weight:600; color:#1e293b;">Sửa thông tin Giảng viên</h2>

    <form action="{{ route('lecturers.update', $lecturer) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569;">
                Mã giảng viên <span style="color:#dc2626;">*</span>
            </label>
            <input
                type="text"
                name="code"
                value="{{ old('code', $lecturer->code) }}"
                required
                style="width:100%; padding:10px; border:1px solid {{ $errors->has('code') ? '#dc2626' : '#cbd5e0' }}; border-radius:6px;">
            @error('code')
            <span style="color:#dc2626; font-size:14px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569;">
                Họ và tên <span style="color:#dc2626;">*</span>
            </label>
            <input
                type="text"
                name="name"
                value="{{ old('name', $lecturer->name) }}"
                required
                style="width:100%; padding:10px; border:1px solid {{ $errors->has('name') ? '#dc2626' : '#cbd5e0' }}; border-radius:6px;">
            @error('name')
            <span style="color:#dc2626; font-size:14px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569;">
                Email <span style="color:#dc2626;">*</span>
            </label>
            <input
                type="email"
                name="email"
                value="{{ old('email', $lecturer->email) }}"
                required
                style="width:100%; padding:10px; border:1px solid {{ $errors->has('email') ? '#dc2626' : '#cbd5e0' }}; border-radius:6px;">
            @error('email')
            <span style="color:#dc2626; font-size:14px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569;">
                Khoa <span style="color:#dc2626;">*</span>
            </label>
            <select
                name="faculty_id"
                required
                style="width:100%; padding:10px; border:1px solid {{ $errors->has('faculty_id') ? '#dc2626' : '#cbd5e0' }}; border-radius:6px;">
                <option value="">-- Chọn Khoa --</option>
                @foreach($faculties as $faculty)
                <option value="{{ $faculty->id }}" {{ old('faculty_id', $lecturer->faculty_id) == $faculty->id ? 'selected' : '' }}>
                    {{ $faculty->name }}
                </option>
                @endforeach
            </select>
            @error('faculty_id')
            <span style="color:#dc2626; font-size:14px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569;">
                Học vị
            </label>
            <input
                type="text"
                name="degree"
                value="{{ old('degree', $lecturer->degree) }}"
                style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px;"
                placeholder="Ví dụ: TS., ThS., PGS.TS.">
            @error('degree')
            <span style="color:#dc2626; font-size:14px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569;">
                Số điện thoại
            </label>
            <input
                type="text"
                name="phone"
                value="{{ old('phone', $lecturer->phone) }}"
                style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px;">
            @error('phone')
            <span style="color:#dc2626; font-size:14px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569;">
                Mật khẩu mới (để trống nếu không đổi)
            </label>
            <input
                type="password"
                name="password"
                style="width:100%; padding:10px; border:1px solid {{ $errors->has('password') ? '#dc2626' : '#cbd5e0' }}; border-radius:6px;"
                placeholder="Tối thiểu 6 ký tự">
            @error('password')
            <span style="color:#dc2626; font-size:14px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="display:flex; gap:12px; margin-top:24px;">
            <button type="submit" style="background:#1976d2; color:white; padding:12px 24px; border:none; border-radius:6px; cursor:pointer; font-weight:500;">
                Cập nhật
            </button>
            <a href="{{ route('lecturers.index') }}" style="background:#e2e8f0; color:#475569; padding:12px 24px; border-radius:6px; text-decoration:none; font-weight:500; display:inline-block;">
                Hủy
            </a>
        </div>
    </form>
</div>
@endsection
