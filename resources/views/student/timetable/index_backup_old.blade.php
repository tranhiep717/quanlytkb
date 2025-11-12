@extends('student.layout')

@section('title','Thời khóa biểu')

@section('content')

<!-- Header -->
<div class="row mb-4">
    <div class="col-md-8">
        <h3 class="mb-0">
            <i class="fas fa-calendar-week me-2" style="color:#1976d2"></i>
            Thời khóa biểu Sinh viên
        </h3>
        <small class="text-muted">Năm học: {{ $academicYear }} -
            {{ $term === 'HK1' ? 'Học kỳ 1' : ($term === 'HK2' ? 'Học kỳ 2' : 'Học kỳ Hè') }}</small>
    </div>
    <div class="col-md-4 text-end">
        <div class="badge bg-primary fs-6">
            <i class="fas fa-book me-1"></i>
            {{ $totalClasses ?? ($regs->count() ?? 0) }} lớp đã đăng ký
        </div>
    </div>
</div>

<!-- Control Bar: Filters, View modes, Navigation, Exports -->
<div class="card mb-3">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <form class="col-12 col-lg-6" method="GET" action="{{ route('student.timetable') }}">
                <div class="row g-2">
                    <div class="col-6 col-md-5">
                        <label class="form-label mb-1">Năm học</label>
                        <select name="academic_year" class="form-select form-select-sm">
                            @foreach(($years ?? collect([$academicYear ?? $year])) as $y)
                            <option value="{{ $y }}" {{ $y == ($academicYear ?? $year) ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-4">
                        <label class="form-label mb-1">Học kỳ</label>
                        <select name="term" class="form-select form-select-sm">
                            @foreach(($terms ?? collect([$term])) as $t)
                            <option value="{{ $t }}" {{ $t == $term ? 'selected' : '' }}>{{ $t === 'HK1' ? 'Học kỳ 1' : ($t === 'HK2' ? 'Học kỳ 2' : 'Học kỳ Hè') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3 d-grid d-md-block">
                        <input type="hidden" name="view" value="{{ $view ?? 'week' }}">
                        <input type="hidden" name="date" value="{{ $baseDate ?? '' }}">
                        <button class="btn btn-primary btn-sm mt-3 mt-md-0"><i class="fas fa-filter me-1"></i>Áp dụng</button>
                    </div>
                </div>
            </form>

            <div class="col-12 col-lg-6 d-flex flex-wrap justify-content-lg-end align-items-center gap-2">
                <div class="btn-group" role="group" aria-label="View Mode">
                    <a class="btn btn-outline-secondary btn-sm {{ ($view ?? 'week')==='week' ? 'active' : '' }}" href="{{ route('student.timetable', ['academic_year'=>($academicYear ?? $year),'term'=>$term,'view'=>'week','date'=>$baseDate ?? null]) }}">
                        <i class="fas fa-calendar-week me-1"></i>Tuần
                    </a>
                    <a class="btn btn-outline-secondary btn-sm {{ ($view ?? 'week')==='month' ? 'active' : '' }}" href="{{ route('student.timetable', ['academic_year'=>($academicYear ?? $year),'term'=>$term,'view'=>'month','date'=>$baseDate ?? null]) }}">
                        <i class="fas fa-calendar-alt me-1"></i>Tháng
                    </a>
                    <a class="btn btn-outline-secondary btn-sm {{ ($view ?? 'week')==='list' ? 'active' : '' }}" href="{{ route('student.timetable', ['academic_year'=>($academicYear ?? $year),'term'=>$term,'view'=>'list','date'=>$baseDate ?? null]) }}">
                        <i class="fas fa-list me-1"></i>Danh sách
                    </a>
                </div>

                <div class="btn-group" role="group" aria-label="Navigate">
                    <a class="btn btn-outline-secondary btn-sm" title="Trước" href="{{ route('student.timetable', ['academic_year'=>($academicYear ?? $year),'term'=>$term,'view'=>$view ?? 'week','date'=>$prevDate ?? null]) }}">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <span class="btn btn-light btn-sm disabled" style="pointer-events: none; min-width: 160px; text-align: center;">{{ $rangeLabel ?? '' }}</span>
                    <a class="btn btn-outline-secondary btn-sm" title="Sau" href="{{ route('student.timetable', ['academic_year'=>($academicYear ?? $year),'term'=>$term,'view'=>$view ?? 'week','date'=>$nextDate ?? null]) }}">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('student.timetable', ['academic_year'=>($academicYear ?? $year),'term'=>$term,'view'=>$view ?? 'week']) }}">Hôm nay</a>
                </div>

                <div class="btn-group" role="group" aria-label="Exports">
                    <a class="btn btn-outline-success btn-sm" href="{{ route('student.timetable.exportCsv', ['academic_year'=>($academicYear ?? $year),'term'=>$term]) }}">
                        <i class="fas fa-file-excel me-1"></i>Excel/CSV
                    </a>
                    <a class="btn btn-outline-danger btn-sm" target="_blank" href="{{ route('student.timetable.print', ['academic_year'=>($academicYear ?? $year),'term'=>$term]) }}">
                        <i class="fas fa-file-pdf me-1"></i>PDF
                    </a>
                    <a class="btn btn-outline-dark btn-sm" href="{{ route('student.exportIcs') }}">
                        <i class="fas fa-calendar-plus me-1"></i>ICS
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if(($totalClasses ?? 0) === 0)
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">Chưa có lớp trong thời khóa biểu</h5>
        <p class="text-muted">Bạn chưa đăng ký lớp học phần nào cho học kỳ này</p>
    </div>
</div>
@else
@if(($view ?? 'week') === 'week')
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 100px;">Thứ / Ca</th>
                        @foreach($days as $day)
                        <th class="text-center">{{ $day }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                    $maxSlots = 0;
                    foreach ($schedule as $dayClasses) {
                        $maxSlots = max($maxSlots, count($dayClasses));
                    }
                    @endphp

                    @for($slot = 0; $slot < max(1, $maxSlots); $slot++)
                        <tr>
                        <td class="text-center align-middle fw-bold bg-light">
                            Ca {{ $slot + 1 }}
                        </td>
                        @foreach($schedule as $dayIndex => $dayClasses)
                        <td class="p-2" style="vertical-align: top;">
                            @if(isset($dayClasses[$slot]))
                            @php $cls = $dayClasses[$slot]; @endphp
                            <div class="class-box p-3"
                                style="background: linear-gradient(135deg,#e3f2fd,#bbdefb); border-left:4px solid #1976d2; border-radius:6px; cursor:pointer;"
                                onclick="openStudentClassDetail({{ $cls['id'] }})">
                                <div class="fw-bold text-primary mb-1" style="font-size:14px;">{{ $cls['course_code'] }}
                                </div>
                                <div class="mb-2" style="font-size:13px;">{{ $cls['course_name'] }}</div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success">{{ $cls['section_code'] }}</span>
                                    <small class="text-muted"><i
                                            class="fas fa-door-open me-1"></i>{{ $cls['room'] }}</small>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted"><i
                                            class="fas fa-clock me-1"></i>{{ $cls['shift'] }}</small>
                                    @if(isset($cls['lecturer']))
                                    <br><small class="text-muted"><i
                                            class="fas fa-user me-1"></i>{{ $cls['lecturer'] }}</small>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </td>
                        @endforeach
                        </tr>
                        @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
@elseif(($view ?? 'week') === 'list')
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mã HP</th>
                        <th>Tên học phần</th>
                        <th>Mã lớp</th>
                        <th>Thứ</th>
                        <th>Ca (Tiết)</th>
                        <th>Phòng</th>
                        <th style="width:80px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(($listSections ?? []) as $s)
                    <tr>
                        <td class="fw-semibold">{{ $s->course->code }}</td>
                        <td>{{ $s->course->name }}</td>
                        <td><span class="badge bg-success">{{ $s->section_code }}</span></td>
                        <td>{{ ['','Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','Chủ nhật'][$s->day_of_week] ?? '' }}
                        </td>
                        <td>
                            @if($s->shift)
                            Tiết {{ $s->shift->start_period }}-{{ $s->shift->end_period }}
                            @endif
                        </td>
                        <td>{{ $s->room->code ?? 'TBA' }}</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-primary" onclick="openStudentClassDetail({{ $s->id }})"><i
                                    class="fas fa-eye"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">#</th>
                        @foreach($days as $day)
                        <th class="text-center">{{ $day }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach(($monthWeeks ?? []) as $wIndex => $week)
                    <tr>
                            @foreach($cell['classes'] as $c)
                            <div class="border rounded p-2 mt-2" style="cursor:pointer; background:#f8fbff; border-left:3px solid #1976d2;" onclick="openStudentClassDetail({{ $c['id'] }})">
                                <div class="small text-primary fw-semibold">{{ $c['course_code'] }}</div>
                                <div class="small">{{ $c['course_name'] }}</div>
                                <div class="d-flex justify-content-between">
                                    <span class="badge bg-success">{{ $c['section_code'] }}</span>
                                    <small class="text-muted">{{ $c['shift'] }}</small>
                                </div>
                            </div>
                            @endforeach
                                <div class="small">{{ $c['course_name'] }}</div>
                                <div class="d-flex justify-content-between small text-muted mt-1">
                                    <span><i class="fas fa-clock me-1"></i>{{ $c['shift'] }}</span>
                                    <span><i class="fas fa-door-open me-1"></i>{{ $c['room'] }}</span>
                                </div>
                            </div>
                            @endforeach
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@include('student.timetable.partials.detail-modal')

@endif

@endsection
@endif

<div class="alert alert-info mt-3">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Hướng dẫn:</strong> Nhấp vào ô lớp học để xem thông tin chi tiết và danh sách bạn cùng lớp.
</div>
@endif

@endsectionnsition: all .3s;
        min-height: 120px;
    }

    .class-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(25, 118, 210, .2);
    }

    .table td,
    .table th {
        border-color: #e0e0e0;
    }

    .table thead th {
        font-weight: 600;
        color: #424242;
    }
</style>
@endsection