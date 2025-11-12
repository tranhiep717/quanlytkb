<div class="modal fade" id="classDetailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(135deg, #1976d2, #64b5f6); color: #fff;">
        <h5 class="modal-title">
          <i class="fas fa-users-class me-2"></i>
          Chi tiết Lớp học phần: <span id="cdm-title">--</span>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Phần 1: Thông tin lớp học -->
        <div class="mb-3">
          <h6 class="mb-2"><i class="fas fa-info-circle me-2 text-primary"></i>Thông tin Lớp học</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <ul class="list-group list-group-flush">
                <li class="list-group-item px-0 d-flex justify-content-between"><span class="text-muted">Học phần:</span><span class="fw-semibold" id="cdm-course">--</span></li>
                <li class="list-group-item px-0 d-flex justify-content-between"><span class="text-muted">Khoa:</span><span id="cdm-faculty">--</span></li>
                <li class="list-group-item px-0 d-flex justify-content-between"><span class="text-muted">Mã lớp:</span><span class="badge bg-success" id="cdm-section">--</span></li>
              </ul>
            </div>
            <div class="col-md-6">
              <ul class="list-group list-group-flush">
                <li class="list-group-item px-0 d-flex justify-content-between"><span class="text-muted">Năm học - Học kỳ:</span><span id="cdm-year-term">--</span></li>
                <li class="list-group-item px-0 d-flex justify-content-between"><span class="text-muted">Lịch học:</span><span id="cdm-schedule">--</span></li>
                <li class="list-group-item px-0 d-flex justify-content-between"><span class="text-muted">Phòng:</span><span id="cdm-room">--</span></li>
                <li class="list-group-item px-0 d-flex justify-content-between"><span class="text-muted">Sĩ số:</span><span id="cdm-enrollment">--</span></li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Phần 2: Danh sách sinh viên -->
        <div>
          <h6 class="mb-2"><i class="fas fa-user-graduate me-2 text-primary"></i>Danh sách Sinh viên</h6>
          <div id="cdm-empty" class="alert alert-secondary d-none"><i class="fas fa-circle-info me-1"></i>Chưa có sinh viên đăng ký.</div>
          <div class="table-responsive" style="max-height: 360px;">
            <table class="table table-hover align-middle mb-0" id="cdm-table">
              <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                <tr>
                  <th style="width:70px;">STT</th>
                  <th style="width:140px;">MSSV</th>
                  <th>Họ tên</th>
                  <th>Email học vụ</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-between">
        <div>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
        </div>
        <!-- Phần 3: Tác vụ -->
        <div>
          <a id="cdm-export-csv" class="btn btn-success me-2" href="#" target="_blank">
            <i class="fas fa-file-excel me-1"></i>Tải danh sách SV (CSV)
          </a>
          <a id="cdm-print" class="btn btn-primary" href="#" target="_blank">
            <i class="fas fa-file-pdf me-1"></i>Xuất TKB lớp (PDF)
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const dayMap = {
    1: 'Thứ 2',
    2: 'Thứ 3',
    3: 'Thứ 4',
    4: 'Thứ 5',
    5: 'Thứ 6',
    6: 'Thứ 7',
    7: 'Chủ nhật'
  };
  let cdmModalInstance = null;

  function openClassDetail(sectionId) {
    const modalEl = document.getElementById('classDetailModal');
    if (!cdmModalInstance) {
      cdmModalInstance = new bootstrap.Modal(modalEl);
    }

    // Reset UI
    document.getElementById('cdm-title').textContent = '--';
    document.getElementById('cdm-course').textContent = '--';
    document.getElementById('cdm-faculty').textContent = '--';
    document.getElementById('cdm-section').textContent = '--';
    document.getElementById('cdm-year-term').textContent = '--';
    document.getElementById('cdm-schedule').textContent = '--';
    document.getElementById('cdm-room').textContent = '--';
    document.getElementById('cdm-enrollment').textContent = '--';
    document.getElementById('cdm-export-csv').href = '#';
    document.getElementById('cdm-print').href = '#';
    const tbody = document.querySelector('#cdm-table tbody');
    tbody.innerHTML = '';
    document.getElementById('cdm-empty').classList.add('d-none');

    cdmModalInstance.show();

    fetch(`${window.location.origin}/lecturer/classes/${sectionId}/detail-json`, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(r => {
        if (!r.ok) throw new Error('Không tải được dữ liệu lớp.');
        return r.json();
      })
      .then(data => {
        document.getElementById('cdm-title').textContent = `${data.course_code} - ${data.course_name}`;
        document.getElementById('cdm-course').textContent = `${data.course_code} - ${data.course_name}`;
        document.getElementById('cdm-faculty').textContent = data.faculty || '--';
        document.getElementById('cdm-section').textContent = data.section_code;
        const termLabel = data.term === 'HK1' ? 'Học kỳ 1' : (data.term === 'HK2' ? 'Học kỳ 2' : 'Học kỳ Hè');
        document.getElementById('cdm-year-term').textContent = `${data.academic_year} - ${termLabel}`;
        document.getElementById('cdm-schedule').textContent = `${dayMap[data.day_of_week] || '---'}, ${data.shift_label}`;
        document.getElementById('cdm-room').textContent = data.room;
        document.getElementById('cdm-enrollment').textContent = `${data.enrollment.current} / ${data.enrollment.max}`;
        document.getElementById('cdm-export-csv').href = `${window.location.origin}/lecturer/classes/${data.id}/students.csv`;
        document.getElementById('cdm-print').href = `${window.location.origin}/lecturer/classes/${data.id}/schedule/print`;

        if (!data.students || data.students.length === 0) {
          document.getElementById('cdm-empty').classList.remove('d-none');
        } else {
          for (const s of data.students) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
              <td class="text-center">${s.stt}</td>
              <td><code class="text-primary">${s.mssv}</code></td>
              <td class="fw-semibold">${s.name}</td>
              <td><a href="mailto:${s.email}" class="text-decoration-none"><i class="fas fa-envelope me-1"></i>${s.email}</a></td>
            `;
            tbody.appendChild(tr);
          }
        }
      })
      .catch(err => {
        const tbody = document.querySelector('#cdm-table tbody');
        tbody.innerHTML = `<tr><td colspan="4" class="text-danger">${err.message}</td></tr>`;
      });
  }
</script>