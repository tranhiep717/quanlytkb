

<?php $__env->startSection('title', 'Hồ sơ cá nhân'); ?>

<?php $__env->startSection('content'); ?>
<h3 class="mb-4">
    <i class="fas fa-user-edit me-2" style="color: #1976d2;"></i>
    Hồ sơ cá nhân
</h3>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Thông tin cá nhân
                </h5>
            </div>
            <div class="card-body">
                <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-1"></i><?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-1"></i><?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <form action="<?php echo e(route('lecturer.profile.update')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <!-- Mã Giảng viên (readonly) -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-id-card me-1"></i>Mã Giảng viên
                        </label>
                        <input type="text" class="form-control bg-light" value="<?php echo e($lecturer->code); ?>" readonly>
                        <small class="text-muted">Thông tin này không thể thay đổi</small>
                    </div>

                    <!-- Họ tên (editable) -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-user me-1"></i>Họ và tên <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="name"
                            value="<?php echo e(old('name', $lecturer->name)); ?>" required maxlength="255"
                            oninvalid="this.setCustomValidity('Vui lòng nhập họ và tên.')"
                            oninput="this.setCustomValidity('')">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Khoa (readonly) -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-building me-1"></i>Khoa
                        </label>
                        <input type="text" class="form-control bg-light"
                            value="<?php echo e($lecturer->faculty ? $lecturer->faculty->name : 'Chưa phân công'); ?>" readonly>
                        <small class="text-muted">Thông tin này không thể thay đổi</small>
                    </div>

                    <!-- Email (editable) -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-envelope me-1"></i>Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email"
                            value="<?php echo e(old('email', $lecturer->email)); ?>" required
                            oninvalid="this.setCustomValidity(this.validity.valueMissing ? 'Vui lòng nhập địa chỉ email.' : (this.validity.typeMismatch ? 'Email không đúng định dạng.' : ''))"
                            oninput="this.setCustomValidity('')">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Số điện thoại (editable) -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-phone me-1"></i>Số điện thoại
                        </label>
                        <input type="text" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="phone"
                            value="<?php echo e(old('phone', $lecturer->phone)); ?>" placeholder="VD: 0901234567" pattern="[0-9]*" maxlength="15"
                            oninvalid="this.setCustomValidity('Số điện thoại chỉ được chứa số (0-9).')"
                            oninput="this.setCustomValidity('')">
                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Ngày sinh (editable) -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-calendar me-1"></i>Ngày sinh
                        </label>
                        <input type="date" class="form-control <?php $__errorArgs = ['dob'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="dob"
                            value="<?php echo e(old('dob', $lecturer->dob)); ?>"
                            oninvalid="this.setCustomValidity('Ngày sinh không đúng định dạng.')"
                            oninput="this.setCustomValidity('')">
                        <?php $__errorArgs = ['dob'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Giới tính (editable) -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-venus-mars me-1"></i>Giới tính
                        </label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="genderMale" value="Nam"
                                    <?php echo e(old('gender', $lecturer->gender ?? 'Nam') == 'Nam' ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="genderMale">Nam</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="Nữ"
                                    <?php echo e(old('gender', $lecturer->gender) == 'Nữ' ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="genderFemale">Nữ</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="genderOther" value="Khác"
                                    <?php echo e(old('gender', $lecturer->gender) == 'Khác' ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="genderOther">Khác</label>
                            </div>
                        </div>
                        <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Avatar (editable) -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-image me-1"></i>Ảnh đại diện
                        </label>
                        <input type="file" class="form-control <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="avatar"
                            accept="image/*">
                        <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">Kích thước tối đa: 2MB. Định dạng: JPG, PNG</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Lưu thay đổi
                        </button>
                        <a href="<?php echo e(route('lecturer.dashboard')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Avatar Preview -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-user-circle me-2"></i>
                    Ảnh đại diện
                </h6>
            </div>
            <div class="card-body text-center">
                <?php if($lecturer->avatar_url): ?>
                <img src="<?php echo e(asset('storage/' . $lecturer->avatar_url)); ?>" alt="Avatar"
                    class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                <?php else: ?>
                <div class="mb-3">
                    <i class="fas fa-user-circle fa-8x text-muted"></i>
                </div>
                <?php endif; ?>
                <p class="text-muted mb-0"><?php echo e($lecturer->name); ?></p>
                <small class="text-muted"><?php echo e($lecturer->code); ?></small>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-link me-2"></i>
                    Liên kết nhanh
                </h6>
            </div>
            <div class="card-body">
                <a href="<?php echo e(route('lecturer.password.change')); ?>" class="btn btn-outline-primary btn-sm w-100 mb-2">
                    <i class="fas fa-key me-1"></i>Đổi mật khẩu
                </a>
                <a href="<?php echo e(route('lecturer.dashboard')); ?>" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="fas fa-calendar me-1"></i>Thời khóa biểu
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('lecturer.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/lecturer/profile/show.blade.php ENDPATH**/ ?>