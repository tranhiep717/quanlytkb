<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi Mật Khẩu</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px
        }

        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
            max-width: 480px;
            width: 100%;
            padding: 36px
        }

        h2 {
            margin: 0 0 8px;
            color: #333
        }

        .subtitle {
            color: #666;
            margin-bottom: 20px;
            font-size: 14px
        }

        .form-group {
            margin-bottom: 18px
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            outline: none;
            font-size: 15px
        }

        input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, .12)
        }

        .btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-weight: 700;
            cursor: pointer
        }

        .msg {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px
        }

        .msg.error {
            background: #fee;
            color: #c33;
            border-left: 4px solid #c33
        }

        .msg.success {
            background: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #2e7d32
        }

        .links {
            text-align: center;
            margin-top: 16px
        }

        .links a {
            color: #667eea;
            text-decoration: none
        }
    </style>
</head>

<body>
    <div class="card">
        <h2>Đổi Mật Khẩu</h2>
        <div class="subtitle">Vì an toàn, hãy đặt mật khẩu mạnh và khác với mật khẩu cũ.</div>

        @if(session('status'))<div class="msg success">{{ session('status') }}</div>@endif
        @if(session('error'))<div class="msg error">{{ session('error') }}</div>@endif
        @if($errors->any())
        <div class="msg error">
            @foreach($errors->all() as $e) {{ $e }}<br>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('password.change.submit') }}">
            @csrf
            <div class="form-group">
                <label for="current_password">Mật khẩu hiện tại</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu mới</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Xác nhận mật khẩu mới</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button class="btn" type="submit">Cập nhật mật khẩu</button>
        </form>

        <div class="links"><a href="{{ route('dashboard') }}">← Quay lại Dashboard</a></div>
    </div>
</body>

</html>