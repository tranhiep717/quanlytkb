@extends('student.layout')

@section('title','Giỏ đăng ký')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h3 style="margin:0;">Giỏ đăng ký</h3>
            <div class="muted">Năm học {{ $year }} - {{ $term }}</div>
        </div>
        <div>
            <span class="badge ok">Tổng tín chỉ (đã đăng ký + giỏ): {{ $totalCredits }}</span>
        </div>
    </div>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Mã lớp</th>
                <th>Môn học</th>
                <th>Lịch học</th>
                <th>Phòng</th>
                <th>Tình trạng</th>
                <th class="text-end">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sections as $s)
            @php($errs = $issues[$s->id] ?? [])
            <tr>
                <td><code>{{ $s->section_code }}</code></td>
                <td>{{ $s->course->code }} - {{ $s->course->name }} ({{ $s->course->credits }} TC)</td>
                <td>
                    <span class="badge info">{{ $s->shift->day_name ?? 'Thứ '.$s->day_of_week }}</span>
                    <div class="muted">Tiết {{ $s->shift->start_period }}-{{ $s->shift->end_period }}</div>
                </td>
                <td>{{ $s->room->code }}</td>
                <td>
                    @if(!empty($errs))
                    @foreach($errs as $e)
                    <div class="badge danger" style="display:inline-block;margin-bottom:4px;">{{ $e }}</div>
                    @endforeach
                    @else
                    <span class="badge ok">Sẵn sàng</span>
                    @endif
                </td>
                <td style="text-align:right;">
                    <form action="{{ route('student.cart.remove', $s) }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="btn" style="background:#ef4444" type="submit">Bỏ khỏi giỏ</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="muted">Giỏ trống. Hãy thêm lớp từ trang Tra cứu học phần.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($sections->count())
    <div style="margin-top:12px;display:flex;justify-content:flex-end;gap:10px;">
        <a class="btn" href="{{ route('student.offerings') }}">Tiếp tục chọn môn</a>
        <form action="{{ route('student.cart.checkout') }}" method="POST">
            @csrf
            <button class="btn" type="submit">Xác nhận đăng ký</button>
        </form>
    </div>
    @endif
</div>
@endsection