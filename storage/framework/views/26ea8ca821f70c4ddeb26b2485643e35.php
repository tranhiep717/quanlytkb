

<?php $__env->startSection('title', 'Danh sách Thông báo'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
    <h2 style="margin:0;">Danh sách Thông báo</h2>
    <a href="<?php echo e(route('announcements.create')); ?>" style="background:#10b981;color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none;">+ Thêm thông báo mới</a>
</div>

<div class="card" style="margin-bottom:16px;">
    <form method="GET" action="<?php echo e(route('announcements.index')); ?>" style="display:flex; gap:12px; align-items:end; flex-wrap:wrap;">
        <div>
            <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Tìm kiếm</label>
            <input type="text" name="q" value="<?php echo e($filters['q'] ?? ''); ?>" placeholder="Tiêu đề...">
        </div>
        <div>
            <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Trạng thái</label>
            <select name="status">
                <option value="">-- Tất cả --</option>
                <option value="published" <?php echo e(($filters['status'] ?? '')==='published' ? 'selected' : ''); ?>>Đã xuất bản</option>
                <option value="draft" <?php echo e(($filters['status'] ?? '')==='draft' ? 'selected' : ''); ?>>Nháp</option>
            </select>
        </div>
        <div>
            <button type="submit">Lọc</button>
        </div>
    </form>
</div>

<div class="card">
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="border-bottom:1px solid rgba(148,163,184,.2);">
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Tiêu đề</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Đối tượng</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Ngày đăng</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Trạng thái</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
            $aud = $a->audience ?? null;
            $label = 'Tất cả';
            if (is_array($aud)) {
            if (!empty($aud['faculties'])) $label = 'Khoa';
            elseif (!empty($aud['roles']) && in_array('lecturers', $aud['roles'])) $label = 'Giảng viên';
            elseif (!empty($aud['roles']) && in_array('students', $aud['roles'])) $label = 'Sinh viên';
            }
            $status = $a->published_at ? 'Đã xuất bản' : 'Nháp';
            ?>
            <tr style="border-bottom:1px solid rgba(148,163,184,.1);">
                <td style="padding:12px;"><?php echo e($a->title); ?></td>
                <td style="padding:12px;"><?php echo e($label); ?></td>
                <td style="padding:12px;"><?php echo e($a->published_at ? $a->published_at->format('d/m/Y H:i') : '-'); ?></td>
                <td style="padding:12px;"><?php echo e($status); ?></td>
                <td style="padding:12px; display:flex; gap:8px;">
                    <a href="<?php echo e(route('announcements.edit', $a)); ?>">Sửa</a>
                    <form action="<?php echo e(route('announcements.destroy', $a)); ?>" method="POST" onsubmit="return confirm('Xóa thông báo này?');">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" style="background:#ef4444; color:#fff; border-radius:4px; padding:6px 10px;">Xóa</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="5" style="padding:24px;text-align:center;color:#64748b;">Chưa có thông báo.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top:16px;">
        <?php echo e($announcements->appends($filters)->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/admin/announcements/index.blade.php ENDPATH**/ ?>