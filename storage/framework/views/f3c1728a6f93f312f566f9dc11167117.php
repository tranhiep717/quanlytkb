

<?php $__env->startSection('title', 'Tạo người dùng mới'); ?>

<?php $__env->startSection('content'); ?>
<h2>Tạo người dùng mới</h2>

<div class="card" style="max-width:600px;">
    <form method="POST" action="<?php echo e(route('admin.users.store')); ?>">
        <?php echo csrf_field(); ?>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Họ tên <span style="color:#ef4444;">*</span></label>
            <input type="text" name="name" value="<?php echo e(old('name')); ?>" required style="width:100%;">
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
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Email <span style="color:#ef4444;">*</span></label>
            <input type="email" name="email" value="<?php echo e(old('email')); ?>" required style="width:100%;">
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#ef4444;font-size:12px;margin-top:4px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Mã người dùng</label>
            <input type="text" name="code" value="<?php echo e(old('code')); ?>" style="width:100%;">
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
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Vai trò <span style="color:#ef4444;">*</span></label>
            <select name="role" required style="width:100%;">
                <option value="">-- Chọn vai trò --</option>
                <option value="student" <?php echo e(old('role') === 'student' ? 'selected' : ''); ?>>Sinh viên</option>
                <option value="lecturer" <?php echo e(old('role') === 'lecturer' ? 'selected' : ''); ?>>Giảng viên</option>
                <option value="faculty_admin" <?php echo e(old('role') === 'faculty_admin' ? 'selected' : ''); ?>>Quản trị khoa</option>
                <option value="super_admin" <?php echo e(old('role') === 'super_admin' ? 'selected' : ''); ?>>Quản trị hệ thống</option>
            </select>
            <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#ef4444;font-size:12px;margin-top:4px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Khoa</label>
            <select name="faculty_id" style="width:100%;">
                <option value="">-- Chọn khoa --</option>
                <?php $__currentLoopData = $faculties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faculty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($faculty->id); ?>" <?php echo e(old('faculty_id') == $faculty->id ? 'selected' : ''); ?>><?php echo e($faculty->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['faculty_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#ef4444;font-size:12px;margin-top:4px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Khóa học (chỉ dành cho sinh viên)</label>
            <select name="class_cohort" style="width:100%;">
                <option value="">-- Chọn khóa học --</option>
                <?php $__currentLoopData = $cohorts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cohort): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cohort); ?>" <?php echo e(old('class_cohort') === $cohort ? 'selected' : ''); ?>><?php echo e($cohort); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <div style="color:#64748b;font-size:12px;margin-top:4px;">Nếu khóa học chưa có trong danh sách, hãy tạo sinh viên khóa đó trước</div>
            <?php $__errorArgs = ['class_cohort'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#ef4444;font-size:12px;margin-top:4px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Mật khẩu <span style="color:#ef4444;">*</span></label>
            <input type="password" name="password" required style="width:100%;">
            <div style="color:#64748b;font-size:12px;margin-top:4px;">Tối thiểu 8 ký tự</div>
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#ef4444;font-size:12px;margin-top:4px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="display:flex;gap:12px;margin-top:24px;">
            <button type="submit" style="background:#10b981;color:#fff;padding:10px 20px;border-radius:8px;cursor:pointer;border:none;">Tạo người dùng</button>
            <a href="<?php echo e(route('admin.users')); ?>" style="background:#475569;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;display:inline-block;">Hủy</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/admin/users/create.blade.php ENDPATH**/ ?>