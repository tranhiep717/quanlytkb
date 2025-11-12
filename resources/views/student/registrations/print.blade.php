@extends('student.layout')

@section('title','In phiếu đăng ký')

@section('content')
<div class="card" id="print-area">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h2 style="margin:0;">Phiếu đăng ký môn học</h2>
            <div class="muted">Năm học {{ $year }} - {{ $term }}</div>
        </div>
        <div>
            <button class="btn" onclick="window.print()">In phiếu</button>
        </div>
    </div>

    <table style="margin-top:12px;">
        <thead>
            <tr>
                <th>Mã lớp</th>
                <th>Môn học</th>
                <th>Tín chỉ</th>
                <th>Thời gian</th>
                <th>Phòng</th>
            </tr>
        </thead>
        <tbody>
            @foreach($regs as $r)
            @php($s = $r->classSection)
            <tr>
                <td>{{ $s->section_code }}</td>
                <td>{{ $s->course->code }} - {{ $s->course->name }}</td>
                <td>{{ $s->course->credits }}</td>
                <td>{{ $s->shift->day_name ?? ('Thứ '.$s->day_of_week) }} | Tiết {{ $s->shift->start_period }}-{{ $s->shift->end_period }}</td>
                <td>{{ $s->room->code }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top:10px;">
        <strong>Tổng tín chỉ:</strong> {{ $credits }}
    </div>
</div>

<style>
    @media print {
        body {
            background: #fff;
            color: #000
        }

        .nav,
        .btn {
            display: none !important
        }

        .card {
            border: none;
            margin: 0;
            padding: 0
        }

        .container {
            max-width: 100%;
            padding: 0
        }
    }
</style>
@endsection