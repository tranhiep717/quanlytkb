<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>In TKB Lớp: {{ $classSection->section_code }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    @media print {
      .no-print {
        display: none !important;
      }
    }
  </style>
</head>

<body onload="setTimeout(()=>window.print(), 300)">
  <div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Thời khóa biểu lớp: {{ $classSection->section_code }}</h4>
      <button class="btn btn-sm btn-secondary no-print" onclick="window.print()">In</button>
    </div>

    <div class="card mb-3">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <p class="mb-1"><strong>Môn học:</strong> {{ $classSection->course->code }} - {{ $classSection->course->name }}</p>
            <p class="mb-1"><strong>Khoa:</strong> {{ $classSection->course->faculty->name ?? '--' }}</p>
            <p class="mb-1"><strong>Sĩ số:</strong> {{ $classSection->current_enrollment }}/{{ $classSection->max_capacity }}</p>
          </div>
          <div class="col-md-6">
            <p class="mb-1"><strong>Năm học - Học kỳ:</strong> {{ $classSection->academic_year }} - {{ $classSection->term }}</p>
            <p class="mb-1"><strong>Lịch học:</strong> Thứ {{ $classSection->day_of_week }}, @if($classSection->shift) Tiết {{ $classSection->shift->start_period }}-{{ $classSection->shift->end_period }} ({{ $classSection->shift->start_time }}-{{ $classSection->shift->end_time }}) @else TBA @endif</p>
            <p class="mb-1"><strong>Phòng:</strong> {{ $classSection->room->code ?? 'Chưa xếp phòng' }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">Danh sách Sinh viên</div>
      <div class="card-body p-0">
        <table class="table table-sm table-bordered mb-0">
          <thead class="table-light">
            <tr>
              <th style="width: 60px;">STT</th>
              <th style="width: 140px;">MSSV</th>
              <th>Họ tên</th>
              <th>Email</th>
            </tr>
          </thead>
          <tbody>
            @php $i=1; @endphp
            @foreach(\App\Models\Registration::where('class_section_id', $classSection->id)->whereIn('status', ['approved','registered'])->with('student')->orderBy('created_at')->get() as $reg)
            <tr>
              <td class="text-center">{{ $i++ }}</td>
              <td>{{ $reg->student->code }}</td>
              <td>{{ $reg->student->name }}</td>
              <td>{{ $reg->student->email }}</td>
            </tr>
            @endforeach
            @if($i===1)
            <tr>
              <td colspan="4" class="text-center text-muted">Chưa có sinh viên đăng ký.</td>
            </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>

</html>