@extends('admin.layout')

@section('title', 'Nhật ký hệ thống')

@section('content')
<h2>Nhật ký hệ thống</h2>

<div class="card">
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="border-bottom:1px solid rgba(148,163,184,.2);">
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Thời gian</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Người dùng</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Hành động</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Chi tiết</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr style="border-bottom:1px solid rgba(148,163,184,.1);">
                <td style="padding:12px;white-space:nowrap;">
                    <small style="color:#94a3b8;">{{ $log->created_at->format('d/m/Y H:i:s') }}</small>
                </td>
                <td style="padding:12px;">
                    {{ $log->user->name ?? 'Hệ thống' }}<br>
                    <small style="color:#64748b;">{{ $log->user->email ?? '-' }}</small>
                </td>
                <td style="padding:12px;">
                    @php
                    $actionLabels = [
                    'user_created' => '✅ Tạo người dùng',
                    'user_updated' => '✏️ Cập nhật người dùng',
                    'user_locked' => '🔒 Khóa người dùng',
                    'user_unlocked' => '🔓 Mở khóa người dùng',
                    'password_reset_sent' => '📧 Gửi reset mật khẩu',
                    'faculty_created' => '✅ Tạo khoa',
                    'faculty_updated' => '✏️ Cập nhật khoa',
                    'faculty_deleted' => '🗑️ Xóa khoa',
                    'course_created' => '✅ Tạo học phần',
                    'course_updated' => '✏️ Cập nhật học phần',
                    'course_deleted' => '🗑️ Xóa học phần',
                    'room_created' => '✅ Tạo phòng học',
                    'room_updated' => '✏️ Cập nhật phòng học',
                    'room_deleted' => '🗑️ Xóa phòng học',
                    'shift_created' => '✅ Tạo ca học',
                    'shift_updated' => '✏️ Cập nhật ca học',
                    'shift_deleted' => '🗑️ Xóa ca học',
                    'class_section_created' => '✅ Tạo lớp học phần',
                    'class_section_updated' => '✏️ Cập nhật lớp học phần',
                    'class_section_deleted' => '🗑️ Xóa lớp học phần',
                    'registration_wave_created' => '✅ Tạo đợt đăng ký',
                    'registration_wave_updated' => '✏️ Cập nhật đợt đăng ký',
                    'registration_wave_deleted' => '🗑️ Xóa đợt đăng ký',
                    'backup_requested' => '💾 Yêu cầu sao lưu',
                    'login' => '🔑 Đăng nhập',
                    'logout' => '🚪 Đăng xuất',
                    ];
                    @endphp
                    {{ $actionLabels[$log->action] ?? $log->action }}
                </td>
                <td style="padding:12px;">
                    <small style="color:#64748b;font-family:monospace;">@json($log->metadata)</small>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding:24px;text-align:center;color:#64748b;">Chưa có nhật ký nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:16px;">
        {{ $logs->links() }}
    </div>
</div>
@endsection