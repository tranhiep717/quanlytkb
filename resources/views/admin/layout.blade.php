<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Hệ Thống Quản Lý Tín Chỉ</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 260px;
            background: #2d3748;
            color: white;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 20px;
            background: #1a202c;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h2 {
            font-size: 18px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 5px;
        }

        .sidebar-header .user-info {
            font-size: 13px;
            color: #a0aec0;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-section {
            margin-bottom: 25px;
        }

        .menu-section-title {
            padding: 0 20px 10px;
            font-size: 11px;
            text-transform: uppercase;
            color: #718096;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .menu-item {
            display: block;
            padding: 12px 20px;
            color: #cbd5e0;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 14px;
            border-left: 3px solid transparent;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            border-left-color: #667eea;
        }

        .menu-item.active {
            background: rgba(102, 126, 234, 0.15);
            color: white;
            border-left-color: #667eea;
            font-weight: 500;
        }

        .menu-item svg {
            width: 18px;
            height: 18px;
            margin-right: 12px;
            vertical-align: middle;
            fill: currentColor;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
        }

        /* Top Bar */
        .top-bar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar h1 {
            font-size: 24px;
            color: #2d3748;
        }

        .top-bar-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .toolbar {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        select,
        button,
        input {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #cbd5e0;
            font-size: 14px;
        }

        button {
            background: #667eea;
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        button:hover {
            background: #5568d3;
        }

        /* Content Area */
        .content {
            padding: 30px;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card h3 {
            font-size: 18px;
            color: #2d3748;
            margin-bottom: 15px;
        }

        /* Alerts */
        .flash {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .flash {
            background: #c6f6d5;
            color: #2f855a;
            border: 1px solid #9ae6b4;
        }

        .flash.error {
            background: #fed7d7;
            color: #c53030;
            border: 1px solid #fc8181;
        }

        a {
            color: #667eea;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .grid {
            display: grid;
            gap: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>🎓 Admin Panel</h2>
            <div class="user-info">{{ auth()->user()->name ?? 'Administrator' }}</div>
        </div>

        <div class="sidebar-menu">
            <!-- Tổng quan -->
            <div class="menu-section">
                <div class="menu-section-title">Tổng quan</div>
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M13,3V9H21V3M13,21H21V11H13M3,21H11V15H3M3,13H11V3H3V13Z" />
                    </svg>
                    Dashboard
                </a>
            </div>

            <!-- Quản lý Người dùng -->
            <div class="menu-section">
                <div class="menu-section-title">Quản lý Người dùng</div>
                <a href="{{ route('admin.users') }}" class="menu-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M16,13C15.71,13 15.38,13 15.03,13.05C16.19,13.89 17,15 17,16.5V19H23V16.5C23,14.17 18.33,13 16,13M8,13C5.67,13 1,14.17 1,16.5V19H15V16.5C15,14.17 10.33,13 8,13M8,11A3,3 0 0,0 11,8A3,3 0 0,0 8,5A3,3 0 0,0 5,8A3,3 0 0,0 8,11M16,11A3,3 0 0,0 19,8A3,3 0 0,0 16,5A3,3 0 0,0 13,8A3,3 0 0,0 16,11Z" />
                    </svg>
                    Quản lý Người dùng
                </a>
            </div>

            <!-- Quản lý Đào tạo -->
            <div class="menu-section">
                <div class="menu-section-title">Quản lý Đào tạo</div>

                <a href="{{ route('faculties.index') }}" class="menu-item {{ request()->routeIs('faculties.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12,3L1,9L5,11.18V17.18L12,21L19,17.18V11.18L21,10.09V17H23V9L12,3M18.82,9L12,12.72L5.18,9L12,5.28L18.82,9M17,16L12,18.72L7,16V12.27L12,15L17,12.27V16Z" />
                    </svg>
                    Khoa
                </a>

                <a href="{{ route('lecturers.index') }}" class="menu-item {{ request()->routeIs('lecturers.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
                    </svg>
                    Giảng viên
                </a>

                <a href="{{ route('courses.index') }}" class="menu-item {{ request()->routeIs('courses.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M19,2L14,6.5V17.5L19,13V2M6.5,5C4.55,5 2.45,5.4 1,6.5V21.16C1,21.41 1.25,21.66 1.5,21.66C1.6,21.66 1.65,21.59 1.75,21.59C3.1,20.94 5.05,20.5 6.5,20.5C8.45,20.5 10.55,20.9 12,22C13.35,21.15 15.8,20.5 17.5,20.5C19.15,20.5 20.85,20.81 22.25,21.56C22.35,21.61 22.4,21.59 22.5,21.59C22.75,21.59 23,21.34 23,21.09V6.5C22.4,6.05 21.75,5.75 21,5.5V19C19.9,18.65 18.7,18.5 17.5,18.5C15.8,18.5 13.35,19.15 12,20V6.5C10.55,5.4 8.45,5 6.5,5Z" />
                    </svg>
                    Học phần
                </a>

                <a href="{{ route('class-sections.index') }}" class="menu-item {{ request()->routeIs('class-sections.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M9,5V9H21V5M9,19H21V15H9M9,14H21V10H9M4,9H8V5H4M4,19H8V15H4M4,14H8V10H4V14Z" />
                    </svg>
                    Lớp học phần
                </a>

                <a href="{{ route('class-sections.assignments') }}" class="menu-item {{ request()->routeIs('class-sections.assignments') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14M19,3H5A2,2 0 0,0 3,5V8H5V5H19V19H5V16H3V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3Z" />
                    </svg>
                    Phân công giảng viên
                </a>

                <a href="{{ route('rooms.index') }}" class="menu-item {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M5,3V21H11V17.5H13V21H19V3H5M7,5H9V7H7V5M11,5H13V7H11V5M15,5H17V7H15V5M7,9H9V11H7V9M11,9H13V11H11V9M15,9H17V11H15V9M7,13H9V15H7V13M11,13H13V15H11V13M15,13H17V15H15V13M7,17H9V19H7V17M15,17H17V19H15V17Z" />
                    </svg>
                    Phòng học
                </a>

                <a href="{{ route('shifts.index') }}" class="menu-item {{ request()->routeIs('shifts.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22C6.47,22 2,17.5 2,12A10,10 0 0,1 12,2M12.5,7V12.25L17,14.92L16.25,16.15L11,13V7H12.5Z" />
                    </svg>
                    Ca học
                </a>
            </div>

            <!-- Quản lý Đăng ký -->
            <div class="menu-section">
                <div class="menu-section-title">Quản lý Đăng ký</div>
                <a href="{{ route('registration-waves.index') }}" class="menu-item {{ request()->routeIs('registration-waves.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M19,19H5V8H19M16,1V3H8V1H6V3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3H18V1M17,12H12V17H17V12Z" />
                    </svg>
                    Cài đặt Kỳ đăng ký
                </a>
            </div>

            <!-- Hệ thống -->
            <div class="menu-section">
                <div class="menu-section-title">Hệ thống</div>

                <a href="{{ route('announcements.index') }}" class="menu-item {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22C17.5,22 22,17.5 22,12A10,10 0 0,0 12,2M11,17H13V19H11V17M11,5H13V15H11V5Z" />
                    </svg>
                    Quản lý Thông báo
                </a>

                <a href="{{ route('admin.reports') }}" class="menu-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M9,17H7V10H9M13,17H11V7H13M17,17H15V13H17M19.5,19.1H4.5V5H19.5M19.5,3H4.5C3.4,3 2.5,3.9 2.5,5V19.1C2.5,20.2 3.4,21.1 4.5,21.1H19.5C20.6,21.1 21.5,20.2 21.5,19.1V5C21.5,3.9 20.6,3 19.5,3Z" />
                    </svg>
                    Báo cáo
                </a>

                <a href="{{ route('admin.logs') }}" class="menu-item {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12,8A4,4 0 0,1 16,12A4,4 0 0,1 12,16A4,4 0 0,1 8,12A4,4 0 0,1 12,8M12,10A2,2 0 0,0 10,12A2,2 0 0,0 12,14A2,2 0 0,0 14,12A2,2 0 0,0 12,10M10,22C9.75,22 9.54,21.82 9.5,21.58L9.13,18.93C8.5,18.68 7.96,18.34 7.44,17.94L4.95,18.95C4.73,19.03 4.46,18.95 4.34,18.73L2.34,15.27C2.21,15.05 2.27,14.78 2.46,14.63L4.57,12.97L4.5,12L4.57,11L2.46,9.37C2.27,9.22 2.21,8.95 2.34,8.73L4.34,5.27C4.46,5.05 4.73,4.96 4.95,5.05L7.44,6.05C7.96,5.66 8.5,5.32 9.13,5.07L9.5,2.42C9.54,2.18 9.75,2 10,2H14C14.25,2 14.46,2.18 14.5,2.42L14.87,5.07C15.5,5.32 16.04,5.66 16.56,6.05L19.05,5.05C19.27,4.96 19.54,5.05 19.66,5.27L21.66,8.73C21.79,8.95 21.73,9.22 21.54,9.37L19.43,11L19.5,12L19.43,13L21.54,14.63C21.73,14.78 21.79,15.05 21.66,15.27L19.66,18.73C19.54,18.95 19.27,19.04 19.05,18.95L16.56,17.95C16.04,18.34 15.5,18.68 14.87,18.93L14.5,21.58C14.46,21.82 14.25,22 14,22H10M11.25,4L10.88,6.61C9.68,6.86 8.62,7.5 7.85,8.39L5.44,7.35L4.69,8.65L6.8,10.2C6.4,11.37 6.4,12.64 6.8,13.8L4.68,15.36L5.43,16.66L7.86,15.62C8.63,16.5 9.68,17.14 10.87,17.38L11.24,20H12.76L13.13,17.39C14.32,17.14 15.37,16.5 16.14,15.62L18.57,16.66L19.32,15.36L17.2,13.81C17.6,12.64 17.6,11.37 17.2,10.2L19.31,8.65L18.56,7.35L16.15,8.39C15.38,7.5 14.32,6.86 13.12,6.62L12.75,4H11.25Z" />
                    </svg>
                    Nhật ký hệ thống
                </a>

                <a href="{{ route('admin.backup') }}" class="menu-item {{ request()->routeIs('admin.backup') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M6,2H18A2,2 0 0,1 20,4V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V4A2,2 0 0,1 6,2M12,4A6,6 0 0,0 6,10C6,13.31 8.69,16 12.1,16L11.22,13.77C10.95,13.29 11.11,12.68 11.59,12.4L12.45,11.9C12.93,11.63 13.54,11.79 13.82,12.27L15.74,14.69C17.12,13.59 18,11.9 18,10A6,6 0 0,0 12,4M12,9A1,1 0 0,1 13,10A1,1 0 0,1 12,11A1,1 0 0,1 11,10A1,1 0 0,1 12,9M7,18A1,1 0 0,0 6,19A1,1 0 0,0 7,20A1,1 0 0,0 8,19A1,1 0 0,0 7,18M12.09,13.27L14.58,19.58L19.17,18.08L16.68,11.77L12.09,13.27Z" />
                    </svg>
                    Sao lưu
                </a>
            </div>

            <!-- Tài khoản -->
            <div class="menu-section">
                <div class="menu-section-title">Tài khoản</div>
                <a href="{{ route('admin.profile') }}" class="menu-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
                    </svg>
                    Trang cá nhân
                </a>
                <a href="{{ route('password.change') }}" class="menu-item {{ request()->routeIs('password.change') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12,17A2,2 0 0,0 14,15V11A2,2 0 0,0 12,9A2,2 0 0,0 10,11V15A2,2 0 0,0 12,17M17,8V7A5,5 0 0,0 7,7V8H5V20H19V8H17M9,7A3,3 0 0,1 12,4A3,3 0 0,1 15,7V8H9V7Z" />
                    </svg>
                    Thay đổi mật khẩu
                </a>
            </div>

            <!-- Đăng xuất -->
            <div class="menu-section">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="menu-item" style="width:100%;text-align:left;background:none;border:none;color:#cbd5e0;cursor:pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M16,17V14H9V10H16V7L21,12L16,17M14,2A2,2 0 0,1 16,4V6H14V4H5V20H14V18H16V20A2,2 0 0,1 14,22H5A2,2 0 0,1 3,20V4A2,2 0 0,1 5,2H14Z" />
                        </svg>
                        Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1>@yield('title', 'Dashboard')</h1>
            <div class="top-bar-actions">
                <form action="{{ route('admin.context.update') }}" method="POST" class="toolbar">
                    @csrf
                    @php
                    $years = ['2024-2025', '2025-2026', '2026-2027'];
                    $terms = [ 'HK1' => 'Học kỳ 1', 'HK2' => 'Học kỳ 2', 'HE' => 'Học kỳ Hè' ];
                    $currentYear = session('academic_year', '2024-2025');
                    $currentTerm = session('term', 'HK1');
                    @endphp
                    <label style="font-size:12px;color:#718096;">
                        Năm học:
                        <select name="academic_year" onchange="this.form.submit()">
                            @foreach($years as $year)
                            <option value="{{ $year }}" {{ $currentYear === $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label style="font-size:12px;color:#718096;">
                        Học kỳ:
                        <select name="term" onchange="this.form.submit()">
                            @foreach($terms as $key => $label)
                            <option value="{{ $key }}" {{ $currentTerm === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                </form>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            @if(session('success'))
            <div class="flash">{{ session('success') }}</div>
            @endif

            @if(session('error'))
            <div class="flash error">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </div>

    @yield('scripts')
</body>

</html>