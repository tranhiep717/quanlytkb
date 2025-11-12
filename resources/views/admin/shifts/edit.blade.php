@extends('admin.layout')

@section('title', 'Sửa Ca học')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Toasts container -->
            <div id="toastContainer" style="position:fixed; top:16px; right:16px; display:flex; flex-direction:column; gap:10px; z-index:10000;"></div>
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('shifts.index') }}" class="btn btn-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="text-white mb-0">📅 Sửa Ca học</h2>
            </div>

            <div class="card bg-dark border-secondary">
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>Vui lòng kiểm tra các lỗi sau:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form action="{{ route('shifts.update', $shift) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-white">Mã ca</label>
                                <input type="text" name="code" class="form-control bg-dark text-white border-secondary @error('code') is-invalid @enderror" value="{{ old('code', $shift->code) }}" maxlength="20">
                                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label text-white">Tên ca <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control bg-dark text-white border-secondary @error('name') is-invalid @enderror" value="{{ old('name', $shift->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="day_of_week" class="form-label text-white">
                                Thứ <span class="text-danger">*</span>
                            </label>
                            <select name="day_of_week" id="day_of_week"
                                class="form-select bg-dark text-white border-secondary @error('day_of_week') is-invalid @enderror"
                                required>
                                <option value="">-- Chọn thứ --</option>
                                <option value="2" {{ old('day_of_week', $shift->day_of_week) == 2 ? 'selected' : '' }}>Thứ 2</option>
                                <option value="3" {{ old('day_of_week', $shift->day_of_week) == 3 ? 'selected' : '' }}>Thứ 3</option>
                                <option value="4" {{ old('day_of_week', $shift->day_of_week) == 4 ? 'selected' : '' }}>Thứ 4</option>
                                <option value="5" {{ old('day_of_week', $shift->day_of_week) == 5 ? 'selected' : '' }}>Thứ 5</option>
                                <option value="6" {{ old('day_of_week', $shift->day_of_week) == 6 ? 'selected' : '' }}>Thứ 6</option>
                                <option value="7" {{ old('day_of_week', $shift->day_of_week) == 7 ? 'selected' : '' }}>Thứ 7</option>
                            </select>
                            @error('day_of_week')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Giờ bắt đầu <span class="text-danger">*</span></label>
                                <input type="time" name="start_time" class="form-control bg-dark text-white border-secondary @error('start_time') is-invalid @enderror" value="{{ old('start_time', $shift->start_time ? substr($shift->start_time,0,5) : '') }}" required>
                                @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Giờ kết thúc <span class="text-danger">*</span></label>
                                <input type="time" name="end_time" class="form-control bg-dark text-white border-secondary @error('end_time') is-invalid @enderror" value="{{ old('end_time', $shift->end_time ? substr($shift->end_time,0,5) : '') }}" required>
                                @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Trạng thái</label>
                            <select name="status" class="form-select bg-dark text-white border-secondary">
                                <option value="active" {{ old('status', $shift->status)==='active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ old('status', $shift->status)==='inactive' ? 'selected' : '' }}>Tạm ngưng</option>
                            </select>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Lưu ý:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Giờ kết thúc phải sau giờ bắt đầu</li>
                                <li>Hệ thống sẽ tự quy đổi ra khoảng tiết và kiểm tra trùng lặp theo thứ</li>
                            </ul>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Cập nhật
                            </button>
                            <a href="{{ route('shifts.index') }}" class="btn btn-secondary">
                                Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showToast(message, type = 'success') {
        const wrap = document.getElementById('toastContainer');
        if (!wrap) return;
        const el = document.createElement('div');
        el.setAttribute('role', 'alert');
        el.setAttribute('aria-live', 'assertive');
        el.style.cssText = `min-width:320px; max-width:520px; padding:14px 16px; border-radius:10px; color:#ffffff; background:${type==='error' ? '#dc2626' : '#16a34a'}; box-shadow:0 8px 24px rgba(0,0,0,0.18); display:flex; align-items:center; gap:12px;`;
        const icon = type === 'error' ?
            '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10Z" fill="rgba(255,255,255,0.25)"/><path d="M13 13H11V7h2v6Zm0 4H11v-2h2v2Z" fill="#fff"/></svg>' :
            '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10Z" fill="rgba(255,255,255,0.25)"/><path d="m10 14.586-2.293-2.293-1.414 1.414L10 17.414l7.707-7.707-1.414-1.414L10 14.586Z" fill="#fff"/></svg>';
        el.innerHTML = `${icon}<div style="font-weight:700; letter-spacing:.2px;">${type==='error'?'Lỗi':'Thành công'}</div><div style="flex:1 1 auto;">${message}</div><button aria-label="Đóng thông báo" style="background:transparent; border:none; color:#fff; font-size:18px; line-height:1; cursor:pointer;" onclick="this.parentElement.remove()">×</button>`;
        wrap.appendChild(el);
        setTimeout(() => {
            el.remove();
        }, 4000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
        showToast(@json(session('success')), 'success');
        @endif
        @if(session('system_error'))
        showToast(@json(session('system_error')), 'error');
        @endif
    @if($errors->any())
        // Scroll to top so the banner and invalid fields are visible
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
        const invalid = document.querySelector('.is-invalid');
        if (invalid) invalid.focus({
            preventScroll: false
        });
        @endif
    });
</script>
@endsection