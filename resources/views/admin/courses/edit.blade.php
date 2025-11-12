@extends('admin.layout')

@section('title', 'Sửa Môn học')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('courses.index') }}" class="btn btn-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="text-white mb-0">📚 Sửa Môn học</h2>
            </div>

            <div class="card bg-dark border-secondary">
                <div class="card-body">
                    <form action="{{ route('courses.update', $course) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="code" class="form-label text-white">
                                Mã môn học <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                name="code"
                                id="code"
                                class="form-control bg-dark text-white border-secondary @error('code') is-invalid @enderror"
                                value="{{ old('code', $course->code) }}"
                                placeholder="VD: IT001"
                                required>
                            @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label text-white">
                                Tên môn học <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                name="name"
                                id="name"
                                class="form-control bg-dark text-white border-secondary @error('name') is-invalid @enderror"
                                value="{{ old('name', $course->name) }}"
                                placeholder="VD: Nhập môn Lập trình"
                                required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="credits" class="form-label text-white">
                                    Số tín chỉ <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                    name="credits"
                                    id="credits"
                                    class="form-control bg-dark text-white border-secondary @error('credits') is-invalid @enderror"
                                    value="{{ old('credits', $course->credits) }}"
                                    min="1"
                                    max="10"
                                    required>
                                @error('credits')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="faculty_id" class="form-label text-white">
                                    Khoa <span class="text-danger">*</span>
                                </label>
                                <select name="faculty_id"
                                    id="faculty_id"
                                    class="form-select bg-dark text-white border-secondary @error('faculty_id') is-invalid @enderror"
                                    required>
                                    <option value="">-- Chọn khoa --</option>
                                    @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}"
                                        {{ old('faculty_id', $course->faculty_id) == $faculty->id ? 'selected' : '' }}>
                                        {{ $faculty->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('faculty_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="prerequisites" class="form-label text-white">
                                Môn tiên quyết
                            </label>
                            <select name="prerequisites[]"
                                id="prerequisites"
                                class="form-select bg-dark text-white border-secondary"
                                multiple
                                size="5">
                                @foreach($allCourses as $c)
                                @if($c->id !== $course->id)
                                <option value="{{ $c->id }}"
                                    {{ in_array($c->id, old('prerequisites', $course->prerequisites->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $c->code }} - {{ $c->name }}
                                </option>
                                @endif
                                @endforeach
                            </select>
                            <small class="text-muted">Giữ Ctrl (hoặc Cmd) để chọn nhiều môn</small>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Lưu ý:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Mã môn học phải duy nhất</li>
                                <li>Số tín chỉ từ 1 đến 10</li>
                                <li>Môn tiên quyết là các môn sinh viên cần học trước môn này</li>
                            </ul>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Cập nhật
                            </button>
                            <a href="{{ route('courses.index') }}" class="btn btn-secondary">
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
