<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Quản trị</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: radial-gradient(circle at 20% 20%, #0ea5e920 0, transparent 40%), linear-gradient(135deg, #0f172a, #1e293b 50%, #0b1220);
            font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        }

        .card {
            width: 100%;
            max-width: 420px;
            background: rgba(2, 6, 23, .75);
            border: 1px solid rgba(148, 163, 184, .25);
            border-radius: 16px;
            padding: 24px;
            color: #e2e8f0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .4);
        }

        h1 {
            margin: 0 0 12px;
            font-size: 22px;
        }

        label {
            display: block;
            margin: 10px 0 6px;
            color: #94a3b8;
            font-size: 13px;
        }

        input[type="email"],
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid rgba(148, 163, 184, .25);
            background: #0b1220;
            color: #e2e8f0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
        }

        .btn {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: 0;
            background: linear-gradient(135deg, #0ea5e9, #22d3ee);
            color: #0b1220;
            font-weight: 700;
            cursor: pointer;
            margin-top: 12px;
        }

        .link {
            color: #8bd3ff;
            text-decoration: none;
            font-size: 13px;
        }

        .flash {
            margin: 8px 0;
            padding: 10px 12px;
            border-radius: 8px;
            background: rgba(239, 68, 68, .12);
            border: 1px solid rgba(239, 68, 68, .45);
            color: #fecaca;
        }

        ul.errors {
            margin: 8px 0;
            padding-left: 18px;
            color: #fecaca;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        // submit on Enter
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('login-form');
            form.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    form.submit();
                }
            });
        });
    </script>
    <!-- Minimal CSP note: inline styles/scripts for demo; consider moving to separate assets in production -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' 'unsafe-inline' data:; img-src 'self' data:; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline'">
    <meta name="referrer" content="no-referrer">
    <meta name="color-scheme" content="dark light">
    <meta name="robots" content="noindex,nofollow">
    <meta name="description" content="Đăng nhập khu vực Quản trị">
    <meta name="x-ua-compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="#0f172a">
    <meta name="supports-color-scheme" content="dark">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- security headers (server-side recommended): X-Frame-Options, X-Content-Type-Options, Referrer-Policy -->
    <noscript>Vui lòng bật JavaScript để sử dụng trang này.</noscript>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- performance: preload not necessary here -->
    <!-- a11y: ensure contrast and labels -->
    <!-- Note: Demo-only assets kept inline for simplicity. Extract to Vite pipeline later. -->
    <!-- Minimal track: no analytics -->
    <!-- End meta -->
    <!-- Security/privacy best-effort inline hints above. -->
    <script>
        /* no-op placeholder to avoid empty script CSP warning */
    </script>

</head>

<body>
    <div class="card">
        <h1>Đăng nhập Quản trị</h1>
        <p style="margin:0 0 16px; color:#94a3b8; font-size:14px;">Chỉ dành cho Super Admin hoặc Giáo vụ Khoa.</p>

        @if(session('error'))
        <div class="flash">{{ session('error') }}</div>
        @endif
        @if(session('status'))
        <div class="flash" style="background: rgba(16,185,129,.12); border-color: rgba(16,185,129,.45); color:#c8facc;">{{ session('status') }}</div>
        @endif
        @if($errors->any())
        <ul class="errors">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif

        <form id="login-form" action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <label for="email">Email hoặc Tên đăng nhập</label>
            <input id="email" name="email" type="text" value="{{ old('email') }}" autocomplete="username" required>

            <label for="password">Mật khẩu</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>

            <div class="row" style="margin-top:10px;">
                <a class="link" href="{{ route('password.request') }}">Quên mật khẩu?</a>
                <a class="link" href="{{ route('home') }}">Về trang chủ</a>
            </div>

            <button class="btn" type="submit">Đăng nhập</button>
        </form>
    </div>
</body>

</html>
