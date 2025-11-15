@extends('admin.layout')

@section('title', 'Thêm thông báo')

@section('content')
<div class="card">
    <h3 style="margin-top:0;">Tạo thông báo mới</h3>
    <form method="POST" action="{{ route('announcements.store') }}" style="display:grid; gap:16px;">
        @csrf
        <div>
            <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Tiêu đề *</label>
            <input type="text" name="title" value="{{ old('title') }}" required style="width:100%;">
            @error('title')<div class="flash error" style="margin-top:8px;">{{ $message }}</div>@enderror
        </div>
        <div>
            <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Nội dung *</label>
            <textarea name="content" rows="8" required style="width:100%;">{{ old('content') }}</textarea>
            @error('content')<div class="flash error" style="margin-top:8px;">{{ $message }}</div>@enderror
        </div>
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
            <div>
                <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Đối tượng *</label>
                <select name="audience_type" id="audience_type" required>
                    <option value="all" {{ old('audience_type')==='all' ? 'selected' : '' }}>Toàn hệ thống</option>
                    <option value="lecturers" {{ old('audience_type')==='lecturers' ? 'selected' : '' }}>Tất cả Giảng viên</option>
                    <option value="students" {{ old('audience_type')==='students' ? 'selected' : '' }}>Tất cả Sinh viên</option>
                    <option value="faculty_lecturers" {{ old('audience_type')==='faculty_lecturers' ? 'selected' : '' }}>Chỉ Giảng viên 1 Khoa</option>
                    <option value="faculty_students" {{ old('audience_type')==='faculty_students' ? 'selected' : '' }}>Chỉ Sinh viên 1 Khoa</option>
                </select>
                @error('audience_type')<div class="flash error" style="margin-top:8px;">{{ $message }}</div>@enderror
            </div>
            <div id="faculty_wrap" style="display:none;">
                <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Chọn Khoa</label>
                <select name="faculty_id" id="faculty_id">
                    <option value="">-- Chọn Khoa --</option>
                    @foreach($faculties as $f)
                    <option value="{{ $f->id }}" {{ old('faculty_id')==$f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                    @endforeach
                </select>
                @error('faculty_id')<div class="flash error" style="margin-top:8px;">{{ $message }}</div>@enderror
            </div>
            <div>
                <label style="font-size:13px;color:#94a3b8;display:block;margin-bottom:4px;">Trạng thái</label>
                <div>
                    <label><input type="radio" name="publish" value="draft" {{ old('publish','publish')==='draft' ? 'checked' : '' }}> Lưu nháp</label>
                    <label style="margin-left:16px;"><input type="radio" name="publish" value="publish" {{ old('publish','publish')==='publish' ? 'checked' : '' }}> Xuất bản ngay</label>
                </div>
            </div>
        </div>
        <div style="display:flex; gap:12px;">
            <a href="{{ route('announcements.index') }}" style="padding:10px 16px;border:1px solid #cbd5e0;border-radius:8px;text-decoration:none;">Hủy</a>
            <button type="submit" style="background:#10b981;color:#fff;padding:10px 16px;border-radius:8px;">Lưu thông báo</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    (function() {
        const typeEl = document.getElementById('audience_type');
        const facWrap = document.getElementById('faculty_wrap');

        function update() {
            const val = typeEl.value;
            facWrap.style.display = (val === 'faculty_lecturers' || val === 'faculty_students') ? 'block' : 'none';
        }
        typeEl.addEventListener('change', update);
        update();
    })();
</script>
@endsection