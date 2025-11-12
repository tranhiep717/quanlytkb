@extends('admin.layout')

@section('title', 'Thêm Ca học')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('shifts.index') }}" class="btn btn-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="text-white mb-0">📅 Thêm Ca học mới</h2>
            </div>

            <div class="card bg-dark border-secondary">
                <div class="card-body">
                    <form action="{{ route('shifts.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-white">Mã ca</label>
                                <input type="text" name="code" class="form-control bg-dark text-white border-secondary @error('code') is-invalid @enderror" value="{{ old('code') }}" maxlength="20">
                                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label text-white">Tên ca <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control bg-dark text-white border-secondary @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="day_of_week" class="form-label text-white">Thứ <span class="text-danger">*</span></label>
                            <select name="day_of_week" id="day_of_week" class="form-select bg-dark text-white border-secondary @error('day_of_week') is-invalid @enderror" required>
                                <option value="">-- Chọn thứ --</option>
                                <option value="1" {{ old('day_of_week') == 1 ? 'selected' : '' }}>Thứ 2</option>
                                <option value="2" {{ old('day_of_week') == 2 ? 'selected' : '' }}>Thứ 3</option>
                                <option value="3" {{ old('day_of_week') == 3 ? 'selected' : '' }}>Thứ 4</option>
                                <option value="4" {{ old('day_of_week') == 4 ? 'selected' : '' }}>Thứ 5</option>
                                <option value="5" {{ old('day_of_week') == 5 ? 'selected' : '' }}>Thứ 6</option>
                                <option value="6" {{ old('day_of_week') == 6 ? 'selected' : '' }}>Thứ 7</option>
                                <option value="7" {{ old('day_of_week') == 7 ? 'selected' : '' }}>CN</option>
                            </select>
                            @error('day_of_week')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Giờ bắt đầu <span class="text-danger">*</span></label>
                                <input type="time" name="start_time" class="form-control bg-dark text-white border-secondary @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                                @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Giờ kết thúc <span class="text-danger">*</span></label>
                                <input type="time" name="end_time" class="form-control bg-dark text-white border-secondary @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                                @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Trạng thái</label>
                            <select name="status" class="form-select bg-dark text-white border-secondary">
                                <option value="active" {{ old('status','active')==='active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ old('status')==='inactive' ? 'selected' : '' }}>Tạm ngưng</option>
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
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Lưu
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