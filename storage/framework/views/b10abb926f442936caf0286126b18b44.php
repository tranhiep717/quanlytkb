

<?php $__env->startSection('title', 'Quản lý Học phần'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .faculty-badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .faculty-cntt {
        background: #dbeafe;
        color: #1e40af;
    }

    .faculty-kt {
        background: #dcfce7;
        color: #166534;
    }

    .faculty-nn {
        background: #fef3c7;
        color: #92400e;
    }

    .faculty-default {
        background: #f3e8ff;
        color: #6b21a8;
    }

    .table-zebra tbody tr:nth-child(even) {
        background-color: rgba(255, 255, 255, 0.02);
    }

    .table-zebra tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.1);
    }

    .action-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    .prereq-badge {
        background: #374151;
        color: #9ca3af;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
        margin-right: 4px;
        display: inline-block;
        margin-bottom: 2px;
    }
</style>

<div style="background:white; padding:24px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
    <!-- Header -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2 style="margin:0; font-size:20px; font-weight:600; color:#1e293b;">
            📚 Quản lý Học phần
        </h2>
        <a href="<?php echo e(route('courses.create')); ?>" style="background:#16a34a; color:white; padding:10px 20px; border-radius:6px; text-decoration:none; font-weight:500; display:inline-flex; align-items:center; gap:8px;">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
            </svg>
            Thêm Học phần
        </a>
    </div>

    <?php if(session('success')): ?>
    <div style="background:#d1fae5; border-left:4px solid #10b981; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#065f46;">
        ✓ <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div style="background:#fee2e2; border-left:4px solid #ef4444; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#991b1b;">
        ✗ <?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>

    <!-- Filters - Single Row -->
    <form action="<?php echo e(route('courses.index')); ?>" method="GET" style="display:flex; gap:12px; margin-bottom:20px; align-items:end;">
        <div style="flex:1;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Khoa</label>
            <select name="faculty_id" style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                <option value="">-- Tất cả Khoa --</option>
                <?php $__currentLoopData = $faculties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faculty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($faculty->id); ?>" <?php echo e(request('faculty_id') == $faculty->id ? 'selected' : ''); ?>>
                    <?php echo e($faculty->name); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div style="flex:2;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Tìm kiếm</label>
            <input
                type="text"
                name="search"
                value="<?php echo e(request('search')); ?>"
                placeholder="Nhập mã môn học hoặc tên môn học..."
                style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
        </div>

        <button type="submit" style="background:#1976d2; color:white; padding:10px 24px; border:none; border-radius:6px; cursor:pointer; font-weight:500; display:flex; align-items:center; gap:8px;">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
            </svg>
            Lọc
        </button>

        <?php if(request('search') || request('faculty_id')): ?>
        <a href="<?php echo e(route('courses.index')); ?>" style="padding:10px 20px; border:1px solid #cbd5e0; border-radius:6px; text-decoration:none; color:#475569; font-weight:500;">
            Xóa bộ lọc
        </a>
        <?php endif; ?>
    </form>

    <!-- Table with Zebra Striping -->
    <div style="overflow-x:auto;">
        <table class="table-zebra" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">MÃ MH</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">TÊN MÔN HỌC</th>
                    <th style="padding:12px; text-align:center; font-weight:600; color:#475569; font-size:13px;">TÍN CHỈ</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">KHOA</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">MÔN TIÊN QUYẾT</th>
                    <th style="padding:12px; text-align:center; font-weight:600; color:#475569; font-size:13px;">THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr style="border-bottom:1px solid #e2e8f0;">
                    <td style="padding:12px;">
                        <code style="background:#fef3c7; color:#92400e; padding:4px 8px; border-radius:4px; font-size:13px; font-weight:600;">
                            <?php echo e($course->code); ?>

                        </code>
                    </td>
                    <td style="padding:12px; font-weight:500; color:#1e293b;"><?php echo e($course->name); ?></td>
                    <td style="padding:12px; text-align:center;">
                        <span style="background:#dbeafe; color:#1e40af; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:600;">
                            <?php echo e($course->credits); ?> TC
                        </span>
                    </td>
                    <td style="padding:12px;">
                        <?php
                        $facultyClass = 'faculty-default';
                        if(stripos($course->faculty->name, 'Công nghệ') !== false || stripos($course->faculty->name, 'CNTT') !== false) {
                        $facultyClass = 'faculty-cntt';
                        } elseif(stripos($course->faculty->name, 'Kinh tế') !== false) {
                        $facultyClass = 'faculty-kt';
                        } elseif(stripos($course->faculty->name, 'Ngoại ngữ') !== false) {
                        $facultyClass = 'faculty-nn';
                        }
                        ?>
                        <span class="faculty-badge <?php echo e($facultyClass); ?>">
                            <?php echo e($course->faculty->code); ?>

                        </span>
                    </td>
                    <td style="padding:12px;">
                        <?php if($course->prerequisites->count() > 0): ?>
                        <?php $__currentLoopData = $course->prerequisites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prereq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="prereq-badge"><?php echo e($prereq->code); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <span style="color:#94a3b8; font-size:13px;">--</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding:12px; text-align:center;">
                        <div style="display:inline-flex; gap:6px;">
                            <!-- Nút Xem chi tiết -->
                            <button
                                onclick="viewCourseDetail(<?php echo e($course->id); ?>)"
                                class="action-btn"
                                style="background:#10b981; color:white; border:none; cursor:pointer;"
                                title="Xem chi tiết">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                </svg>
                            </button>

                            <!-- Nút Sửa -->
                            <a
                                href="<?php echo e(route('courses.edit', $course)); ?>"
                                class="action-btn"
                                style="background:#1976d2; color:white; text-decoration:none;"
                                title="Sửa">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                                </svg>
                            </a>

                            <!-- Nút Sửa điều kiện tiên quyết -->
                            <button
                                onclick="openPrerequisitesModal(<?php echo e($course->id); ?>, '<?php echo e($course->code); ?>', '<?php echo e($course->name); ?>')"
                                class="action-btn"
                                style="background:#8b5cf6; color:white; border:none; cursor:pointer;"
                                title="Điều kiện tiên quyết">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V2z" />
                                </svg>
                            </button>

                            <!-- Nút Xóa -->
                            <form action="<?php echo e(route('courses.destroy', $course)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('⚠️ Xác nhận xóa môn học <?php echo e($course->code); ?>?\n\nLưu ý: Nếu có lớp học phần liên quan sẽ bị xóa theo!');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button
                                    type="submit"
                                    class="action-btn"
                                    style="background:#dc2626; color:white; border:none; cursor:pointer;"
                                    title="Xóa">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" style="padding:60px 20px; text-align:center; color:#94a3b8;">
                        <svg width="64" height="64" fill="currentColor" viewBox="0 0 16 16" style="opacity:0.3; margin-bottom:16px;">
                            <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z" />
                        </svg>
                        <div style="font-size:16px; font-weight:500;">Không tìm thấy học phần nào</div>
                        <div style="font-size:14px; margin-top:4px;">Thử thay đổi bộ lọc hoặc thêm học phần mới</div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if($courses->hasPages()): ?>
    <div style="margin-top:24px; display:flex; justify-content:center;">
        <?php echo e($courses->links()); ?>

    </div>
    <?php endif; ?>
</div>

<!-- Modal Xem Chi tiết Môn học -->
<div id="detailModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:12px; width:90%; max-width:700px; max-height:85vh; overflow:auto; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="padding:24px; border-bottom:2px solid #10b981; background:linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <h3 style="margin:0; font-size:20px; font-weight:600; color:white; display:flex; align-items:center; gap:10px;">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                </svg>
                Chi tiết Môn học
            </h3>
        </div>

        <div style="padding:28px;">
            <!-- Mã và Tên môn học -->
            <div style="background:#f8fafc; padding:20px; border-radius:8px; border-left:4px solid #10b981; margin-bottom:20px;">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
                    <code id="detailCode" style="background:#fef3c7; color:#92400e; padding:6px 12px; border-radius:6px; font-size:15px; font-weight:700; letter-spacing:0.5px;"></code>
                    <span id="detailStatus" style="padding:4px 12px; border-radius:12px; font-size:12px; font-weight:600;"></span>
                </div>
                <h4 id="detailName" style="margin:0; font-size:18px; font-weight:600; color:#1e293b;"></h4>
            </div>

            <!-- Grid thông tin -->
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
                <!-- Số tín chỉ -->
                <div>
                    <label style="display:block; font-size:13px; color:#64748b; font-weight:500; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px;">
                        📊 Số tín chỉ
                    </label>
                    <div id="detailCredits" style="background:#dbeafe; color:#1e40af; padding:8px 16px; border-radius:8px; font-size:16px; font-weight:700; text-align:center;"></div>
                </div>

                <!-- Khoa -->
                <div>
                    <label style="display:block; font-size:13px; color:#64748b; font-weight:500; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px;">
                        🏛️ Khoa quản lý
                    </label>
                    <div id="detailFaculty" style="padding:8px 16px; border-radius:8px; font-size:14px; font-weight:600; text-align:center;"></div>
                </div>
            </div>

            <!-- Loại học phần -->
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; color:#64748b; font-weight:500; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px;">
                    🏷️ Loại học phần
                </label>
                <div id="detailType" style="background:#f1f5f9; padding:10px 16px; border-radius:8px; font-size:14px; color:#475569; font-weight:500;"></div>
            </div>

            <!-- Môn tiên quyết -->
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; color:#64748b; font-weight:500; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px;">
                    🔗 Điều kiện tiên quyết
                </label>
                <div id="detailPrerequisites" style="background:#f1f5f9; padding:12px 16px; border-radius:8px; min-height:40px;"></div>
            </div>

            <!-- Mô tả -->
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; color:#64748b; font-weight:500; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px;">
                    📝 Mô tả chi tiết
                </label>
                <div id="detailDescription" style="background:#f8fafc; padding:16px; border-radius:8px; border:1px solid #e2e8f0; font-size:14px; color:#475569; line-height:1.6; min-height:60px; white-space:pre-wrap;"></div>
            </div>

            <!-- Nút đóng -->
            <div style="display:flex; justify-content:flex-end; gap:12px; padding-top:16px; border-top:1px solid #e2e8f0;">
                <button onclick="closeDetailModal()" style="padding:10px 24px; border:1px solid #cbd5e0; border-radius:6px; background:white; color:#475569; cursor:pointer; font-weight:500;">
                    Đóng
                </button>
                <button onclick="editFromDetail()" id="editFromDetailBtn" style="padding:10px 24px; border:none; border-radius:6px; background:#1976d2; color:white; cursor:pointer; font-weight:500; display:flex; align-items:center; gap:8px;">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                    </svg>
                    Chỉnh sửa
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thiết lập Điều kiện Tiên quyết -->
<div id="prerequisitesModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:12px; width:90%; max-width:600px; max-height:80vh; overflow:auto; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="padding:24px; border-bottom:1px solid #e2e8f0;">
            <h3 style="margin:0; font-size:18px; font-weight:600; color:#1e293b;">
                🔗 Thiết lập Điều kiện Tiên quyết
            </h3>
            <p style="margin:8px 0 0 0; color:#64748b; font-size:14px;">
                Môn học: <strong id="modalCourseName"></strong> (<code id="modalCourseCode"></code>)
            </p>
        </div>

        <div style="padding:24px;">
            <form id="prerequisitesForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div style="margin-bottom:16px;">
                    <label style="display:block; margin-bottom:8px; font-weight:500; color:#475569;">
                        Chọn các môn học tiên quyết:
                    </label>
                    <select multiple id="prerequisiteSelect" name="prerequisites[]" style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; min-height:200px;">
                        <?php $__currentLoopData = $allCourses ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c->id); ?>"><?php echo e($c->code); ?> - <?php echo e($c->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <small style="color:#64748b; font-size:13px; margin-top:4px; display:block;">
                        💡 Giữ Ctrl (hoặc Cmd) để chọn nhiều môn
                    </small>
                </div>

                <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:24px;">
                    <button type="button" onclick="closePrerequisitesModal()" style="padding:10px 20px; border:1px solid #cbd5e0; border-radius:6px; background:white; color:#475569; cursor:pointer; font-weight:500;">
                        Hủy
                    </button>
                    <button type="submit" style="padding:10px 20px; border:none; border-radius:6px; background:#1976d2; color:white; cursor:pointer; font-weight:500;">
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentCourseId = null;
    const ADMIN_COURSES_BASE = "<?php echo e(url('admin/courses')); ?>";

    // Hàm xem chi tiết môn học
    function viewCourseDetail(courseId) {
        currentCourseId = courseId;

        // Hiển thị modal
        document.getElementById('detailModal').style.display = 'flex';

        // Load dữ liệu qua AJAX
        fetch(`${ADMIN_COURSES_BASE}/${courseId}/detail`)
            .then(res => res.json())
            .then(data => {
                // Mã môn học
                document.getElementById('detailCode').textContent = data.code;

                // Tên môn học
                document.getElementById('detailName').textContent = data.name;

                // Trạng thái
                const statusElem = document.getElementById('detailStatus');
                if (data.is_active) {
                    statusElem.textContent = '✓ Hoạt động';
                    statusElem.style.background = '#dcfce7';
                    statusElem.style.color = '#166534';
                } else {
                    statusElem.textContent = '✕ Ngưng hoạt động';
                    statusElem.style.background = '#fee2e2';
                    statusElem.style.color = '#991b1b';
                }

                // Số tín chỉ
                document.getElementById('detailCredits').textContent = data.credits + ' Tín chỉ';

                // Khoa
                const facultyElem = document.getElementById('detailFaculty');
                facultyElem.textContent = data.faculty.name;

                // Áp dụng màu sắc cho khoa
                let facultyClass = 'faculty-default';
                if (data.faculty.name.includes('Công nghệ') || data.faculty.name.includes('CNTT')) {
                    facultyElem.style.background = '#dbeafe';
                    facultyElem.style.color = '#1e40af';
                } else if (data.faculty.name.includes('Kinh tế')) {
                    facultyElem.style.background = '#dcfce7';
                    facultyElem.style.color = '#166534';
                } else if (data.faculty.name.includes('Ngoại ngữ')) {
                    facultyElem.style.background = '#fef3c7';
                    facultyElem.style.color = '#92400e';
                } else {
                    facultyElem.style.background = '#f3e8ff';
                    facultyElem.style.color = '#6b21a8';
                }

                // Loại học phần
                const typeElem = document.getElementById('detailType');
                if (data.type) {
                    typeElem.textContent = data.type;
                    typeElem.style.fontWeight = '600';
                } else {
                    typeElem.textContent = 'Chưa phân loại';
                    typeElem.style.color = '#94a3b8';
                    typeElem.style.fontStyle = 'italic';
                }

                // Môn tiên quyết
                const prereqElem = document.getElementById('detailPrerequisites');
                if (data.prerequisites && data.prerequisites.length > 0) {
                    prereqElem.innerHTML = data.prerequisites.map(p =>
                        `<span style="background:#374151; color:#9ca3af; padding:6px 12px; border-radius:6px; font-size:13px; margin-right:8px; margin-bottom:8px; display:inline-block; font-weight:600;">
                            ${p.code} - ${p.name}
                        </span>`
                    ).join('');
                } else {
                    prereqElem.innerHTML = '<span style="color:#94a3b8; font-style:italic;">Không có điều kiện tiên quyết</span>';
                }

                // Mô tả
                const descElem = document.getElementById('detailDescription');
                if (data.description && data.description.trim()) {
                    descElem.textContent = data.description;
                    descElem.style.color = '#475569';
                } else {
                    descElem.innerHTML = '<em style="color:#94a3b8;">Chưa có mô tả chi tiết</em>';
                }
            })
            .catch(err => {
                console.error('Error loading course detail:', err);
                alert('Không thể tải thông tin chi tiết môn học');
                closeDetailModal();
            });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
        currentCourseId = null;
    }

    function editFromDetail() {
        if (currentCourseId) {
            window.location.href = `${ADMIN_COURSES_BASE}/${currentCourseId}/edit`;
        }
    }

    // Đóng modal khi click bên ngoài
    document.getElementById('detailModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetailModal();
        }
    });

    function openPrerequisitesModal(courseId, courseCode, courseName) {
        document.getElementById('modalCourseCode').textContent = courseCode;
        document.getElementById('modalCourseName').textContent = courseName;
        document.getElementById('prerequisitesForm').action = `${ADMIN_COURSES_BASE}/${courseId}/prerequisites`;
        document.getElementById('prerequisitesModal').style.display = 'flex';

        // Load current prerequisites via AJAX
        fetch(`${ADMIN_COURSES_BASE}/${courseId}/prerequisites`)
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('prerequisiteSelect');
                Array.from(select.options).forEach(option => {
                    option.selected = data.prerequisites.includes(parseInt(option.value));
                });
            })
            .catch(err => console.error('Error loading prerequisites:', err));
    }

    function closePrerequisitesModal() {
        document.getElementById('prerequisitesModal').style.display = 'none';
    }

    // Handle form submission via AJAX
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('prerequisitesForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const url = this.action;

                fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            alert(data.message);
                            closePrerequisitesModal();
                            // Reload page to see updated prerequisites
                            window.location.reload();
                        } else {
                            alert(data.message || 'Có lỗi xảy ra');
                        }
                    })
                    .catch(err => {
                        console.error('Error updating prerequisites:', err);
                        alert('Có lỗi xảy ra khi cập nhật điều kiện tiên quyết');
                    });
            });
        }
    });

    // Close modal when clicking outside
    document.getElementById('prerequisitesModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closePrerequisitesModal();
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/admin/courses/index.blade.php ENDPATH**/ ?>