@extends('admin.layout')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<div class="card" style="max-width:720px;">
    <h2 style="margin-top:0;">Hồ sơ cá nhân</h2>
    <p style="color:#94a3b8; margin-top:4px;">Cập nhật thông tin tài khoản quản trị của bạn.</p>

    @if(session('success'))
    <div class="flash">{{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="flash error">
        <ul style="margin:0; padding-left:18px;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.profile.update') }}" method="POST" style="display:grid; gap:12px; margin-top:12px;">
        @csrf
        <label>
            <span style="font-size:12px;color:#94a3b8;display:block;">Họ tên</span>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
        </label>
        <label>
            <span style="font-size:12px;color:#94a3b8;display:block;">Email</span>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </label>

        {{-- BẮT ĐẦU: KHỐI THAY ĐỔI MẬT KHẨU --}}
        <div style="margin-top: 2rem; padding: 1.5rem; border: 1px solid #e2e8f0; border-radius: 8px; background: #fff;">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin-top: 0; margin-bottom: 0.5rem;">
                Đổi mật khẩu
            </h3>
            <p style="margin-top: 0; margin-bottom: 1.5rem; color: #64748b;">
                Để trống các trường bên dưới nếu bạn không muốn thay đổi mật khẩu.
            </p>

            {{-- Trường Mật khẩu mới --}}
            <div style="margin-bottom: 1rem;">
                <label for="password" style="display: block; font-weight: 500; margin-bottom: 0.25rem;">
                    Mật khẩu mới
                </label>
                <input type="password" id="password" name="password"
                    style="width: 100%; border: 1px solid #cbd5e1; border-radius: 4px; padding: 0.5rem 0.75rem;"
                    autocomplete="new-password">

                {{-- Hiển thị lỗi validation --}}
                @error('password')
                <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Trường Xác nhận Mật khẩu mới --}}
            <div style="margin-bottom: 1rem;">
                <label for="password_confirmation" style="display: block; font-weight: 500; margin-bottom: 0.25rem;">
                    Xác nhận mật khẩu mới
                </label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    style="width: 100%; border: 1px solid #cbd5e1; border-radius: 4px; padding: 0.5rem 0.75rem;"
                    autocomplete="new-password">
            </div>
        </div>
        {{-- KẾT THÚC: KHỐI THAY ĐỔI MẬT KHẨU --}}

        <div style="margin-top: 1.5rem;">
            <button type="submit"
                style="background-color: #3b82f6; color: white; font-weight: 600; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer;">
                Cập nhật thông tin
            </button>
        </div>
    </form>
</div>
@endsection