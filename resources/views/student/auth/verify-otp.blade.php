@extends('student.layout')

@section('title','Xác minh OTP (Sinh viên)')

@section('content')
<div class="card">
    <h3>Xác minh OTP</h3>
    <p class="muted">Nhập MSSV, email và mã OTP để đặt lại mật khẩu.</p>

    @if(session('info'))
    <div class="alert" style="background:#dcfce7;color:#166534;border:1px solid #86efac;padding:12px;border-radius:6px;margin-bottom:16px;">
        ℹ️ {{ session('info') }}
    </div>
    @endif

    @if(session('status'))
    <div class="alert" style="background:#dcfce7;color:#166534;border:1px solid #86efac;padding:12px;border-radius:6px;margin-bottom:16px;">
        ✅ {{ session('status') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="alert" style="background:#fee2e2;color:#991b1b;">
        <ul style="margin:0;padding-left:1rem;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form method="POST" action="{{ route('student.verifyOtp') }}" style="display:grid;gap:.75rem;">
        @csrf
        <div>
            <label for="code">MSSV</label>
            <input id="code" type="text" name="code" value="{{ old('code', request('code')) }}" required />
        </div>
        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', request('email')) }}" required />
        </div>
        <div>
            <label for="otp">Mã OTP</label>
            <input id="otp" type="text" name="otp" value="{{ old('otp') }}" required />
        </div>
        <div>
            <label for="password">Mật khẩu mới</label>
            <input id="password" type="password" name="password" required />
            <small class="muted">Tối thiểu 8 ký tự, gồm chữ hoa, thường, số.</small>
        </div>
        <div>
            <label for="password_confirmation">Xác nhận mật khẩu</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required />
        </div>
        <button class="btn" type="submit">Đặt lại mật khẩu</button>
    </form>
</div>
@endsection