

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .stat-card {
        background: white;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .stat-card .label {
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .stat-card .value {
        font-size: 36px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .stat-card .icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 12px;
    }

    .stat-card.blue .icon {
        background: #dbeafe;
    }

    .stat-card.green .icon {
        background: #dcfce7;
    }

    .stat-card.purple .icon {
        background: #f3e8ff;
    }

    .stat-card.orange .icon {
        background: #fed7aa;
    }

    .quick-action {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        text-decoration: none;
        color: inherit;
        display: block;
        transition: all 0.2s;
        border: 2px solid transparent;
    }

    .quick-action:hover {
        border-color: #1976d2;
        box-shadow: 0 4px 12px rgba(25, 118, 210, 0.15);
        transform: translateY(-2px);
    }

    .quick-action .title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 6px;
        font-size: 15px;
    }

    .quick-action .desc {
        color: #64748b;
        font-size: 13px;
    }

    .chart-container {
        background: white;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
</style>

<!-- Filter Bar -->
<div style="background:white; padding:16px 24px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1); margin-bottom:24px; display:flex; align-items:center; justify-content:space-between;">
    <form action="<?php echo e(route('admin.dashboard')); ?>" method="GET" style="display:flex; gap:12px; align-items:center;">
        <label style="font-size:14px; color:#475569; font-weight:500;">
            Lọc theo Khoa:
            <select name="faculty_id" onchange="this.form.submit()" style="margin-left:8px; padding:8px 12px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                <option value="">Tất cả Khoa</option>
                <?php $__currentLoopData = $faculties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fac): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($fac->id); ?>" <?php echo e((string)$facultyFilter === (string)$fac->id ? 'selected' : ''); ?>>
                    <?php echo e($fac->code); ?> - <?php echo e($fac->name); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </label>
    </form>
    <div style="color:#64748b; font-size:14px;">
        📅 <strong style="color:#1e293b;"><?php echo e($academicYear); ?></strong> - <strong style="color:#1e293b;"><?php echo e($term === 'HK1' ? 'Học kỳ 1' : ($term === 'HK2' ? 'Học kỳ 2' : 'Học kỳ Hè')); ?></strong>
    </div>
</div>

<!-- Statistics Cards -->
<div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:20px; margin-bottom:24px;">
    <div class="stat-card blue">
        <div class="icon">👨‍🎓</div>
        <div class="label">Tổng số Sinh viên</div>
        <div class="value"><?php echo e(number_format($totalStudents)); ?></div>
    </div>

    <div class="stat-card green">
        <div class="icon">📚</div>
        <div class="label">Học phần đang mở</div>
        <div class="value"><?php echo e(number_format($totalOpenCourses)); ?></div>
    </div>

    <div class="stat-card purple">
        <div class="icon">✅</div>
        <div class="label">Tổng lượt đăng ký</div>
        <div class="value"><?php echo e(number_format($totalRegistrations)); ?></div>
    </div>

    <div class="stat-card orange">
        <div class="icon">👨‍🏫</div>
        <div class="label">Giảng viên</div>
        <div class="value"><?php echo e(number_format($totalLecturers ?? 0)); ?></div>
    </div>
</div>

<!-- Charts Row -->
<div style="display:grid; grid-template-columns:2fr 1fr; gap:20px; margin-bottom:24px;">
    <!-- Line Chart -->
    <div class="chart-container">
        <h3 style="margin:0 0 16px 0; font-size:16px; font-weight:600; color:#1e293b;">
            📈 Số lượt đăng ký theo thời gian
        </h3>
        <canvas id="registrationChart" height="80"></canvas>
    </div>

    <!-- Pie Chart -->
    <div class="chart-container">
        <h3 style="margin:0 0 16px 0; font-size:16px; font-weight:600; color:#1e293b;">
            📊 Sinh viên theo Khoa
        </h3>
        <canvas id="facultyChart"></canvas>
    </div>
</div>

<!-- Quick Actions -->
<div style="margin-bottom:24px;">
    <h3 style="margin:0 0 16px 0; font-size:18px; font-weight:600; color:#1e293b;">⚡ Thao tác nhanh</h3>
    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px;">
        <a href="<?php echo e(route('admin.users.create')); ?>?role=student" class="quick-action">
            <div class="title">➕ Thêm Sinh viên</div>
            <div class="desc">Tạo tài khoản SV mới</div>
        </a>

        <a href="<?php echo e(route('lecturers.create')); ?>" class="quick-action">
            <div class="title">➕ Thêm Giảng viên</div>
            <div class="desc">Tạo tài khoản GV mới</div>
        </a>

        <a href="<?php echo e(route('courses.create')); ?>" class="quick-action">
            <div class="title">📖 Tạo Học phần</div>
            <div class="desc">Thêm môn học mới</div>
        </a>

        <a href="<?php echo e(route('class-sections.create')); ?>" class="quick-action">
            <div class="title">🏫 Mở Lớp HP</div>
            <div class="desc">Tạo lớp học phần</div>
        </a>

        <a href="<?php echo e(route('registration-waves.index')); ?>" class="quick-action">
            <div class="title">⏰ Cài đặt Đăng ký</div>
            <div class="desc">Quản lý kỳ đăng ký</div>
        </a>

        <a href="<?php echo e(route('admin.reports')); ?>" class="quick-action">
            <div class="title">📑 Xem Báo cáo</div>
            <div class="desc">Thống kê & báo cáo</div>
        </a>

        <a href="<?php echo e(route('admin.backup')); ?>" class="quick-action">
            <div class="title">💾 Sao lưu</div>
            <div class="desc">Backup dữ liệu</div>
        </a>

        <a href="<?php echo e(route('admin.logs')); ?>" class="quick-action">
            <div class="title">📋 Nhật ký</div>
            <div class="desc">Xem log hệ thống</div>
        </a>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<?php
// Prepare data for charts in PHP to avoid Blade syntax pitfalls
$facultyLabels = $faculties->pluck('code');
$facultyData = $faculties->map(function($f) {
return \App\Models\User::where('role', 'student')->where('faculty_id', $f->id)->count();
});
?>

<script>
    // Registration Trend Chart (Line)
    const registrationCtx = document.getElementById('registrationChart').getContext('2d');
    new Chart(registrationCtx, {
        type: 'line',
        data: {
            labels: ['Tuần 1', 'Tuần 2', 'Tuần 3', 'Tuần 4', 'Tuần 5', 'Tuần 6'],
            datasets: [{
                label: 'Lượt đăng ký',
                data: [120, 350, 580, 720, 850, {
                    {
                        (int)($totalRegistrations ?? 0)
                    }
                }],
                borderColor: '#1976d2',
                backgroundColor: 'rgba(25, 118, 210, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Faculty Distribution Chart (Doughnut)
    const facultyCtx = document.getElementById('facultyChart').getContext('2d');
    new Chart(facultyCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo $facultyLabels->toJson(); ?>,
            datasets: [{
                data: <?php echo $facultyData->toJson(); ?>,
                backgroundColor: ['#1976d2', '#16a34a', '#9333ea', '#f59e0b', '#ef4444', '#06b6d4', '#ec4899']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 10,
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkb\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>