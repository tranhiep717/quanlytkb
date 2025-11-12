@extends('student.layout')

@section('title','Học phần đã đăng ký')

@section('content')
{{-- Thông báo --}}
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

<!-- Dynamic alerts will be injected here by JS when using AJAX actions -->
<div id="pageAlerts"></div>

<div class="card mb-3">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="mb-1">
                    <i class="fas fa-clipboard-list me-2" style="color: #1976d2;"></i>
                    Đăng ký của tôi
                </h3>
                <small class="text-muted">Năm học {{ $year }} - {{ $term }}</small>
            </div>
            <div class="col-md-4 text-end">
                <div class="badge bg-success fs-6">
                    <i class="fas fa-book me-1"></i>
                    Tổng: {{ $credits }} tín chỉ
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 100px;">Mã lớp</th>
                        <th>Môn học</th>
                        <th style="width: 180px;">Lịch học</th>
                        <th style="width: 100px;">Phòng</th>
                        <th style="width: 120px;">Giảng viên</th>
                        <th style="width: 80px;">Sĩ số</th>
                        <th style="width: 200px;" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($regs as $r)
                    @php
                    $s = $r->classSection;
                    $enrolled = \App\Models\Registration::where('class_section_id', $s->id)->count();
                    @endphp
                    <tr>
                        <td><span class="badge bg-primary">{{ $s->section_code }}</span></td>
                        <td>
                            <strong>{{ $s->course->code }}</strong> - {{ $s->course->name }}
                            <br><small class="text-muted">{{ $s->course->credits }} tín chỉ</small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ ['','Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','Chủ nhật'][$s->day_of_week] ?? '' }}</span>
                            <br><small class="text-muted">Tiết {{ $s->shift->start_period }}-{{ $s->shift->end_period }}</small>
                        </td>
                        <td><span class="badge bg-secondary">{{ $s->room->code }}</span></td>
                        <td><small>{{ $s->lecturer->name ?? 'N/A' }}</small></td>
                        <td>
                            <span class="badge {{ $enrolled >= $s->max_capacity ? 'bg-danger' : 'bg-success' }}">
                                {{ $enrolled }}/{{ $s->max_capacity }}
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-primary" onclick="openSwapModal({{ $r->id }}, '{{ $s->course->code }}', '{{ $s->course->name }}', {{ $s->course_id }}, {{ $s->id }})">
                                <i class="fas fa-exchange-alt me-1"></i>Đổi lớp
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="openCancelModal({{ $r->id }}, '{{ $s->course->code }}', '{{ $s->course->name }}', '{{ $s->section_code }}')">
                                <i class="fas fa-times me-1"></i>Hủy
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                            Bạn chưa đăng ký lớp nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Hủy đăng ký --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Xác nhận Hủy đăng ký
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Bạn có chắc chắn muốn hủy học phần:</p>
                <div class="alert alert-warning">
                    <strong id="cancelCourseName"></strong>
                </div>
                <p class="text-muted small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    Sĩ số sẽ được cập nhật và bạn có thể không đăng ký lại được nếu lớp đã đầy.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </button>
                <form id="cancelForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-check me-1"></i>Xác nhận Hủy
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Đổi lớp --}}
<div class="modal fade" id="swapModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exchange-alt me-2"></i>Đổi lớp học phần
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <strong>Môn học:</strong> <span id="swapCourseName"></span>
                </div>

                <div id="swapLoading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                    </div>
                    <p class="mt-2 text-muted">Đang tải danh sách lớp...</p>
                </div>

                <div id="swapSections" style="display: none;">
                    <p class="mb-3">Chọn lớp bạn muốn chuyển đến:</p>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;"></th>
                                    <th>Mã lớp</th>
                                    <th>Lịch học</th>
                                    <th>Phòng</th>
                                    <th>GV</th>
                                    <th>Sĩ số</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody id="swapSectionsList"></tbody>
                        </table>
                    </div>
                </div>

                <div id="swapEmpty" style="display: none;" class="text-center text-muted py-4">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <h5 class="mb-2">Không có lớp học phần nào khác phù hợp để đổi</h5>
                    <p class="mb-0 small">Tất cả các lớp còn lại đã đầy hoặc trùng lịch với các lớp bạn đã đăng ký.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Hủy bỏ
                </button>
                <input type="hidden" id="swapFromRegId">
                <input type="hidden" id="swapToSectionId">
                <button type="button" class="btn btn-primary" id="swapSubmitBtn" onclick="submitSwap()" disabled>
                    <i class="fas fa-check me-1"></i>Xác nhận Đổi
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let selectedSectionId = null;
    let currentFromSectionId = null;

    function openCancelModal(regId, courseCode, courseName, sectionCode) {
        document.getElementById('cancelCourseName').textContent = courseCode + ' - ' + courseName + ' (' + sectionCode + ')';
        document.getElementById('cancelForm').action = '/student/registrations/' + regId;
        const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
        modal.show();
    }

    function openSwapModal(regId, courseCode, courseName, courseId, currentSectionId) {
        currentFromSectionId = currentSectionId;
        document.getElementById('swapCourseName').textContent = courseCode + ' - ' + courseName;
        document.getElementById('swapFromRegId').value = regId;
        document.getElementById('swapToSectionId').value = '';
        document.getElementById('swapSubmitBtn').disabled = true;
        selectedSectionId = null;

        // Reset display
        document.getElementById('swapLoading').style.display = 'block';
        document.getElementById('swapSections').style.display = 'none';
        document.getElementById('swapEmpty').style.display = 'none';

        const modal = new bootstrap.Modal(document.getElementById('swapModal'));
        modal.show();

        // Load available sections
        loadAvailableSections(courseId, currentSectionId);
    }

    function loadAvailableSections(courseId, currentSectionId) {
        fetch(`/api/student/available-sections?course_id=${courseId}&current_section_id=${currentSectionId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('swapLoading').style.display = 'none';

                if (data.sections && data.sections.length > 0) {
                    document.getElementById('swapSections').style.display = 'block';
                    renderSectionsList(data.sections, data.mySchedule || []);
                } else {
                    document.getElementById('swapEmpty').style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('swapLoading').style.display = 'none';
                document.getElementById('swapEmpty').style.display = 'block';
            });
    }

    function renderSectionsList(sections, mySchedule) {
        const tbody = document.getElementById('swapSectionsList');
        tbody.innerHTML = '';

        sections.forEach(section => {
            const isDisabled = section.status !== 'available';

            const tr = document.createElement('tr');
            tr.className = isDisabled ? 'table-secondary' : '';
            tr.style.cursor = isDisabled ? 'not-allowed' : 'pointer';
            if (isDisabled) {
                tr.style.opacity = '0.6';
            }

            let statusBadge = '';
            if (section.status === 'conflict') {
                statusBadge = '<span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i>Trùng lịch</span>';
            } else if (section.status === 'full') {
                statusBadge = '<span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Hết chỗ</span>';
            } else {
                statusBadge = '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Còn chỗ</span>';
            }

            tr.innerHTML = `
            <td class="text-center">
                <input type="radio" name="swapSection" value="${section.id}" 
                       ${isDisabled ? 'disabled' : ''} 
                       onchange="selectSection(${section.id})">
            </td>
            <td><span class="badge bg-primary">${section.section_code}</span></td>
            <td>
                <strong>${section.day_name}</strong>
                <br><small class="text-muted">Tiết ${section.shift}</small>
            </td>
            <td><span class="badge bg-secondary">${section.room}</span></td>
            <td><small>${section.lecturer}</small></td>
            <td>
                <span class="badge ${section.enrolled >= section.max_capacity ? 'bg-danger' : 'bg-success'}">
                    ${section.enrolled}/${section.max_capacity}
                </span>
            </td>
            <td>${statusBadge}${isDisabled && section.reason ? `<div class="small text-muted mt-1">${section.reason}</div>` : ''}</td>
        `;

            tbody.appendChild(tr);
        });
    }

    function selectSection(sectionId) {
        selectedSectionId = sectionId;
        document.getElementById('swapToSectionId').value = sectionId;
        document.getElementById('swapSubmitBtn').disabled = false;
    }

    function submitSwap() {
        const fromRegId = document.getElementById('swapFromRegId').value;
        const toSectionId = document.getElementById('swapToSectionId').value;

        if (!fromRegId || !toSectionId) {
            alert('Vui lòng chọn lớp để đổi');
            return;
        }

        // Disable button to prevent double submit
        const btn = document.getElementById('swapSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang xử lý...';

        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Submit via fetch
        fetch('/student/registrations/swap', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    from_registration_id: fromRegId,
                    to_section_id: toSectionId
                })
            })
            .then(async response => {
                const data = await response.json().catch(() => ({}));
                if (!response.ok || data.success === false) {
                    const validationMsg = data && data.errors ?
                        Object.values(data.errors).flat().join(' ') :
                        null;
                    const msg = data.message || validationMsg || 'Đổi lớp thất bại';
                    throw new Error(msg);
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    // Success - close modal and show success message
                    const modal = bootstrap.Modal.getInstance(document.getElementById('swapModal'));
                    modal.hide();

                    // Create and show success alert at the top of page
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success alert-dismissible fade show';
                    alertDiv.setAttribute('role', 'alert');
                    alertDiv.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                ${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

                    // Insert alert near top of page (prefer #pageAlerts)
                    const host = document.getElementById('pageAlerts') ||
                        document.querySelector('.container') ||
                        document.querySelector('.container-fluid') ||
                        document.body;
                    host.prepend(alertDiv);

                    // Scroll to top to show message
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });

                    // Reload page after a short delay to show the message and update the list
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Đổi lớp thất bại');
                }
            })
            .catch(error => {
                console.error('Error:', error);

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('swapModal'));
                if (modal) modal.hide();

                // Show error alert at the top of page
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                alertDiv.setAttribute('role', 'alert');
                alertDiv.innerHTML = `
            <i class="fas fa-exclamation-circle me-2"></i>
            ${error.message || 'Có lỗi xảy ra. Vui lòng thử lại.'}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
                // Insert alert near top of page (prefer #pageAlerts)
                const host = document.getElementById('pageAlerts') ||
                    document.querySelector('.container') ||
                    document.querySelector('.container-fluid') ||
                    document.body;
                host.prepend(alertDiv);

                // Scroll to top to show error
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });

                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check me-1"></i>Xác nhận Đổi';
            });
    }
</script>
@endpush

@push('styles')
<style>
    .table td {
        vertical-align: middle;
    }

    .modal-header.bg-danger,
    .modal-header.bg-primary {
        border: none;
    }

    .table-responsive {
        max-height: 400px;
        overflow-y: auto;
    }
</style>
@endpush