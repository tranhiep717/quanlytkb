@extends('admin.layout')

@section('title', 'Quản lý Phòng học')

@section('styles')
<style>
    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .filter-row {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto;
        gap: 12px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-label {
        font-size: 13px;
        font-weight: 500;
        color: #475569;
        margin-bottom: 6px;
    }

    .filter-input {
        padding: 8px 12px;
        border: 1px solid #cbd5e0;
        border-radius: 6px;
        font-size: 14px;
    }

    .filter-input:focus {
        outline: none;
        border-color: #1976d2;
        box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
    }

    .rooms-table-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .rooms-table {
        width: 100%;
        border-collapse: collapse;
    }

    .rooms-table thead {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
    }

    .rooms-table th {
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        color: #475569;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .rooms-table tbody tr {
        border-bottom: 1px solid #e2e8f0;
        transition: background 0.2s;
    }

    .rooms-table tbody tr:hover {
        background: rgba(59, 130, 246, 0.05);
    }

    .rooms-table td {
        padding: 14px 16px;
        color: #1e293b;
        font-size: 14px;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-active {
        background: #dcfce7;
        color: #166534;
    }

    .status-inactive {
        background: #f1f5f9;
        color: #64748b;
    }

    .equipment-tag {
        display: inline-block;
        background: #e0e7ff;
        color: #4338ca;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
        margin-right: 4px;
        margin-bottom: 2px;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    .btn-view {
        background: #10b981;
        color: white;
    }

    .btn-edit {
        background: #1976d2;
        color: white;
    }

    .btn-delete {
        background: #dc2626;
        color: white;
    }

    .detail-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .detail-modal-content {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 800px;
        max-height: 85vh;
        overflow: auto;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        padding: 24px;
        border-bottom: 2px solid #10b981;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .modal-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-body {
        padding: 28px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    .detail-field {
        background: #f8fafc;
        padding: 16px;
        border-radius: 8px;
        border-left: 4px solid #10b981;
    }

    .detail-field-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .detail-field-value {
        font-size: 16px;
        color: #1e293b;
        font-weight: 600;
    }

    .usage-history {
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid #e2e8f0;
    }

    .usage-item {
        background: #f8fafc;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 8px;
        border-left: 3px solid #8b5cf6;
    }

    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 99999;
    }

    @media (max-width: 992px) {
        .filter-row {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div>
            <h2 style="margin:0; font-size:24px; font-weight:600; color:#1e293b;">🏫 Quản lý Phòng học</h2>
            <p style="margin:4px 0 0 0; color:#64748b; font-size:14px;">UC2.5: Tìm kiếm, xem, thêm, sửa, xóa phòng học
            </p>
        </div>
        <a href="{{ route('rooms.create') }}"
            style="background:#10b981; color:white; padding:12px 20px; border-radius:8px; text-decoration:none; font-weight:500; display:inline-flex; align-items:center; gap:8px;">
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
            </svg>
            Tạo phòng học mới
        </a>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form action="{{ route('rooms.index') }}" method="GET">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">🔍 Từ khóa (Mã / Tên phòng)</label>
                    <input type="text" name="search" class="filter-input" placeholder="Nhập mã hoặc tên phòng..."
                        value="{{ $filters['search'] ?? '' }}">
                </div>
                <div class="filter-group">
                    <label class="filter-label">🏢 Tòa nhà</label>
                    <select name="building" class="filter-input">
                        <option value="">Tất cả</option>
                        @foreach($buildings as $building)
                        <option value="{{ $building }}"
                            {{ ($filters['building'] ?? '') == $building ? 'selected' : '' }}>{{ $building }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">👥 Sức chứa ≥</label>
                    <input type="number" name="min_capacity" class="filter-input" placeholder="VD: 30"
                        value="{{ $filters['min_capacity'] ?? '' }}" min="0">
                </div>
                <div class="filter-group">
                    <label class="filter-label">🖥️ Thiết bị</label>
                    <select name="equipment" class="filter-input">
                        <option value="">Tất cả</option>
                        @foreach($equipmentOptions as $eq)
                        <option value="{{ $eq }}" {{ ($filters['equipment'] ?? '') == $eq ? 'selected' : '' }}>{{ $eq }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">📊 Trạng thái</label>
                    <select name="status" class="filter-input">
                        <option value="">Tất cả</option>
                        <option value="active" {{ ($filters['status'] ?? '') == 'active' ? 'selected' : '' }}>Hoạt động
                        </option>
                        <option value="inactive" {{ ($filters['status'] ?? '') == 'inactive' ? 'selected' : '' }}>Tạm
                            ngưng</option>
                    </select>
                </div>
                <div style="display:flex; gap:8px;">
                    <button type="submit"
                        style="background:#1976d2; color:white; padding:8px 16px; border:none; border-radius:6px; cursor:pointer; font-weight:500; display:flex; align-items:center; gap:6px;">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                        </svg>
                        Lọc
                    </button>
                    @if(request()->hasAny(['search','building','min_capacity','equipment','status']))
                    <a href="{{ route('rooms.index') }}"
                        style="background:#f1f5f9; color:#64748b; padding:8px 16px; border-radius:6px; text-decoration:none; font-weight:500;">Xóa
                        bộ lọc</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="rooms-table-container">
        <table class="rooms-table">
            <thead>
                <tr>
                    <th>MÃ PHÒNG</th>
                    <th>TÊN PHÒNG</th>
                    <th>TÒA NHÀ / TẦNG</th>
                    <th>SỨC CHỨA</th>
                    <th>THIẾT BỊ CHÍNH</th>
                    <th>TRẠNG THÁI</th>
                    <th style="text-align:center;">THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rooms as $room)
                <tr>
                    <td><code
                            style="background:#fef3c7; color:#92400e; padding:4px 8px; border-radius:4px; font-size:13px; font-weight:700;">{{ $room->code }}</code>
                    </td>
                    <td style="font-weight:600;">{{ $room->name ?? 'Chưa đặt tên' }}</td>
                    <td style="color:#64748b;">
                        @if($room->building)
                        {{ $room->building }}{{ $room->floor ? ' - Tầng '.$room->floor : '' }}
                        @else
                        <span style="font-style:italic;">Chưa cập nhật</span>
                        @endif
                    </td>
                    <td><span
                            style="background:#dbeafe; color:#1e40af; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:600;">👥
                            {{ $room->capacity ?? 0 }} người</span></td>
                    <td>
                        @if($room->equipment && count($room->equipment) > 0)
                        @foreach(array_slice($room->equipment, 0, 2) as $eq)
                        <span class="equipment-tag">{{ $eq }}</span>
                        @endforeach
                        @if(count($room->equipment) > 2)
                        <span style="color:#64748b; font-size:11px;">+{{ count($room->equipment) - 2 }} khác</span>
                        @endif
                        @else
                        <span style="color:#94a3b8; font-style:italic; font-size:13px;">Không có</span>
                        @endif
                    </td>
                    <td><span
                            class="status-badge status-{{ $room->status }}">{{ $room->status === 'active' ? '✓ Hoạt động' : '⏸ Tạm ngưng' }}</span>
                    </td>
                    <td style="text-align:center;">
                        <div style="display:inline-flex; gap:6px;">
                            <button onclick="viewRoomDetail({{ $room->id }})" class="action-btn btn-view"
                                title="Xem chi tiết">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z" />
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z" />
                                </svg>
                            </button>
                            <a href="{{ route('rooms.edit', $room) }}" class="action-btn btn-edit" title="Sửa">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                                </svg>
                            </a>
                            <form action="{{ route('rooms.destroy', $room) }}" method="POST" style="display:inline;"
                                onsubmit="return confirm('⚠️ Xác nhận xóa phòng {{ $room->code }}?\n\nLưu ý: Nếu phòng đang được sử dụng, hệ thống sẽ từ chối và gợi ý chuyển sang Tạm ngưng.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn btn-delete" title="Xóa">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path
                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                        <path fill-rule="evenodd"
                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:60px 20px; text-align:center; color:#94a3b8;">
                        <svg width="64" height="64" fill="currentColor" viewBox="0 0 16 16"
                            style="opacity:0.3; margin-bottom:16px;">
                            <path
                                d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z" />
                        </svg>
                        <div style="font-size:16px; font-weight:500;">Không tìm thấy phòng học nào</div>
                        <div style="font-size:14px; margin-top:4px;">Thử thay đổi bộ lọc hoặc thêm phòng học mới</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($rooms->hasPages())
        <div style="padding:20px; border-top:1px solid #e2e8f0;">{{ $rooms->appends($filters)->links() }}</div>
        @endif
    </div>
</div>

<!-- Modal Chi tiết Phòng học -->
<div id="detailModal" class="detail-modal">
    <div class="detail-modal-content">
        <div class="modal-header">
            <h3>
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z" />
                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z" />
                </svg>
                Chi tiết Phòng học
            </h3>
        </div>
        <div class="modal-body">
            <div
                style="background:#f8fafc; padding:20px; border-radius:8px; border-left:4px solid #10b981; margin-bottom:24px;">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
                    <code id="detailCode"
                        style="background:#fef3c7; color:#92400e; padding:6px 12px; border-radius:6px; font-size:15px; font-weight:700;"></code>
                    <span id="detailStatus" class="status-badge"></span>
                </div>
                <h4 id="detailName" style="margin:0; font-size:18px; font-weight:600; color:#1e293b;"></h4>
            </div>
            <div class="detail-grid">
                <div class="detail-field">
                    <div class="detail-field-label">🏢 Tòa nhà</div>
                    <div class="detail-field-value" id="detailBuilding"></div>
                </div>
                <div class="detail-field">
                    <div class="detail-field-label">📍 Tầng</div>
                    <div class="detail-field-value" id="detailFloor"></div>
                </div>
                <div class="detail-field">
                    <div class="detail-field-label">👥 Sức chứa</div>
                    <div class="detail-field-value" id="detailCapacity"></div>
                </div>
                <div class="detail-field">
                    <div class="detail-field-label">📊 Lần sử dụng</div>
                    <div class="detail-field-value" id="detailTotalUsage"></div>
                </div>
            </div>
            <div style="margin-bottom:20px;">
                <div class="detail-field-label" style="margin-bottom:8px;">🖥️ Trang thiết bị</div>
                <div id="detailEquipment" style="padding:16px; background:#f8fafc; border-radius:8px; min-height:40px;">
                </div>
            </div>
            <div class="usage-history">
                <h5 style="margin:0 0 16px 0; font-size:16px; font-weight:600; color:#1e293b;">📚 Lịch sử sử dụng gần
                    đây (10 lần cuối)</h5>
                <div id="usageHistoryList"></div>
            </div>
            <div
                style="display:flex; justify-content:flex-end; gap:12px; padding-top:20px; border-top:1px solid #e2e8f0; margin-top:24px;">
                <button onclick="closeDetailModal()"
                    style="padding:10px 24px; border:1px solid #cbd5e0; border-radius:6px; background:white; color:#475569; cursor:pointer; font-weight:500;">Đóng</button>
                <button onclick="editFromDetail()" id="editFromDetailBtn"
                    style="padding:10px 24px; border:none; border-radius:6px; background:#1976d2; color:white; cursor:pointer; font-weight:500;">Chỉnh
                    sửa</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>
@endsection

@section('scripts')
<script>
    let currentRoomId = null;

    function viewRoomDetail(roomId) {
        currentRoomId = roomId;
        document.getElementById('detailModal').style.display = 'flex';
        fetch('/admin/rooms/' + roomId + '/detail')
            .then(res => res.json())
            .then(data => {
                document.getElementById('detailCode').textContent = data.code;
                document.getElementById('detailName').textContent = data.name || 'Chưa đặt tên';
                const statusBadge = document.getElementById('detailStatus');
                statusBadge.textContent = data.status === 'active' ? '✓ Hoạt động' : '⏸ Tạm ngưng';
                statusBadge.className = 'status-badge status-' + data.status;
                document.getElementById('detailBuilding').textContent = data.building || 'Chưa cập nhật';
                document.getElementById('detailFloor').textContent = data.floor || 'Chưa cập nhật';
                document.getElementById('detailCapacity').textContent = (data.capacity || 0) + ' người';
                document.getElementById('detailTotalUsage').textContent = data.total_usage + ' lần';
                const equipmentDiv = document.getElementById('detailEquipment');
                if (data.equipment && data.equipment.length > 0) {
                    equipmentDiv.innerHTML = data.equipment.map(eq => '<span class="equipment-tag">' + eq + '</span>')
                        .join('');
                } else {
                    equipmentDiv.innerHTML =
                        '<span style="color:#94a3b8; font-style:italic;">Không có trang thiết bị</span>';
                }
                const usageList = document.getElementById('usageHistoryList');
                if (data.recent_usage && data.recent_usage.length > 0) {
                    usageList.innerHTML = data.recent_usage.map(usage =>
                        '<div class="usage-item">' +
                        '<div style="font-weight:600; color:#1e293b; margin-bottom:4px;">' + usage.class_name +
                        ' - ' + usage.course_code + ': ' + usage.course_name + '</div>' +
                        '<div style="font-size:12px; color:#64748b;">Ca: ' + usage.shift + ' | ' + usage.semester +
                        ' - ' + usage.year + '</div>' +
                        '</div>'
                    ).join('');
                } else {
                    usageList.innerHTML =
                        '<div style="padding:20px; text-align:center; color:#94a3b8; font-style:italic;">Chưa có lịch sử sử dụng</div>';
                }
            })
            .catch(err => {
                console.error('Error loading room detail:', err);
                alert('Không thể tải thông tin chi tiết phòng học');
                closeDetailModal();
            });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
        currentRoomId = null;
    }

    function editFromDetail() {
        if (currentRoomId) {
            window.location.href = '/admin/rooms/' + currentRoomId + '/edit';
        }
    }

    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetailModal();
        }
    });

    @if(session('error'))
    showToast('error', 'Lỗi', '{{ session('
        error ') }}');
    @endif
    @if(session('success'))
    showToast('success', 'Thành công', '{{ session('
        success ') }}');
    @endif
    @if(session('warning'))
    showToast('warning', 'Cảnh báo', '{{ session('
        warning ') }}');
    @endif

    function showToast(type, title, message) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        const iconMap = {
            error: '❌',
            success: '✅',
            warning: '⚠️'
        };
        const bgMap = {
            error: '#fee2e2',
            success: '#d1fae5',
            warning: '#fef3c7'
        };
        const borderMap = {
            error: '#dc2626',
            success: '#10b981',
            warning: '#f59e0b'
        };
        toast.style.cssText = 'background:' + bgMap[type] + ';border-left:4px solid ' + borderMap[type] +
            ';padding:16px;border-radius:8px;margin-bottom:10px;box-shadow:0 4px 12px rgba(0,0,0,0.15);animation: slideIn 0.3s ease-out;min-width:300px;max-width:500px;';
        toast.innerHTML =
            '<div style="display:flex; justify-content:space-between; align-items:start;"><div><strong style="display:flex; align-items:center; gap:8px; margin-bottom:4px;"><span>' +
            iconMap[type] + '</span> ' + title + '</strong><div style="color:#374151; font-size:14px;">' + message +
            '</div></div><button onclick="this.parentElement.parentElement.remove()" style="background:none; border:none; font-size:20px; cursor:pointer; color:#6b7280; padding:0; margin-left:12px;">&times;</button></div>';
        container.appendChild(toast);
        setTimeout(function() {
            toast.remove()
        }, 5000);
    }
</script>
<style>
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>
@endsection