

<?php $__env->startSection('title', 'Kho lưu trữ Đợt đăng ký'); ?>

<?php $__env->startSection('content'); ?>
<div style="background:white; padding:24px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h2 style="margin:0; font-size:20px; font-weight:600; color:#1e293b;">🗄️ Kho lưu trữ Đợt đăng ký</h2>
        <a href="<?php echo e(route('registration-waves.index')); ?>" style="background:#1976d2; color:white; padding:10px 14px; border-radius:6px; text-decoration:none;">Quay lại danh sách</a>
    </div>

    <?php if(session('success')): ?>
    <div style="background:#d1fae5; border-left:4px solid #10b981; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#065f46;"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div style="background:#fee2e2; border-left:4px solid #ef4444; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#991b1b;"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div style="overflow-x:auto;">
        <table class="table-zebra" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                    <th style="padding:12px; text-align:left;">TÊN ĐỢT</th>
                    <th style="padding:12px; text-align:left;">NĂM/KỲ</th>
                    <th style="padding:12px; text-align:left;">ĐÃ XÓA LÚC</th>
                    <th style="padding:12px; text-align:center;">THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $waves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr style="border-bottom:1px solid #e2e8f0;">
                    <td style="padding:12px; font-weight:600; color:#1e293b;"><?php echo e($wave->name); ?></td>
                    <td style="padding:12px;">
                        <span style="background:#dbeafe; color:#1e40af; padding:4px 10px; border-radius:12px; font-size:12px;"><?php echo e($wave->academic_year); ?></span>
                        <span style="background:#cffafe; color:#164e63; padding:4px 10px; border-radius:12px; font-size:12px;"><?php echo e($wave->term); ?></span>
                    </td>
                    <td style="padding:12px; color:#475569;"><?php echo e(optional($wave->deleted_at)->format('d/m/Y H:i')); ?></td>
                    <td style="padding:12px; text-align:center;">
                        <div style="display:inline-flex; gap:8px;">
                            <form method="POST" action="<?php echo e(route('registration-waves.restore', $wave->id)); ?>" onsubmit="return confirm('Khôi phục đợt đăng ký này?')">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="action-btn" style="background:#10b981; color:white; padding:8px 12px; border:none; border-radius:6px;">Khôi phục</button>
                            </form>
                            <form method="POST" action="<?php echo e(route('registration-waves.force-delete', $wave->id)); ?>" onsubmit="return confirm('Xóa vĩnh viễn đợt đăng ký này? Hành động không thể hoàn tác!')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="action-btn" style="background:#dc2626; color:white; padding:8px 12px; border:none; border-radius:6px;">Xóa vĩnh viễn</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" style="padding:40px; text-align:center; color:#94a3b8;">Không có đợt đăng ký đã xóa.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($waves->hasPages()): ?>
    <div style="margin-top:16px; display:flex; justify-content:center;"><?php echo e($waves->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkb\resources\views/admin/registration-waves/trashed.blade.php ENDPATH**/ ?>