

<?php $__env->startSection('title', 'Chi tiết Khoa'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
    <h2 style="margin:0;">Chi tiết Khoa</h2>
    <div style="display:flex; gap:8px;">
        <a href="<?php echo e(route('faculties.edit', $faculty)); ?>" style="background:#eff6ff;color:#1d4ed8;padding:8px 12px;border-radius:8px;text-decoration:none;border:1px solid #bfdbfe;">Sửa</a>
        <form action="<?php echo e(route('faculties.destroy', $faculty)); ?>" method="POST" onsubmit="return confirm('Xác nhận xóa khoa này? Thao tác không thể hoàn tác.');">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" style="background:#fee2e2;color:#991b1b;padding:8px 12px;border-radius:8px;border:1px solid #fecaca;cursor:pointer;">Xóa</button>
        </form>
        <a href="<?php echo e(route('faculties.index')); ?>" style="background:#e2e8f0;color:#334155;padding:8px 12px;border-radius:8px;text-decoration:none;border:1px solid #cbd5e0;">Quay lại</a>
    </div>
</div>

<!-- Thông tin cơ bản -->
<div class="card" style="margin-bottom:16px;">
    <h3 style="margin-bottom:16px;color:#2d3748;font-size:16px;font-weight:600;">Thông tin cơ bản</h3>
    <div style="display:grid; grid-template-columns: 150px 1fr; gap:12px;">
        <div style="color:#94a3b8;">Mã khoa</div>
        <div>
            <span style="background:#eef2ff;color:#4f46e5;padding:2px 8px;border-radius:999px;font-size:12px;font-weight:600;"><?php echo e($faculty->code); ?></span>
        </div>

        <div style="color:#94a3b8;">Tên khoa</div>
        <div style="font-weight:600;"><?php echo e($faculty->name); ?></div>

        <div style="color:#94a3b8;">Trưởng khoa</div>
        <div><?php echo e($faculty->dean->name ?? '-'); ?></div>

        <div style="color:#94a3b8;">Ngày thành lập</div>
        <div><?php echo e($faculty->founding_date ? $faculty->founding_date->format('d/m/Y') : '-'); ?></div>

        <div style="color:#94a3b8;">Trạng thái</div>
        <div>
            <?php if($faculty->is_active): ?>
            <span style="background:#dcfce7;color:#166534;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;">Hoạt động</span>
            <?php else: ?>
            <span style="background:#f3f4f6;color:#4b5563;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;">Ngưng hoạt động</span>
            <?php endif; ?>
        </div>

        <div style="color:#94a3b8;">Mô tả</div>
        <div><?php echo e($faculty->description ?? '-'); ?></div>

        <div style="color:#94a3b8;">Ngày tạo</div>
        <div><?php echo e($faculty->created_at?->format('d/m/Y H:i')); ?></div>

        <div style="color:#94a3b8;">Cập nhật</div>
        <div><?php echo e($faculty->updated_at?->format('d/m/Y H:i')); ?></div>
    </div>
</div>

<!-- Danh sách giảng viên -->
<div class="card" style="margin-bottom:16px;">
    <h3 style="margin-bottom:16px;color:#2d3748;font-size:16px;font-weight:600;">Giảng viên (<?php echo e($faculty->users->count()); ?>)</h3>
    <?php if($faculty->users->count() > 0): ?>
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="border-bottom:1px solid rgba(148,163,184,.2);">
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Mã</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Họ tên</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Email</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $faculty->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lecturer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr style="border-bottom:1px solid rgba(148,163,184,.1);">
                <td style="padding:12px;">
                    <?php if($lecturer->code): ?>
                    <span style="background:#eef2ff;color:#4f46e5;padding:2px 8px;border-radius:999px;font-size:12px;font-weight:600;"><?php echo e($lecturer->code); ?></span>
                    <?php else: ?>
                    <span style="color:#94a3b8;">-</span>
                    <?php endif; ?>
                </td>
                <td style="padding:12px;"><?php echo e($lecturer->name); ?></td>
                <td style="padding:12px;"><?php echo e($lecturer->email); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php else: ?>
    <p style="color:#64748b;text-align:center;padding:16px;">Chưa có giảng viên nào.</p>
    <?php endif; ?>
</div>

<!-- Danh sách học phần -->
<div class="card">
    <h3 style="margin-bottom:16px;color:#2d3748;font-size:16px;font-weight:600;">Học phần (<?php echo e($faculty->courses->count()); ?>)</h3>
    <?php if($faculty->courses->count() > 0): ?>
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="border-bottom:1px solid rgba(148,163,184,.2);">
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Mã HP</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Tên học phần</th>
                <th style="text-align:center;padding:12px;color:#94a3b8;font-weight:500;">Số TC</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $faculty->courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr style="border-bottom:1px solid rgba(148,163,184,.1);">
                <td style="padding:12px;">
                    <span style="background:#eef2ff;color:#4f46e5;padding:2px 8px;border-radius:999px;font-size:12px;font-weight:600;"><?php echo e($course->code); ?></span>
                </td>
                <td style="padding:12px;"><?php echo e($course->name); ?></td>
                <td style="padding:12px;text-align:center;"><?php echo e($course->credits); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php else: ?>
    <p style="color:#64748b;text-align:center;padding:16px;">Chưa có học phần nào.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/admin/faculties/show.blade.php ENDPATH**/ ?>