@extends('admin.layout')

@section('title', 'Thêm Môn học')

@section('styles')
<style>
    /* Toast Notification - Lỗi hệ thống (System Errors) */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    .toast-notification {
        min-width: 300px;
        max-width: 500px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        margin-bottom: 10px;
        animation: slideIn 0.3s ease-out;
        overflow: hidden;
    }

    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .toast-error {
        border-left: 4px solid #dc3545;
    }

    .toast-success {
        border-left: 4px solid #28a745;
    }

    .toast-warning {
        border-left: 4px solid #ffc107;
    }

    .toast-header {
        padding: 12px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #e9ecef;
    }

    .toast-body {
        padding: 12px 16px;
        color: #495057;
    }

    .toast-close {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: #6c757d;
        padding: 0;
        margin-left: 10px;
    }

    /* Form-level Alert - Lỗi nghiệp vụ (Business Logic Errors) */
    .alert-business-error {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        border-left: 4px solid #dc3545;
        color: #721c24;
        padding: 1rem 1.25rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-business-error .alert-icon {
        flex-shrink: 0;
        margin-right: 12px;
        font-size: 20px;
    }

    .alert-business-error .alert-content {
        flex: 1;
    }

    .alert-business-error .alert-title {
        font-weight: 700;
        margin-bottom: 4px;
        font-size: 15px;
    }

    .alert-business-error .alert-message {
        margin: 0;
        font-size: 14px;
        line-height: 1.5;
    }

    /* Inline Validation - Lỗi nhập liệu (Validation Errors) */
    .is-invalid {
        border-color: #dc3545 !important;
        padding-right: calc(1.5em + 0.75rem);
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        font-weight: 500;
    }

    .is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .info-box {
        background: linear-gradient(135deg, rgba(23, 162, 184, 0.15), rgba(23, 162, 184, 0.05));
        border-left: 4px solid #17a2b8;
        padding: 1.25rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
    }

    .info-box ul {
        margin-bottom: 0;
        padding-left: 1.5rem;
    }

    .info-box li {
        margin-bottom: 0.5rem;
        line-height: 1.6;
    }

    .form-section {
        background: white;
        padding: 2rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .badge-required {
        background: #dc3545;
        color: white;
        padding: 0.2rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.7rem;
        font-weight: 600;
        margin-left: 0.25rem;
    }

    .form-control,
    .form-select {
        border: 1px solid #ced4da;
        padding: 0.6rem 0.75rem;
        border-radius: 0.375rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="mb-1">Thêm Môn học</h2>
                    <p class="text-muted mb-0">Tạo học phần mới trong hệ thống</p>
                </div>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Lưu ý quan trọng</h6>
                <ul>
                    <li><strong>Mã môn học phải duy nhất</strong> - Hệ thống sẽ từ chối nếu mã đã tồn tại (Luồng 4a)</li>
                    <li><strong>Số tín chỉ phải lớn hơn 0</strong> - Giá trị hợp lệ từ 1 đến 10 (Luồng 4b)</li>
                    <li>Các trường có dấu <span class="badge-required">BẮT BUỘC</span> phải điền đầy đủ</li>
                    <li><strong>Môn tiên quyết</strong> sẽ được thiết lập riêng tại trang danh sách (sau khi tạo môn học)</li>
                </ul>
            </div>

            <!-- Form Section -->
            <div class="form-section">
                <!-- Business Logic Error Alert (Lỗi nghiệp vụ - Luồng 4a, 4b) -->
                @if($errors->has('code') && str_contains($errors->first('code'), 'đã tồn tại'))
                <div class="alert-business-error">
                    <div class="alert-icon">⚠️</div>
                    <div class="alert-content">
                        <div class="alert-title">Lỗi nghiệp vụ (Luồng 4a)</div>
                        <p class="alert-message">
                            <strong>Mã môn học '{{ old('code') }}' đã tồn tại trong hệ thống.</strong><br>
                            Vui lòng chọn mã môn học khác. Mỗi môn học phải có mã duy nhất.
                        </p>
                    </div>
                </div>
                @endif

                @if($errors->has('faculty_id') && str_contains($errors->first('faculty_id'), 'liên kết'))
                <div class="alert-business-error">
                    <div class="alert-icon">🔒</div>
                    <div class="alert-content">
                        <div class="alert-title">Lỗi nghiệp vụ (Luồng 4b)</div>
                        <p class="alert-message">
                            <strong>Không thể thay đổi Khoa quản lý.</strong><br>
                            Môn học này đang có lớp học phần hoặc điều kiện tiên quyết liên kết.
                        </p>
                    </div>
                </div>
                @endif

                <form action="{{ route('courses.store') }}" method="POST" id="courseForm">
                    @csrf

                    <!-- Mã môn học -->
                    <div class="mb-3">
                        <label for="code" class="form-label">
                            Mã môn học
                            <span class="badge-required">BẮT BUỘC</span>
                        </label>
                        <input type="text"
                            name="code"
                            id="code"
                            class="form-control @error('code') is-invalid @enderror"
                            value="{{ old('code') }}"
                            placeholder="VD: IT001, MATH101"
                            required>
                        <div class="form-text">Mã phải duy nhất trong hệ thống</div>
                        @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tên môn học -->
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            Tên môn học
                            <span class="badge-required">BẮT BUỘC</span>
                        </label>
                        <input type="text"
                            name="name"
                            id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="VD: Nhập môn Lập trình"
                            required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- Số tín chỉ -->
                        <div class="col-md-6 mb-3">
                            <label for="credits" class="form-label">
                                Số tín chỉ
                                <span class="badge-required">BẮT BUỘC</span>
                            </label>
                            <input type="number"
                                name="credits"
                                id="credits"
                                class="form-control @error('credits') is-invalid @enderror"
                                value="{{ old('credits', 3) }}"
                                min="1"
                                max="10"
                                placeholder="3"
                                required>
                            <div class="form-text">Giá trị hợp lệ từ 1 đến 10</div>
                            @error('credits')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Khoa -->
                        <div class="col-md-6 mb-3">
                            <label for="faculty_id" class="form-label">
                                Khoa
                                <span class="badge-required">BẮT BUỘC</span>
                            </label>
                            <select name="faculty_id"
                                id="faculty_id"
                                class="form-select @error('faculty_id') is-invalid @enderror"
                                required>
                                <option value="">-- Chọn khoa --</option>
                                @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                    {{ $faculty->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('faculty_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Loại học phần -->
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Loại học phần</label>
                            <select name="type" id="type" class="form-select">
                                <option value="">-- Không chọn --</option>
                                <option value="Bắt buộc" {{ old('type') == 'Bắt buộc' ? 'selected' : '' }}>Bắt buộc</option>
                                <option value="Tự chọn" {{ old('type') == 'Tự chọn' ? 'selected' : '' }}>Tự chọn</option>
                                <option value="Đại cương" {{ old('type') == 'Đại cương' ? 'selected' : '' }}>Đại cương</option>
                                <option value="Chuyên ngành" {{ old('type') == 'Chuyên ngành' ? 'selected' : '' }}>Chuyên ngành</option>
                            </select>
                        </div>

                        <!-- Trạng thái -->
                        <div class="col-md-6 mb-3">
                            <label for="is_active" class="form-label">Trạng thái</label>
                            <select name="is_active" id="is_active" class="form-select">
                                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Ngưng hoạt động</option>
                            </select>
                            <div class="form-text">Mặc định: Hoạt động</div>
                        </div>
                    </div>

                    <!-- Mô tả -->
                    <div class="mb-4">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea name="description"
                            id="description"
                            rows="4"
                            class="form-control"
                            placeholder="Nhập mô tả ngắn về môn học, mục tiêu, nội dung chính...">{{ old('description') }}</textarea>
                        <div class="form-text">Mô tả giúp sinh viên hiểu rõ hơn về môn học</div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-save me-2"></i>Lưu môn học
                        </button>
                        <a href="{{ route('courses.index') }}" class="btn btn-secondary btn-lg px-4">
                            <i class="fas fa-times me-2"></i>Hủy bỏ
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container for System Errors (Lỗi hệ thống) -->
<div class="toast-container" id="toastContainer"></div>

@endsection

@section('scripts')
<script>
    // ============================================
    // 1. INLINE VALIDATION (Lỗi nhập liệu - Luồng 4b)
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('courseForm');

        // Validate Mã môn học
        const codeInput = document.getElementById('code');
        codeInput.addEventListener('blur', function() {
            validateRequired(this, 'Vui lòng nhập mã môn học');
        });

        // Validate Tên môn học
        const nameInput = document.getElementById('name');
        nameInput.addEventListener('blur', function() {
            validateRequired(this, 'Vui lòng nhập tên môn học');
        });

        // Validate Số tín chỉ
        const creditsInput = document.getElementById('credits');
        creditsInput.addEventListener('blur', function() {
            const value = parseInt(this.value);
            if (!this.value) {
                showInlineError(this, 'Vui lòng nhập số tín chỉ');
            } else if (value < 1 || value > 10) {
                showInlineError(this, 'Số tín chỉ phải từ 1 đến 10');
            } else {
                clearInlineError(this);
            }
        });

        // Validate Khoa
        const facultySelect = document.getElementById('faculty_id');
        facultySelect.addEventListener('blur', function() {
            validateRequired(this, 'Vui lòng chọn khoa quản lý');
        });

        // Form submission validation
        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Check all required fields
            if (!validateRequired(codeInput, 'Vui lòng nhập mã môn học')) isValid = false;
            if (!validateRequired(nameInput, 'Vui lòng nhập tên môn học')) isValid = false;
            if (!validateRequired(facultySelect, 'Vui lòng chọn khoa quản lý')) isValid = false;

            // Check credits
            const creditsValue = parseInt(creditsInput.value);
            if (!creditsInput.value) {
                showInlineError(creditsInput, 'Vui lòng nhập số tín chỉ');
                isValid = false;
            } else if (creditsValue < 1 || creditsValue > 10) {
                showInlineError(creditsInput, 'Số tín chỉ phải từ 1 đến 10');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                showToast('error', 'Lỗi nhập liệu', 'Vui lòng kiểm tra và điền đầy đủ các trường bắt buộc');
            }
        });
    });

    function validateRequired(input, message) {
        if (!input.value || input.value.trim() === '') {
            showInlineError(input, message);
            return false;
        } else {
            clearInlineError(input);
            return true;
        }
    }

    function showInlineError(input, message) {
        // Add invalid class
        input.classList.add('is-invalid');

        // Remove existing error message
        const existingError = input.parentElement.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }

        // Add error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        input.parentElement.appendChild(errorDiv);
    }

    function clearInlineError(input) {
        input.classList.remove('is-invalid');
        const errorDiv = input.parentElement.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    // ============================================
    // 2. TOAST NOTIFICATION (Lỗi hệ thống - Luồng 5a)
    // ============================================
    function showToast(type, title, message) {
        const container = document.getElementById('toastContainer');

        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;

        const iconMap = {
            error: '❌',
            success: '✅',
            warning: '⚠️'
        };

        const titleMap = {
            error: title || 'Lỗi hệ thống',
            success: title || 'Thành công',
            warning: title || 'Cảnh báo'
        };

        toast.innerHTML = `
        <div class="toast-header">
            <strong style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 18px;">${iconMap[type]}</span>
                ${titleMap[type]}
            </strong>
            <button class="toast-close" onclick="closeToast(this)">&times;</button>
        </div>
        <div class="toast-body">${message}</div>
    `;

        container.appendChild(toast);

        // Auto close after 5 seconds
        setTimeout(() => {
            closeToast(toast.querySelector('.toast-close'));
        }, 5000);
    }

    function closeToast(button) {
        const toast = button.closest('.toast-notification');
        toast.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => {
            toast.remove();
        }, 300);
    }

    // Add slideOut animation
    const style = document.createElement('style');
    style.textContent = `
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
    document.head.appendChild(style);

    // ============================================
    // 3. SHOW SYSTEM ERROR IF EXISTS (from session)
    // ============================================
    @if(session('error'))
    showToast('error', 'Lỗi hệ thống (Luồng 5a)', '{{ session('
        error ') }}');
    @endif

    @if(session('success'))
    showToast('success', 'Thành công', '{{ session('
        success ') }}');
    @endif
</script>
@endsection