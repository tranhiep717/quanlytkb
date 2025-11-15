@extends('admin.layout')

@section('title', 'Kho lưu trữ Đợt đăng ký')

@section('content')
<div style="background:white; padding:24px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h2 style="margin:0; font-size:20px; font-weight:600; color:#1e293b;">🗄️ Kho lưu trữ Đợt đăng ký</h2>
        <a href="{{ route('registration-waves.index') }}" style="background:#1976d2; color:white; padding:10px 14px; border-radius:6px; text-decoration:none;">Quay lại danh sách</a>
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
                    <th style="padding:12px; text-align:left;">TÊN ĐỢT</th>
                    <th style="padding:12px; text-align:left;">NĂM/KỲ</th>
                    <th style="padding:12px; text-align:left;">ĐÃ XÓA LÚC</th>
                    <th style="padding:12px; text-align:center;">THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                @forelse($waves as $wave)
                <tr style="border-bottom:1px solid #e2e8f0;">
                    <td style="padding:12px; font-weight:600; color:#1e293b;">{{ $wave->name }}</td>
                    <td style="padding:12px;">
                        <span style="background:#dbeafe; color:#1e40af; padding:4px 10px; border-radius:12px; font-size:12px;">{{ $wave->academic_year }}</span>
                        <span style="background:#cffafe; color:#164e63; padding:4px 10px; border-radius:12px; font-size:12px;">{{ $wave->term }}</span>
                    </td>
                    <td style="padding:12px; color:#475569;">{{ optional($wave->deleted_at)->format('d/m/Y H:i') }}</td>
                    <td style="padding:12px; text-align:center;">
                        <div style="display:inline-flex; gap:8px;">
                            <form method="POST" action="{{ route('registration-waves.restore', $wave->id) }}" onsubmit="return confirm('Khôi phục đợt đăng ký này?')">
                                @csrf
                                <button type="submit" class="action-btn" style="background:#10b981; color:white; padding:8px 12px; border:none; border-radius:6px;">Khôi phục</button>
                            </form>
                            <form method="POST" action="{{ route('registration-waves.force-delete', $wave->id) }}" onsubmit="return confirm('Xóa vĩnh viễn đợt đăng ký này? Hành động không thể hoàn tác!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn" style="background:#dc2626; color:white; padding:8px 12px; border:none; border-radius:6px;">Xóa vĩnh viễn</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding:40px; text-align:center; color:#94a3b8;">Không có đợt đăng ký đã xóa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($waves->hasPages())
    <div style="margin-top:16px; display:flex; justify-content:center;">{{ $waves->links() }}</div>
    @endif
</div>
@endsection