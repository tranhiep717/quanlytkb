@extends('lecturer.layout')

@section('title', 'Đổi mật khẩu')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h3 class="mb-4">
            <i class="fas fa-key me-2" style="color: #1976d2;"></i>
            Đổi mật khẩu
        </h3>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-lock me-2"></i>
                    Thay đổi mật khẩu
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('lecturer.password.change.submit') }}" method="POST">
                    @csrf

                    <!-- Current Password -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-unlock-alt me-1"></i>Mật khẩu hiện tại <span class="text-danger">*</span>
                        </label>
                        <input type="password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            name="current_password"
                            required
                            autocomplete="current-password">
                        @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-lock me-1"></i>Mật khẩu mới <span class="text-danger">*</span>
                        </label>
                        <input type="password"
                            class="form-control @error('new_password') is-invalid @enderror"
                            name="new_password"
                            required
                            minlength="8"
                            autocomplete="new-password">
                        <small class="text-muted">Tối thiểu 8 ký tự</small>
                        @error('new_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-check-circle me-1"></i>Xác nhận mật khẩu mới <span class="text-danger">*</span>
                        </label>
                        <input type="password"
                            class="form-control"
                            name="new_password_confirmation"
                            required
                            minlength="8"
                            autocomplete="new-password">
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Lưu ý:</strong> Sau khi đổi mật khẩu thành công, bạn sẽ được chuyển về trang hồ sơ cá nhân.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Đổi mật khẩu
                        </button>
                        <a href="{{ route('lecturer.profile') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security Tips -->
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-shield-alt me-2"></i>
                    Lời khuyên bảo mật
                </h6>
            </div>
            <div class="card-body">
                <ul class="mb-0 small text-muted">
                    <li>Sử dụng mật khẩu mạnh (chữ hoa, chữ thường, số, ký tự đặc biệt)</li>
                    <li>Không chia sẻ mật khẩu với bất kỳ ai</li>
                    <li>Thay đổi mật khẩu định kỳ (3-6 tháng/lần)</li>
                    <li>Không sử dụng cùng mật khẩu cho nhiều hệ thống</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection