@extends('admin.layout')

@section('title', 'Danh sách Thông báo')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
    <h2 style="margin:0;">Danh sách Thông báo</h2>
    <a href="{{ route('announcements.create') }}" style="background:#10b981;color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none;">+ Thêm thông báo mới</a>
</div>

<div class="card" style="margin-bottom:16px;">
    <form method="GET" action="{{ route('announcements.index') }}" style="display:flex; gap:12px; align-items:end; flex-wrap:wrap;">
        <div>
            <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Tìm kiếm</label>
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Tiêu đề...">
        </div>
        <div>
            <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Trạng thái</label>
            <select name="status">
                <option value="">-- Tất cả --</option>
                <option value="published" {{ ($filters['status'] ?? '')==='published' ? 'selected' : '' }}>Đã xuất bản</option>
                <option value="draft" {{ ($filters['status'] ?? '')==='draft' ? 'selected' : '' }}>Nháp</option>
            </select>
        </div>
        <div>
            <button type="submit">Lọc</button>
        </div>
    </form>
</div>

<div class="card">
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="border-bottom:1px solid rgba(148,163,184,.2);">
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Tiêu đề</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Đối tượng</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Ngày đăng</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Trạng thái</th>
                <th style="text-align:left;padding:12px;color:#94a3b8;font-weight:500;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($announcements as $a)
            @php
            $aud = $a->audience ?? null;
            $label = 'Tất cả';
            if (is_array($aud)) {
            if (!empty($aud['faculties'])) $label = 'Khoa';
            elseif (!empty($aud['roles']) && in_array('lecturers', $aud['roles'])) $label = 'Giảng viên';
            elseif (!empty($aud['roles']) && in_array('students', $aud['roles'])) $label = 'Sinh viên';
            }
            $status = $a->published_at ? 'Đã xuất bản' : 'Nháp';
            @endphp
            <tr style="border-bottom:1px solid rgba(148,163,184,.1);">
                <td style="padding:12px;">{{ $a->title }}</td>
                <td style="padding:12px;">{{ $label }}</td>
                <td style="padding:12px;">{{ $a->published_at ? $a->published_at->format('d/m/Y H:i') : '-' }}</td>
                <td style="padding:12px;">{{ $status }}</td>
                <td style="padding:12px; display:flex; gap:8px;">
                    <a href="{{ route('announcements.edit', $a) }}">Sửa</a>
                    <form action="{{ route('announcements.destroy', $a) }}" method="POST" onsubmit="return confirm('Xóa thông báo này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:#ef4444; color:#fff; border-radius:4px; padding:6px 10px;">Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:24px;text-align:center;color:#64748b;">Chưa có thông báo.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:16px;">
        {{ $announcements->appends($filters)->links() }}
    </div>
</div>
@endsection