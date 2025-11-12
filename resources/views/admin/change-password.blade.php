@extends('admin.layout')

@section('title','Thay đổi mật khẩu')

@section('content')
<div class="card" style="max-width:560px;">
    <h3 style="margin-top:0;">Thay đổi mật khẩu</h3>
    <p style="color:#64748b;margin:6px 0 16px;">Vì an toàn, hãy đặt mật khẩu mạnh và khác với mật khẩu cũ.</p>

    @if(session('status'))
    <div class="flash">{{ session('status') }}</div>
    @endif
    @if(session('error'))
    <div class="flash error">{{ session('error') }}</div>
    @endif
    @if($errors->any())
    <div class="flash error">
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
            <span style="font-size:12px;color:#94a3b8;display:block;">Mật khẩu hiện tại</span>
            <input type="password" name="current_password" required autocomplete="current-password">
        </label>
        <label>
            <span style="font-size:12px;color:#94a3b8;display:block;">Mật khẩu mới</span>
            <input type="password" name="password" required autocomplete="new-password">
            <small style="color:#94a3b8;">Ít nhất 8 ký tự, nên có chữ hoa, chữ thường, số và ký tự đặc biệt.</small>
        </label>
        <label>
            <span style="font-size:12px;color:#94a3b8;display:block;">Xác nhận mật khẩu mới</span>
            <input type="password" name="password_confirmation" required autocomplete="new-password">
        </label>

        <div style="margin-top:6px; display:flex; gap:10px;">
            <button type="submit">Cập nhật</button>
            <a href="{{ route('admin.dashboard') }}" style="align-self:center;">Hủy</a>
        </div>
    </form>
</div>
@endsection