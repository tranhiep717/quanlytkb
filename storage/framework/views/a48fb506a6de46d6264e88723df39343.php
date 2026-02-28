

<?php $__env->startSection('title', 'Nhật ký hệ thống'); ?>

<?php $__env->startSection('content'); ?>
<h2>Nhật ký hệ thống</h2>

<div class="card">
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="border-bottom:1px solid rgba(148,163,184,.2);">
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Thời gian</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Người dùng</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Hành động</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Chi tiết</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr style="border-bottom:1px solid rgba(148,163,184,.1);">
                <td style="padding:12px;white-space:nowrap;">
                    <small style="color:#94a3b8;"><?php echo e($log->created_at->format('d/m/Y H:i:s')); ?></small>
                </td>
                <td style="padding:12px;">
                    <?php echo e($log->user->name ?? 'Hệ thống'); ?><br>
                    <small style="color:#64748b;"><?php echo e($log->user->email ?? '-'); ?></small>
                </td>
                <td style="padding:12px;">
                    <?php
                    $actionLabels = [
                    'user_created' => '✅ Tạo người dùng',
                    'user_updated' => '✏️ Cập nhật người dùng',
                    'user_locked' => '🔒 Khóa người dùng',
                    'user_unlocked' => '🔓 Mở khóa người dùng',
                    'password_reset_sent' => '📧 Gửi reset mật khẩu',
                    'faculty_created' => '✅ Tạo khoa',
                    'faculty_updated' => '✏️ Cập nhật khoa',
                    'faculty_deleted' => '🗑️ Xóa khoa',
                    'course_created' => '✅ Tạo học phần',
                    'course_updated' => '✏️ Cập nhật học phần',
                    'course_deleted' => '🗑️ Xóa học phần',
                    'room_created' => '✅ Tạo phòng học',
                    'room_updated' => '✏️ Cập nhật phòng học',
                    'room_deleted' => '🗑️ Xóa phòng học',
                    'shift_created' => '✅ Tạo ca học',
                    'shift_updated' => '✏️ Cập nhật ca học',
                    'shift_deleted' => '🗑️ Xóa ca học',
                    'class_section_created' => '✅ Tạo lớp học phần',
                    'class_section_updated' => '✏️ Cập nhật lớp học phần',
                    'class_section_deleted' => '🗑️ Xóa lớp học phần',
                    'registration_wave_created' => '✅ Tạo đợt đăng ký',
                    'registration_wave_updated' => '✏️ Cập nhật đợt đăng ký',
                    'registration_wave_deleted' => '🗑️ Xóa đợt đăng ký',
                    'backup_requested' => '💾 Yêu cầu sao lưu',
                    'login' => '🔑 Đăng nhập',
                    'logout' => '🚪 Đăng xuất',
                    ];
                    ?>
                    <?php echo e($actionLabels[$log->action] ?? $log->action); ?>

                </td>
                <td style="padding:12px;">
                    <small style="color:#64748b;font-family:monospace;"><?php echo json_encode($log->metadata, 15, 512) ?></small>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="4" style="padding:24px;text-align:center;color:#64748b;">Chưa có nhật ký nào.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top:16px;">
        <?php echo e($logs->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkb\resources\views/admin/logs/index.blade.php ENDPATH**/ ?>