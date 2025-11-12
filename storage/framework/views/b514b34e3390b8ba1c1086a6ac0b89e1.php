

<?php $__env->startSection('title', 'Sửa Giảng viên'); ?>

<?php $__env->startSection('content'); ?>
<div style="background:white; padding:24px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1); max-width:800px;">
    <h2 style="margin:0 0 24px 0; font-size:20px; font-weight:600; color:#1e293b;">Sửa thông tin Giảng viên</h2>

    <form action="<?php echo e(route('lecturers.update', $lecturer)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569;">
                Mã giảng viên <span style="color:#dc2626;">*</span>
            </label>
            <input
                type="text"
                name="code"
                value="<?php echo e(old('code', $lecturer->code)); ?>"
                required
                style="width:100%; padding:10px; border:1px solid <?php echo e($errors->has('code') ? '#dc2626' : '#cbd5e0'); ?>; border-radius:6px;">
            <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span style="color:#dc2626; font-size:14px;"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569;">
                Họ và tên <span style="color:#dc2626;">*</span>
            </label>
            <input
                type="text"
                name="name"
                value="<?php echo e(old('name', $lecturer->name)); ?>"
                required
                style="width:100%; padding:10px; border:1px solid <?php echo e($errors->has('name') ? '#dc2626' : '#cbd5e0'); ?>; border-radius:6px;">
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span style="color:#dc2626; font-size:14px;"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569;">
                Email <span style="color:#dc2626;">*</span>
            </label>
            <input
                type="email"
                name="email"
                value="<?php echo e(old('email', $lecturer->email)); ?>"
                required
                style="width:100%; padding:10px; border:1px solid <?php echo e($errors->has('email') ? '#dc2626' : '#cbd5e0'); ?>; border-radius:6px;">
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span style="color:#dc2626; font-size:14px;"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569;">
                Khoa <span style="color:#dc2626;">*</span>
            </label>
            <select
                name="faculty_id"
                required
                style="width:100%; padding:10px; border:1px solid <?php echo e($errors->has('faculty_id') ? '#dc2626' : '#cbd5e0'); ?>; border-radius:6px;">
                <option value="">-- Chọn Khoa --</option>
                <?php $__currentLoopData = $faculties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faculty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($faculty->id); ?>" <?php echo e(old('faculty_id', $lecturer->faculty_id) == $faculty->id ? 'selected' : ''); ?>>
                    <?php echo e($faculty->name); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['faculty_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span style="color:#dc2626; font-size:14px;"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569;">
                Học vị
            </label>
            <input
                type="text"
                name="degree"
                value="<?php echo e(old('degree', $lecturer->degree)); ?>"
                style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px;"
                placeholder="Ví dụ: TS., ThS., PGS.TS.">
            <?php $__errorArgs = ['degree'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span style="color:#dc2626; font-size:14px;"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569;">
                Số điện thoại
            </label>
            <input
                type="text"
                name="phone"
                value="<?php echo e(old('phone', $lecturer->phone)); ?>"
                style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px;">
            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span style="color:#dc2626; font-size:14px;"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569;">
                Mật khẩu mới (để trống nếu không đổi)
            </label>
            <input
                type="password"
                name="password"
                style="width:100%; padding:10px; border:1px solid <?php echo e($errors->has('password') ? '#dc2626' : '#cbd5e0'); ?>; border-radius:6px;"
                placeholder="Tối thiểu 6 ký tự">
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span style="color:#dc2626; font-size:14px;"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="display:flex; gap:12px; margin-top:24px;">
            <button type="submit" style="background:#1976d2; color:white; padding:12px 24px; border:none; border-radius:6px; cursor:pointer; font-weight:500;">
                Cập nhật
            </button>
            <a href="<?php echo e(route('lecturers.index')); ?>" style="background:#e2e8f0; color:#475569; padding:12px 24px; border-radius:6px; text-decoration:none; font-weight:500; display:inline-block;">
                Hủy
            </a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/admin/lecturers/edit.blade.php ENDPATH**/ ?>