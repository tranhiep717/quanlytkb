<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Sinh viên'); ?> - Hệ Thống Quản Lý Tín Chỉ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #5e35b1;
            --secondary-color: #7e57c2;
            --sidebar-bg: #2c3e50;
            --sidebar-hover: #34495e;
            --text-light: #ecf0f1;
            --role-badge-bg: #7e57c2;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }

        .top-bar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .role-badge {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.4);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-left: 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .top-bar h1 {
            font-size: 22px;
            margin: 0;
            font-weight: 600;
        }

        .top-bar .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .top-bar .user-name {
            font-size: 14px;
            font-weight: 500;
        }

        .container-wrapper {
            display: flex;
            min-height: calc(100vh - 62px);
        }

        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            color: var(--text-light);
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar .menu-title {
            padding: 10px 20px;
            font-size: 12px;
            color: #95a5a6;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .sidebar .menu-item {
            display: block;
            padding: 12px 20px;
            color: var(--text-light);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar .menu-item:hover {
            background: var(--sidebar-hover);
            border-left-color: var(--secondary-color);
            color: white;
        }

        .sidebar .menu-item.active {
            background: var(--sidebar-hover);
            border-left-color: var(--secondary-color);
            font-weight: 600;
        }

        .sidebar .menu-item i {
            width: 24px;
            margin-right: 10px;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
            font-weight: 600;
        }

        .top-bar .title-wrapper {
            line-height: 1.2;
            /* Giảm khoảng cách dòng */
        }

        .top-bar .main-title {
            font-size: 20px;
            font-weight: 600;
            margin: 0;
        }

        .top-bar .subtitle {
            font-size: 14px;
            font-weight: 300;
            margin: 0;
            opacity: 0.9;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>
    <div class="top-bar">
        <div class="title-wrapper d-flex align-items-center">
            
            <i class="fas fa-university fa-2x me-3" style="opacity: 0.8;"></i>
            <div>
                <div class="main-title">
                    Trường Đại học ABC
                    <span class="role-badge">
                        <i class="fas fa-graduation-cap"></i>
                        Sinh viên
                    </span>
                </div>
                <div class="subtitle">Phần mềm Quản lý Thời khóa biểu</div>
            </div>
        </div>
        <div class="user-info">
            <span class="user-name">
                <i class="fas fa-user-circle me-1"></i>
                <?php echo e(auth()->user()->name); ?>

            </span>
            <span class="badge bg-light text-primary">
                <?php echo e(auth()->user()->code); ?>

            </span>
        </div>
    </div>

    <div class="container-wrapper">
        <div class="sidebar" id="sidebar">
            <div class="menu-title">MENU CHÍNH</div>

            <a href="<?php echo e(route('student.dashboard')); ?>" class="menu-item <?php echo e(request()->routeIs('student.dashboard') ? 'active' : ''); ?>">
                <i class="fas fa-calendar-alt"></i>
                Thời khóa biểu
            </a>

            <a href="<?php echo e(route('student.offerings')); ?>" class="menu-item <?php echo e(request()->routeIs('student.offerings') ? 'active' : ''); ?>">
                <i class="fas fa-edit"></i>
                Đăng ký học phần
            </a>

            <a href="<?php echo e(route('student.my')); ?>" class="menu-item <?php echo e(request()->routeIs('student.my') ? 'active' : ''); ?>">
                <i class="fas fa-list-check"></i>
                Đăng ký của tôi
            </a>

            <a href="<?php echo e(route('student.notifications')); ?>" class="menu-item <?php echo e(request()->routeIs('student.notifications') ? 'active' : ''); ?>">
                <i class="fas fa-bell"></i>
                Thông báo
            </a>

            <div class="menu-title mt-4">TÀI KHOẢN</div>

            <a href="<?php echo e(route('student.profile.show')); ?>" class="menu-item <?php echo e(request()->routeIs('student.profile.show') ? 'active' : ''); ?>">
                <i class="fas fa-user-edit"></i>
                Hồ sơ cá nhân
            </a>

            <a href="<?php echo e(route('password.change')); ?>" class="menu-item <?php echo e(request()->routeIs('password.change') ? 'active' : ''); ?>">
                <i class="fas fa-key"></i>
                Đổi mật khẩu
            </a>

            <form action="<?php echo e(route('logout')); ?>" method="POST" class="m-0">
                <?php echo csrf_field(); ?>
                <button type="submit" class="menu-item w-100 text-start border-0" style="background: none;">
                    <i class="fas fa-sign-out-alt"></i>
                    Đăng xuất
                </button>
            </form>
        </div>

        <div class="main-content">
            <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/student/layout.blade.php ENDPATH**/ ?>