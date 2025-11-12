<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Quản Lý Đăng Ký Tín Chỉ</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar h1 {
            font-size: 24px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 20px;
            border: 2px solid white;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 14px;
        }

        .logout-btn:hover {
            background: white;
            color: #667eea;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .welcome-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .welcome-card h2 {
            color: #333;
            margin-bottom: 10px;
        }

        .welcome-card p {
            color: #666;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            color: #667eea;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .stat-card p {
            color: #666;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <h1>🎓 Hệ Thống Đăng Ký Tín Chỉ</h1>
        <div class="user-info">
            <span>Xin chào, {{ Auth::user()->name ?? 'User' }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Đăng xuất</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-card">
            <h2>Chào mừng đến với Hệ Thống Đăng Ký Tín Chỉ!</h2>
            <p>Đăng ký và quản lý các môn học, tín chỉ của bạn một cách dễ dàng và hiệu quả.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>0</h3>
                <p>Môn học đã đăng ký</p>
            </div>

            <div class="stat-card">
                <h3>0/0</h3>
                <p>Tín chỉ đã đăng ký</p>
            </div>

            <div class="stat-card">
                <h3>0</h3>
                <p>Môn học còn trống</p>
            </div>

            <div class="stat-card">
                <h3>0.00</h3>
                <p>Điểm trung bình (GPA)</p>
            </div>
        </div>

        <div class="welcome-card">
            <h2>� Đăng Ký Môn Học</h2>
            <p>Hệ thống đã sẵn sàng. Bạn có thể bắt đầu đăng ký các môn học cho học kỳ này.</p>
            <div style="margin-top: 20px;">
                <button style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 30px; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; margin-right: 10px;">
                    Đăng Ký Môn Học
                </button>
                <button style="background: white; color: #667eea; padding: 12px 30px; border: 2px solid #667eea; border-radius: 8px; font-size: 16px; cursor: pointer;">
                    Xem Thời Khóa Biểu
                </button>
            </div>
        </div>
    </div>
</body>

</html>