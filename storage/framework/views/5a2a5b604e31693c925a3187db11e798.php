<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In TKB Sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body onload="setTimeout(()=>window.print(), 300)">
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Thời khóa biểu Sinh viên — <?php echo e($year); ?> / <?php echo e($term); ?></h4>
            <button class="btn btn-sm btn-secondary no-print" onclick="window.print()">In</button>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <table class="table table-sm table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:120px">Mã HP</th>
                            <th>Tên học phần</th>
                            <th style="width:100px">Mã lớp</th>
                            <th style="width:90px">Thứ</th>
                            <th style="width:120px">Ca (Tiết)</th>
                            <th style="width:140px">Giờ</th>
                            <th style="width:100px">Phòng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($s->course->code); ?></td>
                            <td><?php echo e($s->course->name); ?></td>
                            <td><?php echo e($s->section_code); ?></td>
                            <td><?php echo e(['','Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','Chủ nhật'][$s->day_of_week] ?? ''); ?></td>
                            <td>
                                <?php if($s->shift): ?>
                                Tiết <?php echo e($s->shift->start_period); ?>-<?php echo e($s->shift->end_period); ?>

                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($s->shift && $s->shift->start_time && $s->shift->end_time): ?>
                                <?php echo e($s->shift->start_time); ?> - <?php echo e($s->shift->end_time); ?>

                                <?php endif; ?>
                            </td>
                            <td><?php echo e($s->room->code ?? ''); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/student/timetable/print.blade.php ENDPATH**/ ?>