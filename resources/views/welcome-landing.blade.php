<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Lý Đăng Ký Tín Chỉ</title>
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
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .welcome-container {
            background: white;
            border-radius: 20px;
            padding: 60px 50px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .logo-circle {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .logo-circle svg {
            width: 60px;
            height: 60px;
            fill: white;
        }

        h1 {
            color: #2d3748;
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        h2 {
            color: #667eea;
            font-size: 22px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .description {
            color: #718096;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 16px 50px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .features {
            display: flex;
            justify-content: space-around;
            margin-top: 50px;
            gap: 30px;
        }

        .feature-item {
            flex: 1;
            text-align: center;
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: #edf2f7;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
        }

        .feature-icon svg {
            width: 28px;
            height: 28px;
            fill: #667eea;
        }

        .feature-title {
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .feature-desc {
            font-size: 12px;
            color: #718096;
        }
    </style>
</head>

<body>
    <div class="welcome-container">
        <div class="logo-circle">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 3L1 9L12 15L21 10.09V17H23V9M5 13.18V17.18L12 21L19 17.18V13.18L12 17L5 13.18Z" />
            </svg>
        </div>

        <h1>Chào Mừng Đến Với</h1>
        <h2>Hệ Thống Quản Lý Đăng Ký Tín Chỉ</h2>

        <p class="description">
            Đăng ký môn học, quản lý tín chỉ một cách dễ dàng, nhanh chóng và hiệu quả.
            Đăng nhập để bắt đầu đăng ký học phần của bạn!
        </p>

        <a href="{{ route('login') }}" class="btn-login">Đăng Nhập Ngay</a>

        <div class="features">
            <div class="feature-item">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M19,4H18V2H16V4H8V2H6V4H5A2,2 0 0,0 3,6V20A2,2 0 0,0 5,22H19A2,2 0 0,0 21,20V6A2,2 0 0,0 19,4M19,20H5V10H19V20M19,8H5V6H19V8Z" />
                    </svg>
                </div>
                <div class="feature-title">Đăng Ký Môn Học</div>
                <div class="feature-desc">Nhanh chóng, tiện lợi</div>
            </div>

            <div class="feature-item">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M16.2,16.2L11,13V7H12.5V12.2L17,14.9L16.2,16.2Z" />
                    </svg>
                </div>
                <div class="feature-title">Quản Lý Lịch Học</div>
                <div class="feature-desc">Xem thời khóa biểu</div>
            </div>

            <div class="feature-item">
                <div class="feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M9,10A3.04,3.04 0 0,1 12,7A3.04,3.04 0 0,1 15,10A3.04,3.04 0 0,1 12,13A3.04,3.04 0 0,1 9,10M12,19L16,20V16.92A7.54,7.54 0 0,1 12,18A7.54,7.54 0 0,1 8,16.92V20M12,4A5.78,5.78 0 0,0 7.76,5.74A5.78,5.78 0 0,0 6,10A5.78,5.78 0 0,0 7.76,14.23A5.78,5.78 0 0,0 12,16A5.78,5.78 0 0,0 16.24,14.23A5.78,5.78 0 0,0 18,10A5.78,5.78 0 0,0 16.24,5.74A5.78,5.78 0 0,0 12,4M20,10A8.04,8.04 0 0,1 19.43,12.8A7.87,7.87 0 0,1 18,15.28V23L12,21L6,23V15.28A7.87,7.87 0 0,1 4.57,12.8A8.04,8.04 0 0,1 4,10A8.04,8.04 0 0,1 4.57,7.2A7.87,7.87 0 0,1 6,4.72V1L12,3L18,1V4.72A7.87,7.87 0 0,1 19.43,7.2A8.04,8.04 0 0,1 20,10Z" />
                    </svg>
                </div>
                <div class="feature-title">Theo Dõi Tín Chỉ</div>
                <div class="feature-desc">Quản lý điểm số</div>
            </div>
        </div>
    </div>
</body>

</html>