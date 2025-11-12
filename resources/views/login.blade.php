<!DOCTYPE html>
<html lang='vi'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Đăng Nhập</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px
        }

        .login-container {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .3);
            max-width: 450px;
            width: 100%;
            padding: 50px 40px
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center
        }

        .logo svg {
            width: 40px;
            height: 40px;
            fill: #fff
        }

        h2 {
            text-align: center;
            color: #333;
            font-size: 28px;
            margin-bottom: 10px
        }

        .subtitle {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 30px
        }

        .form-group {
            margin-bottom: 25px
        }

        label {
            display: block;
            color: #333;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px
        }

        .required {
            color: #f44
        }

        .input-wrapper {
            position: relative
        }

        .input-wrapper svg {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            fill: #999
        }

        input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            outline: none
        }

        input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, .1)
        }

        input.error {
            border-color: #f44
        }

        .field-error {
            color: #f44;
            font-size: 12px;
            margin-top: 5px;
            display: none
        }

        .field-error.show {
            display: block
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 14px
        }

        .remember-me {
            display: flex;
            align-items: center;
            color: #666
        }

        .remember-me input[type=checkbox] {
            width: auto;
            margin-right: 8px
        }

        .forgot-password {
            color: #667eea;
            text-decoration: none;
            font-weight: 600
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer
        }

        .btn-submit:disabled {
            opacity: .6;
            cursor: not-allowed
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            color: #666;
            font-size: 14px
        }

        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600
        }

        .back-home {
            text-align: center;
            margin-top: 20px
        }

        .back-home a {
            color: #999;
            text-decoration: none;
            font-size: 14px
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #c33
        }

        .success-message {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #2e7d32;
            font-size: 14px
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer
        }
    </style>
</head>

<body>
    <div class='login-container'>
        <div class='logo'><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'>
                <path d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z' />
            </svg></div>
        <h2>Đăng Nhập</h2>
        <p class='subtitle'>Hệ Thống Đăng Ký Tín Chỉ</p>

        @if(session('status'))
        <div class='success-message'>{{ session('status') }}</div>
        @endif

        @if(session('error'))
        <div class='error-message'>{{ session('error') }}</div>
        @endif

        @if($errors->any())
        <div class='error-message'>
            <ul style="margin:0;padding-left:20px;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST" id="loginForm">
            @csrf
            <div class='form-group'><label for='email'>Email hoặc MSSV <span class='required'>*</span></label>
                <div class='input-wrapper'><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'>
                        <path d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z' />
                    </svg>
                    <input type='text' id='email' name='email' placeholder='Nhập email hoặc MSSV' value="{{ old('email') }}" class="{{ $errors->has('email') ? 'error' : '' }}">
                </div>
                @error('email')
                <div class='field-error show'>{{ $message }}</div>
                @else
                <div class='field-error' id='emailError'></div>
                @enderror
            </div>
            <div class='form-group'><label for='password'>Mật khẩu <span class='required'>*</span></label>
                <div class='input-wrapper'><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'>
                        <path d='M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z' />
                    </svg>
                    <input type='password' id='password' name='password' placeholder='Mật khẩu (tối thiểu 6 ký tự)' class="{{ $errors->has('password') ? 'error' : '' }}">
                    <span class='toggle-password' onclick='togglePassword()'></span>
                </div>
                @error('password')
                <div class='field-error show'>{{ $message }}</div>
                @else
                <div class='field-error' id='passwordError'></div>
                @enderror
            </div>
            <div class='remember-forgot'><label class='remember-me'><input type='checkbox' name='remember'>Ghi nhớ đăng nhập</label>
                <a href="{{ route('password.request') }}" class='forgot-password'>Quên mật khẩu?</a>
            </div>
            <button type='submit' class='btn-submit' id='submitBtn'>Đăng Nhập</button>
        </form>
        <div class='register-link'>Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a></div>
        <div class='back-home'><a href="{{ route('home') }}">← Quay lại trang chủ</a></div>
    </div>
    <script>
        const e = document.getElementById('email'),
            p = document.getElementById('password'),
            ee = document.getElementById('emailError'),
            pe = document.getElementById('passwordError'),
            f = document.getElementById('loginForm');

        function ve() {
            // Skip validation if server already showed error
            if (!ee) return true;

            const v = e.value.trim();
            if (v === '') {
                e.classList.add('error');
                ee.textContent = 'Vui lòng nhập email hoặc MSSV.';
                ee.classList.add('show');
                return false
            }
            if (v.includes('@') && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) {
                e.classList.add('error');
                ee.textContent = 'Email không đúng định dạng.';
                ee.classList.add('show');
                return false
            }
            e.classList.remove('error');
            ee.classList.remove('show');
            return true
        }

        function vp() {
            // Skip validation if server already showed error
            if (!pe) return true;

            const v = p.value;
            if (v === '') {
                p.classList.add('error');
                pe.textContent = 'Vui lòng nhập mật khẩu.';
                pe.classList.add('show');
                return false
            }
            if (v.length < 6) {
                p.classList.add('error');
                pe.textContent = 'Mật khẩu phải có ít nhất 6 ký tự.';
                pe.classList.add('show');
                return false
            }
            p.classList.remove('error');
            pe.classList.remove('show');
            pe.classList.remove('show');
            return true
        }
        e.addEventListener('blur', ve);
        p.addEventListener('blur', vp);
        e.addEventListener('input', () => e.classList.remove('error'));
        p.addEventListener('input', () => p.classList.remove('error'));
        f.addEventListener('submit', function(ev) {
            ev.preventDefault();
            if (ve() && vp()) {
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('submitBtn').textContent = 'Đang đăng nhập...';
                this.submit()
            }
        });

        function togglePassword() {
            const t = p.type === 'password' ? 'text' : 'password';
            p.type = t;
            document.querySelector('.toggle-password').textContent = t === 'password' ? '' : ''
        }
    </script>
</body>

</html>