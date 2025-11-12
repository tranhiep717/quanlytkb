

<?php $__env->startSection('title', 'Quản lý người dùng'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
    <h2 style="margin:0;">Quản lý người dùng</h2>
    <a href="<?php echo e(route('admin.users.create')); ?>" style="background:#10b981;color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none;">+ Tạo người dùng mới</a>
</div>

<!-- U-2: Bộ lọc và tìm kiếm -->
<div class="card" style="margin-bottom:16px;">
    <form method="GET" action="<?php echo e(route('admin.users')); ?>">
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:12px;">
            <div>
                <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Tìm kiếm</label>
                <input type="text" name="search" value="<?php echo e($filters['search'] ?? ''); ?>" placeholder="Tên, email, mã..." style="width:100%;">
            </div>
            <div>
                <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Vai trò</label>
                <select name="role" style="width:100%;">
                    <option value="">-- Tất cả --</option>
                    <option value="student" <?php echo e(($filters['role'] ?? '') === 'student' ? 'selected' : ''); ?>>Sinh viên</option>
                    <option value="lecturer" <?php echo e(($filters['role'] ?? '') === 'lecturer' ? 'selected' : ''); ?>>Giảng viên</option>
                    <option value="faculty_admin" <?php echo e(($filters['role'] ?? '') === 'faculty_admin' ? 'selected' : ''); ?>>Quản trị khoa</option>
                    <option value="super_admin" <?php echo e(($filters['role'] ?? '') === 'super_admin' ? 'selected' : ''); ?>>Quản trị hệ thống</option>
                </select>
            </div>
            <div>
                <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Trạng thái</label>
                <select name="status" style="width:100%;">
                    <option value="">-- Tất cả --</option>
                    <option value="active" <?php echo e(($filters['status'] ?? '') === 'active' ? 'selected' : ''); ?>>Đang hoạt động</option>
                    <option value="locked" <?php echo e(($filters['status'] ?? '') === 'locked' ? 'selected' : ''); ?>>Đã khóa</option>
                </select>
            </div>
            <div>
                <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Khoa</label>
                <select name="faculty_id" style="width:100%;">
                    <option value="">-- Tất cả --</option>
                    <?php $__currentLoopData = $faculties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faculty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($faculty->id); ?>" <?php echo e(($filters['faculty_id'] ?? '') == $faculty->id ? 'selected' : ''); ?>>
                        <?php echo e($faculty->name); ?>

                    </option>
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

<!-- U-1: Bảng danh sách người dùng -->
<div class="card">
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="border-bottom:1px solid rgba(148,163,184,.2);">
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Mã</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Họ tên</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Email</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Vai trò</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Khoa</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Khóa</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Trạng thái</th>
                <th style="text-align:right;padding:12px;color:#94a3b8;font-weight:500;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr style="border-bottom:1px solid rgba(148,163,184,.1);">
                <td style="padding:12px;">
                    <?php if($user->code): ?>
                    <span style="background:#eef2ff;color:#4f46e5;padding:2px 8px;border-radius:999px;font-size:12px;font-weight:600;"><?php echo e($user->code); ?></span>
                    <?php else: ?>
                    <span style="color:#94a3b8;">-</span>
                    <?php endif; ?>
                </td>
                <td style="padding:12px;"><?php echo e($user->name); ?></td>
                <td style="padding:12px;"><?php echo e($user->email); ?></td>
                <td style="padding:12px;">
                    <?php if($user->role === 'student'): ?> Sinh viên
                    <?php elseif($user->role === 'lecturer'): ?> Giảng viên
                    <?php elseif($user->role === 'faculty_admin'): ?> Quản trị khoa
                    <?php elseif($user->role === 'super_admin'): ?> Quản trị hệ thống
                    <?php else: ?> <?php echo e($user->role); ?>

                    <?php endif; ?>
                </td>
                <td style="padding:12px;"><?php echo e($user->faculty->name ?? '-'); ?></td>
                <td style="padding:12px;"><?php echo e($user->class_cohort ?? '-'); ?></td>
                <td style="padding:12px;">
                    <?php if($user->is_locked): ?>
                    <span style="background:#fee2e2;color:#b91c1c;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;">Đã khóa</span>
                    <?php else: ?>
                    <span style="background:#dcfce7;color:#166534;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;">Hoạt động</span>
                    <?php endif; ?>
                </td>
                <td style="padding:12px;text-align:right;">
                    <div style="display:flex;gap:8px;justify-content:flex-end;">
                        <!-- Xem chi tiết -->
                        <a href="<?php echo e(route('admin.users.show', $user)); ?>" title="Xem chi tiết" style="color:#334155;display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1px solid #e2e8f0;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                <path fill="currentColor" d="M12,6A10.91,10.91,0,0,1,23,12a10.91,10.91,0,0,1-11,6A10.91,10.91,0,0,1,1,12,10.91,10.91,0,0,1,12,6m0,2C8.13,8,4.7,10.06,3,12c1.7,1.94,5.13,4,9,4s7.3-2.06,9-4C19.3,10.06,15.87,8,12,8m0,2a2,2,0,1,1-2,2,2,2,0,0,1,2-2Z" />
                            </svg>
                        </a>

                        <!-- Sửa -->
                        <a href="<?php echo e(route('admin.users.edit', $user)); ?>" title="Sửa" style="color:#1d4ed8;display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1px solid #bfdbfe;background:#eff6ff;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                <path fill="currentColor" d="M5,18.08V21h2.92L17.81,11.11l-2.92-2.92ZM20.71,7a1,1,0,0,0,0-1.41L18.37,3.29a1,1,0,0,0-1.41,0L15,5.25l2.92,2.92Z" />
                            </svg>
                        </a>

                        <!-- Khóa/Mở khóa -->
                        <?php if($user->is_locked): ?>
                        <form action="<?php echo e(route('admin.users.unlock', $user)); ?>" method="POST" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" title="Mở khóa" style="color:#166534;display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1px solid #86efac;background:#dcfce7;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                    <path fill="currentColor" d="M12,17a2,2,0,1,1,2-2A2,2,0,0,1,12,17Zm6-5H18V10a6,6,0,0,0-12,0h2a4,4,0,0,1,8,0v2H6a2,2,0,0,0-2,2v6a2,2,0,0,0,2,2H18a2,2,0,0,0,2-2V14A2,2,0,0,0,18,12Z" />
                                </svg>
                            </button>
                        </form>
                        <?php else: ?>
                        <form action="<?php echo e(route('admin.users.lock', $user)); ?>" method="POST" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" title="Khóa" style="color:#991b1b;display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1px solid #fecaca;background:#fee2e2;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                    <path fill="currentColor" d="M12,17a2,2,0,1,1,2-2A2,2,0,0,1,12,17Zm6-5H18V10a6,6,0,0,0-12,0H4a8,8,0,0,1,16,0v2h0a2,2,0,0,1,2,2v6a2,2,0,0,1-2,2H6a2,2,0,0,1-2-2V14a2,2,0,0,1,2-2Z" />
                                </svg>
                            </button>
                        </form>
                        <?php endif; ?>

                        <!-- Gửi reset mật khẩu -->
                        <form action="<?php echo e(route('admin.users.reset_password', $user)); ?>" method="POST" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" title="Gửi đặt lại mật khẩu" style="color:#92400e;display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1px solid #fde68a;background:#fef3c7;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                    <path fill="currentColor" d="M7,14A3,3 0 0,1 10,11A3,3 0 0,1 13,14A3,3 0 0,1 10,17A3,3 0 0,1 7,14M10,5A9,9 0 0,1 19,14C19,16.39 18.05,18.56 16.5,20.22L14.95,18.67C16.09,17.41 16.8,15.78 16.8,14A6.8,6.8 0 0,0 10,7.2A6.8,6.8 0 0,0 3.2,14C3.2,15.78 3.91,17.41 5.05,18.67L3.5,20.22C1.95,18.56 1,16.39 1,14A9,9 0 0,1 10,5Z" />
                                </svg>
                            </button>
                        </form>

                        <!-- Xóa -->
                        <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST" style="display:inline;" class="js-delete-form">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" title="Xóa" style="color:#991b1b;display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:6px;border:1px solid #fecaca;background:#fee2e2;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                                    <path fill="currentColor" d="M9,3V4H4V6H5V19A2,2 0 0,0 7,21H17A2,2 0 0,0 19,19V6H20V4H15V3H9M7,6H17V19H7V6Z" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="8" style="padding:24px;text-align:center;color:#64748b;">Không tìm thấy người dùng nào.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div style="margin-top:16px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
        <div style="color:#64748b; font-size:14px;">
            <?php if($users->total() > 0): ?>
            Hiển thị <?php echo e($users->firstItem()); ?>–<?php echo e($users->lastItem()); ?> của <?php echo e($users->total()); ?>

            <?php else: ?>
            Không có dữ liệu
            <?php endif; ?>
        </div>
        <div>
            <?php echo e($users->withQueryString()->links()); ?>

        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function showFlash(type, text) {
            const container = document.querySelector('.content');
            if (!container) return;
            const div = document.createElement('div');
            div.className = 'flash' + (type === 'error' ? ' error' : '');
            div.textContent = text;
            container.prepend(div);
            setTimeout(() => {
                div.remove();
            }, 4000);
        }

        document.querySelectorAll('form.js-delete-form').forEach(function(form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                if (!confirm('Xác nhận xóa người dùng này? Thao tác không thể hoàn tác.')) return;
                const url = form.getAttribute('action');
                const token = form.querySelector('input[name="_token"]').value;
                const row = form.closest('tr');
                try {
                    const res = await fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    let data = {};
                    try {
                        data = await res.json();
                    } catch (_) {}
                    if (res.ok) {
                        if (row) row.remove();
                        showFlash('success', (data && data.message) ? data.message : 'Xóa thành công');
                    } else {
                        showFlash('error', (data && data.message) ? data.message : 'Xóa thất bại (lỗi DB/kết nối). Vui lòng thử lại.');
                    }
                } catch (err) {
                    showFlash('error', 'Xóa thất bại (lỗi DB/kết nối). Vui lòng thử lại.');
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/admin/users/index.blade.php ENDPATH**/ ?>