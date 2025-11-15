

<?php $__env->startSection('title','Thay đổi mật khẩu'); ?>

<?php $__env->startSection('content'); ?>
<div class="card" style="max-width:720px;">
    <h2 style="margin-top:0;">Thay đổi mật khẩu</h2>
    <p class="muted" style="margin:6px 0 16px;">Mật khẩu mạnh: ≥ 8 ký tự, gồm chữ hoa, chữ thường, số và ký tự đặc biệt.</p>

    <?php if(session('status')): ?>
    <div class="status success"><?php echo e(session('status')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div class="status error"><?php echo e(session('error')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
    <div class="status error">
        <ul style="margin:0; padding-left:18px;">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($e); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('password.change.submit')); ?>" style="display:grid; gap:12px;">
        <?php echo csrf_field(); ?>
        <label>
            <span class="muted" style="display:block;">Mật khẩu hiện tại</span>
            <input type="password" name="current_password" required autocomplete="current-password">
        </label>
        <label>
            <span class="muted" style="display:block;">Mật khẩu mới</span>
            <input type="password" name="password" required autocomplete="new-password">
            <small class="muted">Ít nhất 8 ký tự, nên có chữ hoa, chữ thường, số và ký tự đặc biệt.</small>
        </label>
        <label>
            <span class="muted" style="display:block;">Xác nhận mật khẩu mới</span>
            <input type="password" name="password_confirmation" required autocomplete="new-password">
        </label>
        <div style="margin-top:6px; display:flex; gap:10px;">
            <button class="btn" type="submit">Cập nhật mật khẩu</button>
            <a href="<?php echo e(route('student.dashboard')); ?>" style="align-self:center;">Hủy</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('student.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/student/profile/change-password.blade.php ENDPATH**/ ?>