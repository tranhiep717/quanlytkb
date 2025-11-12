<div class="modal fade" id="studentClassDetailModal" tabindex="-1" aria-labelledby="studentClassDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentClassDetailLabel">Chi tiết lớp học phần</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small">Mã học phần</div>
                                <div class="fw-bold" id="sdm-course-code">--</div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-success" id="sdm-section-code">--</span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="text-muted small">Tên học phần</div>
                            <div class="fs-6" id="sdm-course-name">--</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Ngày - Ca</div>
                        <div id="sdm-shift">--</div>
                        <div class="text-muted small">Giờ học</div>
                        <div id="sdm-time">--</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Phòng</div>
                        <div id="sdm-room">--</div>
                        <div class="text-muted small">Giảng viên</div>
                        <div id="sdm-lecturer">--</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Sĩ số</div>
                        <div id="sdm-enrollment">--</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<script>
    function openStudentClassDetail(sectionId) {
        const url = `${window.location.origin}/student/timetable/classes/${sectionId}/detail-json`;
        fetch(url, {
                credentials: 'same-origin'
            })
            .then(r => {
                if (!r.ok) throw new Error('Không thể tải chi tiết lớp.');
                return r.json();
            })
            .then(data => {
                document.getElementById('sdm-course-code').textContent = `${data.course_code}`;
                document.getElementById('sdm-course-name').textContent = `${data.course_name}`;
                document.getElementById('sdm-section-code').textContent = data.section_code;
                const dayMap = ['', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];
                const day = dayMap[data.day_of_week] || '';
                document.getElementById('sdm-shift').textContent = `${day} • ${data.shift_label}`;
                document.getElementById('sdm-time').textContent = data.time || '';
                document.getElementById('sdm-room').textContent = data.room || '';
                document.getElementById('sdm-lecturer').textContent = data.lecturer || '';
                document.getElementById('sdm-enrollment').textContent = `${data.enrollment.current}/${data.enrollment.max}`;
                const modal = new bootstrap.Modal(document.getElementById('studentClassDetailModal'));
                modal.show();
            })
            .catch(err => {
                alert(err.message || 'Đã xảy ra lỗi.');
            });
    }
</script>