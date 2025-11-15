<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>In TKB Lớp: <?php echo e($classSection->section_code); ?></title>
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
      <h4 class="mb-0">Thời khóa biểu lớp: <?php echo e($classSection->section_code); ?></h4>
      <button class="btn btn-sm btn-secondary no-print" onclick="window.print()">In</button>
    </div>

    <div class="card mb-3">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <p class="mb-1"><strong>Môn học:</strong> <?php echo e($classSection->course->code); ?> - <?php echo e($classSection->course->name); ?></p>
            <p class="mb-1"><strong>Khoa:</strong> <?php echo e($classSection->course->faculty->name ?? '--'); ?></p>
            <p class="mb-1"><strong>Sĩ số:</strong> <?php echo e($classSection->current_enrollment); ?>/<?php echo e($classSection->max_capacity); ?></p>
          </div>
          <div class="col-md-6">
            <p class="mb-1"><strong>Năm học - Học kỳ:</strong> <?php echo e($classSection->academic_year); ?> - <?php echo e($classSection->term); ?></p>
            <p class="mb-1"><strong>Lịch học:</strong> Thứ <?php echo e($classSection->day_of_week); ?>, <?php if($classSection->shift): ?> Tiết <?php echo e($classSection->shift->start_period); ?>-<?php echo e($classSection->shift->end_period); ?> (<?php echo e($classSection->shift->start_time); ?>-<?php echo e($classSection->shift->end_time); ?>) <?php else: ?> TBA <?php endif; ?></p>
            <p class="mb-1"><strong>Phòng:</strong> <?php echo e($classSection->room->code ?? 'Chưa xếp phòng'); ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">Danh sách Sinh viên</div>
      <div class="card-body p-0">
        <table class="table table-sm table-bordered mb-0">
          <thead class="table-light">
            <tr>
              <th style="width: 60px;">STT</th>
              <th style="width: 140px;">MSSV</th>
              <th>Họ tên</th>
              <th>Email</th>
            </tr>
          </thead>
          <tbody>
            <?php $i=1; ?>
            <?php $__currentLoopData = \App\Models\Registration::where('class_section_id', $classSection->id)->with('student')->orderBy('created_at')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td class="text-center"><?php echo e($i++); ?></td>
              <td><?php echo e($reg->student->code); ?></td>
              <td><?php echo e($reg->student->name); ?></td>
              <td><?php echo e($reg->student->email); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if($i===1): ?>
            <tr>
              <td colspan="4" class="text-center text-muted">Chưa có sinh viên đăng ký.</td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>

</html><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/lecturer/classes/print.blade.php ENDPATH**/ ?>