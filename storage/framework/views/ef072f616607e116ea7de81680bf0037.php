

<?php $__env->startSection('title', 'Quản lý Đợt đăng ký'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .table-zebra tbody tr:nth-child(even) {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .table-zebra tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.08);
    }

    .action-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all .2s;
        border: none;
        cursor: pointer;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    .badge-chip {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .tag {
        background: #e5e7eb;
        color: #374151;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
    }

    .tab-pane {
        display: block;
    }

    .section-title {
        font-weight: 700;
        color: #0f172a;
        margin: 6px 0 12px;
        font-size: 15px;
    }

    .error-text {
        color: #dc2626;
        font-size: 12px;
        margin-top: 6px;
    }

    .invalid {
        border-color: #ef4444 !important;
    }

    .overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .modal-card {
        background: white;
        color: #1f2937;
        border-radius: 12px;
        width: 95%;
        max-width: 1100px;
        max-height: 88vh;
        overflow: auto;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 2px solid #1976d2;
        background: linear-gradient(135deg, #1976d2 0%, #0ea5e9 100%);
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-body {
        padding: 20px 24px;
    }

    .modal-footer {
        padding: 16px 24px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        border-top: 1px solid #e5e7eb;
    }

    /* Multi-select dropdown */
    .multiselect {
        position: relative;
    }

    .ms-trigger {
        width: 100%;
        text-align: left;
        padding: 10px;
        border: 1px solid #cbd5e0;
        border-radius: 6px;
        background: white;
        color: #1f2937;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .ms-trigger span {
        color: #64748b;
    }

    .ms-panel {
        position: absolute;
        z-index: 10;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        max-height: 260px;
        overflow: auto;
        padding: 6px;
        display: none;
    }

    .ms-panel.open {
        display: block;
    }

    .ms-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        border-radius: 6px;
        cursor: pointer;
    }

    .ms-item:hover {
        background: #f8fafc;
    }

    .ms-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 10px;
        border-top: 1px solid #e5e7eb;
    }

    /* Toasts */
    .toast-container {
        position: fixed;
        right: 16px;
        top: 16px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        z-index: 10000;
    }

    .toast {
        padding: 10px 14px;
        border-radius: 8px;
        color: #0f172a;
        background: #e2e8f0;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        transition: opacity .3s, transform .3s;
    }

    .toast.success {
        background: #dcfce7;
        color: #14532d;
        border-left: 4px solid #16a34a;
    }

    .toast.error {
        background: #fee2e2;
        color: #7f1d1d;
        border-left: 4px solid #dc2626;
    }

    .toast.hide {
        opacity: 0;
        transform: translateY(-6px);
    }
</style>

<div id="toast" class="toast-container"></div>

<div style="background:white; padding:24px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2 style="margin:0; font-size:20px; font-weight:600; color:#1e293b;">⏰ Quản lý Đợt đăng ký</h2>
        <button type="button" onclick="openWaveModal()" style="background:#16a34a; color:white; padding:10px 20px; border-radius:6px; border:none; display:inline-flex; align-items:center; gap:8px; font-weight:500;">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
            </svg>
            Thêm Đợt đăng ký
        </button>
    </div>

    <?php if(session('success')): ?>
    <div style="background:#d1fae5; border-left:4px solid #10b981; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#065f46;"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div style="background:#fee2e2; border-left:4px solid #ef4444; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#991b1b;"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div style="overflow-x:auto;">
        <table class="table-zebra" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">TÊN ĐỢT</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">NĂM/KỲ</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">THỜI GIAN</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">ĐỐI TƯỢNG</th>
                    <th style="padding:12px; text-align:center; font-weight:600; color:#475569; font-size:13px;">TRẠNG THÁI</th>
                    <th style="padding:12px; text-align:center; font-weight:600; color:#475569; font-size:13px;">THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $waves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr style="border-bottom:1px solid #e2e8f0;">
                    <td style="padding:12px; font-weight:600; color:#1e293b;"><?php echo e($wave->name); ?></td>
                    <td style="padding:12px;">
                        <span class="badge-chip" style="background:#dbeafe; color:#1e40af;"><?php echo e($wave->academic_year); ?></span>
                        <span class="badge-chip" style="background:#cffafe; color:#164e63;"><?php echo e($wave->term); ?></span>
                    </td>
                    <td style="padding:12px; font-size:13px; color:#475569;">
                        <?php echo e(\Carbon\Carbon::parse($wave->starts_at)->format('d/m/Y H:i')); ?> → <?php echo e(\Carbon\Carbon::parse($wave->ends_at)->format('d/m/Y H:i')); ?>

                    </td>
                    <td style="padding:12px;">
                        <?php
                        $audience = is_array($wave->audience) ? $wave->audience : json_decode($wave->audience, true);
                        $facIds = $audience['faculties'] ?? [];
                        $cohorts = $audience['cohorts'] ?? [];
                        $facCodes = $faculties->whereIn('id', $facIds)->pluck('code')->values()->all();
                        $facPreview = collect($facCodes)->take(2)->implode(', ');
                        $facMore = max(count($facCodes) - 2, 0);
                        $cohPreview = collect($cohorts)->take(2)->implode(', ');
                        $cohMore = max(count($cohorts) - 2, 0);
                        ?>
                        <div class="small" style="display:flex; gap:6px; flex-wrap:wrap;">
                            <?php if(count($facCodes)>0): ?>
                            <span class="tag">Khoa: <?php echo e($facPreview); ?><?php if($facMore>0): ?>, +<?php echo e($facMore); ?><?php endif; ?></span>
                            <?php endif; ?>
                            <?php if(count($cohorts)>0): ?>
                            <span class="tag">Khóa: <?php echo e($cohPreview); ?><?php if($cohMore>0): ?>, +<?php echo e($cohMore); ?><?php endif; ?></span>
                            <?php endif; ?>
                            <?php if(count($facCodes)==0 && count($cohorts)==0): ?>
                            <span style="color:#94a3b8;">Toàn bộ</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td style="padding:12px; text-align:center;">
                        <?php
                        $now = now();
                        $starts = \Carbon\Carbon::parse($wave->starts_at);
                        $ends = \Carbon\Carbon::parse($wave->ends_at);
                        ?>
                        <?php if($now < $starts): ?>
                            <span class="badge-chip" style="background:#fef3c7; color:#92400e;">Sắp diễn ra</span>
                            <?php elseif($now >= $starts && $now <= $ends): ?>
                                <span class="badge-chip" style="background:#dcfce7; color:#166534;">Đang mở</span>
                                <?php else: ?>
                                <span class="badge-chip" style="background:#e5e7eb; color:#374151;">Đã kết thúc</span>
                                <?php endif; ?>
                    </td>
                    <td style="padding:12px; text-align:center;">
                        <div style="display:inline-flex; gap:6px;">
                            <button class="action-btn" style="background:#10b981; color:white;" title="Xem chi tiết" onclick="openDetailModal(<?php echo e($wave->id); ?>)">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z" />
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z" />
                                </svg>
                            </button>
                            <button class="action-btn" style="background:#1976d2; color:white;" title="Sửa" onclick="openWaveModal(<?php echo e($wave->id); ?>)">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3z" />
                                    <path d="M13.5 6.207 9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5V12.707l6.5-6.5z" />
                                </svg>
                            </button>
                            <button type="button" class="action-btn" style="background:#dc2626; color:white;" title="Xóa" onclick="deleteWave(<?php echo e($wave->id); ?>, this)">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zM8 5.5a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM2.5 3V2h11v1h-11z" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" style="padding:60px 20px; text-align:center; color:#94a3b8;">
                        <svg width="64" height="64" fill="currentColor" viewBox="0 0 16 16" style="opacity:0.3; margin-bottom:16px;">
                            <path d="M8 3a5 5 0 0 0-5 5h1a4 4 0 1 1 4 4v1l2-2-2-2v1a3 3 0 1 0-3-3H3a5 5 0 0 0 5-5z" />
                        </svg>
                        <div style="font-size:16px; font-weight:500;">Chưa có đợt đăng ký nào</div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($waves->hasPages()): ?>
    <div style="margin-top:24px; display:flex; justify-content:center;"><?php echo e($waves->links()); ?></div>
    <?php endif; ?>
</div>

<!-- Detail Modal (overlay) -->
<div id="detailModal" class="overlay" onclick="if(event.target===this) closeDetailModal()">
    <div class="modal-card">
        <div class="modal-header">
            <h5 style="margin:0; font-size:18px; font-weight:600;">Chi tiết đợt đăng ký</h5>
            <button onclick="closeDetailModal()" style="background:white; color:#1f2937; border:none; padding:6px 10px; border-radius:6px; cursor:pointer;">Đóng</button>
        </div>
        <div class="modal-body" id="detailBody">Đang tải...</div>
    </div>
</div>

<!-- Create/Edit Modal (overlay) -->
<div id="waveModal" class="overlay" onclick="if(event.target===this) closeWaveModal()">
    <div class="modal-card">
        <div class="modal-header">
            <h5 style="margin:0; font-size:18px; font-weight:600;">Đợt đăng ký</h5>
            <button onclick="closeWaveModal()" style="background:white; color:#1f2937; border:none; padding:6px 10px; border-radius:6px; cursor:pointer;">Đóng</button>
        </div>
        <form id="waveForm" action="<?php echo e(route('registration-waves.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div id="formMethod"></div>
            <input type="hidden" id="wave_id" value="">
            <div class="modal-body">
                <div id="backendErrorBanner" style="display:none; margin-bottom:12px; padding:10px 12px; border-radius:8px; border-left:4px solid #dc2626; background:#fee2e2; color:#7f1d1d;"></div>
                <div style="padding-top:4px;">
                    <div class="tab-pane" id="tab-general">
                        <div class="section-title">1. Thông tin chung</div>
                        <div style="display:grid; grid-template-columns:2fr 1fr 1fr; gap:12px; margin-bottom:12px;">
                            <div>
                                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Tên đợt đăng ký <span style="color:#dc2626">*</span></label>
                                <input type="text" id="name" name="name" value="<?php echo e(old('name')); ?>" required style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                            </div>
                            <div>
                                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Năm học <span style="color:#dc2626">*</span></label>
                                <select id="academic_year" name="academic_year" required style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px; background:white;">
                                    <option value="">-- Chọn Năm học --</option>
                                    <?php $__currentLoopData = ($years ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($y); ?>" <?php if(old('academic_year')==$y): echo 'selected'; endif; ?>><?php echo e($y); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Học kỳ <span style="color:#dc2626">*</span></label>
                                <select id="term" name="term" required style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px; background:white;">
                                    <option value="">-- Chọn Học kỳ --</option>
                                    <?php $__currentLoopData = ($terms ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($t); ?>" <?php if(old('term')==$t): echo 'selected'; endif; ?>><?php echo e($t); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                            <div>
                                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Bắt đầu <span style="color:#dc2626">*</span></label>
                                <input type="datetime-local" id="starts_at" name="starts_at" value="<?php echo e(old('starts_at') ? str_replace(' ', 'T', 
                                old('starts_at')) : ''); ?>" required style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                            </div>
                            <div>
                                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Kết thúc <span style="color:#dc2626">*</span></label>
                                <input type="datetime-local" id="ends_at" name="ends_at" value="<?php echo e(old('ends_at') ? str_replace(' ', 'T', old('ends_at')) : ''); ?>" required style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab-audience">
                        <div class="section-title">2. Đối tượng</div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                            <div>
                                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Khu vực 1: Chọn Khoa/Ngành</label>
                                <div id="ms_faculties" class="multiselect" data-name="faculties[]">
                                    <button type="button" class="ms-trigger" data-placeholder="-- Chọn Khoa/Ngành --"><span>-- Chọn Khoa/Ngành --</span><svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M1.5 5.5l6 6 6-6" />
                                        </svg></button>
                                    <div class="ms-panel">
                                        <label class="ms-item"><input type="checkbox" data-role="all"> Tất cả các Khoa</label>
                                        <?php $__currentLoopData = $faculties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fac): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="ms-item"><input type="checkbox" value="<?php echo e($fac->id); ?>"> <?php echo e($fac->code); ?> — <?php echo e($fac->name); ?></label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <div class="ms-footer">
                                            <small style="color:#64748b;">Mẹo: Giữ Ctrl để chọn nhanh khi dùng bàn phím</small>
                                            <button type="button" class="action-btn" style="background:#1976d2; color:#fff; width:auto; padding:6px 10px;" onclick="closeAllMsPanels(this)">Xong</button>
                                        </div>
                                    </div>
                                    <div class="ms-hidden"></div>
                                </div>
                                <small style="color:#64748b; display:block; margin-top:6px;">Gợi ý: Có thể mở rộng để chọn Ngành (khi có dữ liệu).</small>
                            </div>
                            <div>
                                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Khu vực 2: Chọn Khóa học/Lớp</label>
                                <div id="ms_cohorts" class="multiselect" data-name="cohorts[]">
                                    <button type="button" class="ms-trigger" data-placeholder="-- Chọn Khóa học/Lớp --"><span>-- Chọn Khóa học/Lớp --</span><svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M1.5 5.5l6 6 6-6" />
                                        </svg></button>
                                    <div class="ms-panel">
                                        <label class="ms-item"><input type="checkbox" data-role="all"> Tất cả các Khóa</label>
                                        <?php $__empty_1 = true; $__currentLoopData = $cohorts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <label class="ms-item"><input type="checkbox" value="<?php echo e($coh); ?>"> <?php echo e($coh); ?></label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div style="padding:8px 10px; color:#94a3b8;">Chưa có dữ liệu Khóa trong hệ thống</div>
                                        <?php endif; ?>
                                        <div class="ms-footer">
                                            <small style="color:#64748b;">Chọn nhiều theo nhu cầu</small>
                                            <button type="button" class="action-btn" style="background:#1976d2; color:#fff; width:auto; padding:6px 10px;" onclick="closeAllMsPanels(this)">Xong</button>
                                        </div>
                                    </div>
                                    <div class="ms-hidden"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab-offerings">
                        <div class="section-title">3. Học phần mở</div>
                        <div style="display:flex; gap:12px; align-items:end; margin-bottom:12px;">
                            <div style="flex:1;">
                                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Tìm kiếm</label>
                                <input type="text" id="offerings_search" placeholder="Mã/ tên môn hoặc mã lớp" style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                            </div>
                        </div>
                        <div id="offerings_container" style="max-height:420px; overflow:auto; border:1px solid #e5e7eb; border-radius:8px;">
                            <table class="table-zebra" style="width:100%; border-collapse:collapse;">
                                <thead>
                                    <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                                        <th style="padding:10px; width:36px;"><input type="checkbox" id="offerings_check_all" title="Chọn tất cả"></th>
                                        <th style="padding:10px; text-align:left; font-size:13px; color:#475569;">MÃ LHP</th>
                                        <th style="padding:10px; text-align:left; font-size:13px; color:#475569;">TÊN MÔN HỌC</th>
                                        <th style="padding:10px; text-align:left; font-size:13px; color:#475569;">GV</th>
                                        <th style="padding:10px; text-align:left; font-size:13px; color:#475569;">LỊCH</th>
                                        <th style="padding:10px; text-align:left; font-size:13px; color:#475569;">SĨ SỐ</th>
                                    </tr>
                                </thead>
                                <tbody id="offerings_tbody">
                                    <tr>
                                        <td colspan="8" style="padding:16px; text-align:center; color:#94a3b8;">Chọn Năm học và Học kỳ để tải lớp học phần</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeWaveModal()" style="padding:10px 20px; border:1px solid #cbd5e0; border-radius:6px; background:white; color:#475569;">Hủy</button>
                <button type="submit" style="background:#1976d2; color:white; padding:10px 20px; border:none; border-radius:6px; font-weight:500;">Lưu</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Toast helper
    function showToast(message, type = 'success') {
        try {
            const container = document.getElementById('toast');
            if (!container) return alert(message);
            const el = document.createElement('div');
            el.className = `toast ${type}`;
            el.textContent = message;
            container.appendChild(el);
            setTimeout(() => {
                el.classList.add('hide');
                setTimeout(() => el.remove(), 300);
            }, 2800);
        } catch {
            alert(message);
        }
    }
    // Tabs removed – stacked sections
    function openDetailModal(id) {
        const overlay = document.getElementById('detailModal');
        const body = document.getElementById('detailBody');
        overlay.style.display = 'flex';
        body.innerHTML = 'Đang tải...';
        fetch(`<?php echo e(url('admin/registration-waves')); ?>/${id}/detail`)
            .then(r => r.json())
            .then(({
                wave,
                offerings
            }) => {
                body.innerHTML = `
                    <div style='background:#f8fafc; padding:16px; border-radius:8px; margin-bottom:16px;'>
                        <div style='font-weight:700; font-size:16px;'>${escapeHtml(wave.name)}</div>
                        <div style='color:#475569;'>${escapeHtml(wave.academic_year)} / ${escapeHtml(wave.term)} | ${escapeHtml(wave.starts_at||'')} → ${escapeHtml(wave.ends_at||'')}</div>
                        <div style='margin-top:8px;'>
                            <strong>Đối tượng:</strong>
                            ${(wave.faculties||[]).map(f=>`<span class='tag' style='margin-right:6px;'>${escapeHtml(f.code)}</span>`).join('')}
                            ${(wave.cohorts||[]).map(c=>`<span class='tag' style='margin-right:6px;'>${escapeHtml(c)}</span>`).join('')}
                        </div>
                    </div>`;
                const tbl = document.createElement('div');
                tbl.innerHTML = `
                    <div style='overflow:auto; border:1px solid #e5e7eb; border-radius:8px;'>
                        <table class='table-zebra' style='width:100%; border-collapse:collapse;'>
                            <thead><tr style='background:#f8fafc; border-bottom:2px solid #e2e8f0;'>
                                <th style='padding:10px; text-align:left;'>MÃ LỚP</th>
                                <th style='padding:10px; text-align:left;'>MÔN HỌC</th>
                                <th style='padding:10px; text-align:left;'>KHOA</th>
                                <th style='padding:10px; text-align:left;'>GV</th>
                                <th style='padding:10px; text-align:left;'>PHÒNG</th>
                                <th style='padding:10px; text-align:left;'>THỨ</th>
                                <th style='padding:10px; text-align:left;'>CA</th>
                            </tr></thead>
                            <tbody>${(offerings||[]).map(row=>`<tr style='border-bottom:1px solid #e2e8f0;'>
                                <td style='padding:10px;'>${escapeHtml(row.section_code||'')}</td>
                                <td style='padding:10px;'><span class='badge-chip' style='background:#cffafe; color:#164e63;'>${escapeHtml(row.course_code||'')}</span> ${escapeHtml(row.course_name||'')}</td>
                                <td style='padding:10px;'>${row.faculty? escapeHtml(row.faculty.code): ''}</td>
                                <td style='padding:10px;'>${escapeHtml(row.lecturer||'')}</td>
                                <td style='padding:10px;'>${escapeHtml(row.room||'')}</td>
                                <td style='padding:10px;'>${escapeHtml(String(row.day_of_week||''))}</td>
                                <td style='padding:10px;'>${row.shift? `${escapeHtml(row.shift.name)} ${(row.shift.start_time||'').substring(0,5)}-${(row.shift.end_time||'').substring(0,5)}`: ''}</td>
                            </tr>`).join('')}</tbody>
                        </table>
                    </div>`;
                body.appendChild(tbl);
            })
            .catch(() => {
                body.innerHTML = '<p style="color:#dc2626;">Không tải được chi tiết.</p>';
            });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
    }

    function openWaveModal(id) {
        const overlay = document.getElementById('waveModal');
        const form = document.getElementById('waveForm');
        form.reset();
        document.getElementById('wave_id').value = id || '';
        document.getElementById('formMethod').innerHTML = '';
        form.action = id ? `<?php echo e(url('admin/registration-waves')); ?>/${id}` : `<?php echo e(route('registration-waves.store')); ?>`;
        // Reset multiselects
        resetAllMultiSelects();
        if (id) {
            document.getElementById('formMethod').innerHTML = "<input type='hidden' name='_method' value='PUT'>";
            fetch(`<?php echo e(url('admin/registration-waves')); ?>/${id}/detail`).then(r => r.json()).then(({
                wave,
                offerings
            }) => {
                setValue('name', wave.name);
                ensureOption('academic_year', wave.academic_year);
                setValue('academic_year', wave.academic_year);
                ensureOption('term', wave.term);
                setValue('term', wave.term);
                setValue('starts_at', wave.starts_at ? wave.starts_at.replace(' ', 'T').slice(0, 16) : '');
                setValue('ends_at', wave.ends_at ? wave.ends_at.replace(' ', 'T').slice(0, 16) : '');
                setMultiChecklist('faculties[]', (wave.faculties || []).map(f => String(f.id)));
                setMultiChecklist('cohorts[]', (wave.cohorts || []).map(String));
                maybeLoadOfferings(() => {
                    setChecked('class_section_ids[]', (offerings || []).map(o => String(o.id)));
                });
                overlay.style.display = 'flex';
            });
        } else {
            clearOfferingsTable();
            overlay.style.display = 'flex';
        }
    }

    function closeWaveModal() {
        document.getElementById('waveModal').style.display = 'none';
    }

    function setValue(id, val) {
        const el = document.getElementById(id);
        if (el) el.value = val ?? '';
    }
    // Multiselect helpers
    function resetAllMultiSelects() {
        document.querySelectorAll('.multiselect').forEach(initMultiSelect);
    }

    function setMultiChecklist(name, values) {
        const el = Array.from(document.querySelectorAll('.multiselect')).find(ms => ms.dataset.name === name);
        if (!el) return;
        const checkboxes = el.querySelectorAll(".ms-panel input[type='checkbox'][value]");
        checkboxes.forEach(cb => {
            cb.checked = values.includes(String(cb.value));
        });
        updateMsSummary(el);
        syncMsHidden(el);
        // update "all" checkbox state
        const all = el.querySelector(".ms-panel input[type='checkbox'][data-role='all']");
        if (all) {
            const items = Array.from(checkboxes);
            all.checked = items.length > 0 && items.every(i => i.checked);
        }
    }

    function setChecked(name, values) {
        document.querySelectorAll(`input[name='${name}']`).forEach(cb => {
            cb.checked = values.includes(String(cb.value));
        });
    }

    function maybeLoadOfferings(cb) {
        const year = v('academic_year');
        const term = v('term');
        if (year && term) {
            loadOfferings().then(() => cb && cb());
        }
    }

    function v(id) {
        return (document.getElementById(id)?.value || '').trim();
    }
    async function loadOfferings() {
        const year = v('academic_year');
        const term = v('term');
        const facultyId = '';
        const q = (document.getElementById('offerings_search').value || '').trim();
        if (!year || !term) {
            clearOfferingsTable();
            return;
        }
        const url = new URL(`<?php echo e(url('admin/registration-waves/offerings')); ?>`);
        url.searchParams.set('academic_year', year);
        url.searchParams.set('term', term);
        if (facultyId) url.searchParams.set('faculty_id', facultyId);
        if (q) url.searchParams.set('q', q);
        const res = await fetch(url);
        const json = await res.json();
        const rows = (json.data || []).map(row => `
            <tr>
                <td style='padding:8px;'><input type='checkbox' name='class_section_ids[]' value='${row.id}'></td>
                <td style='padding:8px;'>${escapeHtml(row.section_code||'')}</td>
                <td style='padding:8px;'><span class='badge-chip' style='background:#cffafe; color:#164e63;'>${escapeHtml(row.course_code||'')}</span> ${escapeHtml(row.course_name||'')}</td>
                <td style='padding:8px;'>${escapeHtml(row.lecturer||'')}</td>
                <td style='padding:8px;'>${formatSchedule(row)}</td>
                <td style='padding:8px;'>${escapeHtml(String(row.max_capacity||''))}</td>
            </tr>`).join('');
        document.getElementById('offerings_tbody').innerHTML = rows || `<tr><td colspan='8' style='padding:16px; text-align:center; color:#94a3b8;'>Không có lớp học phần phù hợp</td></tr>`;
        const master = document.getElementById('offerings_check_all');
        if (master) master.checked = false;
    }

    function clearOfferingsTable() {
        document.getElementById('offerings_tbody').innerHTML = `<tr><td colspan='8' style='padding:16px; text-align:center; color:#94a3b8;'>Chọn Năm học và Học kỳ để tải lớp học phần</td></tr>`;
    }

    function escapeHtml(s) {
        return (s || '').toString().replace(/[&<>"']/g, c => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            '\'': '&#39;'
        } [c]))
    }

    // Multi-select dropdown implementation
    function initMultiSelect(el) {
        if (!el || el.__inited) return;
        el.__inited = true;
        const trigger = el.querySelector('.ms-trigger');
        const panel = el.querySelector('.ms-panel');
        const allCb = panel?.querySelector("input[type='checkbox'][data-role='all']");
        const items = panel?.querySelectorAll("input[type='checkbox'][value]") || [];
        const placeholder = trigger?.getAttribute('data-placeholder') || '-- Chọn --';
        const updateAllState = () => {
            const arr = Array.from(items);
            if (allCb) {
                allCb.checked = arr.length > 0 && arr.every(i => i.checked);
            }
        };
        const onChange = () => {
            updateMsSummary(el);
            syncMsHidden(el);
            updateAllState();
        };
        trigger?.addEventListener('click', (e) => {
            e.stopPropagation();
            closeAllMsPanels();
            panel?.classList.toggle('open');
        });
        panel?.addEventListener('click', (e) => {
            e.stopPropagation();
        });
        allCb?.addEventListener('change', () => {
            items.forEach(cb => cb.checked = allCb.checked);
            onChange();
        });
        items.forEach(cb => cb.addEventListener('change', onChange));
        // Initial summary
        updateMsSummary(el, placeholder);
        // Hidden inputs container
        syncMsHidden(el);
    }

    function closeAllMsPanels(excludeBtn) {
        document.querySelectorAll('.ms-panel.open').forEach(p => {
            if (!excludeBtn || !p.contains(excludeBtn)) p.classList.remove('open');
        });
    }

    function updateMsSummary(el, placeholder = null) {
        const trigger = el.querySelector('.ms-trigger');
        const selected = getMsSelectedValues(el);
        const text = selected.length === 0 ? (placeholder || trigger.getAttribute('data-placeholder') || '-- Chọn --') : `${selected.length} đã chọn`;
        const span = trigger.querySelector('span');
        if (span) span.textContent = text;
    }

    function getMsSelectedValues(el) {
        return Array.from(el.querySelectorAll(".ms-panel input[type='checkbox'][value]:checked")).map(cb => cb.value);
    }

    function syncMsHidden(el) {
        const name = el.dataset.name;
        const hidden = el.querySelector('.ms-hidden');
        hidden.innerHTML = '';
        getMsSelectedValues(el).forEach(val => {
            const h = document.createElement('input');
            h.type = 'hidden';
            h.name = name;
            h.value = val;
            hidden.appendChild(h);
        });
    }
    // Initialize on DOM ready
    document.addEventListener('click', () => closeAllMsPanels());
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.multiselect').forEach(initMultiSelect);
    });
    // Ensure hidden inputs are present before submit
    document.getElementById('waveForm').addEventListener('submit', function(e) {
        document.querySelectorAll('.multiselect').forEach(syncMsHidden);
        if (!validateWaveFrontend()) {
            e.preventDefault();
        }
    });
    // Offerings: master checkbox
    document.addEventListener('change', function(e) {
        if (e.target && e.target.id === 'offerings_check_all') {
            const on = e.target.checked;
            document.querySelectorAll("#offerings_tbody input[type='checkbox'][name='class_section_ids[]']").forEach(cb => cb.checked = on);
        }
    });

    function formatSchedule(row) {
        const dow = row.day_of_week ? `Thứ ${row.day_of_week}` : '';
        const shift = row.shift ? `${row.shift.name} ${(row.shift.start_time||'').substring(0,5)}-${(row.shift.end_time||'').substring(0,5)}` : '';
        const room = row.room ? `Phòng ${row.room}` : '';
        return [dow, shift, room].filter(Boolean).join(', ');
    }
    // Ensure a value exists in a select (for editing waves with future terms/years)
    function ensureOption(selectId, value) {
        const sel = document.getElementById(selectId);
        if (!sel || value == null || value === '') return;
        const exists = Array.from(sel.options).some(o => o.value == String(value));
        if (!exists) {
            const opt = document.createElement('option');
            opt.value = String(value);
            opt.textContent = String(value);
            sel.appendChild(opt);
        }
    }
    // Auto-refresh offerings on year/term change when on the offerings tab
    ['academic_year', 'term'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('change', () => {
            maybeLoadOfferings();
        });
    });

    // Frontend validation with inline messages and auto-scroll
    function validateWaveFrontend() {
        // Cleanup
        document.querySelectorAll('.error-text').forEach(el => el.remove());
        document.querySelectorAll('.invalid').forEach(el => el.classList.remove('invalid'));
        const errs = [];

        const req = (id, label) => {
            const el = document.getElementById(id);
            const val = (el?.value || '').trim();
            if (!val) {
                addError(el, `${label} là bắt buộc.`);
                errs.push(el);
            }
            return val;
        };

        const name = req('name', 'Tên đợt đăng ký');
        const year = req('academic_year', 'Năm học');
        const term = req('term', 'Học kỳ');
        const starts = req('starts_at', 'Thời gian bắt đầu');
        const ends = req('ends_at', 'Thời gian kết thúc');
        if (starts && ends && new Date(starts) >= new Date(ends)) {
            const el = document.getElementById('ends_at');
            addError(el, 'Thời gian kết thúc phải sau thời gian bắt đầu.');
            errs.push(el);
        }

        // Audience selections
        const facSelected = getMsSelectedValues(document.getElementById('ms_faculties')).length;
        const cohSelected = getMsSelectedValues(document.getElementById('ms_cohorts')).length;
        if (!facSelected) {
            addError(document.getElementById('ms_faculties'), 'Vui lòng chọn ít nhất một Khoa/Ngành.');
            errs.push(document.getElementById('ms_faculties'));
        }
        if (!cohSelected) {
            addError(document.getElementById('ms_cohorts'), 'Vui lòng chọn ít nhất một Khóa/Lớp.');
            errs.push(document.getElementById('ms_cohorts'));
        }

        // Offerings
        const anyOffering = document.querySelector("#offerings_tbody input[name='class_section_ids[]']:checked");
        if (!anyOffering) {
            addError(document.getElementById('offerings_container'), 'Vui lòng chọn ít nhất một Lớp học phần.');
            errs.push(document.getElementById('offerings_container'));
        }

        if (errs.length) {
            const first = errs[0];
            first?.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            return false;
        }
        return true;
    }

    function addError(target, message) {
        if (!target) return;
        const err = document.createElement('div');
        err.className = 'error-text';
        err.textContent = message;
        // Apply red border to inputs
        if (target.tagName === 'INPUT' || target.tagName === 'SELECT') {
            target.classList.add('invalid');
            target.insertAdjacentElement('afterend', err);
        } else {
            target.insertAdjacentElement('afterend', err);
        }
    }

    // Auto-reopen modal with old() values and show backend banner if present
    document.addEventListener('DOMContentLoaded', () => {
        const hasErrors = Boolean(<?php echo json_encode($errors->any(), 15, 512) ?>);
        const be = <?php echo json_encode(session('business_error'), 15, 512) ?>;
        if (hasErrors || be) {
            openWaveModal();
            setValue('name', <?php echo json_encode(old('name'), 15, 512) ?> || '');
            ensureOption('academic_year', <?php echo json_encode(old('academic_year'), 15, 512) ?>);
            setValue('academic_year', <?php echo json_encode(old('academic_year'), 15, 512) ?> || '');
            ensureOption('term', <?php echo json_encode(old('term'), 15, 512) ?>);
            setValue('term', <?php echo json_encode(old('term'), 15, 512) ?> || '');
            setValue('starts_at', (<?php echo json_encode(old('starts_at'), 15, 512) ?> || '').replace(' ', 'T'));
            setValue('ends_at', (<?php echo json_encode(old('ends_at'), 15, 512) ?> || '').replace(' ', 'T'));
            setMultiChecklist('faculties[]', (<?php echo json_encode(old('faculties', []), 512) ?> || []).map(String));
            setMultiChecklist('cohorts[]', (<?php echo json_encode(old('cohorts', []), 512) ?> || []).map(String));
            const oldIds = (<?php echo json_encode(old('class_section_ids', []), 512) ?> || []).map(String);
            maybeLoadOfferings(() => setChecked('class_section_ids[]', oldIds));
            if (be) {
                const b = document.getElementById('backendErrorBanner');
                b.style.display = 'block';
                b.textContent = be;
            }
        }
    });
    // Delete wave via AJAX and remove row without full reload
    async function deleteWave(id, btn) {
        if (!confirm('Bạn có chắc muốn xóa đợt đăng ký này?')) return;
        const url = `<?php echo e(url('admin/registration-waves')); ?>/${id}`;
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: new URLSearchParams({
                _method: 'DELETE'
            })
        });
        if (res.ok) {
            const tr = btn.closest('tr');
            if (tr) tr.remove();
            showToast('Đã xóa đợt đăng ký thành công.', 'success');
        } else {
            try {
                const j = await res.json();
                showToast(j.message || 'Xóa thất bại.', 'error');
            } catch {
                showToast('Xóa thất bại.', 'error');
            }
        }
    }
    // On load: also echo session toasts if available
    document.addEventListener('DOMContentLoaded', () => {
        const suc = <?php echo json_encode(session('success'), 15, 512) ?>;
        const err = <?php echo json_encode(session('error'), 15, 512) ?>;
        if (suc) showToast(suc, 'success');
        if (err) showToast(err, 'error');
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/admin/registration-waves/index.blade.php ENDPATH**/ ?>