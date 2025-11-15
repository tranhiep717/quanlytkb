

<?php $__env->startSection('title', 'Sao lưu & Phục hồi'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width:900px;">
    <div style="background:white; padding:24px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1); margin-bottom:24px;">
        <h2 style="margin:0 0 16px 0; font-size:20px; font-weight:600; color:#1e293b;">
            📦 Sao lưu Dữ liệu
        </h2>
        <p style="color:#64748b; margin-bottom:20px;">
            Tạo bản sao lưu toàn bộ cơ sở dữ liệu để bảo vệ dữ liệu hệ thống. Bạn có thể khôi phục lại dữ liệu từ các bản sao lưu này khi cần thiết.
        </p>

        <form action="<?php echo e(route('admin.backup.create')); ?>" method="POST" onsubmit="return confirm('Xác nhận tạo bản sao lưu mới?');">
            <?php echo csrf_field(); ?>
            <button type="submit" style="background:#16a34a; color:white; padding:12px 24px; border:none; border-radius:6px; cursor:pointer; font-weight:500; display:flex; align-items:center; gap:8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Tạo Bản Sao Lưu Mới
            </button>
        </form>

        <div style="margin-top:24px; padding:16px; background:#f0fdf4; border-left:4px solid #16a34a; border-radius:4px;">
            <p style="margin:0; color:#166534; font-size:14px;">
                💡 <strong>Lưu ý:</strong> Quá trình sao lưu có thể mất vài phút tùy thuộc vào kích thước dữ liệu. File sao lưu sẽ được lưu trong thư mục <code style="background:#dcfce7; padding:2px 6px; border-radius:3px;">storage/backups/</code>
            </p>
        </div>
    </div>

    <div style="background:white; padding:24px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
        <h2 style="margin:0 0 16px 0; font-size:20px; font-weight:600; color:#1e293b;">
            🔄 Phục Hồi Dữ Liệu
        </h2>
        <p style="color:#64748b; margin-bottom:20px;">
            Khôi phục dữ liệu từ một bản sao lưu trước đó. <strong style="color:#dc2626;">Chú ý: Thao tác này sẽ ghi đè toàn bộ dữ liệu hiện tại!</strong>
        </p>

        <form action="<?php echo e(route('admin.restore')); ?>" method="POST" enctype="multipart/form-data" onsubmit="return confirm('⚠️ CẢNH BÁO: Việc phục hồi sẽ GHI ĐÈ toàn bộ dữ liệu hiện tại. Bạn có chắc chắn muốn tiếp tục?');">
            <?php echo csrf_field(); ?>

            <div style="margin-bottom:20px;">
                <label style="display:block; margin-bottom:8px; font-weight:500; color:#475569;">
                    Chọn File Sao Lưu (.sql hoặc .zip)
                </label>
                <input
                    type="file"
                    name="backup_file"
                    accept=".sql,.zip"
                    required
                    style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px;">
            </div>

            <button type="submit" style="background:#dc2626; color:white; padding:12px 24px; border:none; border-radius:6px; cursor:pointer; font-weight:500; display:flex; align-items:center; gap:8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="23 4 23 10 17 10"></polyline>
                    <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                </svg>
                Phục Hồi Dữ Liệu
            </button>
        </form>

        <div style="margin-top:24px; padding:16px; background:#fef2f2; border-left:4px solid #dc2626; border-radius:4px;">
            <p style="margin:0; color:#991b1b; font-size:14px;">
                ⚠️ <strong>Cảnh báo:</strong> Thao tác phục hồi không thể hoàn tác. Hãy chắc chắn bạn đã tạo bản sao lưu mới nhất trước khi thực hiện phục hồi!
            </p>
        </div>
    </div>

    <div style="background:#fff7ed; border-left:4px solid #f59e0b; padding:16px; border-radius:4px; margin-top:24px;">
        <h3 style="margin:0 0 8px 0; color:#92400e; font-size:16px;">📋 Khuyến nghị</h3>
        <ul style="margin:0; padding-left:20px; color:#78350f; line-height:1.8;">
            <li>Tạo bản sao lưu định kỳ (ít nhất 1 lần/tuần)</li>
            <li>Lưu trữ các bản sao lưu quan trọng ở vị trí an toàn bên ngoài server</li>
            <li>Kiểm tra tính toàn vẹn của file sao lưu trước khi phục hồi</li>
            <li>Thực hiện backup trước khi cập nhật hệ thống hoặc thay đổi lớn</li>
        </ul>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/admin/backup.blade.php ENDPATH**/ ?>