

<?php $__env->startSection('title', 'Thời khóa biểu'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-4">
    <div class="col-md-8">
        <h3 class="mb-0">
            <i class="fas fa-calendar-week me-2" style="color: #1976d2;"></i>
            Thời khóa biểu cá nhân
        </h3>
        <small class="text-muted">Năm học: <?php echo e($academicYear); ?> - <?php echo e($term === 'HK1' ? 'Học kỳ 1' : ($term === 'HK2' ? 'Học kỳ 2' : 'Học kỳ Hè')); ?></small>
    </div>
    <div class="col-md-4 text-end">
        <div class="badge bg-primary fs-6">
            <i class="fas fa-check-circle me-1"></i>
            <?php echo e($totalCredits ?? 0); ?> tín chỉ
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <form class="col-12 col-lg-6" method="GET" action="<?php echo e(route('student.dashboard')); ?>">
                <div class="row g-2">
                    <div class="col-6 col-md-5">
                        <label class="form-label mb-1">Năm học</label>
                        <select name="academic_year" class="form-select form-select-sm">
                            <?php $__currentLoopData = ($years ?? collect([$academicYear])); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($y); ?>" <?php echo e($y == $academicYear ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="form-label mb-1">Học kỳ</label>
                        <select name="term" class="form-select form-select-sm">
                            <?php $__currentLoopData = ($terms ?? collect([$term])); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($t); ?>" <?php echo e($t == $term ? 'selected' : ''); ?>><?php echo e($t === 'HK1' ? 'Học kỳ 1' : ($t === 'HK2' ? 'Học kỳ 2' : 'Học kỳ Hè')); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-3 d-grid d-md-block">
                        <input type="hidden" name="view" value="<?php echo e($view); ?>">
                        <input type="hidden" name="date" value="<?php echo e($baseDate ?? ''); ?>">
                        <button class="btn btn-primary btn-sm mt-3 mt-md-0"><i class="fas fa-filter me-1"></i>Áp dụng</button>
                    </div>
                </div>
            </form>

            <div class="col-12 col-lg-6 d-flex flex-wrap justify-content-lg-end align-items-center gap-2">
                <div class="btn-group" role="group" aria-label="View Mode">
                    <a class="btn btn-outline-secondary btn-sm <?php echo e($view==='week' ? 'active' : ''); ?>" href="<?php echo e(route('student.dashboard', ['academic_year'=>$academicYear,'term'=>$term,'view'=>'week','date'=>$baseDate])); ?>">
                        <i class="fas fa-calendar-week me-1"></i>Tuần
                    </a>
                    <a class="btn btn-outline-secondary btn-sm <?php echo e($view==='month' ? 'active' : ''); ?>" href="<?php echo e(route('student.dashboard', ['academic_year'=>$academicYear,'term'=>$term,'view'=>'month','date'=>$baseDate])); ?>">
                        <i class="fas fa-calendar-alt me-1"></i>Tháng
                    </a>
                    <a class="btn btn-outline-secondary btn-sm <?php echo e($view==='list' ? 'active' : ''); ?>" href="<?php echo e(route('student.dashboard', ['academic_year'=>$academicYear,'term'=>$term,'view'=>'list','date'=>$baseDate])); ?>">
                        <i class="fas fa-list me-1"></i>Danh sách
                    </a>
                </div>

                <div class="btn-group" role="group" aria-label="Navigate">
                    <a class="btn btn-outline-secondary btn-sm" title="Trước" href="<?php echo e(route('student.dashboard', ['academic_year'=>$academicYear,'term'=>$term,'view'=>$view,'date'=>$prevDate])); ?>">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <span class="btn btn-light btn-sm disabled" style="pointer-events: none; min-width: 160px; text-align: center;"><?php echo e($rangeLabel ?? ''); ?></span>
                    <a class="btn btn-outline-secondary btn-sm" title="Sau" href="<?php echo e(route('student.dashboard', ['academic_year'=>$academicYear,'term'=>$term,'view'=>$view,'date'=>$nextDate])); ?>">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <a class="btn btn-outline-primary btn-sm" href="<?php echo e(route('student.dashboard', ['academic_year'=>$academicYear,'term'=>$term,'view'=>$view])); ?>">Hôm nay</a>
                </div>

                <div class="btn-group" role="group" aria-label="Exports">
                    <a class="btn btn-outline-success btn-sm" href="<?php echo e(route('student.timetable.exportCsv', ['academic_year'=>$academicYear,'term'=>$term])); ?>">
                        <i class="fas fa-file-excel me-1"></i>Excel/CSV
                    </a>
                    <a class="btn btn-outline-danger btn-sm" target="_blank" href="<?php echo e(route('student.timetable.print', ['academic_year'=>$academicYear,'term'=>$term])); ?>">
                        <i class="fas fa-file-pdf me-1"></i>PDF
                    </a>
                    <a class="btn btn-outline-dark btn-sm" href="<?php echo e(route('student.exportIcs', ['academic_year'=>$academicYear,'term'=>$term])); ?>">
                        <i class="fas fa-calendar-plus me-1"></i>ICS
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(($totalCredits ?? 0) == 0): ?>
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">Bạn chưa đăng ký lớp học phần nào</h5>
        <p class="text-muted">Vui lòng vào mục "Đăng ký học phần" để đăng ký.</p>
    </div>
</div>
<?php else: ?>
<?php if(($view ?? 'week') === 'week'): ?>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 100px;">Thứ / Ca</th>
                        <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th class="text-center"><?php echo e($day); ?></th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $maxSlots = 0;
                    foreach ($schedule as $dayClasses) {
                    $maxSlots = max($maxSlots, count($dayClasses));
                    }
                    ?>

                    <?php for($slot = 0; $slot < max(1, $maxSlots); $slot++): ?>
                        <tr>
                        <td class="text-center align-middle fw-bold bg-light">
                            Ca <?php echo e($slot + 1); ?>

                        </td>
                        <?php $__currentLoopData = $schedule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayIndex => $dayClasses): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td class="p-2" style="vertical-align: top;">
                            <?php if(isset($dayClasses[$slot])): ?>
                            <?php $class = $dayClasses[$slot]; ?>
                            <div class="class-box p-3" style="background: linear-gradient(135deg, #e3f2fd, #bbdefb); border-left: 4px solid #1976d2; border-radius: 6px;">
                                <div class="fw-bold text-primary mb-1" style="font-size: 14px;">
                                    <?php echo e($class['course_code']); ?>

                                </div>
                                <div class="mb-2" style="font-size: 13px;">
                                    <?php echo e($class['course_name']); ?>

                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success"><?php echo e($class['section_code']); ?></span>
                                    <small class="text-muted">
                                        <i class="fas fa-door-open me-1"></i><?php echo e($class['room']); ?>

                                    </small>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted" title="Giảng viên">
                                        <i class="fas fa-chalkboard-teacher me-1"></i><?php echo e($class['lecturer']); ?>

                                    </small>
                                </div>
                                <div class="mt-1">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i><?php echo e($class['shift']); ?>

                                    </small>
                                </div>
                            </div>
                            <?php endif; ?>
                        </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                        <?php endfor; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php elseif($view === 'list'): ?>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mã HP</th>
                        <th>Tên học phần</th>
                        <th>Mã lớp</th>
                        <th>Thứ</th>
                        <th>Ca (Tiết)</th>
                        <th>Phòng</th>
                        <th>Giảng viên</th>
                        <th style="width:120px">Tín chỉ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = ($currentRegs ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $s = $reg->classSection; ?>
                    <tr>
                        <td class="fw-semibold"><?php echo e($s->course->code); ?></td>
                        <td><?php echo e($s->course->name); ?></td>
                        <td><span class="badge bg-success"><?php echo e($s->section_code); ?></span></td>
                        <td><?php echo e(['','Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','Chủ nhật'][$s->day_of_week] ?? ''); ?></td>
                        <td>
                            <?php if($s->shift): ?>
                            Tiết <?php echo e($s->shift->start_period); ?>-<?php echo e($s->shift->end_period); ?>

                            <?php endif; ?>
                        </td>
                        <td><?php echo e($s->room->code ?? 'TBA'); ?></td>
                        <td><?php echo e($s->lecturer->name ?? 'TBA'); ?></td>
                        <td><?php echo e($s->course->credits ?? 0); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php else: ?>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th class="text-center"><?php echo e($day); ?></th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = ($monthWeeks ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wIndex => $week): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center align-middle fw-bold">Tuần <?php echo e($wIndex+1); ?></td>
                        <?php $__currentLoopData = $week; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td class="align-top" style="min-width: 180px;">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold <?php echo e($cell['inMonth'] ? '' : 'text-muted'); ?>"><?php echo e($cell['day']); ?></span>
                            </div>
                            <?php $__currentLoopData = $cell['classes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border rounded p-2 mt-2" style="background:#f8fbff; border-left:3px solid #1976d2;">
                                <div class="small text-primary fw-semibold"><?php echo e($c['course_code']); ?></div>
                                <div class="small"><?php echo e($c['course_name']); ?></div>
                                <div class="d-flex justify-content-between">
                                    <span class="badge bg-success"><?php echo e($c['section_code']); ?></span>
                                    <small class="text-muted"><?php echo e($c['shift']); ?></small>
                                </div>
                                <div class="small text-muted mt-1"><i class="fas fa-chalkboard-teacher me-1"></i><?php echo e($c['lecturer']); ?></div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .class-box {
        transition: all 0.3s;
        min-height: 140px;
    }

    .table td,
    .table th {
        border-color: #e0e0e0;
    }

    .table thead th {
        font-weight: 600;
        color: #424242;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('student.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/student/dashboard.blade.php ENDPATH**/ ?>