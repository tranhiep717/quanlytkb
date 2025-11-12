<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu - Hệ Thống Đăng Ký Tín Chỉ</title>
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

        .forgot-container {
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

        .success-message {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #2e7d32;
            font-size: 14px
        }

        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px
        }

        .info-box p {
            color: #1565c0;
            font-size: 13px;
            line-height: 1.6;
            margin: 0
        }

        .info-box svg {
            width: 16px;
            height: 16px;
            fill: #2196f3;
            margin-right: 8px;
            vertical-align: middle
        }

        @media (max-width: 480px) {
            .forgot-container {
                padding: 40px 30px
            }
        }
    </style>
</head>

<body>
    <div class="forgot-container">
        <div class="logo">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
            </svg>
        </div>

        <h2>Quên Mật Khẩu?</h2>
        <p class="subtitle">Nhập email của bạn để nhận liên kết thiết lập lại mật khẩu</p>

        <!-- UC1.2 Bước 5b: Hiển thị thông báo thành công -->
        @if(session('status'))
        <div class="success-message">
            {{ session('status') }}
        </div>
        @endif

        <!-- UC1.2 Bước 5a: Hiển thị thông báo lỗi -->
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

        <!-- UC1.2: Thông tin quan trọng -->
        <div class="info-box">
            <p>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                </svg>
                Liên kết thiết lập lại mật khẩu sẽ có hiệu lực trong vòng <strong>60 phút</strong> kể từ khi được gửi.
            </p>
        </div>

        <!-- UC1.2 Bước 2: Form thiết lập lại mật khẩu -->
        <form action="{{ route('password.email') }}" method="POST" id="forgotForm">
            @csrf

            <!-- UC1.2 Bước 3: Nhập email -->
            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <div class="input-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                    </svg>
                    <input type="email" id="email" name="email" placeholder="Nhập địa chỉ email của bạn"
                        value="{{ old('email') }}" required>
                </div>
                <div class="field-error" id="emailError"></div>
            </div>

            <!-- UC1.2 Bước 4: Yêu cầu thiết lập lại mật khẩu -->
            <button type="submit" class="btn-submit" id="submitBtn">
                Gửi Liên Kết Thiết Lập Lại
            </button>
        </form>

        <div class="back-login">
            <a href="{{ route('login') }}">← Quay lại đăng nhập</a>
        </div>
    </div>

    <script>
        // UC1.2 Bước 5: Validation phía client
        const emailInput = document.getElementById('email');
        const emailError = document.getElementById('emailError');
        const form = document.getElementById('forgotForm');
        const submitBtn = document.getElementById('submitBtn');

        function validateEmail() {
            const email = emailInput.value.trim();

            if (email === '') {
                emailInput.classList.add('error');
                emailError.textContent = 'Vui lòng nhập địa chỉ email';
                emailError.classList.add('show');
                return false;
            }

            // Kiểm tra định dạng email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                emailInput.classList.add('error');
                emailError.textContent = 'Email không đúng định dạng';
                emailError.classList.add('show');
                return false;
            }

            emailInput.classList.remove('error');
            emailError.classList.remove('show');
            return true;
        }

        emailInput.addEventListener('blur', validateEmail);
        emailInput.addEventListener('input', function() {
            emailInput.classList.remove('error');
            emailError.classList.remove('show');
        });

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            if (validateEmail()) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Đang gửi...';
                this.submit();
            }
        });
    </script>
</body>

</html>