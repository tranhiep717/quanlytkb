@extends('student.layout')

@section('title','Quên mật khẩu (Sinh viên)')

@section('content')
<div class="card">
    <h3>Quên mật khẩu</h3>
    <p class="muted">Nhập MSSV và email đã đăng ký để nhận mã OTP đặt lại mật khẩu.</p>
    @if ($errors->any())
    <div class="alert" style="background:#fee2e2;color:#991b1b;">
        <ul style="margin:0;padding-left:1rem;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form method="POST" action="{{ route('student.sendOtp') }}" style="display:grid;gap:.75rem;">
        @csrf
        <div>
            <label for="code">MSSV</label>
            <input id="code" type="text" name="code" value="{{ old('code') }}" required />
        </div>
        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required />
        </div>
        <button class="btn" type="submit">Gửi mã OTP</button>
    </form>
</div>
@endsection