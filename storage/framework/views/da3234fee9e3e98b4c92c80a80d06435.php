

<?php $__env->startSection('title', 'Sửa Lớp học phần'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex align-items-center mb-4">
                <a href="<?php echo e(route('class-sections.index')); ?>" class="btn btn-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="text-white mb-0">🎓 Sửa Lớp học phần</h2>
            </div>

            <div class="card bg-dark border-secondary">
                <div class="card-body">
                    <form action="<?php echo e(route('class-sections.update', $classSection)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="academic_year" class="form-label text-white">
                                    Năm học <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    name="academic_year"
                                    id="academic_year"
                                    class="form-control bg-dark text-white border-secondary <?php $__errorArgs = ['academic_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('academic_year', $classSection->academic_year)); ?>"
                                    placeholder="VD: 2024-2025"
                                    required>
                                <?php $__errorArgs = ['academic_year'];
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

                            <div class="col-md-6 mb-3">
                                <label for="term" class="form-label text-white">
                                    Học kỳ <span class="text-danger">*</span>
                                </label>
                                <select name="term"
                                    id="term"
                                    class="form-select bg-dark text-white border-secondary <?php $__errorArgs = ['term'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    required>
                                    <option value="">-- Chọn học kỳ --</option>
                                    <option value="HK1" <?php echo e(old('term', $classSection->term) == 'HK1' ? 'selected' : ''); ?>>Học kỳ 1</option>
                                    <option value="HK2" <?php echo e(old('term', $classSection->term) == 'HK2' ? 'selected' : ''); ?>>Học kỳ 2</option>
                                    <option value="HK3" <?php echo e(old('term', $classSection->term) == 'HK3' ? 'selected' : ''); ?>>Học kỳ 3 (Hè)</option>
                                </select>
                                <?php $__errorArgs = ['term'];
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
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="course_id" class="form-label text-white">
                                    Môn học <span class="text-danger">*</span>
                                </label>
                                <select name="course_id"
                                    id="course_id"
                                    class="form-select bg-dark text-white border-secondary <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    required>
                                    <option value="">-- Chọn môn học --</option>
                                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($course->id); ?>"
                                        <?php echo e(old('course_id', $classSection->course_id) == $course->id ? 'selected' : ''); ?>>
                                        <?php echo e($course->code); ?> - <?php echo e($course->name); ?> (<?php echo e($course->credits); ?> TC)
                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['course_id'];
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

                            <div class="col-md-6 mb-3">
                                <label for="section_code" class="form-label text-white">
                                    Mã lớp <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    name="section_code"
                                    id="section_code"
                                    class="form-control bg-dark text-white border-secondary <?php $__errorArgs = ['section_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('section_code', $classSection->section_code)); ?>"
                                    placeholder="VD: IT001.01"
                                    required>
                                <?php $__errorArgs = ['section_code'];
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
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Giảng viên</label>
                            <div class="alert alert-secondary p-2 mb-0 d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-user-graduate me-2"></i>
                                    <?php if($classSection->lecturer): ?>
                                        <strong><?php echo e($classSection->lecturer->code); ?> - <?php echo e($classSection->lecturer->name); ?></strong>
                                    <?php else: ?>
                                        <strong>Chưa phân công</strong>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo e(route('class-sections.assignments')); ?>" class="btn btn-sm btn-outline-light">Quản lý phân công</a>
                            </div>
                            <small class="text-muted">Việc phân công/đổi/bỏ giảng viên được thực hiện ở trang riêng (UC2.9).</small>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="day_of_week" class="form-label text-white">
                                    Thứ <span class="text-danger">*</span>
                                </label>
                                <select name="day_of_week"
                                    id="day_of_week"
                                    class="form-select bg-dark text-white border-secondary <?php $__errorArgs = ['day_of_week'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    required>
                                    <option value="">-- Chọn thứ --</option>
                                    <option value="2" <?php echo e(old('day_of_week', $classSection->day_of_week) == 2 ? 'selected' : ''); ?>>Thứ 2</option>
                                    <option value="3" <?php echo e(old('day_of_week', $classSection->day_of_week) == 3 ? 'selected' : ''); ?>>Thứ 3</option>
                                    <option value="4" <?php echo e(old('day_of_week', $classSection->day_of_week) == 4 ? 'selected' : ''); ?>>Thứ 4</option>
                                    <option value="5" <?php echo e(old('day_of_week', $classSection->day_of_week) == 5 ? 'selected' : ''); ?>>Thứ 5</option>
                                    <option value="6" <?php echo e(old('day_of_week', $classSection->day_of_week) == 6 ? 'selected' : ''); ?>>Thứ 6</option>
                                    <option value="7" <?php echo e(old('day_of_week', $classSection->day_of_week) == 7 ? 'selected' : ''); ?>>Thứ 7</option>
                                </select>
                                <?php $__errorArgs = ['day_of_week'];
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

                            <div class="col-md-4 mb-3">
                                <label for="shift_id" class="form-label text-white">
                                    Ca học <span class="text-danger">*</span>
                                </label>
                                <select name="shift_id"
                                    id="shift_id"
                                    class="form-select bg-dark text-white border-secondary <?php $__errorArgs = ['shift_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    required>
                                    <option value="">-- Chọn ca học --</option>
                                    <?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($shift->id); ?>"
                                        <?php echo e(old('shift_id', $classSection->shift_id) == $shift->id ? 'selected' : ''); ?>>
                                        Tiết <?php echo e($shift->start_period); ?>-<?php echo e($shift->end_period); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['shift_id'];
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

                            <div class="col-md-4 mb-3">
                                <label for="room_id" class="form-label text-white">
                                    Phòng học <span class="text-danger">*</span>
                                </label>
                                <select name="room_id"
                                    id="room_id"
                                    class="form-select bg-dark text-white border-secondary <?php $__errorArgs = ['room_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    required>
                                    <option value="">-- Chọn phòng --</option>
                                    <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($room->id); ?>"
                                        <?php echo e(old('room_id', $classSection->room_id) == $room->id ? 'selected' : ''); ?>>
                                        <?php echo e($room->code); ?> - <?php echo e($room->building); ?> (<?php echo e($room->capacity); ?> chỗ)
                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['room_id'];
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
                        </div>

                        <div class="mb-3">
                            <label for="max_capacity" class="form-label text-white">
                                Sĩ số tối đa <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                name="max_capacity"
                                id="max_capacity"
                                class="form-control bg-dark text-white border-secondary <?php $__errorArgs = ['max_capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('max_capacity', $classSection->max_capacity)); ?>"
                                min="1"
                                required>
                            <?php $__errorArgs = ['max_capacity'];
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

                        <div class="mb-3">
                            <label for="status" class="form-label text-white">
                                Trạng thái
                            </label>
                            <select name="status"
                                id="status"
                                class="form-select bg-dark text-white border-secondary <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="active" <?php echo e(old('status', $classSection->status) == 'active' ? 'selected' : ''); ?>>✓ Hoạt động</option>
                                <option value="locked" <?php echo e(old('status', $classSection->status) == 'locked' ? 'selected' : ''); ?>>🔒 Tạm khóa</option>
                            </select>
                            <?php $__errorArgs = ['status'];
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

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Số sinh viên đã đăng ký: <strong><?php echo e($classSection->registrations->count()); ?></strong>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Kiểm tra ràng buộc (A-4):</strong>
                            <ul class="mb-0 mt-2">
                                <li>Mã lớp không trùng trong cùng năm học & học kỳ</li>
                                <li>Phòng học không bị trùng cùng thứ & ca</li>
                                <li><strong>Sĩ số tối đa ≤ Sức chứa phòng</strong> (tự động điều chỉnh nếu vượt)</li>
                                <li>Phân công giảng viên được xử lý ở bước riêng (UC2.9)</li>
                            </ul>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Cập nhật
                            </button>
                            <a href="<?php echo e(route('class-sections.index')); ?>" class="btn btn-secondary">
                                Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\quanlytkbieu\resources\views/admin/class-sections/edit.blade.php ENDPATH**/ ?>