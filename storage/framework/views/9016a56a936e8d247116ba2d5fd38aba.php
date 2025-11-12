

<?php $__env->startSection('title', 'Chỉnh sửa khoa'); ?>

<?php $__env->startSection('content'); ?>
<h2>Chỉnh sửa khoa: <?php echo e($faculty->name); ?></h2>

<div class="card" style="max-width:700px;">
    <form method="POST" action="<?php echo e(route('faculties.update', $faculty)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Mã khoa <span style="color:#ef4444;">*</span></label>
            <input type="text" name="code" value="<?php echo e(old('code', $faculty->code)); ?>" required style="width:100%;">
            <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#ef4444;font-size:12px;margin-top:4px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Tên khoa <span style="color:#ef4444;">*</span></label>
            <input type="text" name="name" value="<?php echo e(old('name', $faculty->name)); ?>" required style="width:100%;">
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#ef4444;font-size:12px;margin-top:4px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Trưởng khoa</label>
            <select name="dean_id" style="width:100%;">
                <option value="">-- Chọn trưởng khoa --</option>
                <?php $__currentLoopData = $lecturers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lecturer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($lecturer->id); ?>" <?php echo e(old('dean_id', $faculty->dean_id) == $lecturer->id ? 'selected' : ''); ?>>
                    <?php echo e($lecturer->name); ?> (<?php echo e($lecturer->code ?? $lecturer->email); ?>)
                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['dean_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#ef4444;font-size:12px;margin-top:4px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Ngày thành lập</label>
            <input type="date" name="founding_date" value="<?php echo e(old('founding_date', $faculty->founding_date?->format('Y-m-d'))); ?>" style="width:100%;">
            <?php $__errorArgs = ['founding_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#ef4444;font-size:12px;margin-top:4px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Mô tả</label>
            <textarea name="description" rows="4" style="width:100%;resize:vertical;" placeholder="Mô tả về khoa..."><?php echo e(old('description', $faculty->description)); ?></textarea>
            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#ef4444;font-size:12px;margin-top:4px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                <input type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', $faculty->is_active) ? 'checked' : ''); ?>>
                <span style="color:#e2e8f0;font-size:14px;">Hoạt động</span>
            </label>
            <?php $__errorArgs = ['is_active'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#ef4444;font-size:12px;margin-top:4px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="display:flex;gap:12px;margin-top:24px;">
            <button type="submit" style="background:#0ea5e9;color:#fff;padding:10px 20px;border-radius:8px;cursor:pointer;border:none;">Cập nhật</button>
            <a href="<?php echo e(route('faculties.index')); ?>" style="background:#475569;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;display:inline-block;">Hủy</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/admin/faculties/edit.blade.php ENDPATH**/ ?>