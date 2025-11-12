@extends('admin.layout')

@section('title', 'Chỉnh sửa phòng học')

@section('content')
<div style="max-width:800px; margin:0 auto;">
    <div style="margin-bottom:24px;">
        <h2 style="margin:0; font-size:24px; font-weight:600; color:#1e293b;">✏️ Chỉnh sửa phòng học: {{ $room->code }}</h2>
        <p style="margin:4px 0 0 0; color:#64748b; font-size:14px;">Cập nhật thông tin phòng học</p>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('rooms.update', $room) }}">
            @csrf
            @method('PUT')

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                <div>
                    <label style="display:block;margin-bottom:6px;color:#475569;font-size:14px;font-weight:500;">Mã phòng <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $room->code) }}" required
                        style="width:100%;background:white;color:#1e293b;border:1px solid #cbd5e0;border-radius:6px;padding:10px 12px;font-size:14px;">
                    @error('code')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label style="display:block;margin-bottom:6px;color:#475569;font-size:14px;font-weight:500;">Tên phòng <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $room->name) }}" required
                        style="width:100%;background:white;color:#1e293b;border:1px solid #cbd5e0;border-radius:6px;padding:10px 12px;font-size:14px;">
                    @error('name')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                <div>
                    <label style="display:block;margin-bottom:6px;color:#475569;font-size:14px;font-weight:500;">Tòa nhà</label>
                    <input type="text" name="building" value="{{ old('building', $room->building) }}"
                        style="width:100%;background:white;color:#1e293b;border:1px solid #cbd5e0;border-radius:6px;padding:10px 12px;font-size:14px;">
                    @error('building')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label style="display:block;margin-bottom:6px;color:#475569;font-size:14px;font-weight:500;">Tầng</label>
                    <input type="text" name="floor" value="{{ old('floor', $room->floor) }}"
                        style="width:100%;background:white;color:#1e293b;border:1px solid #cbd5e0;border-radius:6px;padding:10px 12px;font-size:14px;">
                    @error('floor')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;margin-bottom:6px;color:#475569;font-size:14px;font-weight:500;">Sức chứa (số người) <span style="color:#ef4444;">*</span></label>
                <input type="number" name="capacity" value="{{ old('capacity', $room->capacity) }}" required min="1"
                    style="width:100%;background:white;color:#1e293b;border:1px solid #cbd5e0;border-radius:6px;padding:10px 12px;font-size:14px;">
                @error('capacity')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block;margin-bottom:8px;color:#475569;font-size:14px;font-weight:500;">Trang thiết bị</label>
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px;">
                    @php
                    $equipmentOptions = ['Máy chiếu', 'Bảng thông minh', 'Điều hòa', 'Micro', 'Loa', 'Máy tính', 'Bảng viết'];
                    $currentEquipment = old('equipment', $room->equipment ?? []);
                    @endphp
                    @foreach($equipmentOptions as $eq)
                    <label style="display:flex; align-items:center; gap:8px; padding:8px; background:#f8fafc; border-radius:6px; cursor:pointer;">
                        <input type="checkbox" name="equipment[]" value="{{ $eq }}"
                            {{ in_array($eq, $currentEquipment) ? 'checked' : '' }}
                            style="width:16px; height:16px; cursor:pointer;">
                        <span style="font-size:13px; color:#1e293b;">{{ $eq }}</span>
                    </label>
                    @endforeach
                </div>
                @error('equipment')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block;margin-bottom:6px;color:#475569;font-size:14px;font-weight:500;">Trạng thái</label>
                <select name="status" style="width:100%;background:white;color:#1e293b;border:1px solid #cbd5e0;border-radius:6px;padding:10px 12px;font-size:14px;">
                    <option value="active" {{ old('status', $room->status) == 'active' ? 'selected' : '' }}>✓ Hoạt động</option>
                    <option value="inactive" {{ old('status', $room->status) == 'inactive' ? 'selected' : '' }}>⏸ Tạm ngưng</option>
                </select>
                @error('status')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="display:flex;gap:12px;padding-top:16px;border-top:1px solid #e2e8f0;">
                <button type="submit" style="background:#1976d2;color:#fff;padding:12px 24px;border-radius:8px;cursor:pointer;border:none;font-weight:500;font-size:14px;">
                    <span style="margin-right:6px;">💾</span> Cập nhật
                </button>
                <a href="{{ route('rooms.index') }}" style="background:#f1f5f9;color:#64748b;padding:12px 24px;border-radius:8px;text-decoration:none;display:inline-block;font-weight:500;font-size:14px;">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection