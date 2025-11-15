

<?php $__env->startSection('title','Thông báo hệ thống'); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-2">
    <div class="card">
        <h3 style="margin:0 0 8px 0;">📣 Thông báo</h3>
        <ul style="margin:0;padding:0;list-style:none;">
            <?php $__empty_1 = true; $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <li style="padding:10px 0;border-bottom:1px solid rgba(148,163,184,.12)">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <strong><?php echo e($a->title); ?></strong>
                    <span class="muted"><?php echo e(optional($a->published_at)->format('d/m/Y H:i')); ?></span>
                </div>
                <div style="margin-top:6px;"><?php echo nl2br(e($a->content)); ?></div>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <li class="muted">Chưa có thông báo.</li>
            <?php endif; ?>
        </ul>
        <div style="margin-top:10px;"><?php echo e($announcements->links()); ?></div>
    </div>
    <div class="card">
        <h3 style="margin:0 0 8px 0;">⏰ Các đợt đăng ký</h3>
        <ul style="margin:0;padding:0;list-style:none;">
            <?php $__currentLoopData = $waves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li style="padding:10px 0;border-bottom:1px solid rgba(148,163,184,.12)">
                <strong><?php echo e($w->name); ?></strong>
                <div class="muted"><?php echo e($w->academic_year); ?> - <?php echo e($w->term); ?></div>
                <div class="muted"><?php echo e(\Carbon\Carbon::parse($w->starts_at)->format('d/m H:i')); ?> → <?php echo e(\Carbon\Carbon::parse($w->ends_at)->format('d/m H:i')); ?></div>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('student.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/student/notifications/index.blade.php ENDPATH**/ ?>