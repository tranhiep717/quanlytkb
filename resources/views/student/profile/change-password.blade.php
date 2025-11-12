@extends('student.layout')

@section('title','Thay đổi mật khẩu')

@section('content')
<div class="card" style="max-width:720px;">
    <h2 style="margin-top:0;">Thay đổi mật khẩu</h2>
    <p class="muted" style="margin:6px 0 16px;">Mật khẩu mạnh: ≥ 8 ký tự, gồm chữ hoa, chữ thường, số và ký tự đặc biệt.</p>

    @if(session('status'))
    <div class="status success">{{ session('status') }}</div>
    @endif
    @if(session('error'))
    <div class="status error">{{ session('error') }}</div>
    @endif
    @if($errors->any())
    <div class="status error">
        <ul style="margin:0; padding-left:18px;">
            @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('password.change.submit') }}" style="display:grid; gap:12px;">
        @csrf
        <label>
            <span class="muted" style="display:block;">Mật khẩu hiện tại</span>
            <input type="password" name="current_password" required autocomplete="current-password">
        </label>
        <label>
            <span class="muted" style="display:block;">Mật khẩu mới</span>
            <input type="password" name="password" required autocomplete="new-password">
            <small class="muted">Ít nhất 8 ký tự, nên có chữ hoa, chữ thường, số và ký tự đặc biệt.</small>
        </label>
        <label>
            <span class="muted" style="display:block;">Xác nhận mật khẩu mới</span>
            <input type="password" name="password_confirmation" required autocomplete="new-password">
        </label>
        <div style="margin-top:6px; display:flex; gap:10px;">
            <button class="btn" type="submit">Cập nhật mật khẩu</button>
            <a href="{{ route('student.dashboard') }}" style="align-self:center;">Hủy</a>
        </div>
    </form>
</div>
@endsection