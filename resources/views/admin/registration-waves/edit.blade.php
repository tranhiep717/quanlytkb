@extends('admin.layout')

@section('title', 'Sửa Đợt đăng ký')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('registration-waves.index') }}" class="btn btn-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="text-white mb-0">⏰ Sửa Đợt đăng ký</h2>
            </div>

            <div class="card bg-dark border-secondary">
                <div class="card-body">
                    <form action="{{ route('registration-waves.update', $wave) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="academic_year" class="form-label text-white">
                                    Năm học <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    name="academic_year"
                                    id="academic_year"
                                    class="form-control bg-dark text-white border-secondary @error('academic_year') is-invalid @enderror"
                                    value="{{ old('academic_year', $wave->academic_year) }}"
                                    placeholder="VD: 2024-2025"
                                    required>
                                @error('academic_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="term" class="form-label text-white">
                                    Học kỳ <span class="text-danger">*</span>
                                </label>
                                <select name="term"
                                    id="term"
                                    class="form-select bg-dark text-white border-secondary @error('term') is-invalid @enderror"
                                    required>
                                    <option value="">-- Chọn học kỳ --</option>
                                    <option value="HK1" {{ old('term', $wave->term) == 'HK1' ? 'selected' : '' }}>Học kỳ 1</option>
                                    <option value="HK2" {{ old('term', $wave->term) == 'HK2' ? 'selected' : '' }}>Học kỳ 2</option>
                                    <option value="HK3" {{ old('term', $wave->term) == 'HK3' ? 'selected' : '' }}>Học kỳ 3 (Hè)</option>
                                </select>
                                @error('term')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label text-white">
                                Tên đợt <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                name="name"
                                id="name"
                                class="form-control bg-dark text-white border-secondary @error('name') is-invalid @enderror"
                                value="{{ old('name', $wave->name) }}"
                                placeholder="VD: Đợt 1 - Ưu tiên Khóa cũ"
                                required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="starts_at" class="form-label text-white">
                                    Thời gian bắt đầu <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local"
                                    name="starts_at"
                                    id="starts_at"
                                    class="form-control bg-dark text-white border-secondary @error('starts_at') is-invalid @enderror"
                                    value="{{ old('starts_at', \Carbon\Carbon::parse($wave->starts_at)->format('Y-m-d\TH:i')) }}"
                                    required>
                                @error('starts_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="ends_at" class="form-label text-white">
                                    Thời gian kết thúc <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local"
                                    name="ends_at"
                                    id="ends_at"
                                    class="form-control bg-dark text-white border-secondary @error('ends_at') is-invalid @enderror"
                                    value="{{ old('ends_at', \Carbon\Carbon::parse($wave->ends_at)->format('Y-m-d\TH:i')) }}"
                                    required>
                                @error('ends_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @php
                        $audience = json_decode($wave->audience, true);
                        $selectedFaculties = old('faculties', $audience['faculties'] ?? []);
                        $selectedCohorts = old('cohorts', $audience['cohorts'] ?? []);
                        @endphp

                        <div class="mb-3">
                            <label class="form-label text-white">
                                Khoa được phép đăng ký <span class="text-danger">*</span>
                            </label>
                            <div class="border border-secondary rounded p-3 bg-dark">
                                @foreach($faculties as $faculty)
                                <div class="form-check mb-2">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        name="faculties[]"
                                        value="{{ $faculty->id }}"
                                        id="faculty{{ $faculty->id }}"
                                        {{ in_array($faculty->id, $selectedFaculties) ? 'checked' : '' }}>
                                    <label class="form-check-label text-white" for="faculty{{ $faculty->id }}">
                                        {{ $faculty->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('faculties')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">
                                Khóa được phép đăng ký
                            </label>
                            <div class="border border-secondary rounded p-3 bg-dark">
                                @php
                                $cohorts = ['K17', 'K18', 'K19', 'K20', 'K21'];
                                @endphp
                                @foreach($cohorts as $cohort)
                                <div class="form-check form-check-inline mb-2">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        name="cohorts[]"
                                        value="{{ $cohort }}"
                                        id="cohort{{ $cohort }}"
                                        {{ in_array($cohort, $selectedCohorts) ? 'checked' : '' }}>
                                    <label class="form-check-label text-white" for="cohort{{ $cohort }}">
                                        {{ $cohort }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            <small class="text-muted">Để trống = tất cả các khóa</small>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Lưu ý:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Thời gian kết thúc phải sau thời gian bắt đầu</li>
                                <li>Chọn ít nhất 1 khoa</li>
                                <li>Không chọn khóa = tất cả sinh viên trong các khoa được chọn</li>
                            </ul>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Cập nhật
                            </button>
                            <a href="{{ route('registration-waves.index') }}" class="btn btn-secondary">
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