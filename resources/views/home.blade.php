<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Quản lý Thời Khóa Biểu</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            padding: 60px 40px;
            text-align: center;
            animation: fadeIn 0.6s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .logo svg {
            width: 60px;
            height: 60px;
            fill: white;
        }

        h1 {
            color: #333;
            font-size: 32px;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .welcome-text {
            color: #666;
            font-size: 18px;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 50px;
            border: none;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            text-decoration: none;
            display: inline-block;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .features {
            margin-top: 50px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
        }

        .feature-item {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            background: #e9ecef;
            transform: translateY(-5px);
        }

        .feature-item svg {
            width: 40px;
            height: 40px;
            margin-bottom: 10px;
            fill: #667eea;
        }

        .feature-item h3 {
            color: #333;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .feature-item p {
            color: #666;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 40px 30px;
            }

            h1 {
                font-size: 26px;
            }

            .welcome-text {
                font-size: 16px;
            }

            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z" />
            </svg>
        </div>

        <h1>Chào Mừng Đến Với</h1>
        <h1
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 20px;">
            Hệ Thống Quản Lý Đăng Ký Tín Chỉ
        </h1>

        <p class="welcome-text">
            Đăng ký môn học, quản lý tín chỉ một cách dễ dàng, nhanh chóng và hiệu quả.
            Đăng nhập để bắt đầu đăng ký học phần của bạn!
        </p>

        @auth
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('dashboard') }}" class="btn-login">
                Vào Bảng Điều Khiển
            </a>
            <form action="{{ route('logout') }}" method="POST" style="display: inline-block;">
                @csrf
                <button type="submit" class="btn-login" style="background: linear-gradient(135deg, #475569 0%, #334155 100%);">
                    Đăng Xuất
                </button>
            </form>
        </div>
        @else
        <a href="{{ route('login') }}" class="btn-login">
            Đăng Nhập Ngay
        </a>
        @endauth

        <div class="features">
            <div class="feature-item">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z" />
                </svg>
                <h3>Đăng Ký Môn Học</h3>
                <p>Nhanh chóng, tiện lợi</p>
            </div>

            <div class="feature-item">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path
                        d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z" />
                </svg>
                <h3>Quản Lý Lịch Học</h3>
                <p>Xem thời khóa biểu</p>
            </div>

            <div class="feature-item">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                </svg>
                <h3>Theo Dõi Tín Chỉ</h3>
                <p>Quản lý điểm số</p>
            </div>
        </div>
    </div>
</body>

</html>