@extends('lecturer.layout')

@section('title', 'Chi tiết Lớp học')

@section('content')
<div class="mb-3">
    <a href="{{ route('lecturer.classes') }}" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left me-1"></i>Quay lại danh sách
    </a>
</div>

<!-- Thông tin lớp học -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-info-circle me-2"></i>
            Thông tin Lớp học phần
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width: 150px;">
                            <i class="fas fa-book me-2"></i>Môn học:
                        </td>
                        <td class="fw-bold">{{ $classSection->course->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i class="fas fa-code me-2"></i>Mã môn học:
                        </td>
                        <td><span class="badge bg-primary">{{ $classSection->course->code }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i class="fas fa-users-class me-2"></i>Mã lớp:
                        </td>
                        <td><span class="badge bg-success">{{ $classSection->section_code }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i class="fas fa-credit-card me-2"></i>Số tín chỉ:
                        </td>
                        <td>{{ $classSection->course->credits }} TC</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width: 150px;">
                            <i class="fas fa-calendar-alt me-2"></i>Thời gian:
                        </td>
                        <td>
                            Thứ {{ $classSection->day_of_week }} -
                            @if($classSection->shift)
                            Tiết {{ $classSection->shift->start_period }}-{{ $classSection->shift->end_period }}
                            @else
                            TBA
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i class="fas fa-clock me-2"></i>Giờ học:
                        </td>
                        <td>
                            @if($classSection->shift)
                            {{ $classSection->shift->start_time }} - {{ $classSection->shift->end_time }}
                            @else
                            TBA
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i class="fas fa-door-open me-2"></i>Phòng học:
                        </td>
                        <td>
                            <span class="badge bg-info">
                                {{ $classSection->room ? $classSection->room->code : 'Chưa xếp phòng' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i class="fas fa-users me-2"></i>Sĩ số:
                        </td>
                        <td>
                            <span class="badge {{ $classSection->current_enrollment >= $classSection->max_capacity ? 'bg-danger' : 'bg-success' }}">
                                {{ $classSection->current_enrollment }}/{{ $classSection->max_capacity }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Danh sách sinh viên -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-users me-2"></i>
                Danh sách Sinh viên ({{ $students->count() }} sinh viên)
            </h5>
            <button class="btn btn-sm btn-success" onclick="exportToExcel()">
                <i class="fas fa-file-excel me-1"></i>Xuất Excel
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        @if($students->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
            <p class="text-muted">Chưa có sinh viên đăng ký lớp này</p>
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="studentTable">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">STT</th>
                        <th>Mã số SV</th>
                        <th>Họ và tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Ngày đăng ký</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td class="text-center">{{ $student['stt'] }}</td>
                        <td><code class="text-primary">{{ $student['mssv'] }}</code></td>
                        <td class="fw-bold">{{ $student['name'] }}</td>
                        <td>
                            <a href="mailto:{{ $student['email'] }}" class="text-decoration-none">
                                <i class="fas fa-envelope me-1"></i>{{ $student['email'] }}
                            </a>
                        </td>
                        <td>
                            @if($student['phone'] !== '--')
                            <i class="fas fa-phone me-1"></i>{{ $student['phone'] }}
                            @else
                            <span class="text-muted">--</span>
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $student['registered_at'] }}</small></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    function exportToExcel() {
        // Simple export to CSV functionality
        let table = document.getElementById('studentTable');
        let csv = [];

        // Headers
        let headers = [];
        table.querySelectorAll('thead th').forEach(th => {
            headers.push(th.textContent.trim());
        });
        csv.push(headers.join(','));

        // Rows
        table.querySelectorAll('tbody tr').forEach(tr => {
            let row = [];
            tr.querySelectorAll('td').forEach((td, idx) => {
                if (idx === 3) { // Email column
                    row.push('"' + td.querySelector('a').textContent.trim() + '"');
                } else {
                    row.push('"' + td.textContent.trim() + '"');
                }
            });
            csv.push(row.join(','));
        });

        // Download
        let csvContent = 'data:text/csv;charset=utf-8,' + csv.join('\n');
        let encodedUri = encodeURI(csvContent);
        let link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', 'danh-sach-sinh-vien-{{ $classSection->section_code }}.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
@endsection

@section('styles')
<style>
    .table th {
        font-weight: 600;
        color: #424242;
        background-color: #f5f5f5;
    }

    .table tbody tr:hover {
        background-color: #f0f7ff;
    }

    code {
        background: #e3f2fd;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 13px;
    }
</style>
@endsection