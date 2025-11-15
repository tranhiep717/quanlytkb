

<?php $__env->startSection('title', 'Báo cáo'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
    <h2 style="margin:0;">Báo cáo đăng ký</h2>
    <a href="<?php echo e(route('admin.reports.export', $filters)); ?>" style="background:#10b981;color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none;">📊 Xuất báo cáo</a>
</div>

<!-- Bộ lọc -->
<div class="card" style="margin-bottom:16px;">
    <form method="GET" action="<?php echo e(route('admin.reports')); ?>">
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:12px;">
            <div>
                <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Năm học</label>
                <input type="text" name="academic_year" value="<?php echo e($filters['academic_year'] ?? session('academic_year', '2024-2025')); ?>" style="width:100%;">
            </div>
            <div>
                <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Học kỳ</label>
                <select name="term" style="width:100%;">
                    <option value="HK1" <?php echo e(($filters['term'] ?? session('term')) === 'HK1' ? 'selected' : ''); ?>>Học kỳ 1</option>
                    <option value="HK2" <?php echo e(($filters['term'] ?? session('term')) === 'HK2' ? 'selected' : ''); ?>>Học kỳ 2</option>
                    <option value="HE" <?php echo e(($filters['term'] ?? session('term')) === 'HE' ? 'selected' : ''); ?>>Học kỳ Hè</option>
                </select>
            </div>
            <div>
                <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Khoa</label>
                <select name="faculty_id" style="width:100%;">
                    <option value="">-- Tất cả --</option>
                    <?php $__currentLoopData = $faculties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faculty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($faculty->id); ?>" <?php echo e(($filters['faculty_id'] ?? '') == $faculty->id ? 'selected' : ''); ?>><?php echo e($faculty->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Khóa học</label>
                <input type="text" name="cohort" value="<?php echo e($filters['cohort'] ?? ''); ?>" placeholder="VD: K17" style="width:100%;">
            </div>
            <div style="display:flex;align-items:flex-end;">
                <button type="submit" style="background:#0ea5e9;color:#fff;cursor:pointer;width:100%;">Lọc</button>
            </div>
        </div>
    </form>
</div>

<!-- Bảng danh sách đăng ký -->
<div class="card">
    <h3 style="margin-top:0;">Danh sách đăng ký (<?php echo e($registrations->total()); ?> kết quả)</h3>
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="border-bottom:1px solid rgba(148,163,184,.2);">
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Sinh viên</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Lớp học phần</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Học phần</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Tín chỉ</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Khoa</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Thời gian ĐK</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $registrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr style="border-bottom:1px solid rgba(148,163,184,.1);">
                <td style="padding:12px;"><?php echo e($reg->student->name ?? 'N/A'); ?><br><small style="color:#64748b;"><?php echo e($reg->student->code ?? ''); ?></small></td>
                <td style="padding:12px;"><?php echo e($reg->classSection->section_code ?? 'N/A'); ?></td>
                <td style="padding:12px;"><?php echo e($reg->classSection->course->name ?? 'N/A'); ?><br><small style="color:#64748b;"><?php echo e($reg->classSection->course->code ?? ''); ?></small></td>
                <td style="padding:12px;"><?php echo e($reg->classSection->course->credits ?? 0); ?></td>
                <td style="padding:12px;"><?php echo e($reg->classSection->course->faculty->name ?? 'N/A'); ?></td>
                <td style="padding:12px;"><small style="color:#64748b;"><?php echo e($reg->created_at->format('d/m/Y H:i')); ?></small></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="6" style="padding:24px;text-align:center;color:#64748b;">Không có dữ liệu đăng ký.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top:16px;">
        <?php echo e($registrations->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/admin/reports/index.blade.php ENDPATH**/ ?>