# UC3.1 Testing Guide

## 🎯 Quick Test Scenarios

### Scenario 1: Filter by Faculty
1. Login as student (`sv001@dktc.edu.vn`)
2. Go to **Đăng ký học phần**
3. Select a faculty from **Khoa** dropdown
4. Click **Lọc**
5. ✅ **Expected**: Only courses from that faculty appear

### Scenario 2: Filter by Shift  
1. Select a shift from **Ca học** dropdown (e.g., "Tiết 1-3")
2. Click **Lọc**
3. ✅ **Expected**: Only sections with that shift appear

### Scenario 3: Only Available Classes
1. Check **"Chỉ hiển thị lớp còn chỗ"**
2. Click **Lọc**
3. ✅ **Expected**: Full classes (50/50) disappear from list

### Scenario 4: Register for Available Class
1. Find a class with **Đăng ký** button (green, enabled)
2. Click **Đăng ký**
3. ✅ **Expected**: 
   - Button shows ⏳ "Đang xử lý..."
   - Green toast appears: "Đăng ký thành công..."
   - Button changes to "✅ Đã đăng ký"
   - Enrolled count increases (e.g., 45/50 → 46/50)
   - Page reloads after 2 seconds
   - Right sidebar shows newly registered course

### Scenario 5: Already Registered Course
1. Find a course you already registered (different section)
2. ✅ **Expected**: Shows **Đổi lớp** button instead of "Đăng ký"

### Scenario 6: Full Class
1. Find a class with enrolled = max_capacity (50/50)
2. ✅ **Expected**: Shows disabled **Đã đầy** button (grayed out)

### Scenario 7: Schedule Conflict
**Setup**: Register for IT101.01 (Thứ 3, Tiết 1-3)

1. Try to register for IT101.02 (also Thứ 3, Tiết 1-3)
2. ✅ **Expected**: 
   - Shows disabled button: **⚠️ Trùng lịch**
   - Hover shows tooltip with conflicting course name

### Scenario 8: Missing Prerequisites
**Setup**: Create course with prerequisites not yet passed

1. Try to register for advanced course
2. ✅ **Expected**:
   - Shows disabled button: **🚫 Thiếu tiên quyết**
   - Hover shows tooltip listing missing courses

### Scenario 9: Cancel Registration
1. Go to **Đăng ký của tôi**
2. Click **Hủy** on a registered course
3. Confirm dialog
4. ✅ **Expected**: 
   - Course removed from "Học phần đã đăng ký"
   - Enrolled count decreases in offerings list
   - Can now register again

### Scenario 10: Error Handling
1. Try to register when wave is closed (or use locked account)
2. ✅ **Expected**:
   - Red toast appears with error message
   - Button re-enables
   - Can try again

## 🔍 Detailed Validation Tests

### Filter Combinations
```
1. Faculty + Shift → Only courses from faculty X with shift Y
2. Room + Only Available → Only available sections in room Z
3. Search + Faculty → Search within specific faculty
4. All filters at once → Correct intersection
```

### Status Priority
```
Priority order (highest first):
1. already_registered → ✅ Đã đăng ký
2. swap_available → Đổi lớp
3. prereq_missing → 🚫 Thiếu tiên quyết
4. conflict → ⚠️ Trùng lịch
5. full → Đã đầy
6. available → Đăng ký
```

### AJAX Edge Cases
```
1. Double-click prevention → Button disables immediately
2. Network error → Shows error toast, button re-enables
3. Session timeout → Redirects to login
4. Concurrent registration → Last-check catches capacity
```

## 🐛 Common Issues & Fixes

### Issue: Filter dropdown empty
**Cause**: Missing data in faculties/shifts/rooms  
**Fix**: Run seeders or check database

### Issue: All buttons show "Đăng ký" regardless of status
**Cause**: `$sectionStates` not passed to view  
**Fix**: Check controller returns `compact('sectionStates', ...)`

### Issue: Toast doesn't appear
**Cause**: CSRF token missing or JavaScript error  
**Fix**: Check browser console, ensure CSRF meta tag exists

### Issue: Button doesn't change after registration
**Cause**: JavaScript error in fetch handler  
**Fix**: Check Network tab for response, verify JSON format

### Issue: Schedule conflict not detected
**Cause**: Shift periods not set correctly  
**Fix**: Check `study_shifts` table has `start_period` and `end_period`

## 📊 Database State for Testing

### Recommended Test Data
```sql
-- Student with some registrations
INSERT INTO registrations (student_id, class_section_id)
VALUES (12346, 101), (12346, 155);

-- Create conflicting section
INSERT INTO class_sections (course_id, section_code, day_of_week, shift_id, ...)
VALUES (1001, 'IT101.02', 3, 1, ...); -- Same day & shift as IT101.01

-- Create full section
INSERT INTO class_sections (..., max_capacity) VALUES (..., 50);
INSERT INTO registrations (class_section_id, ...) 
SELECT 210, ... FROM generate_series(1, 50); -- Fill to capacity

-- Course with prerequisites
INSERT INTO course_prerequisites (course_id, prerequisite_course_id)
VALUES (1005, 1001), (1005, 1002);
```

## ✅ Acceptance Criteria Checklist

### UC3.1 Requirements
- [x] **R1**: Tra cứu với bộ lọc mở rộng (Khoa, Ca, Phòng)
- [x] **R2**: Checkbox "Chỉ hiển thị lớp còn chỗ"
- [x] **R3**: Hiển thị trạng thái từng lớp (Đầy, Trùng lịch, etc.)
- [x] **R4**: Nút "Đăng ký" chỉ enabled khi hợp lệ
- [x] **R5**: Kiểm tra tiên quyết (Luồng 2a)
- [x] **R6**: Kiểm tra trùng lịch (Luồng 2b)
- [x] **R7**: Kiểm tra giới hạn tín chỉ (Luồng 2c)
- [x] **R8**: Kiểm tra trùng học phần (Luồng 2d)
- [x] **R9**: Kiểm tra hết chỗ (Luồng 2e)
- [x] **R10**: Thông báo toast cho kết quả (Luồng 4)
- [x] **R11**: Không reload trang khi đăng ký
- [x] **R12**: Cập nhật giao diện real-time sau đăng ký

### UI/UX Requirements
- [x] Visual feedback cho mọi action
- [x] Icons phân biệt trạng thái
- [x] Tooltip giải thích lỗi
- [x] Loading state khi xử lý
- [x] Auto-dismiss notifications

## 🚀 Performance Targets

- Filter response: < 500ms
- Registration AJAX: < 1s
- Toast animation: smooth 60fps
- Page load: < 2s with 100 sections

---

**Last Updated**: November 12, 2025  
**Test Coverage**: UC3.1 Complete
