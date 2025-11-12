@extends('student.layout')

@section('title','Tra cứu học phần')

@section('content')
<!-- Toast notification container -->
<div id="toastContainer" style="position:fixed;top:80px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:12px;max-width:400px;"></div>

<div class="card">
    @if($wave && $openForUser)
    <div class="status success" style="margin:0 0 8px 0;">Đợt đăng ký đang mở</div>
    @else
    <div class="status error" style="margin:0 0 8px 0;">Hiện không trong thời gian đăng ký của bạn</div>
    @endif
    @if($wave)
    <div class="muted">Hạn đăng ký: Mã từ {{ \Carbon\Carbon::parse($wave->starts_at)->format('d/m/Y') }} / Hạn đăng ký {{ \Carbon\Carbon::parse($wave->ends_at)->format('d/m/Y') }}, Hạn chót: {{ \Carbon\Carbon::parse($wave->ends_at)->format('d/m/Y H:i') }}</div>
    @endif
</div>

<div class="card">
    <h3 style="margin:0 0 12px 0;color:#1976d2;font-size:16px;">Đợt đăng ký đang mở</h3>
    <form method="GET" action="{{ route('student.offerings') }}" style="display:grid;gap:8px;grid-template-columns:repeat(6, 1fr);align-items:end;margin-bottom:16px;">
        <div>
            <label class="muted" style="display:block;margin-bottom:4px;">Tìm kiếm</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Mã môn, tên môn, mã lớp" style="width:100%;padding:8px;border-radius:4px;border:1px solid #ddd;">
        </div>
        <div>
            <label class="muted" style="display:block;margin-bottom:4px;">Năm học</label>
            <input type="text" name="academic_year" value="{{ $year }}" style="width:100%;padding:8px;border-radius:4px;border:1px solid #ddd;" placeholder="2024-2025">
        </div>
        <div>
            <label class="muted" style="display:block;margin-bottom:4px;">Học kỳ</label>
            <select name="term" style="width:100%;padding:8px;border-radius:4px;border:1px solid #ddd;">
                <option value="HK1" {{ $term=='HK1'?'selected':'' }}>HK1</option>
                <option value="HK2" {{ $term=='HK2'?'selected':'' }}>HK2</option>
                <option value="HK3" {{ $term=='HK3'?'selected':'' }}>HK3</option>
            </select>
        </div>
        <div>
            <label class="muted" style="display:block;margin-bottom:4px;">Khoa</label>
            <select name="faculty_id" style="width:100%;padding:8px;border-radius:4px;border:1px solid #ddd;">
                <option value="">-- Tất cả --</option>
                @isset($faculties)
                @foreach($faculties as $f)
                <option value="{{ $f->id }}" {{ request('faculty_id')==$f->id?'selected':'' }}>{{ $f->name }}</option>
                @endforeach
                @endisset
            </select>
        </div>
        <div>
            <label class="muted" style="display:block;margin-bottom:4px;">Ca học</label>
            <select name="shift_id" style="width:100%;padding:8px;border-radius:4px;border:1px solid #ddd;">
                <option value="">-- Tất cả --</option>
                @isset($shifts)
                @foreach($shifts as $sh)
                <option value="{{ $sh->id }}" {{ request('shift_id')==$sh->id?'selected':'' }}>Tiết {{ $sh->start_period }}-{{ $sh->end_period }}</option>
                @endforeach
                @endisset
            </select>
        </div>
        <div>
            <label class="muted" style="display:block;margin-bottom:4px;">Phòng</label>
            <select name="room_id" style="width:100%;padding:8px;border-radius:4px;border:1px solid #ddd;">
                <option value="">-- Tất cả --</option>
                @isset($rooms)
                @foreach($rooms as $r)
                <option value="{{ $r->id }}" {{ request('room_id')==$r->id?'selected':'' }}>{{ $r->code }}</option>
                @endforeach
                @endisset
            </select>
        </div>
        <div style="grid-column: 1 / -1; display:flex; align-items:center; gap:12px; justify-content:space-between;">
            <label class="muted" style="display:flex;align-items:center;gap:8px;">
                <input type="checkbox" name="only_available" value="1" {{ request('only_available') ? 'checked' : '' }}>
                Chỉ hiển thị lớp còn chỗ
            </label>
            <div>
                <button class="btn" type="submit">Lọc</button>
            </div>
        </div>
    </form>

    <div class="grid" style="grid-template-columns: 2fr 1fr; gap:16px;margin-top:16px;">
        <div>
            <h4 style="margin:0 0 12px 0;font-size:14px;color:#1976d2;">Danh mục học phần mở</h4>

            <!-- Course Cards -->
            <div class="courses-grid" style="display:flex;flex-direction:column;gap:16px;">
                @forelse($courses as $course)
                <div class="course-card" data-course-id="{{ $course->id }}" style="
                    background:white;
                    border-radius:12px;
                    box-shadow:0 2px 8px rgba(0,0,0,0.08);
                    padding:20px;
                    transition:all 0.3s ease;
                    border:1px solid #e9ecef;
                    cursor:pointer;
                ">
                    <!-- Card Header -->
                    <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:12px;">
                        <div style="flex:1;">
                            <h3 style="margin:0 0 8px 0;font-size:18px;color:#1976d2;font-weight:600;">
                                <span class="course-code" style="background:#e3f2fd;padding:4px 12px;border-radius:6px;font-size:14px;margin-right:8px;">{{ $course->code }}</span>
                                {{ $course->name }}
                            </h3>
                            <div style="display:flex;gap:16px;align-items:center;flex-wrap:wrap;">
                                <span style="color:#666;font-size:14px;">
                                    <i class="fas fa-book" style="color:#1976d2;margin-right:6px;"></i>
                                    <strong>{{ $course->credits }}</strong> tín chỉ
                                </span>
                                <span style="color:#666;font-size:14px;">
                                    <i class="fas fa-university" style="color:#1976d2;margin-right:6px;"></i>
                                    {{ $course->faculty?->name ?? 'N/A' }}
                                </span>
                                <span style="color:#666;font-size:14px;">
                                    <i class="fas fa-users" style="color:#1976d2;margin-right:6px;"></i>
                                    <strong>{{ $course->total_sections }}</strong> lớp mở
                                </span>
                            </div>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:end;gap:8px;">
                            @if(in_array($course->id, $registeredCourseIds))
                            <span style="background:#d4edda;color:#155724;padding:8px 16px;border-radius:8px;font-weight:600;font-size:14px;white-space:nowrap;">
                                <i class="fas fa-check-circle"></i> Đã đăng ký
                            </span>
                            @elseif($course->available_sections > 0)
                            <span style="background:#d1ecf1;color:#0c5460;padding:8px 16px;border-radius:8px;font-weight:600;font-size:14px;white-space:nowrap;">
                                <i class="fas fa-door-open"></i> {{ $course->available_sections }} lớp còn chỗ
                            </span>
                            @else
                            <span style="background:#f8d7da;color:#721c24;padding:8px 16px;border-radius:8px;font-weight:600;font-size:14px;white-space:nowrap;">
                                <i class="fas fa-ban"></i> Tất cả đã đầy
                            </span>
                            @endif
                            <button class="expand-btn" style="
                                background:#1976d2;
                                color:white;
                                border:none;
                                padding:8px 16px;
                                border-radius:6px;
                                font-size:13px;
                                font-weight:500;
                                cursor:pointer;
                                transition:all 0.2s;
                                white-space:nowrap;
                            " onmouseover="this.style.background='#1565c0'" onmouseout="this.style.background='#1976d2'">
                                <i class="fas fa-chevron-down expand-icon" style="transition:transform 0.3s;"></i>
                                Xem các lớp
                            </button>
                        </div>
                    </div>

                    <!-- Expandable Sections -->
                    <div class="sections-container" style="display:none;margin-top:16px;padding-top:16px;border-top:2px dashed #dee2e6;">
                        <div class="sections-loading" style="text-align:center;padding:30px;">
                            <div style="font-size:32px;animation:spin 1s linear infinite;">⏳</div>
                            <p style="margin:8px 0 0 0;color:#666;">Đang tải danh sách lớp...</p>
                        </div>
                        <div class="sections-content" style="display:none;">
                            <div class="sections-list"></div>
                        </div>
                    </div>
                </div>
                @empty
                <div style="text-align:center;padding:60px 20px;background:white;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                    <i class="fas fa-search" style="font-size:64px;color:#dee2e6;margin-bottom:16px;"></i>
                    <h3 style="color:#666;margin:0 0 8px 0;">Không tìm thấy học phần nào</h3>
                    <p style="color:#999;margin:0;">Thử điều chỉnh bộ lọc để xem thêm học phần</p>
                </div>
                @endforelse
            </div>

            <div style="margin-top:20px;">{{ $courses->links() }}</div>
        </div>

        <div>
            <h4 style="margin:0 0 12px 0;font-size:14px;color:#1976d2;">Học phần đã đăng ký</h4>
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Môn học</th>
                            <th>Lịch</th>
                            <th>TC</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($currentRegs as $r)
                        @php($cs = $r->classSection)
                        <tr>
                            <td>
                                <div><strong>{{ $cs->course->code }}</strong></div>
                                <div class="muted">{{ $cs->course->name }}</div>
                            </td>
                            <td>
                                <div>{{ $cs->shift->day_name ?? ('Thứ '.$cs->day_of_week) }}</div>
                                <div class="muted">T{{ $cs->shift->start_period }}-{{ $cs->shift->end_period }}</div>
                            </td>
                            <td>{{ $cs->course->credits }}</td>
                            <td>
                                <form action="{{ route('student.cancel', $r) }}" method="POST" onsubmit="return confirm('Hủy lớp này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn danger" type="submit">Hủy</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="muted" style="text-align:center;">Chưa có đăng ký.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Toast notification helper
    function showToast(type, message) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');

        const bgColor = type === 'success' ? '#4caf50' : type === 'error' ? '#f44336' : '#ff9800';
        const icon = type === 'success' ? '✅' : type === 'error' ? '❌' : '⚠️';

        toast.style.cssText = `
        background: ${bgColor};
        color: white;
        padding: 16px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        animation: slideIn 0.3s ease-out;
        cursor: pointer;
    `;

        toast.innerHTML = `
        <span style="font-size:20px;">${icon}</span>
        <span style="flex:1;">${message}</span>
        <button onclick="this.parentElement.remove()" style="background:none;border:none;color:white;font-size:20px;cursor:pointer;padding:0;line-height:1;">&times;</button>
    `;

        container.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
        }, 5000);

        toast.onclick = () => toast.remove();
    }

    // Add CSS animations and styles
    const style = document.createElement('style');
    style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(400px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(400px); opacity: 0; }
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    @keyframes slideDown {
        from { opacity: 0; max-height: 0; }
        to { opacity: 1; max-height: 2000px; }
    }
    .course-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    .expand-icon.expanded {
        transform: rotate(180deg);
    }
    .section-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 12px;
        border-left: 4px solid #1976d2;
        transition: all 0.2s;
    }
    .section-card:hover {
        background: #e9ecef;
        border-left-color: #1565c0;
    }
    .btn-primary { background: #1976d2; }
    .btn-primary:hover { background: #1565c0; }
    .btn-secondary { background: #6c757d; }
    .btn-success { background: #28a745; }
    .btn-warning { background: #ffc107; color: #000; }
`;
    document.head.appendChild(style);

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const loadedCourses = new Set();

    // Handle course card click to expand/collapse
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.course-card').forEach(card => {
            const expandBtn = card.querySelector('.expand-btn');

            expandBtn.addEventListener('click', async function(e) {
                e.stopPropagation();

                const courseId = card.dataset.courseId;
                const sectionsContainer = card.querySelector('.sections-container');
                const expandIcon = card.querySelector('.expand-icon');
                const isExpanded = sectionsContainer.style.display !== 'none';

                if (isExpanded) {
                    // Collapse
                    sectionsContainer.style.display = 'none';
                    expandIcon.classList.remove('expanded');
                    expandBtn.innerHTML = '<i class="fas fa-chevron-down expand-icon"></i> Xem các lớp';
                } else {
                    // Expand
                    sectionsContainer.style.display = 'block';
                    sectionsContainer.style.animation = 'slideDown 0.3s ease-out';
                    expandIcon.classList.add('expanded');
                    expandBtn.innerHTML = '<i class="fas fa-chevron-up expand-icon expanded"></i> Ẩn bớt';

                    // Load sections if not already loaded
                    if (!loadedCourses.has(courseId)) {
                        await loadCourseSections(courseId, sectionsContainer);
                        loadedCourses.add(courseId);
                    }
                }
            });
        });
    });

    // Load sections for a course via AJAX
    async function loadCourseSections(courseId, sectionsRow) {
        const loadingDiv = sectionsRow.querySelector('.sections-loading');
        const contentDiv = sectionsRow.querySelector('.sections-content');
        const listTbody = sectionsRow.querySelector('.sections-list');

        loadingDiv.style.display = 'block';
        contentDiv.style.display = 'none';

        try {
            const params = new URLSearchParams(window.location.search);
            const url = `/api/student/courses/${courseId}/sections?${params.toString()}`;

            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const data = await response.json();

            if (data.success && data.sections) {
                renderSections(data.sections, listTbody);
                loadingDiv.style.display = 'none';
                contentDiv.style.display = 'block';
            } else {
                throw new Error('Failed to load sections');
            }
        } catch (error) {
            console.error('Error loading sections:', error);
            loadingDiv.innerHTML = '<div style="padding:20px;text-align:center;color:#dc3545;">Không thể tải danh sách lớp. Vui lòng thử lại.</div>';
        }
    }

    // Render sections as cards in the expanded area
    function renderSections(sections, container) {
        const sectionsListDiv = container.querySelector('.sections-list') || container;
        sectionsListDiv.innerHTML = '';

        if (sections.length === 0) {
            sectionsListDiv.innerHTML = '<div class="text-center text-muted" style="padding:20px;">Không có lớp nào.</div>';
            return;
        }

        sections.forEach(section => {
            const card = document.createElement('div');
            card.className = 'section-card';

            const enrolledBadgeClass = section.enrolled >= section.max_capacity ? 'danger' :
                section.enrolled >= section.max_capacity * 0.8 ? 'warning' : 'ok';

            let statusBadge = '';
            let actionHtml = '';

            if (section.status === 'already_registered') {
                statusBadge = '<span class="badge info"><i class="fas fa-check"></i> Đã đăng ký</span>';
                actionHtml = `<button class="btn btn-secondary" disabled style="min-width:120px;"><i class="fas fa-check"></i> Đã đăng ký</button>`;
            } else if (section.status === 'swap_available') {
                statusBadge = '<span class="badge ok"><i class="fas fa-exchange-alt"></i> Có thể đổi</span>';
                actionHtml = `<button class="btn btn-warning btn-register" type="button" 
                                  data-section-id="${section.id}" 
                                  data-action="swap"
                                  style="min-width:120px;">
                            <i class="fas fa-exchange-alt"></i> Đổi lớp
                          </button>`;
            } else {
                // Hiển thị badge cảnh báo nhưng VẪN CHO PHÉP đăng ký
                if (section.status === 'full') {
                    statusBadge = '<span class="badge danger"><i class="fas fa-times-circle"></i> Đã đầy</span>';
                } else if (section.status === 'conflict') {
                    statusBadge = '<span class="badge warning"><i class="fas fa-exclamation-triangle"></i> Trùng lịch</span>';
                } else if (section.status === 'prereq_missing') {
                    statusBadge = '<span class="badge warning"><i class="fas fa-exclamation-triangle"></i> Thiếu tiên quyết</span>';
                } else {
                    statusBadge = '<span class="badge ok"><i class="fas fa-check-circle"></i> Còn chỗ</span>';
                }

                // Luôn cho phép click nút đăng ký (sẽ validate khi submit)
                actionHtml = `<button class="btn btn-primary btn-register" type="button" 
                                  data-section-id="${section.id}" 
                                  data-action="register"
                                  style="min-width:120px;"
                                  title="${section.reason || ''}">
                            <i class="fas fa-plus-circle"></i> Đăng ký
                          </button>`;
            }

            card.innerHTML = `
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:20px;">
                <div style="flex:1;">
                    <h4 style="margin:0 0 12px;font-size:18px;color:#1976d2;">
                        <span class="badge info" style="font-size:14px;padding:6px 12px;">${section.section_code}</span>
                    </h4>
                    
                    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:10px;margin-bottom:12px;">
                        <div style="display:flex;align-items:center;gap:6px;">
                            <i class="fas fa-chalkboard-teacher" style="color:#666;width:16px;"></i>
                            <span><strong>Giảng viên:</strong> ${section.lecturer || 'Chưa phân công'}</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:6px;">
                            <i class="fas fa-door-open" style="color:#666;width:16px;"></i>
                            <span><strong>Phòng:</strong> ${section.room || 'N/A'}</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:6px;">
                            <i class="fas fa-calendar-alt" style="color:#666;width:16px;"></i>
                            <span><strong>Thứ:</strong> ${section.day_name || 'N/A'}</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:6px;">
                            <i class="fas fa-clock" style="color:#666;width:16px;"></i>
                            <span><strong>Ca:</strong> ${section.shift || 'N/A'}</span>
                        </div>
                    </div>
                    
                    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                        <span class="badge ${enrolledBadgeClass}" style="padding:6px 12px;">
                            <i class="fas fa-users"></i> ${section.enrolled}/${section.max_capacity} sinh viên
                        </span>
                        ${statusBadge}
                    </div>
                </div>
                
                <div style="display:flex;flex-direction:column;gap:8px;align-items:flex-end;">
                    ${actionHtml}
                </div>
            </div>
        `;

            sectionsListDiv.appendChild(card);
        });

        // Re-attach event listeners for register buttons
        attachRegisterHandlers();
    }

    // Handle register button clicks (including newly loaded ones)
    function attachRegisterHandlers() {
        document.querySelectorAll('.btn-register:not(.has-listener)').forEach(btn => {
            btn.classList.add('has-listener');
            btn.addEventListener('click', async function(e) {
                e.stopPropagation(); // Prevent row expansion

                const sectionId = this.dataset.sectionId;
                const action = this.dataset.action || 'register';

                if (action === 'swap') {
                    // Redirect to my registrations page with swap UI
                    window.location.href = `/student/registrations/my?swap_section_id=${sectionId}`;
                    return;
                }

                // Disable button and show loading
                this.disabled = true;
                const originalText = this.textContent;
                this.innerHTML = '<span style="animation: spin 1s linear infinite;">⏳</span> Đang xử lý...';

                try {
                    const response = await fetch(`/student/registrations/${sectionId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({})
                    });

                    const data = await response.json();

                    if (data.success) {
                        showToast('success', data.message);

                        // Update button to registered status
                        this.outerHTML = '<span class="badge success">✅ Đã đăng ký</span>';

                        // Update enrolled count
                        if (data.enrolled && data.max_capacity) {
                            const row = this.closest('tr');
                            const badgeCell = row.querySelector('td:nth-child(5) .badge');
                            if (badgeCell) {
                                badgeCell.textContent = `${data.enrolled}/${data.max_capacity}`;
                                if (data.enrolled >= data.max_capacity) {
                                    badgeCell.classList.remove('ok');
                                    badgeCell.classList.add('danger');
                                }
                            }
                        }

                        // Reload after 2 seconds
                        setTimeout(() => window.location.reload(), 2000);
                    } else {
                        showToast('error', data.message || 'Đăng ký thất bại');
                        this.disabled = false;
                        this.textContent = originalText;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
                    this.disabled = false;
                    this.textContent = originalText;
                }
            });
        });
    }
</script>
@endsection