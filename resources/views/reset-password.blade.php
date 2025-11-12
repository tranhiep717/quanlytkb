<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thiết Lập Lại Mật Khẩu - Hệ Thống Đăng Ký Tín Chỉ</title>
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

        .reset-container {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .3);
            max-width: 500px;
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
            margin-bottom: 30px;
            line-height: 1.6
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

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            font-size: 20px;
            user-select: none
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
            cursor: pointer;
            transition: transform 0.2s
        }

        .btn-submit:hover {
            transform: translateY(-2px)
        }

        .btn-submit:disabled {
            opacity: .6;
            cursor: not-allowed;
            transform: none
        }

        .back-login {
            text-align: center;
            margin-top: 25px
        }

        .back-login a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #c33;
            font-size: 14px
        }

        .password-requirements {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px
        }

        .password-requirements h4 {
            color: #333;
            font-size: 14px;
            margin-bottom: 10px
        }

        .password-requirements ul {
            list-style: none;
            padding: 0
        }

        .password-requirements li {
            color: #666;
            font-size: 13px;
            margin-bottom: 5px;
            padding-left: 20px;
            position: relative
        }

        .password-requirements li:before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #4caf50;
            font-weight: bold
        }

        @media (max-width: 480px) {
            .reset-container {
                padding: 40px 30px
            }
        }
    </style>
</head>

<body>
    <div class="reset-container">
        <div class="logo">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
            </svg>
        </div>

        <h2>Thiết Lập Lại Mật Khẩu</h2>
        <p class="subtitle">Vui lòng nhập mật khẩu mới cho tài khoản của bạn</p>

        @if(session('error'))
        <div class="error-message">
            {{ session('error') }}
        </div>
        @endif

        @if($errors->any())
        <div class="error-message">
            @foreach ($errors->all() as $error)
            {{ $error }}<br>
            @endforeach
        </div>
        @endif

        <div class="password-requirements">
            <h4>Yêu cầu mật khẩu:</h4>
            <ul>
                <li>Tối thiểu 8 ký tự</li>
                <li>Bao gồm chữ hoa và chữ thường</li>
                <li>Bao gồm ít nhất một số</li>
                <li>Bao gồm ít nhất một ký tự đặc biệt</li>
                <li>Hai mật khẩu phải trùng khớp</li>
            </ul>
        </div>

        <form action="{{ route('password.update') }}" method="POST" id="resetForm">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group">
                <label for="password">Mật Khẩu Mới <span class="required">*</span></label>
                <div class="input-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                    </svg>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu mới (tối thiểu 8 ký tự)" required>
                    <span class="toggle-password" onclick="togglePassword('password')">👁</span>
                </div>
                <div class="field-error" id="passwordError"></div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Xác Nhận Mật Khẩu <span class="required">*</span></label>
                <div class="input-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                    </svg>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu mới" required>
                    <span class="toggle-password" onclick="togglePassword('password_confirmation')">👁</span>
                </div>
                <div class="field-error" id="confirmError"></div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                Thiết Lập Lại Mật Khẩu
            </button>
        </form>

        <div class="back-login">
            <a href="{{ route('login') }}">← Quay lại đăng nhập</a>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const passwordError = document.getElementById('passwordError');
        const confirmError = document.getElementById('confirmError');
        const form = document.getElementById('resetForm');
        const submitBtn = document.getElementById('submitBtn');

        function validatePassword() {
            const password = passwordInput.value;

            if (password === '') {
                passwordInput.classList.add('error');
                passwordError.textContent = 'Vui lòng nhập mật khẩu mới.';
                passwordError.classList.add('show');
                return false;
            }

            if (password.length < 8) {
                passwordInput.classList.add('error');
                passwordError.textContent = 'Mật khẩu phải có ít nhất 8 ký tự.';
                passwordError.classList.add('show');
                return false;
            }

            passwordInput.classList.remove('error');
            passwordError.classList.remove('show');
            return true;
        }

        function validateConfirm() {
            const password = passwordInput.value;
            const confirm = confirmInput.value;

            if (confirm === '') {
                confirmInput.classList.add('error');
                confirmError.textContent = 'Vui lòng xác nhận mật khẩu.';
                confirmError.classList.add('show');
                return false;
            }

            if (password !== confirm) {
                confirmInput.classList.add('error');
                confirmError.textContent = 'Mật khẩu xác nhận không trùng khớp.';
                confirmError.classList.add('show');
                return false;
            }

            confirmInput.classList.remove('error');
            confirmError.classList.remove('show');
            return true;
        }

        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const toggle = field.nextElementSibling;

            if (field.type === 'password') {
                field.type = 'text';
                toggle.textContent = '🙈';
            } else {
                field.type = 'password';
                toggle.textContent = '👁';
            }
        }

        passwordInput.addEventListener('blur', validatePassword);
        confirmInput.addEventListener('blur', validateConfirm);

        passwordInput.addEventListener('input', function() {
            passwordInput.classList.remove('error');
            passwordError.classList.remove('show');
        });

        confirmInput.addEventListener('input', function() {
            confirmInput.classList.remove('error');
            confirmError.classList.remove('show');
        });

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const isPasswordValid = validatePassword();
            const isConfirmValid = validateConfirm();

            if (isPasswordValid && isConfirmValid) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Đang xử lý...';
                this.submit();
            }
        });
    </script>
</body>

</html>