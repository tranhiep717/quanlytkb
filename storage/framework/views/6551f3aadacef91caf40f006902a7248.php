

<?php $__env->startSection('title','Xem cập nhật hồ sơ'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <h3 style="margin:0 0 16px 0;color:#1976d2;font-size:16px;">Xem cập nhật hồ sơ</h3>

    <?php if(session('status')): ?>
    <div style="background:#4caf50;color:white;padding:12px;border-radius:4px;margin-bottom:16px;">
        <?php echo e(session('status')); ?>

    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div style="background:#d32f2f;color:white;padding:12px;border-radius:4px;margin-bottom:16px;">
        <?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('student.profile.update')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div style="border-bottom:1px solid #e0e0e0;margin-bottom:16px;">
            <button type="button" style="padding:8px 16px;border:none;background:transparent;border-bottom:2px solid #1976d2;color:#1976d2;font-weight:600;cursor:pointer;">Thông tin cá nhân</button>
            <button type="button" style="padding:8px 16px;border:none;background:transparent;color:#757575;cursor:pointer;">Kỳ học hiện tại</button>
            <button type="button" style="padding:8px 16px;border:none;background:transparent;color:#757575;cursor:pointer;">Chứng chỉ</button>
            <button type="button" style="padding:8px 16px;border:none;background:transparent;color:#757575;cursor:pointer;">Học phần chưa đạt</button>
            <button type="button" style="padding:8px 16px;border:none;background:transparent;color:#757575;cursor:pointer;">Tiến độ học tập</button>
            <button type="button" style="padding:8px 16px;border:none;background:transparent;color:#757575;cursor:pointer;">Kết quả học tập</button>
            <button type="button" style="padding:8px 16px;border:none;background:transparent;color:#757575;cursor:pointer;">Cài đặt</button>
        </div>

        <div style="background:#e8f5e9;padding:12px;border-radius:6px;margin-bottom:16px;">
            <h4 style="margin:0 0 12px 0;font-size:14px;color:#2e7d32;">1 Thông tin chung</h4>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Mã sinh viên</label>
                        <div><strong><?php echo e(auth()->user()->code ?? '200741021024'); ?></strong></div>
                    </div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Họ và tên <span style="color:#d32f2f;">*</span></label>
                        <div>
                            <input type="text" name="name" value="<?php echo e(old('name', auth()->user()->name)); ?>" required maxlength="255" style="width:100%;padding:6px;border:1px solid <?php echo e($errors->has('name') ? '#d32f2f' : '#ddd'); ?>;border-radius:4px;"
                                oninvalid="this.setCustomValidity('Vui lòng nhập họ và tên.')"
                                oninput="this.setCustomValidity('')" />
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#d32f2f;font-size:12px;margin-top:2px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Tên gọi khác</label>
                        <div>-</div>
                    </div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Giới tính</label>
                        <div>
                            <label style="margin-right:8px;"><input type="radio" name="gender" value="Nam" <?php echo e(old('gender', auth()->user()->gender ?? 'Nam') == 'Nam' ? 'checked' : ''); ?> /> Nam</label>
                            <label style="margin-right:8px;"><input type="radio" name="gender" value="Nữ" <?php echo e(old('gender', auth()->user()->gender) == 'Nữ' ? 'checked' : ''); ?> /> Nữ</label>
                            <label><input type="radio" name="gender" value="Khác" <?php echo e(old('gender', auth()->user()->gender) == 'Khác' ? 'checked' : ''); ?> /> Khác</label>
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Ngày sinh</label>
                        <div>
                            <input type="date" name="dob" value="<?php echo e(old('dob', auth()->user()->dob)); ?>" style="padding:6px;border:1px solid <?php echo e($errors->has('dob') ? '#d32f2f' : '#ddd'); ?>;border-radius:4px;"
                                oninvalid="this.setCustomValidity('Ngày sinh không đúng định dạng.')"
                                oninput="this.setCustomValidity('')" />
                            <?php $__errorArgs = ['dob'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#d32f2f;font-size:12px;margin-top:2px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Dân tộc</label>
                        <div>
                            <label style="margin-right:8px;"><input type="radio" name="ethnic" value="Kinh" checked /> Kinh</label>
                            <label><input type="radio" name="ethnic" value="Khác" /> Khác</label>
                        </div>
                    </div>
                </div>

                <div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Số CCCD</label>
                        <div><?php echo e(auth()->user()->id_card ?? '187961287'); ?></div>
                    </div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Quê quán</label>
                        <select name="country" style="width:100%;padding:6px;border:1px solid #ddd;border-radius:4px;">
                            <option>Việt Nam</option>
                        </select>
                    </div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Nơi sinh</label>
                        <select name="birthplace" style="width:100%;padding:6px;border:1px solid #ddd;border-radius:4px;">
                            <option>-</option>
                        </select>
                    </div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Tỉnh/TP</label>
                        <select name="province" style="width:100%;padding:6px;border:1px solid #ddd;border-radius:4px;">
                            <option>-</option>
                        </select>
                    </div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Tôn giáo</label>
                        <select name="religion" style="width:100%;padding:6px;border:1px solid #ddd;border-radius:4px;">
                            <option>Không</option>
                        </select>
                    </div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Ảnh đại diện</label>
                        <div>
                            <input type="file" name="avatar" accept="image/png,image/jpeg,image/jpg,image/gif" style="font-size:12px;" />
                            <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#d32f2f;font-size:12px;margin-top:2px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <?php if(auth()->user()->avatar_url): ?>
                            <div style="margin-top:4px;"><img src="<?php echo e(auth()->user()->avatar_url); ?>" style="max-width:80px;border-radius:4px;" alt="Avatar" /></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="background:#fff3e0;padding:12px;border-radius:6px;margin-bottom:16px;">
            <h4 style="margin:0 0 12px 0;font-size:14px;color:#f57c00;">THÔNG TIN LIÊN HỆ</h4>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Email <span style="color:#d32f2f;">*</span></label>
                        <div>
                            <input type="email" name="email" value="<?php echo e(old('email', auth()->user()->email)); ?>" required maxlength="255" style="width:100%;padding:6px;border:1px solid <?php echo e($errors->has('email') ? '#d32f2f' : '#ddd'); ?>;border-radius:4px;"
                                oninvalid="this.setCustomValidity(this.validity.valueMissing ? 'Vui lòng nhập địa chỉ email.' : (this.validity.typeMismatch ? 'Email không đúng định dạng.' : ''))"
                                oninput="this.setCustomValidity('')" />
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#d32f2f;font-size:12px;margin-top:2px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Số điện thoại</label>
                        <div>
                            <input type="text" name="phone" value="<?php echo e(old('phone', auth()->user()->phone)); ?>" pattern="[0-9]*" maxlength="15" style="width:100%;padding:6px;border:1px solid <?php echo e($errors->has('phone') ? '#d32f2f' : '#ddd'); ?>;border-radius:4px;"
                                oninvalid="this.setCustomValidity('Số điện thoại chỉ được chứa số (0-9).')"
                                oninput="this.setCustomValidity('')" />
                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#d32f2f;font-size:12px;margin-top:2px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
                <div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Địa chỉ liên hệ</label>
                        <div>
                            <input type="text" name="address" value="<?php echo e(old('address', auth()->user()->address)); ?>" maxlength="255" style="width:100%;padding:6px;border:1px solid #ddd;border-radius:4px;" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="background:#e3f2fd;padding:12px;border-radius:6px;margin-bottom:16px;">
            <h4 style="margin:0 0 12px 0;font-size:14px;color:#1976d2;">THÔNG TIN LỚP NGÀNH 1</h4>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Hệ đào tạo</label>
                        <div><strong>Hệ Đại học chính quy</strong></div>
                    </div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Khóa học</label>
                        <div><strong>Khóa 61</strong></div>
                    </div>
                </div>
                <div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Ngành đào tạo</label>
                        <div><strong>K61 7340525_Sư phạm Toán học</strong></div>
                    </div>
                </div>
            </div>
        </div>

        
        <div style="background:#f3e5f5;padding:12px;border-radius:6px;margin-bottom:16px;">
            <h4 style="margin:0 0 6px 0;font-size:14px;color:#7b1fa2;">🔒 THAY ĐỔI MẬT KHẨU</h4>
            <p class="muted" style="margin:0 0 12px 0;font-size:13px;">Để trống các trường bên dưới nếu bạn không muốn thay đổi mật khẩu.</p>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Mật khẩu cũ</label>
                        <div>
                            <input type="password" name="current_password" autocomplete="current-password" style="width:100%;padding:6px;border:1px solid <?php echo e($errors->has('current_password') ? '#d32f2f' : '#ddd'); ?>;border-radius:4px;" />
                            <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#d32f2f;font-size:12px;margin-top:2px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
                <div></div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Mật khẩu mới</label>
                        <div>
                            <input type="password" name="password" autocomplete="new-password" style="width:100%;padding:6px;border:1px solid <?php echo e($errors->has('password') ? '#d32f2f' : '#ddd'); ?>;border-radius:4px;" />
                            <small class="muted" style="font-size:11px;">Ít nhất 8 ký tự, nên có chữ hoa, chữ thường, số và ký tự đặc biệt.</small>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#d32f2f;font-size:12px;margin-top:2px;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
                <div>
                    <div style="display:grid;grid-template-columns:150px 1fr;gap:8px;margin-bottom:8px;">
                        <label class="muted">Xác nhận mật khẩu mới</label>
                        <div>
                            <input type="password" name="password_confirmation" autocomplete="new-password" style="width:100%;padding:6px;border:1px solid #ddd;border-radius:4px;" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <div style="text-align:right;">
            <button type="reset" class="btn" style="background:#9e9e9e;margin-right:8px;">Hủy</button>
            <button type="submit" class="btn">Lưu thay đổi</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('student.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/student/profile.blade.php ENDPATH**/ ?>