@extends('admin.layout')

@section('title', 'Quản lý Lớp học phần')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white mb-0">🎓 Quản lý Lớp học phần</h2>
        <a href="{{ route('class-sections.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Thêm Lớp học phần
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filters -->
    <div class="card bg-dark border-secondary mb-3">
        <div class="card-body">
            <form action="{{ route('class-sections.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="academic_year" class="form-label text-white">Năm học</label>
                    <input type="text"
                        name="academic_year"
                        id="academic_year"
                        class="form-control bg-dark text-white border-secondary"
                        placeholder="VD: 2024-2025"
                        value="{{ request('academic_year') }}">
                </div>

                <div class="col-md-2">
                    <label for="term" class="form-label text-white">Học kỳ</label>
                    <select name="term" id="term" class="form-select bg-dark text-white border-secondary">
                        <option value="">-- Tất cả --</option>
                        <option value="HK1" {{ request('term') == 'HK1' ? 'selected' : '' }}>HK1</option>
                        <option value="HK2" {{ request('term') == 'HK2' ? 'selected' : '' }}>HK2</option>
                        <option value="HK3" {{ request('term') == 'HK3' ? 'selected' : '' }}>HK3</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="faculty_id" class="form-label text-white">Khoa</label>
                    <select name="faculty_id" id="faculty_id" class="form-select bg-dark text-white border-secondary">
                        <option value="">-- Tất cả --</option>
                        @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}" {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>
                            {{ $faculty->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="search" class="form-label text-white">Tìm kiếm</label>
                    <input type="text"
                        name="search"
                        id="search"
                        class="form-control bg-dark text-white border-secondary"
                        placeholder="Mã lớp, môn học..."
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card bg-dark border-secondary">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>Mã lớp</th>
                            <th>Môn học</th>
                            <th>Giảng viên</th>
                            <th>Lịch học</th>
                            <th>Phòng</th>
                            <th>Sĩ số</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classSections as $section)
                        <tr>
                            <td>
                                <code class="text-warning">{{ $section->section_code }}</code>
                                <br>
                                <small class="text-muted">{{ $section->academic_year }} - {{ $section->term }}</small>
                            </td>
                            <td>
                                <strong>{{ $section->course->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $section->course->code }}</small>
                            </td>
                            <td>
                                {{ $section->lecturer->name }}
                                <br>
                                <small class="text-muted">{{ $section->lecturer->code }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $section->shift->day_name }}</span>
                                <br>
                                <small class="text-muted">Tiết {{ $section->shift->start_period }}-{{ $section->shift->end_period }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $section->room->code }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $section->current_enrollment >= $section->max_capacity ? 'bg-danger' : 'bg-success' }}">
                                    {{ $section->current_enrollment }}/{{ $section->max_capacity }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('class-sections.edit', $section) }}"
                                    class="btn btn-sm btn-primary me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('class-sections.destroy', $section) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Bạn có chắc muốn xóa lớp học phần này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-graduation-cap fa-3x mb-3 d-block"></i>
                                Không tìm thấy lớp học phần nào
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($classSections->hasPages())
            <div class="mt-4">
                {{ $classSections->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection