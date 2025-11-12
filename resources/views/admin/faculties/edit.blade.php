@extends('admin.layout')

@section('title', 'Chỉnh sửa khoa')

@section('content')
<h2>Chỉnh sửa khoa: {{ $faculty->name }}</h2>

<div class="card" style="max-width:700px;">
    <form method="POST" action="{{ route('faculties.update', $faculty) }}">
        @csrf
        @method('PUT')

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Mã khoa <span style="color:#ef4444;">*</span></label>
            <input type="text" name="code" value="{{ old('code', $faculty->code) }}" required style="width:100%;">
            @error('code')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Tên khoa <span style="color:#ef4444;">*</span></label>
            <input type="text" name="name" value="{{ old('name', $faculty->name) }}" required style="width:100%;">
            @error('name')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Trưởng khoa</label>
            <select name="dean_id" style="width:100%;">
                <option value="">-- Chọn trưởng khoa --</option>
                @foreach($lecturers as $lecturer)
                <option value="{{ $lecturer->id }}" {{ old('dean_id', $faculty->dean_id) == $lecturer->id ? 'selected' : '' }}>
                    {{ $lecturer->name }} ({{ $lecturer->code ?? $lecturer->email }})
                </option>
                @endforeach
            </select>
            @error('dean_id')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Ngày thành lập</label>
            <input type="date" name="founding_date" value="{{ old('founding_date', $faculty->founding_date?->format('Y-m-d')) }}" style="width:100%;">
            @error('founding_date')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block;margin-bottom:4px;color:#94a3b8;font-size:13px;">Mô tả</label>
            <textarea name="description" rows="4" style="width:100%;resize:vertical;" placeholder="Mô tả về khoa...">{{ old('description', $faculty->description) }}</textarea>
            @error('description')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $faculty->is_active) ? 'checked' : '' }}>
                <span style="color:#e2e8f0;font-size:14px;">Hoạt động</span>
            </label>
            @error('is_active')<div style="color:#ef4444;font-size:12px;margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <div style="display:flex;gap:12px;margin-top:24px;">
            <button type="submit" style="background:#0ea5e9;color:#fff;padding:10px 20px;border-radius:8px;cursor:pointer;border:none;">Cập nhật</button>
            <a href="{{ route('faculties.index') }}" style="background:#475569;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;display:inline-block;">Hủy</a>
        </div>
    </form>
</div>
@endsection