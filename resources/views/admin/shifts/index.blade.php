@extends('admin.layout')

@section('title', 'Quản lý Ca học')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white mb-0">📅 Quản lý Ca học</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createShiftModal">
            <i class="fas fa-plus me-2"></i>Thêm Ca học
        </button>
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

    <div class="card bg-dark border-secondary">
        <div class="card-body">
            <!-- Filters -->
            <form method="GET" action="{{ route('shifts.index') }}" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}"
                        class="form-control bg-dark text-white border-secondary" placeholder="Tìm theo Mã ca / Tên ca">
                </div>
                <div class="col-md-2">
                    <select name="day" class="form-select bg-dark text-white border-secondary">
                        <option value="">Tất cả thứ</option>
                        @for($i=1;$i<=7;$i++) <option value="{{ $i }}"
                            {{ ($filters['day'] ?? '') == $i ? 'selected' : '' }}>
                            {{ [1=>'Thứ 2',2=>'Thứ 3',3=>'Thứ 4',4=>'Thứ 5',5=>'Thứ 6',6=>'Thứ 7',7=>'CN'][$i] }}
                            </option>
                            @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="frame" class="form-select bg-dark text-white border-secondary">
                        <option value="">Tất cả khung</option>
                        <option value="morning" {{ ($filters['frame'] ?? '')=='morning' ? 'selected' : '' }}>Sáng
                        </option>
                        <option value="afternoon" {{ ($filters['frame'] ?? '')=='afternoon' ? 'selected' : '' }}>Chiều
                        </option>
                        <option value="evening" {{ ($filters['frame'] ?? '')=='evening' ? 'selected' : '' }}>Tối
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select bg-dark text-white border-secondary">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active" {{ ($filters['status'] ?? '')=='active' ? 'selected' : '' }}>Hoạt động
                        </option>
                        <option value="inactive" {{ ($filters['status'] ?? '')=='inactive' ? 'selected' : '' }}>Tạm
                            ngưng</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex">
                    <button class="btn btn-primary me-2" type="submit"><i class="fas fa-search me-1"></i>Lọc</button>
                    <a href="{{ route('shifts.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>Mã ca</th>
                            <th>Tên ca</th>
                            <th>Thứ</th>
                            <th>Giờ bắt đầu</th>
                            <th>Giờ kết thúc</th>
                            <th>Khung</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shifts as $shift)
                        @php($times = explode(' - ', $shift->time_range))
                        <tr>
                            <td>{{ $shift->code ?? ('C'.$shift->day_of_week.$shift->start_period.'-'.$shift->end_period) }}
                            </td>
                            <td>{{ $shift->name ?? ('Ca tiết '.$shift->start_period.'-'.$shift->end_period) }}</td>
                            <td><span class="badge bg-primary">{{ $shift->day_name }}</span></td>
                            <td>{{ $times[0] ?? '' }}</td>
                            <td>{{ $times[1] ?? '' }}</td>
                            <td>{{ $shift->frame }}</td>
                            <td>
                                @extends('admin.layout')

                                @section('title', 'Quản lý Ca học')

                                @section('content')
                                <style>
                                    .table-zebra tbody tr:nth-child(even) {
                                        background-color: rgba(0, 0, 0, 0.02);
                                    }

                                    .table-zebra tbody tr:hover {
                                        background-color: rgba(59, 130, 246, 0.1);
                                    }

                                    .action-btn {
                                        width: 32px;
                                        height: 32px;
                                        padding: 0;
                                        display: inline-flex;
                                        align-items: center;
                                        justify-content: center;
                                        border-radius: 6px;
                                        transition: all .2s;
                                    }

                                    .action-btn:hover {
                                        transform: translateY(-2px);
                                    }
                                </style>

                                <div
                                    style="background:white; padding:24px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                                    <!-- Header -->
                                    <div
                                        style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
                                        <h2 style="margin:0; font-size:20px; font-weight:600; color:#1e293b;">📅 Quản lý
                                            Ca học</h2>
                                        <button onclick="openCreateModal()"
                                            style="background:#16a34a; color:white; padding:10px 20px; border-radius:6px; border:none; font-weight:500; display:inline-flex; align-items:center; gap:8px; cursor:pointer;">
                                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path
                                                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                            </svg>
                                            Thêm Ca học
                                        </button>
                                    </div>

                                    @if(session('success'))
                                    <div
                                        style="background:#d1fae5; border-left:4px solid #10b981; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#065f46;">
                                        {{ session('success') }}
                                    </div>
                                    @endif
                                    @if(session('error'))
                                    <div
                                        style="background:#fee2e2; border-left:4px solid #ef4444; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#991b1b;">
                                        {{ session('error') }}
                                    </div>
                                    @endif

                                    <!-- Filters -->
                                    <form method="GET" action="{{ route('shifts.index') }}" style="margin-bottom:20px;">
                                        <div style="display:flex; gap:12px; align-items:end;">
                                            <div style="flex:2;">
                                                <label
                                                    style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Tìm
                                                    kiếm</label>
                                                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}"
                                                    placeholder="Mã ca / Tên ca"
                                                    style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                                            </div>
                                            <div style="flex:1;">
                                                <label
                                                    style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Thứ</label>
                                                <select name="day"
                                                    style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                                                    <option value="">-- Tất cả --</option>
                                                    @for($i=1;$i<=7;$i++) <option value="{{ $i }}"
                                                        {{ ($filters['day'] ?? '') == $i ? 'selected' : '' }}>
                                                        {{ [1=>'Thứ 2',2=>'Thứ 3',3=>'Thứ 4',4=>'Thứ 5',5=>'Thứ 6',6=>'Thứ 7',7=>'CN'][$i] }}
                                                        </option>
                                                        @endfor
                                                </select>
                                            </div>
                                            <div style="flex:1;">
                                                <label
                                                    style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Khung</label>
                                                <select name="frame"
                                                    style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                                                    <option value="">-- Tất cả --</option>
                                                    <option value="morning"
                                                        {{ ($filters['frame'] ?? '')=='morning' ? 'selected' : '' }}>
                                                        Sáng</option>
                                                    <option value="afternoon"
                                                        {{ ($filters['frame'] ?? '')=='afternoon' ? 'selected' : '' }}>
                                                        Chiều</option>
                                                    <option value="evening"
                                                        {{ ($filters['frame'] ?? '')=='evening' ? 'selected' : '' }}>Tối
                                                    </option>
                                                </select>
                                            </div>
                                            <div style="flex:1;">
                                                <label
                                                    style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Trạng
                                                    thái</label>
                                                <select name="status"
                                                    style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                                                    <option value="">-- Tất cả --</option>
                                                    <option value="active"
                                                        {{ ($filters['status'] ?? '')=='active' ? 'selected' : '' }}>
                                                        Hoạt động</option>
                                                    <option value="inactive"
                                                        {{ ($filters['status'] ?? '')=='inactive' ? 'selected' : '' }}>
                                                        Tạm ngưng</option>
                                                </select>
                                            </div>
                                            <button type="submit"
                                                style="background:#1976d2; color:white; padding:10px 24px; border:none; border-radius:6px; cursor:pointer; font-weight:500; display:flex; align-items:center; gap:8px;">
                                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path
                                                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                                </svg>
                                                Lọc
                                            </button>
                                            @if(request()->hasAny(['q','day','frame','status']))
                                            <a href="{{ route('shifts.index') }}"
                                                style="padding:10px 20px; border:1px solid #cbd5e0; border-radius:6px; text-decoration:none; color:#475569; font-weight:500;">Xóa
                                                bộ lọc</a>
                                            @endif
                                        </div>
                                    </form>

                                    <!-- Table -->
                                    <div style="overflow-x:auto;">
                                        <table class="table-zebra" style="width:100%; border-collapse:collapse;">
                                            <thead>
                                                <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                                                    <th
                                                        style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">
                                                        MÃ CA</th>
                                                    <th
                                                        style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">
                                                        TÊN CA</th>
                                                    <th
                                                        style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">
                                                        THỨ</th>
                                                    <th
                                                        style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">
                                                        GIỜ BẮT ĐẦU</th>
                                                    <th
                                                        style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">
                                                        GIỜ KẾT THÚC</th>
                                                    <th
                                                        style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">
                                                        KHUNG</th>
                                                    <th
                                                        style="padding:12px; text-align:center; font-weight:600; color:#475569; font-size:13px;">
                                                        TRẠNG THÁI</th>
                                                    <th
                                                        style="padding:12px; text-align:center; font-weight:600; color:#475569; font-size:13px;">
                                                        THAO TÁC</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($shifts as $shift)
                                                @php($times = explode(' - ', $shift->time_range))
                                                <tr style="border-bottom:1px solid #e2e8f0;">
                                                    <td style="padding:12px;"><code
                                                            style="background:#eef2ff; color:#3730a3; padding:4px 8px; border-radius:4px; font-size:13px; font-weight:600;">{{ $shift->code ?? ('C'.$shift->day_of_week.$shift->start_period.'-'.$shift->end_period) }}</code>
                                                    </td>
                                                    <td style="padding:12px; font-weight:500; color:#1e293b;">
                                                        {{ $shift->name ?? ('Ca tiết '.$shift->start_period.'-'.$shift->end_period) }}
                                                    </td>
                                                    <td style="padding:12px;"><span
                                                            style="background:#dbeafe; color:#1e40af; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:500;">{{ $shift->day_name }}</span>
                                                    </td>
                                                    <td style="padding:12px;">{{ $times[0] ?? '' }}</td>
                                                    <td style="padding:12px;">{{ $times[1] ?? '' }}</td>
                                                    <td style="padding:12px;">{{ $shift->frame }}</td>
                                                    <td style="padding:12px; text-align:center;">
                                                        @if(($shift->status ?? 'active') === 'active')
                                                        <span
                                                            style="background:#dcfce7; color:#166534; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:600;">Hoạt
                                                            động</span>
                                                        @else
                                                        <span
                                                            style="background:#e5e7eb; color:#374151; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:600;">Tạm
                                                            ngưng</span>
                                                        @endif
                                                    </td>
                                                    <td style="padding:12px; text-align:center;">
                                                        <div style="display:inline-flex; gap:6px;">
                                                            <button onclick="openDetailFromEl(this)"
                                                                data-code="{{ $shift->code }}"
                                                                data-name="{{ $shift->name }}"
                                                                data-day="{{ $shift->day_name }}"
                                                                data-start="{{ $times[0] ?? '' }}"
                                                                data-end="{{ $times[1] ?? '' }}"
                                                                data-periods="{{ $shift->start_period }}-{{ $shift->end_period }}"
                                                                data-frame="{{ $shift->frame }}"
                                                                data-status="{{ $shift->status_label }}"
                                                                class="action-btn"
                                                                style="background:#10b981; color:white; border:none; cursor:pointer;"
                                                                title="Xem chi tiết">
                                                                <svg width="16" height="16" fill="currentColor"
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z" />
                                                                    <path
                                                                        d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z" />
                                                                </svg>
                                                            </button>
                                                            <a href="{{ route('shifts.edit', $shift) }}"
                                                                class="action-btn"
                                                                style="background:#1976d2; color:white; text-decoration:none;"
                                                                title="Sửa">
                                                                <svg width="16" height="16" fill="currentColor"
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3z" />
                                                                    <path
                                                                        d="M.146 14.146a.5.5 0 0 0 .168.11l5 2a.5.5 0 0 0 .65-.65l-2-5a.5.5 0 0 0-.11-.168L.146 14.146z" />
                                                                </svg>
                                                            </a>
                                                            <form action="{{ route('shifts.destroy', $shift) }}"
                                                                method="POST" style="display:inline;"
                                                                onsubmit="return confirmDeleteShift();">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="action-btn"
                                                                    style="background:#dc2626; color:white; border:none; cursor:pointer;"
                                                                    title="Xóa">
                                                                    <svg width="16" height="16" fill="currentColor"
                                                                        viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zM8 5.5a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                                                        <path fill-rule="evenodd"
                                                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1z" />
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="8"
                                                        style="padding:60px 20px; text-align:center; color:#94a3b8;">
                                                        <svg width="64" height="64" fill="currentColor"
                                                            viewBox="0 0 16 16"
                                                            style="opacity:0.3; margin-bottom:16px;">
                                                            <path
                                                                d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z" />
                                                        </svg>
                                                        <div style="font-size:16px; font-weight:500;">Chưa có ca học nào
                                                        </div>
                                                        <div style="font-size:14px; margin-top:4px;">Thử thay đổi bộ lọc
                                                            hoặc thêm ca học mới</div>
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    @if($shifts->hasPages())
                                    <div style="margin-top:24px; display:flex; justify-content:center;">
                                        {{ $shifts->links() }}
                                    </div>
                                    @endif
                                </div>

                                <!-- Create Modal -->
                                <div id="createModal"
                                    style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
                                    <div
                                        style="background:white; border-radius:12px; width:90%; max-width:720px; max-height:85vh; overflow:auto; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
                                        <div
                                            style="padding:20px; border-bottom:2px solid #16a34a; background:linear-gradient(135deg,#16a34a 0%, #15803d 100%); color:white;">
                                            <div
                                                style="display:flex; align-items:center; justify-content:space-between;">
                                                <h5 style="margin:0; font-size:18px;">Thêm Ca học</h5>
                                                <button onclick="closeCreateModal()"
                                                    style="background:transparent; border:none; color:white; font-size:20px; cursor:pointer;">×</button>
                                            </div>
                                        </div>
                                        <div style="padding:20px;">
                                            <form method="POST" action="{{ route('shifts.store') }}">
                                                @csrf
                                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                                                    <div>
                                                        <label
                                                            style="display:block; margin-bottom:6px; color:#475569; font-size:14px;">Mã
                                                            ca</label>
                                                        <input type="text" name="code" maxlength="20"
                                                            style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                                                    </div>
                                                    <div>
                                                        <label
                                                            style="display:block; margin-bottom:6px; color:#475569; font-size:14px;">Tên
                                                            ca <span class="text-danger">*</span></label>
                                                        <input type="text" name="name" required
                                                            style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                                                    </div>
                                                    <div>
                                                        <label
                                                            style="display:block; margin-bottom:6px; color:#475569; font-size:14px;">Thứ
                                                            <span class="text-danger">*</span></label>
                                                        <select name="day_of_week" required
                                                            style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                                                            @for($i=1;$i<=7;$i++) <option value="{{ $i }}">
                                                                {{ [1=>'Thứ 2',2=>'Thứ 3',3=>'Thứ 4',4=>'Thứ 5',5=>'Thứ 6',6=>'Thứ 7',7=>'CN'][$i] }}
                                                                </option>
                                                                @endfor
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label
                                                            style="display:block; margin-bottom:6px; color:#475569; font-size:14px;">Giờ
                                                            bắt đầu <span class="text-danger">*</span></label>
                                                        <input type="time" name="start_time" required
                                                            style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                                                    </div>
                                                    <div>
                                                        <label
                                                            style="display:block; margin-bottom:6px; color:#475569; font-size:14px;">Giờ
                                                            kết thúc <span class="text-danger">*</span></label>
                                                        <input type="time" name="end_time" required
                                                            style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                                                    </div>
                                                    <div>
                                                        <label
                                                            style="display:block; margin-bottom:6px; color:#475569; font-size:14px;">Trạng
                                                            thái</label>
                                                        <select name="status"
                                                            style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                                                            <option value="active" selected>Hoạt động</option>
                                                            <option value="inactive">Tạm ngưng</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div style="margin-top:16px; text-align:right;">
                                                    <button type="button" onclick="closeCreateModal()"
                                                        style="padding:10px 20px; border:1px solid #cbd5e0; border-radius:6px; background:white; color:#475569; cursor:pointer; margin-right:8px;">Đóng</button>
                                                    <button type="submit"
                                                        style="background:#1976d2; color:white; padding:10px 24px; border:none; border-radius:6px; cursor:pointer; font-weight:500;">Lưu</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detail Modal -->
                                <div id="detailModal"
                                    style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
                                    <div
                                        style="background:white; border-radius:12px; width:90%; max-width:600px; max-height:80vh; overflow:auto; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
                                        <div
                                            style="padding:20px; border-bottom:2px solid #1976d2; background:linear-gradient(135deg,#1976d2 0%, #0f4da8 100%); color:white;">
                                            <div
                                                style="display:flex; align-items:center; justify-content:space-between;">
                                                <h5 style="margin:0; font-size:18px;">Chi tiết Ca học</h5>
                                                <button onclick="closeDetail()"
                                                    style="background:transparent; border:none; color:white; font-size:20px; cursor:pointer;">×</button>
                                            </div>
                                        </div>
                                        <div style="padding:20px;" id="detailBody">—</div>
                                        <div style="padding:12px 20px; text-align:right;">
                                            <button onclick="closeDetail()"
                                                style="padding:10px 20px; border:1px solid #cbd5e0; border-radius:6px; background:white; color:#475569; cursor:pointer;">Đóng</button>
                                        </div>
                                    </div>
                                </div>
                                @endsection

                                @section('scripts')
                                <script>
                                    function openCreateModal() {
                                        document.getElementById('createModal').style.display = 'flex';
                                    }

                                    function closeCreateModal() {
                                        document.getElementById('createModal').style.display = 'none';
                                    }

                                    function confirmDeleteShift() {
                                        return confirm(
                                            'Bạn có chắc muốn xóa ca học này? Nếu ca học đang được tham chiếu trong Lớp học phần, đề xuất Tạm ngưng thay vì xóa.'
                                        );
                                    }

                                    function openDetail(data) {
                                        const body = document.getElementById('detailBody');
                                        const time = (data.start || '') + (data.end ? (' - ' + data.end) : '');
                                        body.innerHTML = `
                                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                                      <div><label style="font-size:13px; color:#64748b;">Mã ca</label><div style="background:#f1f5f9; padding:10px; border-radius:6px; margin-top:6px;">${data.code||'—'}</div></div>
                                      <div><label style="font-size:13px; color:#64748b;">Tên ca</label><div style="background:#f1f5f9; padding:10px; border-radius:6px; margin-top:6px;">${data.name||'—'}</div></div>
                                      <div><label style="font-size:13px; color:#64748b;">Thứ</label><div style="background:#f1f5f9; padding:10px; border-radius:6px; margin-top:6px;">${data.day||'—'}</div></div>
                                      <div><label style="font-size:13px; color:#64748b;">Thời gian</label><div style="background:#f1f5f9; padding:10px; border-radius:6px; margin-top:6px;">${time||'—'}</div></div>
                                      <div><label style="font-size:13px; color:#64748b;">Khoảng tiết</label><div style="background:#f1f5f9; padding:10px; border-radius:6px; margin-top:6px;">${data.periods||'—'}</div></div>
                                      <div><label style="font-size:13px; color:#64748b;">Khung</label><div style="background:#f1f5f9; padding:10px; border-radius:6px; margin-top:6px;">${data.frame||'—'}</div></div>
                                      <div><label style="font-size:13px; color:#64748b;">Trạng thái</label><div style="background:${data.status==='Hoạt động'?'#dcfce7':'#e5e7eb'}; color:${data.status==='Hoạt động'?'#166534':'#374151'}; padding:6px 12px; border-radius:12px; margin-top:6px; display:inline-block;">${data.status||'—'}</div></div>
                                    </div>
                                  `;
                                        document.getElementById('detailModal').style.display = 'flex';
                                    }

                                    function closeDetail() {
                                        document.getElementById('detailModal').style.display = 'none';
                                    }

                                    function openDetailFromEl(el) {
                                        const data = {
                                            code: el.dataset.code || null,
                                            name: el.dataset.name || null,
                                            day: el.dataset.day || null,
                                            start: el.dataset.start || null,
                                            end: el.dataset.end || null,
                                            periods: el.dataset.periods || null,
                                            frame: el.dataset.frame || null,
                                            status: el.dataset.status || null,
                                        };
                                        openDetail(data);
                                    }

                                    // Close on backdrop click
                                    ['createModal', 'detailModal'].forEach(id => {
                                        const el = document.getElementById(id);
                                        if (el) el.addEventListener('click', function(e) {
                                            if (e.target === this) this.style.display = 'none';
                                        });
                                    });
                                </script>
                                @endsection