@extends('admin.layout')

@section('title', 'Quản lý Đợt đăng ký')

@section('content')
<style>
    .table-zebra tbody tr:nth-child(even) {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .table-zebra tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.08);
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
        border: none;
        cursor: pointer;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    .badge-chip {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .tag {
        background: #e5e7eb;
        color: #374151;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
    }

    .tab-pane {
        display: block;
    }

    .section-title {
        font-weight: 700;
        color: #0f172a;
        margin: 6px 0 12px;
        font-size: 15px;
    }

    .error-text {
        color: #dc2626;
        font-size: 12px;
        margin-top: 6px;
    }

    .invalid {
        border-color: #ef4444 !important;
    }

    .overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .modal-card {
        background: white;
        color: #1f2937;
        border-radius: 12px;
        width: 95%;
        max-width: 1100px;
        max-height: 88vh;
        overflow: auto;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 2px solid #1976d2;
        background: linear-gradient(135deg, #1976d2 0%, #0ea5e9 100%);
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-body {
        padding: 20px 24px;
    }

    .modal-footer {
        padding: 16px 24px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        border-top: 1px solid #e5e7eb;
    }

    /* Toasts */
    .toast-container {
        position: fixed;
        right: 16px;
        top: 16px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        z-index: 10000;
    }

    .toast {
        padding: 10px 14px;
        border-radius: 8px;
        color: #0f172a;
        background: #e2e8f0;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        transition: opacity .3s, transform .3s;
    }

    .toast.success {
        background: #dcfce7;
        color: #14532d;
        border-left: 4px solid #16a34a;
    }

    .toast.error {
        background: #fee2e2;
        color: #7f1d1d;
        border-left: 4px solid #dc2626;
    }

    .toast.hide {
        opacity: 0;
        transform: translateY(-6px);
    }
</style>

<div id="toast" class="toast-container"></div>

<div style="background:white; padding:24px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2 style="margin:0; font-size:20px; font-weight:600; color:#1e293b;">⏰ Quản lý Đợt đăng ký</h2>
        <div style="display:flex; gap:8px; align-items:center;">
            <a href="{{ route('registration-waves.trashed') }}" style="background:#64748b; color:white; padding:10px 14px; border-radius:6px; text-decoration:none; display:inline-flex; align-items:center; gap:8px; font-weight:500;">Kho lưu trữ</a>
        <form method="POST" action="{{ route('registration-waves.bulk-destroy') }}" onsubmit="return confirm('Bạn có chắc muốn xóa tất cả các đợt đăng ký? Các đợt sẽ được đưa vào Kho lưu trữ.');" style="display:inline;">
            @csrf
            <button type="submit" style="background:#dc2626; color:white; padding:10px 14px; border-radius:6px; border:none; display:inline-flex; align-items:center; gap:8px; font-weight:500;">Xóa tất cả</button>
        </form>
            <button type="button" onclick="openWaveModal()" style="background:#16a34a; color:white; padding:10px 20px; border-radius:6px; border:none; display:inline-flex; align-items:center; gap:8px; font-weight:500;">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                </svg>
                Thêm Đợt đăng ký
            </button>
        </div>
    </div>

    @if(session('success'))
    <div style="background:#d1fae5; border-left:4px solid #10b981; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#065f46;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div style="background:#fee2e2; border-left:4px solid #ef4444; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#991b1b;">{{ session('error') }}</div>
    @endif

    <div style="overflow-x:auto;">
        <table class="table-zebra" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">TÊN ĐỢT</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">NĂM/KỲ</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">THỜI GIAN</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">ĐỐI TƯỢNG</th>
                    <th style="padding:12px; text-align:center; font-weight:600; color:#475569; font-size:13px;">TRẠNG THÁI</th>
                    <th style="padding:12px; text-align:center; font-weight:600; color:#475569; font-size:13px;">THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                @forelse($waves as $wave)
                <tr style="border-bottom:1px solid #e2e8f0;">
                    <td style="padding:12px; font-weight:600; color:#1e293b;">{{ $wave->name }}</td>
                    <td style="padding:12px;">
                        <span class="badge-chip" style="background:#dbeafe; color:#1e40af;">{{ $wave->academic_year }}</span>
                        <span class="badge-chip" style="background:#cffafe; color:#164e63;">{{ $wave->term }}</span>
                    </td>
                    <td style="padding:12px; font-size:13px; color:#475569;">
                        {{ \Carbon\Carbon::parse($wave->starts_at)->format('d/m/Y H:i') }} → {{ \Carbon\Carbon::parse($wave->ends_at)->format('d/m/Y H:i') }}
                    </td>
                    <td style="padding:12px;">
                        @php
                        $audience = is_array($wave->audience) ? $wave->audience : json_decode($wave->audience, true);
                        $facIds = $audience['faculties'] ?? [];
                        // Rename to avoid shadowing the $cohorts passed from controller
                        $audCohorts = $audience['cohorts'] ?? [];
                        $facCodes = $faculties->whereIn('id', $facIds)->pluck('code')->values()->all();
                        $facPreview = collect($facCodes)->take(2)->implode(', ');
                        $facMore = max(count($facCodes) - 2, 0);
                        $cohPreview = collect($audCohorts)->take(2)->implode(', ');
                        $cohMore = max(count($audCohorts) - 2, 0);
                        @endphp
                        <div class="small" style="display:flex; gap:6px; flex-wrap:wrap;">
                            @if(count($facCodes)>0)
                            <span class="tag">Khoa: {{ $facPreview }}@if($facMore>0), +{{ $facMore }}@endif</span>
                            @endif
                            @if(count($audCohorts)>0)
                            <span class="tag">Khóa: {{ $cohPreview }}@if($cohMore>0), +{{ $cohMore }}@endif</span>
                            @endif
                            @if(count($facCodes)==0 && count($audCohorts)==0)
                            <span style="color:#94a3b8;">Toàn bộ</span>
                            @endif
                        </div>
                    </td>
                    <td style="padding:12px; text-align:center;">
                        @php
                        $now = now();
                        $starts = \Carbon\Carbon::parse($wave->starts_at);
                        $ends = \Carbon\Carbon::parse($wave->ends_at);
                        @endphp
                        @if($now < $starts)
                            <span class="badge-chip" style="background:#fef3c7; color:#92400e;">Sắp diễn ra</span>
                            @elseif($now >= $starts && $now <= $ends)
                                <span class="badge-chip" style="background:#dcfce7; color:#166534;">Đang mở</span>
                                @else
                                <span class="badge-chip" style="background:#e5e7eb; color:#374151;">Đã kết thúc</span>
                                @endif
                    </td>
                    <td style="padding:12px; text-align:center;">
                        <div style="display:inline-flex; gap:6px;">
                            <button class="action-btn" style="background:#10b981; color:white;" title="Xem chi tiết" onclick="openDetailModal({{ $wave->id }})">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z" />
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z" />
                                </svg>
                            </button>
                            <button class="action-btn" style="background:#1976d2; color:white;" title="Sửa" onclick="openWaveModal({{ $wave->id }})">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3z" />
                                    <path d="M13.5 6.207 9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5V12.707l6.5-6.5z" />
                                </svg>
                            </button>
                            <button type="button" class="action-btn" style="background:#dc2626; color:white;" title="Xóa" onclick="deleteWave({{ $wave->id }}, this)">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zM8 5.5a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM2.5 3V2h11v1h-11z" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:60px 20px; text-align:center; color:#94a3b8;">
                        <svg width="64" height="64" fill="currentColor" viewBox="0 0 16 16" style="opacity:0.3; margin-bottom:16px;">
                            <path d="M8 3a5 5 0 0 0-5 5h1a4 4 0 1 1 4 4v1l2-2-2-2v1a3 3 0 1 0-3-3H3a5 5 0 0 0 5-5z" />
                        </svg>
                        <div style="font-size:16px; font-weight:500;">Chưa có đợt đăng ký nào</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($waves->hasPages())
    <div style="margin-top:24px; display:flex; justify-content:center;">{{ $waves->links() }}</div>
    @endif
</div>

<!-- Detail Modal (overlay) -->
<div id="detailModal" class="overlay" onclick="if(event.target===this) closeDetailModal()">
    <div class="modal-card">
        <div class="modal-header">
            <h5 style="margin:0; font-size:18px; font-weight:600;">Chi tiết đợt đăng ký</h5>
            <button onclick="closeDetailModal()" style="background:white; color:#1f2937; border:none; padding:6px 10px; border-radius:6px; cursor:pointer;">Đóng</button>
        </div>
        <div class="modal-body" id="detailBody">Đang tải...</div>
    </div>
</div>

<!-- Create/Edit Modal (overlay) -->
<div id="waveModal" class="overlay" onclick="if(event.target===this) closeWaveModal()">
    <div class="modal-card">
        <div class="modal-header">
            <h5 style="margin:0; font-size:18px; font-weight:600;">Đợt đăng ký</h5>
            <button onclick="closeWaveModal()" style="background:white; color:#1f2937; border:none; padding:6px 10px; border-radius:6px; cursor:pointer;">Đóng</button>
        </div>
        <form id="waveForm" action="{{ route('registration-waves.store') }}" method="POST">
            @csrf
            <div id="formMethod"></div>
            <input type="hidden" id="wave_id" value="">
            <div class="modal-body">
                <div id="backendErrorBanner" style="display:none; margin-bottom:12px; padding:10px 12px; border-radius:8px; border-left:4px solid #dc2626; background:#fee2e2; color:#7f1d1d;"></div>
                <div style="padding-top:4px;">
                    <div class="tab-pane" id="tab-general">
                        <div class="section-title">1. Thông tin chung</div>
                        <div style="display:grid; grid-template-columns:2fr 1fr 1fr; gap:12px; margin-bottom:12px;">
                            <div>
                                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Tên đợt đăng ký <span style="color:#dc2626">*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                            </div>
                            <div>
                                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Năm học <span style="color:#dc2626">*</span></label>
                                <select id="academic_year" name="academic_year" required style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px; background:white;">
                                    <option value="">-- Chọn Năm học --</option>
                                    @foreach(($years ?? []) as $y)
                                    <option value="{{ $y }}" @selected(old('academic_year')==$y)>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Học kỳ <span style="color:#dc2626">*</span></label>
                                <select id="term" name="term" required style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px; background:white;">
                                    <option value="">-- Chọn Học kỳ --</option>
                                    @foreach(($terms ?? []) as $t)
                                    <option value="{{ $t }}" @selected(old('term')==$t)>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                            <div>
                                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Bắt đầu <span style="color:#dc2626">*</span></label>
                                <input type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at') ? str_replace(' ', 'T', 
                                old('starts_at')) : '' }}" required style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                            </div>
                            <div>
                                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Kết thúc <span style="color:#dc2626">*</span></label>
                                <input type="datetime-local" id="ends_at" name="ends_at" value="{{ old('ends_at') ? str_replace(' ', 'T', old('ends_at')) : '' }}" required style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab-audience">
                        <div class="section-title">2. Chọn đối tượng</div>

                        <!-- Faculties -->
                        <div style="margin-bottom:24px;">
                            <label style="display:block; margin-bottom:8px; font-weight:600; color:#475569; font-size:14px;">Lọc theo Khoa</label>
                            <div style="max-height:180px; overflow-y:auto; border:1px solid #e5e7eb; border-radius:8px; padding:8px; background:#fafafa;">
                                <label style="display:flex; align-items:center; gap:8px; padding:6px 8px; cursor:pointer; border-radius:4px; transition:background .15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background=''">
                                    <input type="checkbox" id="faculties_all" onchange="toggleAllFaculties(this.checked)">
                                    <strong>Chọn tất cả Khoa</strong>
                                </label>
                                @foreach($faculties as $fac)
                                <label style="display:flex; align-items:center; gap:8px; padding:6px 8px; cursor:pointer; border-radius:4px; transition:background .15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background=''">
                                    <input type="checkbox" name="faculties[]" value="{{ $fac->id }}" class="faculty-checkbox" onchange="updateFacultyTags()">
                                    {{ $fac->code }} — {{ $fac->name }}
                                </label>
                                @endforeach
                            </div>
                            <div id="faculty_tags" style="margin-top:8px; display:flex; flex-wrap:wrap; gap:6px; min-height:28px;"></div>
                        </div>

                        <!-- Cohorts -->
                        <div>
                            <label style="display:block; margin-bottom:8px; font-weight:600; color:#475569; font-size:14px;">Lọc theo Khóa học ({{ count($cohorts) }} khóa)</label>
                            <div style="max-height:180px; overflow-y:auto; border:1px solid #e5e7eb; border-radius:8px; padding:8px; background:#fafafa;">
                                <label style="display:flex; align-items:center; gap:8px; padding:6px 8px; cursor:pointer; border-radius:4px; transition:background .15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background=''">
                                    <input type="checkbox" id="cohorts_all" onchange="toggleAllCohorts(this.checked)">
                                    <strong>Chọn tất cả Khóa</strong>
                                </label>
                                @foreach($cohorts as $coh)
                                <label style="display:flex; align-items:center; gap:8px; padding:6px 8px; cursor:pointer; border-radius:4px; transition:background .15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background=''">
                                    <input type="checkbox" name="cohorts[]" value="{{ $coh }}" class="cohort-checkbox" onchange="updateCohortTags()">
                                    {{ $coh }}
                                </label>
                                @endforeach
                            </div>
                            <div id="cohort_tags" style="margin-top:8px; display:flex; flex-wrap:wrap; gap:6px; min-height:28px;"></div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab-offerings">
                        <div class="section-title">3. Học phần mở</div>
                        <div style="display:flex; gap:12px; align-items:end; margin-bottom:12px; flex-wrap:wrap;">
                            <div style="flex:1; min-width:240px;">
                                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Tìm kiếm</label>
                                <input type="text" id="offerings_search" placeholder="Mã/ tên môn hoặc mã lớp" style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                            </div>
                            <div style="width:260px;">
                                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Khoa</label>
                                <select id="offerings_faculty" style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px; background:white;">
                                    <option value="">-- Tất cả Khoa --</option>
                                    @foreach($faculties as $fac)
                                    <option value="{{ $fac->id }}">{{ $fac->code }} — {{ $fac->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="offerings_container" style="max-height:420px; overflow:auto; border:1px solid #e5e7eb; border-radius:8px;">
                            <table class="table-zebra" style="width:100%; border-collapse:collapse;">
                                <thead>
                                    <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                                        <th style="padding:10px; width:36px;"><input type="checkbox" id="offerings_check_all" title="Chọn tất cả"></th>
                                        <th style="padding:10px; text-align:left; font-size:13px; color:#475569;">MÃ LHP</th>
                                        <th style="padding:10px; text-align:left; font-size:13px; color:#475569;">TÊN MÔN HỌC</th>
                                        <th style="padding:10px; text-align:left; font-size:13px; color:#475569;">KHOA</th>
                                        <th style="padding:10px; text-align:left; font-size:13px; color:#475569;">GV</th>
                                        <th style="padding:10px; text-align:left; font-size:13px; color:#475569;">LỊCH</th>
                                        <th style="padding:10px; text-align:left; font-size:13px; color:#475569;">SĨ SỐ</th>
                                    </tr>
                                </thead>
                                <tbody id="offerings_tbody">
                                    <tr>
                                        <td colspan="8" style="padding:16px; text-align:center; color:#94a3b8;">Đang tải...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeWaveModal()" style="padding:10px 20px; border:1px solid #cbd5e0; border-radius:6px; background:white; color:#475569;">Hủy</button>
                <button type="submit" style="background:#1976d2; color:white; padding:10px 20px; border:none; border-radius:6px; font-weight:500;">Lưu</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Toast helper
    function showToast(message, type = 'success') {
        try {
            const container = document.getElementById('toast');
            if (!container) return alert(message);
            const el = document.createElement('div');
            el.className = `toast ${type}`;
            el.textContent = message;
            container.appendChild(el);
            setTimeout(() => {
                el.classList.add('hide');
                setTimeout(() => el.remove(), 300);
            }, 2800);
        } catch {
            alert(message);
        }
    }
    // Tabs removed – stacked sections
    function openDetailModal(id) {
        const overlay = document.getElementById('detailModal');
        const body = document.getElementById('detailBody');
        overlay.style.display = 'flex';
        body.innerHTML = 'Đang tải...';
        fetch(`{{ url('admin/registration-waves') }}/${id}/detail`)
            .then(r => r.json())
            .then(({
                wave,
                offerings
            }) => {
                body.innerHTML = `
                    <div style='background:#f8fafc; padding:16px; border-radius:8px; margin-bottom:16px;'>
                        <div style='font-weight:700; font-size:16px;'>${escapeHtml(wave.name)}</div>
                        <div style='color:#475569;'>${escapeHtml(wave.academic_year)} / ${escapeHtml(wave.term)} | ${escapeHtml(wave.starts_at||'')} → ${escapeHtml(wave.ends_at||'')}</div>
                        <div style='margin-top:8px;'>
                            <strong>Đối tượng:</strong>
                            ${(wave.faculties||[]).map(f=>`<span class='tag' style='margin-right:6px;'>${escapeHtml(f.code)}</span>`).join('')}
                            ${(wave.cohorts||[]).map(c=>`<span class='tag' style='margin-right:6px;'>${escapeHtml(c)}</span>`).join('')}
                        </div>
                    </div>`;
                const tbl = document.createElement('div');
                tbl.innerHTML = `
                    <div style='overflow:auto; border:1px solid #e5e7eb; border-radius:8px;'>
                        <table class='table-zebra' style='width:100%; border-collapse:collapse;'>
                            <thead><tr style='background:#f8fafc; border-bottom:2px solid #e2e8f0;'>
                                <th style='padding:10px; text-align:left;'>MÃ LỚP</th>
                                <th style='padding:10px; text-align:left;'>MÔN HỌC</th>
                                <th style='padding:10px; text-align:left;'>KHOA</th>
                                <th style='padding:10px; text-align:left;'>GV</th>
                                <th style='padding:10px; text-align:left;'>PHÒNG</th>
                                <th style='padding:10px; text-align:left;'>THỨ</th>
                                <th style='padding:10px; text-align:left;'>CA</th>
                            </tr></thead>
                            <tbody>${(offerings||[]).map(row=>`<tr style='border-bottom:1px solid #e2e8f0;'>
                                <td style='padding:10px;'>${escapeHtml(row.section_code||'')}</td>
                                <td style='padding:10px;'><span class='badge-chip' style='background:#cffafe; color:#164e63;'>${escapeHtml(row.course_code||'')}</span> ${escapeHtml(row.course_name||'')}</td>
                                <td style='padding:10px;'>${row.faculty? escapeHtml(row.faculty.code): ''}</td>
                                <td style='padding:10px;'>${escapeHtml(row.lecturer||'')}</td>
                                <td style='padding:10px;'>${escapeHtml(row.room||'')}</td>
                                <td style='padding:10px;'>${escapeHtml(String(row.day_of_week||''))}</td>
                                <td style='padding:10px;'>${row.shift? `${escapeHtml(row.shift.name)} ${(row.shift.start_time||'').substring(0,5)}-${(row.shift.end_time||'').substring(0,5)}`: ''}</td>
                            </tr>`).join('')}</tbody>
                        </table>
                    </div>`;
                body.appendChild(tbl);
            })
            .catch(() => {
                body.innerHTML = '<p style="color:#dc2626;">Không tải được chi tiết.</p>';
            });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
    }

    function openWaveModal(id) {
        const overlay = document.getElementById('waveModal');
        const form = document.getElementById('waveForm');
        form.reset();
        document.getElementById('wave_id').value = id || '';
        document.getElementById('formMethod').innerHTML = '';
        form.action = id ? `{{ url('admin/registration-waves') }}/${id}` : `{{ route('registration-waves.store') }}`;
        // Reset checkboxes
        document.querySelectorAll('.faculty-checkbox, .cohort-checkbox').forEach(cb => cb.checked = false);
        document.getElementById('faculties_all').checked = false;
        document.getElementById('cohorts_all').checked = false;
        updateFacultyTags();
        updateCohortTags();
        if (id) {
            document.getElementById('formMethod').innerHTML = "<input type='hidden' name='_method' value='PUT'>";
            fetch(`{{ url('admin/registration-waves') }}/${id}/detail`).then(r => r.json()).then(({
                wave,
                offerings
            }) => {
                setValue('name', wave.name);
                ensureOption('academic_year', wave.academic_year);
                setValue('academic_year', wave.academic_year);
                ensureOption('term', wave.term);
                setValue('term', wave.term);
                setValue('starts_at', wave.starts_at ? wave.starts_at.replace(' ', 'T').slice(0, 16) : '');
                setValue('ends_at', wave.ends_at ? wave.ends_at.replace(' ', 'T').slice(0, 16) : '');
                setFaculties((wave.faculties || []).map(f => String(f.id)));
                setCohorts((wave.cohorts || []).map(String));
                maybeLoadOfferings(() => {
                    setChecked('class_section_ids[]', (offerings || []).map(o => String(o.id)));
                });
                overlay.style.display = 'flex';
            });
        } else {
            overlay.style.display = 'flex';
            // If year/term are empty, pick the first available options to avoid extra user steps
            const yearSel = document.getElementById('academic_year');
            const termSel = document.getElementById('term');
            if (yearSel && !yearSel.value) {
                const opt = Array.from(yearSel.options).find(o => o.value);
                if (opt) yearSel.value = opt.value;
            }
            if (termSel && !termSel.value) {
                const opt = Array.from(termSel.options).find(o => o.value);
                if (opt) termSel.value = opt.value;
            }
            // Auto-load offerings immediately based on selected year/term
            maybeLoadOfferings();
        }
    }

    function closeWaveModal() {
        document.getElementById('waveModal').style.display = 'none';
    }

    function setValue(id, val) {
        const el = document.getElementById(id);
        if (el) el.value = val ?? '';
    }

    // Simple checkbox helpers for faculties
    function toggleAllFaculties(checked) {
        document.querySelectorAll('.faculty-checkbox').forEach(cb => cb.checked = checked);
        updateFacultyTags();
    }

    function updateFacultyTags() {
        const container = document.getElementById('faculty_tags');
        container.innerHTML = '';
        const checked = Array.from(document.querySelectorAll('.faculty-checkbox:checked'));
        checked.forEach(cb => {
            const label = cb.closest('label').textContent.trim();
            const tag = document.createElement('span');
            tag.style.cssText = 'background:#dbeafe; color:#1e40af; padding:4px 10px; border-radius:12px; font-size:12px; display:inline-flex; align-items:center; gap:4px;';
            tag.innerHTML = `${escapeHtml(label)} <button type="button" onclick="removeFaculty('${cb.value}')" style="background:none; border:none; color:#1e40af; cursor:pointer; padding:0; font-size:14px;">⊗</button>`;
            container.appendChild(tag);
        });
        const all = document.getElementById('faculties_all');
        if (all) all.checked = checked.length > 0 && checked.length === document.querySelectorAll('.faculty-checkbox').length;
    }

    function removeFaculty(id) {
        const cb = document.querySelector(`.faculty-checkbox[value="${CSS.escape(id)}"]`);
        if (cb) {
            cb.checked = false;
            updateFacultyTags();
        }
    }

    // Simple checkbox helpers for cohorts
    function toggleAllCohorts(checked) {
        document.querySelectorAll('.cohort-checkbox').forEach(cb => cb.checked = checked);
        updateCohortTags();
    }

    function updateCohortTags() {
        const container = document.getElementById('cohort_tags');
        container.innerHTML = '';
        const checked = Array.from(document.querySelectorAll('.cohort-checkbox:checked'));
        checked.forEach(cb => {
            const label = cb.value;
            const tag = document.createElement('span');
            tag.style.cssText = 'background:#cffafe; color:#164e63; padding:4px 10px; border-radius:12px; font-size:12px; display:inline-flex; align-items:center; gap:4px;';
            tag.innerHTML = `${escapeHtml(label)} <button type="button" onclick="removeCohort('${label}')" style="background:none; border:none; color:#164e63; cursor:pointer; padding:0; font-size:14px;">⊗</button>`;
            container.appendChild(tag);
        });
        const all = document.getElementById('cohorts_all');
        if (all) all.checked = checked.length > 0 && checked.length === document.querySelectorAll('.cohort-checkbox').length;
    }

    function removeCohort(val) {
        const cb = document.querySelector(`.cohort-checkbox[value="${CSS.escape(val)}"]`);
        if (cb) {
            cb.checked = false;
            updateCohortTags();
        }
    }

    function setFaculties(ids) {
        document.querySelectorAll('.faculty-checkbox').forEach(cb => {
            cb.checked = ids.includes(String(cb.value));
        });
        updateFacultyTags();
    }

    function setCohorts(vals) {
        document.querySelectorAll('.cohort-checkbox').forEach(cb => {
            cb.checked = vals.includes(String(cb.value));
        });
        updateCohortTags();
    }

    function v(id) {
        return (document.getElementById(id)?.value || '').trim();
    }

    function setChecked(name, values) {
        document.querySelectorAll(`input[name='${name}']`).forEach(cb => {
            cb.checked = values.includes(String(cb.value));
        });
    }

    function maybeLoadOfferings(cb) {
        const year = v('academic_year');
        const term = v('term');
        if (year && term) {
            loadOfferings().then(() => cb && cb());
        }
    }

    async function loadOfferings() {
        const year = v('academic_year');
        const term = v('term');
        const facultyId = (document.getElementById('offerings_faculty')?.value || '').trim();
        const q = (document.getElementById('offerings_search').value || '').trim();
        if (!year || !term) {
            clearOfferingsTable();
            return;
        }
        // Preserve current selections across reloads
        const previouslyChecked = new Set(Array.from(document.querySelectorAll("#offerings_tbody input[name='class_section_ids[]']:checked")).map(el => el.value));
        // Show a lightweight loading row while fetching
        document.getElementById('offerings_tbody').innerHTML = `<tr><td colspan='8' style='padding:16px; text-align:center; color:#64748b;'>Đang tải danh sách lớp học phần...</td></tr>`;
        const url = new URL(`{{ url('admin/registration-waves/offerings') }}`);
        url.searchParams.set('academic_year', year);
        url.searchParams.set('term', term);
        if (facultyId) url.searchParams.set('faculty_id', facultyId);
        if (q) url.searchParams.set('q', q);
        const res = await fetch(url);
        const json = await res.json();
        const rows = (json.data || []).map(row => `
            <tr>
                <td style='padding:8px;'><input type='checkbox' name='class_section_ids[]' value='${row.id}' ${previouslyChecked.has(String(row.id)) ? 'checked' : ''}></td>
                <td style='padding:8px;'>${escapeHtml(row.section_code||'')}</td>
                <td style='padding:8px;'><span class='badge-chip' style='background:#cffafe; color:#164e63;'>${escapeHtml(row.course_code||'')}</span> ${escapeHtml(row.course_name||'')}</td>
                <td style='padding:8px;'>${row.faculty ? escapeHtml(row.faculty.code) : ''}</td>
                <td style='padding:8px;'>${escapeHtml(row.lecturer||'')}</td>
                <td style='padding:8px;'>${formatSchedule(row)}</td>
                <td style='padding:8px;'>${escapeHtml(String(row.max_capacity||''))}</td>
            </tr>`).join('');
        document.getElementById('offerings_tbody').innerHTML = rows || `<tr><td colspan='8' style='padding:16px; text-align:center; color:#94a3b8;'>Không có lớp học phần phù hợp</td></tr>`;
        const master = document.getElementById('offerings_check_all');
        if (master) master.checked = false;
    }

    function clearOfferingsTable() {
        document.getElementById('offerings_tbody').innerHTML = `<tr><td colspan='8' style='padding:16px; text-align:center; color:#94a3b8;'>Chưa có dữ liệu</td></tr>`;
    }

    function escapeHtml(s) {
        return (s || '').toString().replace(/[&<>"']/g, c => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            '\'': '&#39;'
        } [c]))
    }

    function formatSchedule(row) {
        const dow = row.day_of_week ? `Thứ ${row.day_of_week}` : '';
        const shift = row.shift ? `${row.shift.name} ${(row.shift.start_time||'').substring(0,5)}-${(row.shift.end_time||'').substring(0,5)}` : '';
        const room = row.room ? `Phòng ${row.room}` : '';
        return [dow, shift, room].filter(Boolean).join(', ');
    }
    // Ensure a value exists in a select (for editing waves with future terms/years)
    function ensureOption(selectId, value) {
        const sel = document.getElementById(selectId);
        if (!sel || value == null || value === '') return;
        const exists = Array.from(sel.options).some(o => o.value == String(value));
        if (!exists) {
            const opt = document.createElement('option');
            opt.value = String(value);
            opt.textContent = String(value);
            sel.appendChild(opt);
        }
    }
    // Auto-refresh offerings on year/term change when on the offerings tab
    ['academic_year', 'term'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('change', () => {
            maybeLoadOfferings();
        });
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
        // Debounce search to avoid excessive requests
        const search = document.getElementById('offerings_search');
        let t;
        if (search) {
            search.addEventListener('input', () => {
                clearTimeout(t);
                t = setTimeout(() => maybeLoadOfferings(), 300);
            });
        }
        // Faculty filter
        document.getElementById('offerings_faculty')?.addEventListener('change', () => maybeLoadOfferings());
    });
    // Offerings: master checkbox
    document.addEventListener('change', function(e) {
        if (e.target && e.target.id === 'offerings_check_all') {
            const on = e.target.checked;
            document.querySelectorAll("#offerings_tbody input[type='checkbox'][name='class_section_ids[]']").forEach(cb => cb.checked = on);
        }
    });

    // Frontend validation with inline messages and auto-scroll
    function validateWaveFrontend() {
        // Cleanup
        document.querySelectorAll('.error-text').forEach(el => el.remove());
        document.querySelectorAll('.invalid').forEach(el => el.classList.remove('invalid'));
        const errs = [];

        const req = (id, label) => {
            const el = document.getElementById(id);
            const val = (el?.value || '').trim();
            if (!val) {
                addError(el, `${label} là bắt buộc.`);
                errs.push(el);
            }
            return val;
        };

        const name = req('name', 'Tên đợt đăng ký');
        const year = req('academic_year', 'Năm học');
        const term = req('term', 'Học kỳ');
        const starts = req('starts_at', 'Thời gian bắt đầu');
        const ends = req('ends_at', 'Thời gian kết thúc');
        if (starts && ends && new Date(starts) >= new Date(ends)) {
            const el = document.getElementById('ends_at');
            addError(el, 'Thời gian kết thúc phải sau thời gian bắt đầu.');
            errs.push(el);
        }

        // Audience selections
        const facSelected = document.querySelectorAll('.faculty-checkbox:checked').length;
        const cohSelected = document.querySelectorAll('.cohort-checkbox:checked').length;
        if (!facSelected) {
            addError(document.getElementById('faculty_tags').previousElementSibling, 'Vui lòng chọn ít nhất một Khoa.');
            errs.push(document.getElementById('faculty_tags'));
        }
        if (!cohSelected) {
            addError(document.getElementById('cohort_tags').previousElementSibling, 'Vui lòng chọn ít nhất một Khóa.');
            errs.push(document.getElementById('cohort_tags'));
        }

        // Offerings
        const anyOffering = document.querySelector("#offerings_tbody input[name='class_section_ids[]']:checked");
        if (!anyOffering) {
            addError(document.getElementById('offerings_container'), 'Vui lòng chọn ít nhất một Lớp học phần.');
            errs.push(document.getElementById('offerings_container'));
        }

        if (errs.length) {
            const first = errs[0];
            first?.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            return false;
        }
        return true;
    }

    function addError(target, message) {
        if (!target) return;
        const err = document.createElement('div');
        err.className = 'error-text';
        err.textContent = message;
        // Apply red border to inputs
        if (target.tagName === 'INPUT' || target.tagName === 'SELECT') {
            target.classList.add('invalid');
            target.insertAdjacentElement('afterend', err);
        } else {
            target.insertAdjacentElement('afterend', err);
        }
    }

    // Auto-reopen modal with old() values and show backend banner if present
    document.addEventListener('DOMContentLoaded', () => {
        const hasErrors = Boolean(@json($errors->any()));
        const be = @json(session('business_error'));
        if (hasErrors || be) {
            openWaveModal();
            setValue('name', @json(old('name')) || '');
            ensureOption('academic_year', @json(old('academic_year')));
            setValue('academic_year', @json(old('academic_year')) || '');
            ensureOption('term', @json(old('term')));
            setValue('term', @json(old('term')) || '');
            setValue('starts_at', (@json(old('starts_at')) || '').replace(' ', 'T'));
            setValue('ends_at', (@json(old('ends_at')) || '').replace(' ', 'T'));
            setFaculties((@json(old('faculties', [])) || []).map(String));
            setCohorts((@json(old('cohorts', [])) || []).map(String));
            const oldIds = (@json(old('class_section_ids', [])) || []).map(String);
            maybeLoadOfferings(() => setChecked('class_section_ids[]', oldIds));
            if (be) {
                const b = document.getElementById('backendErrorBanner');
                b.style.display = 'block';
                b.textContent = be;
            }
        }
    });
    // Delete wave via AJAX and remove row without full reload
    async function deleteWave(id, btn) {
        if (!confirm('Bạn có chắc muốn xóa đợt đăng ký này?')) return;
        const url = `{{ url('admin/registration-waves') }}/${id}`;
        try {
            const res = await fetch(url, {
                method: 'DELETE',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            if (!res.ok) {
                const j = await res.json().catch(() => ({}));
                throw new Error(j.message || `Xóa thất bại (HTTP ${res.status}).`);
            }
            // Success
            const tr = btn.closest('tr');
            if (tr) tr.remove();
            showToast('Đã xóa đợt đăng ký thành công.', 'success');
        } catch (e) {
            showToast(e.message || 'Xóa thất bại.', 'error');
        }
    }
    // On load: also echo session toasts if available
    document.addEventListener('DOMContentLoaded', () => {
        const suc = @json(session('success'));
        const err = @json(session('error'));
        if (suc) showToast(suc, 'success');
        if (err) showToast(err, 'error');
    });
</script>
@endsection