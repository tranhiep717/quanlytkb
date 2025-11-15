

<?php $__env->startSection('title', 'Lớp giảng dạy'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h3 class="mb-0">
            <i class="fas fa-users-class me-2" style="color: #1976d2;"></i>
            Danh sách Lớp giảng dạy
        </h3>
        <small class="text-muted">Năm học: <?php echo e($academicYear); ?> - <?php echo e($term === 'HK1' ? 'Học kỳ 1' : ($term === 'HK2' ? 'Học kỳ 2' : 'Học kỳ Hè')); ?></small>
    </div>
    <div class="col-md-6 text-end">
        <div class="badge bg-primary fs-6">
            <i class="fas fa-chalkboard me-1"></i>
            <?php echo e($classSections->count()); ?> lớp
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('lecturer.classes')); ?>">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label">Năm học</label>
                    <select name="academic_year" class="form-select" required>
                        <?php $currentYear = $academicYear; ?>
                        <?php $__currentLoopData = ($years ?? collect([$academicYear])); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($y); ?>" <?php echo e($y == $currentYear ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label">Học kỳ</label>
                    <select name="term" class="form-select" required>
                        <?php $currentTerm = $term; ?>
                        <?php $__currentLoopData = ($terms ?? collect([$term])); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t); ?>" <?php echo e($t == $currentTerm ? 'selected' : ''); ?>>
                            <?php echo e($t === 'HK1' ? 'Học kỳ 1' : ($t === 'HK2' ? 'Học kỳ 2' : 'Học kỳ Hè')); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-12 col-md-6 text-md-end">
                    <button class="btn btn-primary"><i class="fas fa-filter me-1"></i>Áp dụng</button>
                    <a href="<?php echo e(route('lecturer.classes')); ?>" class="btn btn-outline-secondary ms-1">Đặt lại</a>
                    <button class="btn btn-link text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters" aria-expanded="false">
                        <i class="fas fa-sliders-h me-1"></i>Bộ lọc nâng cao
                    </button>
                </div>
            </div>

            <div class="collapse mt-3" id="advancedFilters">
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label">Tìm theo Mã/Tên học phần hoặc Mã lớp</label>
                        <input type="text" name="search" class="form-control" placeholder="VD: EC1013, CSDL, LHP-01" value="<?php echo e($filters['search'] ?? ''); ?>">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Trạng thái lớp</label>
                        <select name="status" class="form-select">
                            <option value="">-- Tất cả --</option>
                            <option value="active" <?php echo e(($filters['status'] ?? '') === 'active' ? 'selected' : ''); ?>>Mở</option>
                            <option value="locked" <?php echo e(($filters['status'] ?? '') === 'locked' ? 'selected' : ''); ?>>Đã khóa</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Thứ</label>
                        <select name="day_of_week" class="form-select">
                            <option value="">-- Tất cả --</option>
                            <?php $__currentLoopData = [1=>'Thứ 2',2=>'Thứ 3',3=>'Thứ 4',4=>'Thứ 5',5=>'Thứ 6',6=>'Thứ 7',7=>'Chủ nhật']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dVal=>$dLab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($dVal); ?>" <?php echo e((string)($filters['day_of_week'] ?? '') === (string)$dVal ? 'selected' : ''); ?>><?php echo e($dLab); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label">Tòa nhà</label>
                        <select name="building" class="form-select">
                            <option value="">-- Tất cả --</option>
                            <?php $__currentLoopData = ($buildings ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($b); ?>" <?php echo e(($filters['building'] ?? '') === $b ? 'selected' : ''); ?>><?php echo e($b); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card-footer py-2 small text-muted">
        Gợi ý: Sử dụng bộ lọc nâng cao để nhanh chóng tìm lớp theo mã, trạng thái hoặc lịch dạy.
    </div>
</div>

<?php if($classSections->isEmpty()): ?>
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-chalkboard fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">Chưa có lớp học phần được phân công</h5>
        <p class="text-muted">Liên hệ với bộ phận đào tạo để biết thêm thông tin</p>
    </div>
</div>
<?php else: ?>
<div class="row">
    <?php $__currentLoopData = $classSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-md-6 mb-4">
        <div class="card h-100 class-card" onclick="openClassDetail(<?php echo e($section->id); ?>)" style="cursor: pointer;">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><?php echo e($section->course->code); ?></h5>
                    <span class="badge bg-light text-dark"><?php echo e($section->section_code); ?></span>
                </div>
            </div>
            <div class="card-body">
                <h6 class="text-primary mb-3"><?php echo e($section->course->name); ?></h6>

                <div class="row g-2">
                    <div class="col-6">
                        <small class="text-muted d-block">
                            <i class="fas fa-calendar-day me-1"></i>Thứ <?php echo e($section->day_of_week); ?>

                        </small>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">
                            <i class="fas fa-clock me-1"></i>
                            <?php if($section->shift): ?>
                            Tiết <?php echo e($section->shift->start_period); ?>-<?php echo e($section->shift->end_period); ?>

                            <?php else: ?>
                            TBA
                            <?php endif; ?>
                        </small>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">
                            <i class="fas fa-door-open me-1"></i>
                            <?php echo e($section->room ? $section->room->code : 'Chưa xếp phòng'); ?>

                        </small>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">
                            <i class="fas fa-users me-1"></i>
                            Sĩ số: <?php echo e($section->current_enrollment); ?>/<?php echo e($section->max_capacity); ?>

                        </small>
                    </div>
                </div>

                <?php
                $percentage = $section->max_capacity > 0 ? ($section->current_enrollment / $section->max_capacity) * 100 : 0;
                $progressClass = $percentage >= 90 ? 'bg-danger' : ($percentage >= 70 ? 'bg-warning' : 'bg-success');
                ?>

                <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar <?php echo e($progressClass); ?>" role="progressbar" style="width: <?php echo e($percentage); ?>%"></div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <button type="button" class="btn btn-sm btn-primary w-100" onclick="event.stopPropagation(); openClassDetail(<?php echo e($section->id); ?>)">
                    <i class="fas fa-eye me-1"></i>Xem danh sách sinh viên
                </button>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .class-card {
        transition: all 0.3s;
        border: 1px solid #e0e0e0;
    }

    .class-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(25, 118, 210, 0.15);
        border-color: #1976d2;
    }

    .card-header {
        background: linear-gradient(135deg, #1976d2, #64b5f6);
        color: white;
    }

    .progress-bar {
        transition: width 0.6s ease;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php echo $__env->make('lecturer.classes.partials.detail-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('lecturer.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/lecturer/classes/index.blade.php ENDPATH**/ ?>