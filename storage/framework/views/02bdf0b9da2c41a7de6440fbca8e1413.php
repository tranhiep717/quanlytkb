

<?php $__env->startSection('title', (!empty($assignmentMode) && $assignmentMode) ? 'Phân công giảng viên' : 'Quản lý Lớp học phần'); ?>

<?php $__env->startSection('content'); ?>
<style>
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
</style>

<div style="background:white; padding:24px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
    <!-- Header -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2 style="margin:0; font-size:20px; font-weight:600; color:#1e293b;">
            <?php echo e(!empty($assignmentMode) && $assignmentMode ? 'Phân công giảng viên' : 'Quản lý Lớp học phần'); ?>

        </h2>
        <?php if (! (!empty($assignmentMode) && $assignmentMode)): ?>
        <a href="<?php echo e(route('class-sections.create')); ?>"
            style="background:#16a34a; color:white; padding:10px 20px; border-radius:6px; text-decoration:none; font-weight:500; display:inline-flex; align-items:center; gap:8px;">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
            </svg>
            Thêm Lớp học phần
        </a>
        <?php endif; ?>
    </div>

    <?php if(session('success')): ?>
    <div
        style="background:#d1fae5; border-left:4px solid #10b981; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#065f46;">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div
        style="background:#fee2e2; border-left:4px solid #ef4444; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#991b1b;">
        <?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>
    <?php if(session('warning')): ?>
    <div
        style="background:#fef3c7; border-left:4px solid #f59e0b; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#92400e;">
        <?php echo e(session('warning')); ?>

    </div>
    <?php endif; ?>

    <!-- Filters -->
    <form action="<?php echo e(route('class-sections.index')); ?>" method="GET" style="margin-bottom:20px;">
        <?php if(!empty($assignmentMode) && $assignmentMode): ?>
        <input type="hidden" name="mode" value="assign" />
        <?php endif; ?>
        <!-- Row 1 -->
        <div style="display:flex; gap:12px; margin-bottom:12px; align-items:end;">
            <div style="flex:1;">
                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Năm học</label>
                <select name="academic_year" style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                    <?php $selectedYear = $filters['academic_year'] ?? $academicYear; ?>
                    <?php if(!empty($academicYears) && count($academicYears)): ?>
                    <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($year); ?>" <?php echo e($selectedYear == $year ? 'selected' : ''); ?>><?php echo e($year); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                    <option value="<?php echo e($selectedYear); ?>" selected><?php echo e($selectedYear); ?></option>
                    <?php endif; ?>
                </select>
            </div>
            <div style="flex:1;">
                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Học
                    kỳ</label>
                <select name="term"
                    style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                    <option value="">-- Tất cả --</option>
                    <option value="HK1" <?php echo e(($filters['term'] ?? $term) == 'HK1' ? 'selected' : ''); ?>>Học kỳ 1</option>
                    <option value="HK2" <?php echo e(($filters['term'] ?? $term) == 'HK2' ? 'selected' : ''); ?>>Học kỳ 2</option>
                    <option value="HE" <?php echo e(($filters['term'] ?? $term) == 'HE' ? 'selected' : ''); ?>>Học kỳ Hè</option>
                </select>
            </div>
            <div style="flex:1.5;">
                <label
                    style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Khoa</label>
                <select name="faculty_id"
                    style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                    <option value="">-- Tất cả --</option>
                    <?php $__currentLoopData = $faculties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faculty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($faculty->id); ?>"
                        <?php echo e(($filters['faculty_id'] ?? '') == $faculty->id ? 'selected' : ''); ?>><?php echo e($faculty->name); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div style="flex:2;">
                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Tìm
                    kiếm</label>
                <input type="text" name="search" value="<?php echo e($filters['search'] ?? ''); ?>" placeholder="Mã lớp, môn học..."
                    style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
            </div>
        </div>

        <!-- Row 2 -->
        <div style="display:flex; gap:12px; align-items:end;">
            <div style="flex:1;">
                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Trạng
                    thái</label>
                <select name="status"
                    style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                    <option value="">-- Tất cả --</option>
                    <option value="active" <?php echo e(($filters['status'] ?? '') == 'active' ? 'selected' : ''); ?>>Hoạt động
                    </option>
                    <option value="locked" <?php echo e(($filters['status'] ?? '') == 'locked' ? 'selected' : ''); ?>>Tạm khóa
                    </option>
                </select>
            </div>
            <div style="flex:1.5;">
                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Phòng
                    học</label>
                <select name="room_id"
                    style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                    <option value="">-- Tất cả --</option>
                    <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($room->id); ?>" <?php echo e(($filters['room_id'] ?? '') == $room->id ? 'selected' : ''); ?>>
                        <?php echo e($room->code); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div style="flex:1;">
                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Ca
                    học</label>
                <select name="shift_id"
                    style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                    <option value="">-- Tất cả --</option>
                    <?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($shift->id); ?>" <?php echo e(($filters['shift_id'] ?? '') == $shift->id ? 'selected' : ''); ?>>
                        Ca <?php echo e($shift->start_period); ?>-<?php echo e($shift->end_period); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div style="flex:1.5;">
                <div style="padding-top:26px;">
                    <label style="display:inline-flex; align-items:center; gap:6px; cursor:pointer;">
                        <input type="checkbox" name="unassigned_lecturer" value="1"
                            <?php echo e((($filters['unassigned_lecturer'] ?? '') == '1') ? 'checked' : ''); ?>

                            style="width:18px; height:18px;">
                        <span style="font-size:14px; color:#475569;">Chưa phân công GV</span>
                    </label>
                </div>
            </div>
            <button type="submit"
                style="background:#1976d2; color:white; padding:10px 24px; border:none; border-radius:6px; cursor:pointer; font-weight:500; display:flex; align-items:center; gap:8px;">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path
                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                </svg>
                Lọc
            </button>
            <?php if(request()->hasAny(['search','faculty_id','status','room_id','shift_id','unassigned_lecturer'])): ?>
            <a href="<?php echo e(route('class-sections.index')); ?>"
                style="padding:10px 20px; border:1px solid #cbd5e0; border-radius:6px; text-decoration:none; color:#475569; font-weight:500;">Xóa
                bộ lọc</a>
            <?php endif; ?>
        </div>
    </form>

    <!-- Table -->
    <div style="overflow-x:auto;">
        <table class="table-zebra" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">MÃ LHP
                    </th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">TÊN MÔN
                        HỌC</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">KHOA</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">GIẢNG
                        VIÊN</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">LỊCH
                        &amp; PHÒNG</th>
                    <th style="padding:12px; text-align:center; font-weight:600; color:#475569; font-size:13px;">SĨ SỐ
                    </th>
                    <th style="padding:12px; text-align:center; font-weight:600; color:#475569; font-size:13px;">TRẠNG
                        THÁI</th>
                    <th style="padding:12px; text-align:center; font-weight:600; color:#475569; font-size:13px;">THAO
                        TÁC</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $classSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                $enrolled = $cs->registrations->count();
                $capacity = $cs->max_capacity;
                $percentage = $capacity > 0 ? ($enrolled / $capacity * 100) : 0;
                $days = ['', 'Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','CN'];
                if ($percentage >= 90) { $bgColor = '#fee2e2'; $textColor = '#991b1b'; }
                elseif ($percentage >= 70) { $bgColor = '#fef3c7'; $textColor = '#92400e'; }
                else { $bgColor = '#dcfce7'; $textColor = '#166534'; }
                ?>
                <tr style="border-bottom:1px solid #e2e8f0;">
                    <td style="padding:12px;"><code
                            style="background:#fef3c7; color:#92400e; padding:4px 8px; border-radius:4px; font-size:13px; font-weight:600;"><?php echo e($cs->course->code ?? 'N/A'); ?>-<?php echo e($cs->section_code); ?></code>
                    </td>
                    <td style="padding:12px; font-weight:500; color:#1e293b;"><?php echo e($cs->course->name ?? 'N/A'); ?></td>
                    <td style="padding:12px;"><span
                            style="background:#dbeafe; color:#1e40af; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:500;"><?php echo e($cs->course->faculty->code ?? 'N/A'); ?></span>
                    </td>
                    <td style="padding:12px;">
                        <?php if($cs->lecturer): ?>
                        <span style="color:#166534;"> <?php echo e($cs->lecturer->name); ?></span>
                        <?php else: ?>
                        <span style="color:#94a3b8; font-style:italic;">Chưa phân công</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding:12px; font-size:13px;">
                        <div style="margin-bottom:2px;"><strong><?php echo e($days[$cs->day_of_week] ?? ''); ?></strong>
                            <?php if($cs->shift): ?><span style="color:#64748b;">(Ca
                                <?php echo e($cs->shift->start_period); ?>-<?php echo e($cs->shift->end_period); ?>)</span><?php endif; ?></div>
                        <?php if($cs->room): ?>
                        <span style="color:#64748b;"> <?php echo e($cs->room->code); ?></span>
                        <?php else: ?>
                        <span style="color:#94a3b8; font-style:italic;">Chưa xếp phòng</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding:12px; text-align:center;"><span
                            style="background:<?php echo e($bgColor); ?>; color:<?php echo e($textColor); ?>; padding:4px 10px; border-radius:8px; font-size:12px; font-weight:600;"><?php echo e($enrolled); ?>/<?php echo e($capacity); ?></span>
                    </td>
                    <td style="padding:12px; text-align:center;">
                        <?php if(($cs->status ?? 'active') === 'active'): ?>
                        <span
                            style="background:#dcfce7; color:#166534; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:600;">
                            Hoạt động</span>
                        <?php else: ?>
                        <span
                            style="background:#e5e7eb; color:#374151; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:600;">
                            Tạm khóa</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding:12px; text-align:center;">
                        <div style="display:inline-flex; gap:6px;">
                            <button onclick="viewDetail(<?php echo e($cs->id); ?>)" class="action-btn"
                                style="background:#10b981; color:white; border:none; cursor:pointer;"
                                title="Xem chi tiết">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                    <path
                                        d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                </svg>
                            </button>
                            <?php if(!empty($assignmentMode) && $assignmentMode): ?>
                            
                            <?php if(!$cs->lecturer): ?>
                            <button onclick="openAssignModal(<?php echo e($cs->id); ?>)" class="action-btn"
                                style="background:#16a34a; color:white; border:none; cursor:pointer;" title="Phân công">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M10,17V14H3V10H10V7L15,12L10,17M19,3H5C3.89,3 3,3.89 3,5V8H5V5H19V19H5V16H3V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3Z" />
                                </svg>
                            </button>
                            <?php else: ?>
                            <button onclick="openAssignModal(<?php echo e($cs->id); ?>)" class="action-btn"
                                style="background:#1976d2; color:white; border:none; cursor:pointer;" title="Đổi giảng viên">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5z" />
                                </svg>
                            </button>
                            <button onclick="unassignLecturer(<?php echo e($cs->id); ?>)" class="action-btn"
                                style="background:#dc2626; color:white; border:none; cursor:pointer;" title="Thu hồi phân công">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1z" />
                                </svg>
                            </button>
                            <?php endif; ?>
                            <?php else: ?>
                            
                            <a href="<?php echo e(route('class-sections.edit', $cs)); ?>" class="action-btn"
                                style="background:#1976d2; color:white; text-decoration:none;" title="Sửa">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                                </svg>
                            </a>
                            <form action="<?php echo e(route('class-sections.destroy', $cs)); ?>" method="POST" style="display:inline;"
                                onsubmit="return confirm('Xác nhận xóa lớp <?php echo e($cs->course->code ?? ''); ?>-<?php echo e($cs->section_code); ?>?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="action-btn" style="background:#dc2626; color:white; border:none; cursor:pointer;" title="Xóa">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1z" />
                                    </svg>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" style="padding:60px 20px; text-align:center; color:#94a3b8;">
                        <svg width="64" height="64" fill="currentColor" viewBox="0 0 16 16"
                            style="opacity:0.3; margin-bottom:16px;">
                            <path
                                d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z" />
                        </svg>
                        <div style="font-size:16px; font-weight:500;">Không tìm thấy lớp học phần nào</div>
                        <div style="font-size:14px; margin-top:4px;">Thử thay đổi bộ lọc hoặc thêm lớp học phần mới
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($classSections->hasPages()): ?>
    <div style="margin-top:24px; display:flex; justify-content:center;"><?php echo e($classSections->appends($filters)->links()); ?>

    </div>
    <?php endif; ?>
</div>

<!-- Modal -->
<div id="detailModal"
    style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div
        style="background:white; border-radius:12px; width:90%; max-width:800px; max-height:85vh; overflow:auto; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
        <div
            style="padding:24px; border-bottom:2px solid #10b981; background:linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <h3 style="margin:0; font-size:20px; font-weight:600; color:white;">Chi tiết Lớp học phần</h3>
        </div>
        <div style="padding:28px;" id="detailBody">Đang tải...</div>
    </div>
</div>

<!-- Assign Modal -->
<div id="assignModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,.5); z-index:10000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; width:92%; max-width:860px; max-height:85vh; overflow:auto; box-shadow:0 10px 25px rgba(0,0,0,.15);">
        <div style="padding:18px 24px; border-bottom:1px solid #e5e7eb; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="margin:0; font-size:18px; font-weight:600; color:#111827;">Phân công/Đổi giảng viên</h3>
            <button onclick="closeAssignModal()" style="background:none; border:none; font-size:18px; cursor:pointer;">×</button>
        </div>
        <div style="padding:18px 24px;">
            <div id="assignHeader" style="background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; padding:12px 14px; margin-bottom:12px; display:none;"></div>
            <div style="display:flex; gap:12px; align-items:end; margin-bottom:12px;">
                <div style="flex:1;">
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Khoa</label>
                    <select id="assignFaculty" style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px;"></select>
                </div>
                <div style="flex:2;">
                    <label style="display:block; font-size:12px; color:#6b7280; margin-bottom:6px;">Tìm giảng viên</label>
                    <input id="assignSearch" type="text" placeholder="Tên/Mã/Email" style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px;">
                </div>
                <div style="display:flex; flex-direction:column; gap:6px; padding-bottom:4px;">
                    <label style="display:inline-flex; align-items:center; gap:6px; font-size:13px; color:#374151;">
                        <input type="checkbox" id="onlySameFaculty"> Chỉ cùng Khoa
                    </label>
                    <label style="display:inline-flex; align-items:center; gap:6px; font-size:13px; color:#374151;">
                        <input type="checkbox" id="onlyFree"> Chỉ hiển thị GV rảnh
                    </label>
                </div>
                <button id="assignRefresh" style="height:40px; padding:0 16px; background:#1976d2; color:#fff; border:none; border-radius:6px; cursor:pointer;">Lọc</button>
            </div>
            <div id="assignAlert" style="display:none; margin-bottom:12px; padding:10px 12px; border-radius:6px;"></div>
            <div id="candidatesWrap" style="border:1px solid #e5e7eb; border-radius:8px; overflow:hidden;">
                <table style="width:100%; border-collapse:collapse;">
                    <thead style="background:#f9fafb;">
                        <tr>
                            <th style="text-align:left; padding:10px; font-size:13px; color:#6b7280;">Giảng viên</th>
                            <th style="text-align:left; padding:10px; font-size:13px; color:#6b7280;">Khoa</th>
                            <th style="text-align:center; padding:10px; font-size:13px; color:#6b7280;">Tải</th>
                            <th style="text-align:center; padding:10px; font-size:13px; color:#6b7280;">Trạng thái</th>
                            <th style="text-align:center; padding:10px; font-size:13px; color:#6b7280; width:110px;">Chọn</th>
                        </tr>
                    </thead>
                    <tbody id="candidatesBody"></tbody>
                </table>
            </div>
            <div id="assignReasonWrap" style="display:none; margin-top:14px;">
                <label for="assignReason" style="display:block; font-size:13px; color:#374151; margin-bottom:6px;">Lý do thay đổi (bắt buộc khi đổi giảng viên)</label>
                <textarea id="assignReason" rows="3" placeholder="Ví dụ: GV bận công tác đột xuất..." style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px;"></textarea>
            </div>
            <div style="margin-top:14px; display:flex; justify-content:space-between; align-items:center; gap:12px;">
                <button onclick="closeAssignModal()" style="padding:8px 16px; border:1px solid #cbd5e0; border-radius:6px; background:#fff; color:#374151;">Hủy bỏ</button>
                <button id="assignSubmit" disabled style="padding:8px 16px; border:none; border-radius:6px; background:#16a34a; color:#fff; cursor:not-allowed;">Xác nhận</button>
            </div>
        </div>
    </div>
    <input type="hidden" id="assignSectionId" value="">
    <input type="hidden" id="assignCourseFaculty" value="">
    <input type="hidden" id="assignYear" value="">
    <input type="hidden" id="assignTerm" value="">
    <input type="hidden" id="assignShift" value="">
    <input type="hidden" id="assignDOW" value="">
    <input type="hidden" id="assignRoom" value="">
    <input type="hidden" id="assignHasLecturer" value="0">
    <input type="hidden" id="assignSelectedLecturerId" value="">
</div>

<script>
    // Provide faculties list for Assign modal filter
    const FACULTIES = <?php echo json_encode(($faculties ?? collect())->map(function($f) {
        return ['id' => $f->id, 'name' => $f->name];
    })->values(), 512) ?>;
    // Absolute base URL for admin class-section APIs (works with subfolder deployments like /quanlytkbieu/public)
    const ADMIN_CLASS_SECTIONS_BASE = "<?php echo e(url('admin/class-sections')); ?>";

    // CSRF helpers and unified fetch wrapper
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return undefined;
    }

    async function apiFetch(input, init = {}) {
        const headers = new Headers(init.headers || {});
        headers.set('Accept', 'application/json');
        headers.set('X-Requested-With', 'XMLHttpRequest');
        // Prefer meta token; fall back to XSRF-TOKEN cookie
        try {
            const metaToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            if (metaToken) headers.set('X-CSRF-TOKEN', metaToken);
        } catch (e) {}
        const xsrfCookie = getCookie('XSRF-TOKEN');
        if (xsrfCookie) headers.set('X-XSRF-TOKEN', decodeURIComponent(xsrfCookie));

        const res = await fetch(input, {
            credentials: 'same-origin',
            ...init,
            headers,
        });

        const contentType = res.headers.get('content-type') || '';
        const isJson = contentType.includes('application/json');
        const payload = isJson ? await res.json().catch(() => ({})) : await res.text();

        if (!res.ok) {
            // Laravel common cases: 419 Page Expired, 401/403 unauthorized
            if (res.status === 419) {
                throw {
                    status: 419,
                    code: 'CSRF',
                    message: 'Phiên đăng nhập đã hết hạn (419). Vui lòng tải lại trang.'
                };
            }
            if (res.status === 401 || res.status === 403) {
                throw {
                    status: res.status,
                    code: 'UNAUTHORIZED',
                    message: 'Phiên đăng nhập không hợp lệ hoặc thiếu quyền. Vui lòng đăng nhập lại.'
                };
            }
            if (isJson && payload && payload.message) {
                throw payload;
            }
            throw {
                status: res.status,
                message: typeof payload === 'string' ? payload : 'Yêu cầu thất bại.'
            };
        }

        return isJson ? payload : {
            ok: true,
            html: payload
        };
    }

    function viewDetail(id) {
        const modal = document.getElementById('detailModal');
        const body = document.getElementById('detailBody');
        modal.style.display = 'flex';
        body.innerHTML = 'Đang tải dữ liệu...';
        apiFetch(`${ADMIN_CLASS_SECTIONS_BASE}/${id}/detail`)
            .then(data => {
                const cs = data.class_section;
                const students = data.students || [];
                const days = ['', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'CN'];
                body.innerHTML = `
        <div style="background:#f8fafc; padding:20px; border-radius:8px; margin-bottom:20px;">
          <code style="background:#fef3c7; color:#92400e; padding:6px 12px; border-radius:6px; font-weight:700;">${cs.course?.code||'N/A'}-${cs.section_code}</code>
          <h4 style="margin:12px 0 0 0; font-size:18px;">${cs.course?.name||'N/A'}</h4>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
          <div><label style="font-size:13px; color:#64748b;">Năm học - Học kỳ</label><div style="background:#f1f5f9; padding:10px; border-radius:6px; margin-top:6px;">${cs.academic_year} - ${cs.term}</div></div>
          <div><label style="font-size:13px; color:#64748b;">Khoa</label><div style="background:#f1f5f9; padding:10px; border-radius:6px; margin-top:6px;">${cs.course?.faculty?.name||'N/A'}</div></div>
          <div><label style="font-size:13px; color:#64748b;">Giảng viên</label><div style="background:#f1f5f9; padding:10px; border-radius:6px; margin-top:6px;">${cs.lecturer?cs.lecturer.name:'<em>Chưa phân công</em>'}</div></div>
          <div><label style="font-size:13px; color:#64748b;">Lịch học</label><div style="background:#f1f5f9; padding:10px; border-radius:6px; margin-top:6px;">${days[cs.day_of_week]||'N/A'}${cs.shift?` (Ca ${cs.shift.start_period}-${cs.shift.end_period})`:''}</div></div>
          <div><label style="font-size:13px; color:#64748b;">Phòng học</label><div style="background:#f1f5f9; padding:10px; border-radius:6px; margin-top:6px;">${cs.room?cs.room.code:'<em>Chưa xếp phòng</em>'}</div></div>
          <div><label style="font-size:13px; color:#64748b;">Sĩ số</label><div style="background:#f1f5f9; padding:10px; border-radius:6px; margin-top:6px; text-align:center; font-weight:700;">${students.length}/${cs.max_capacity}</div></div>
        </div>
        <div>
          <h5 style="font-size:16px; margin-bottom:12px;">Danh sách Sinh viên (${students.length})</h5>
          ${students.length>0 ? `<div style="background:#f8fafc; border-radius:8px; overflow:hidden;"><table style="width:100%;"><thead style="background:#e2e8f0;"><tr><th style="padding:10px; text-align:left;">STT</th><th style="padding:10px; text-align:left;">MSSV</th><th style="padding:10px; text-align:left;">Họ tên</th></tr></thead><tbody>${students.map((s,i)=>`<tr style=\"border-bottom:1px solid #e2e8f0;\"><td style=\"padding:10px;\">${i+1}</td><td style=\"padding:10px;\">${s.student_code}</td><td style=\"padding:10px;\">${s.name}</td></tr>`).join('')}</tbody></table></div>` : '<p style="text-align:center; color:#94a3b8;">Chưa có sinh viên</p>'}
        </div>
        <div style="margin-top:20px; text-align:right;"><button onclick="closeDetailModal()" style="padding:10px 24px; border:1px solid #cbd5e0; border-radius:6px; background:white; color:#475569; cursor:pointer;">Đóng</button></div>
      `;
            })
            .catch((e) => {
                const msg = (e && e.message) ? e.message : 'Không thể tải dữ liệu';
                body.innerHTML = `<p style="color:#dc2626;">${msg}</p>`;
            });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
    }
    // close on backdrop
    (document.getElementById('detailModal') || {}).addEventListener?.('click', function(e) {
        if (e.target === this) closeDetailModal();
    });

    // ----- Assign modal logic -----
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function openAssignModal(sectionId) {
        // Open modal immediately with loading placeholders so users see feedback even if fetch fails
        const modal = document.getElementById('assignModal');
        const header = document.getElementById('assignHeader');
        const tbody = document.getElementById('candidatesBody');
        const alertBox = document.getElementById('assignAlert');
        const submitBtn = document.getElementById('assignSubmit');

        modal.style.display = 'flex';
        header.style.display = 'block';
        header.innerHTML = '<div style="color:#6b7280;">Đang tải thông tin lớp học phần...</div>';
        tbody.innerHTML = '<tr><td colspan="5" style="padding:12px; text-align:center; color:#6b7280;">Đang tải...</td></tr>';
        alertBox.style.display = 'none';
        submitBtn.disabled = true;
        submitBtn.style.cursor = 'not-allowed';
        submitBtn.style.background = '#9ca3af';

        // Keep some context by reading row dataset via a small detail fetch
        apiFetch(`${ADMIN_CLASS_SECTIONS_BASE}/${sectionId}/detail`)
            .then(data => {
                const cs = data.class_section;
                document.getElementById('assignSectionId').value = sectionId;
                document.getElementById('assignCourseFaculty').value = cs.course?.faculty ? cs.course.faculty.name : '';
                document.getElementById('assignYear').value = cs.academic_year;
                document.getElementById('assignTerm').value = cs.term;
                document.getElementById('assignShift').value = cs.shift ? cs.shift.start_period + "-" + cs.shift.end_period : '';
                document.getElementById('assignDOW').value = cs.day_of_week;
                document.getElementById('assignHasLecturer').value = cs.lecturer ? '1' : '0';
                // Default filters
                document.getElementById('onlySameFaculty').checked = true;
                document.getElementById('onlyFree').checked = true;
                // Header info
                const header = document.getElementById('assignHeader');
                const days = ['', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'CN'];
                header.style.display = 'block';
                header.innerHTML = `
                <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                    <div>
                        <div style="font-size:12px; color:#6b7280;">Lớp học phần</div>
                        <div style="font-weight:700;">${cs.course?.code||'N/A'}-${cs.section_code}: ${cs.course?.name||''}</div>
                    </div>
                    <div>
                        <div style="font-size:12px; color:#6b7280;">Lịch</div>
                        <div>${days[cs.day_of_week]||''} ${cs.shift?`(Ca ${cs.shift.start_period}-${cs.shift.end_period})`:''} – Phòng: ${cs.room?cs.room.code:'N/A'}</div>
                    </div>
                    <div>
                        <div style="font-size:12px; color:#6b7280;">Giảng viên hiện tại</div>
                        <div>${cs.lecturer? (cs.lecturer.name) : '<em>Chưa phân công</em>'}</div>
                    </div>
                </div>`;
                // Reason field visibility
                document.getElementById('assignReasonWrap').style.display = cs.lecturer ? 'block' : 'none';
                document.getElementById('assignReason').value = '';
                // Reset selection
                document.getElementById('assignSelectedLecturerId').value = '';
                const submitBtn = document.getElementById('assignSubmit');
                submitBtn.disabled = true;
                submitBtn.style.cursor = 'not-allowed';
                submitBtn.style.background = '#9ca3af';
                submitBtn.textContent = cs.lecturer ? 'Xác nhận đổi giảng viên' : 'Xác nhận phân công';

                // Populate faculty filter from FACULTIES; default to course's faculty
                const facultySelect = document.getElementById('assignFaculty');
                facultySelect.innerHTML = '';
                const optAll = document.createElement('option');
                optAll.value = '';
                optAll.textContent = '-- Tất cả --';
                facultySelect.appendChild(optAll);
                FACULTIES.forEach(f => {
                    const opt = document.createElement('option');
                    opt.value = f.id;
                    opt.textContent = f.name;
                    facultySelect.appendChild(opt);
                });
                if (cs.course?.faculty_id) {
                    facultySelect.value = String(cs.course.faculty_id);
                }

                loadCandidates();
            }).catch((e) => {
                alertBox.style.display = 'block';
                alertBox.style.background = '#fee2e2';
                alertBox.style.color = '#991b1b';
                alertBox.textContent = (e && e.message) ? e.message : 'Không thể mở modal phân công.';
            });
    }

    function closeAssignModal() {
        document.getElementById('assignModal').style.display = 'none';
    }

    function loadCandidates() {
        const sectionId = document.getElementById('assignSectionId').value;
        const q = document.getElementById('assignSearch').value.trim();
        const facultyId = document.getElementById('assignFaculty').value;
        const url = new URL(`${ADMIN_CLASS_SECTIONS_BASE}/${sectionId}/lecturer-candidates`);
        if (q) url.searchParams.set('q', q);
        const onlySame = document.getElementById('onlySameFaculty').checked;
        if (facultyId) url.searchParams.set('faculty_id', facultyId);

        const tbody = document.getElementById('candidatesBody');
        tbody.innerHTML = '<tr><td colspan="5" style="padding:12px; text-align:center; color:#6b7280;">Đang tải...</td></tr>';

        apiFetch(url)
            .then(data => {
                const items = data.candidates || [];
                const filtered = items.filter(c => {
                    if (document.getElementById('onlyFree').checked && c.has_conflict) return false;
                    if (onlySame && !c.qualified) return false;
                    return true;
                });
                if (!filtered.length) {
                    tbody.innerHTML = '<tr><td colspan="5" style="padding:20px; text-align:center; color:#9ca3af;">Không có giảng viên phù hợp</td></tr>';
                    return;
                }
                tbody.innerHTML = filtered.map(c => {
                    const statusBadges = [];
                    if (c.has_conflict) statusBadges.push('<span style="background:#fee2e2;color:#991b1b;padding:4px 8px;border-radius:999px;font-size:12px;">Trùng ca</span>');
                    if (!c.qualified) statusBadges.push('<span style="background:#fef3c7;color:#92400e;padding:4px 8px;border-radius:999px;font-size:12px;">Khác khoa</span>');
                    if (c.current_load >= c.max_load) statusBadges.push('<span style="background:#fee2e2;color:#991b1b;padding:4px 8px;border-radius:999px;font-size:12px;">Quá tải</span>');
                    const disabled = c.has_conflict || (c.current_load >= c.max_load);
                    const btnLabel = 'Chọn';
                    return `<tr style="border-top:1px solid #e5e7eb;">
                    <td style="padding:10px;">${c.name}<div style="font-size:12px;color:#6b7280;">${c.email||''}</div></td>
                    <td style="padding:10px;">${c.faculty||''}</td>
                    <td style="padding:10px; text-align:center;">${c.current_load}/${c.max_load}</td>
                    <td style="padding:10px; text-align:center; display:flex; gap:6px; justify-content:center;">${statusBadges.join(' ')||'<span style="color:#16a34a;">Hợp lệ</span>'}</td>
                    <td style="padding:10px; text-align:center;"><button ${disabled?'disabled':''} onclick="selectCandidate(${c.id}, this)" style="padding:6px 12px; border:none; border-radius:6px; background:${disabled?'#9ca3af':'#2563eb'}; color:#fff; cursor:${disabled?'not-allowed':'pointer'};">${btnLabel}</button></td>
                </tr>`;
                }).join('');
            }).catch((e) => {
                tbody.innerHTML = `<tr><td colspan="5" style="padding:20px; text-align:center; color:#dc2626;">Lỗi tải danh sách (${e?.message||'Unknown'})</td></tr>`;
            });
    }

    document.getElementById('assignRefresh').addEventListener('click', function() {
        loadCandidates();
    });
    document.getElementById('assignSearch').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            loadCandidates();
        }
    });
    document.getElementById('assignFaculty').addEventListener('change', function() {
        loadCandidates();
    });

    function selectCandidate(lecturerId, btn) {
        document.getElementById('assignSelectedLecturerId').value = String(lecturerId);
        // Enable submit
        const submitBtn = document.getElementById('assignSubmit');
        submitBtn.disabled = false;
        submitBtn.style.cursor = 'pointer';
        submitBtn.style.background = '#16a34a';
        // Visual feedback
        Array.from(document.querySelectorAll('#candidatesBody button')).forEach(b => {
            if (b !== btn) b.textContent = 'Chọn';
        });
        btn.textContent = 'Đã chọn';
    }

    document.getElementById('assignSubmit').addEventListener('click', function() {
        const lecturerId = document.getElementById('assignSelectedLecturerId').value;
        if (!lecturerId) return;
        const sectionId = document.getElementById('assignSectionId').value;
        const hasLecturer = document.getElementById('assignHasLecturer').value === '1';
        const note = document.getElementById('assignReason').value.trim();
        if (hasLecturer && !note) {
            const alertBox = document.getElementById('assignAlert');
            alertBox.style.display = 'block';
            alertBox.style.background = '#fee2e2';
            alertBox.style.color = '#991b1b';
            alertBox.textContent = 'Vui lòng nhập lý do thay đổi giảng viên.';
            return;
        }
        apiFetch(`${ADMIN_CLASS_SECTIONS_BASE}/${sectionId}/assign-lecturer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    lecturer_id: Number(lecturerId),
                    note: note
                })
            })
            .then(data => {
                const alert = document.getElementById('assignAlert');
                alert.style.display = 'block';
                alert.style.background = data.pending ? '#fef3c7' : '#d1fae5';
                alert.style.color = data.pending ? '#92400e' : '#065f46';
                alert.textContent = data.message || (data.pending ? 'Đã tạo yêu cầu phê duyệt.' : 'Thành công');
                if (!data.pending) {
                    setTimeout(() => {
                        window.location.reload();
                    }, 700);
                }
            })
            .catch(err => {
                const alert = document.getElementById('assignAlert');
                alert.style.display = 'block';
                alert.style.background = '#fee2e2';
                alert.style.color = '#991b1b';
                let msg = 'Không thể phân công. Vui lòng thử lại.';
                if (err && err.message) msg = err.message;
                if (err && err.code) msg += ` (Mã: ${err.code})`;
                alert.textContent = msg;
            });
    });

    function unassignLecturer(sectionId) {
        if (!confirm('Thu hồi phân công giảng viên?')) return;
        apiFetch(`${ADMIN_CLASS_SECTIONS_BASE}/${sectionId}/unassign-lecturer`, {
                method: 'DELETE'
            })
            .then(data => {
                alert(data.message || 'Đã thu hồi.');
                window.location.reload();
            })
            .catch(err => {
                alert((err && err.message) ? err.message : 'Không thể thu hồi.');
            });
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/admin/class-sections/index.blade.php ENDPATH**/ ?>