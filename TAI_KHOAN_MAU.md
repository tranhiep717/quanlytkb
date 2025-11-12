# 📋 THÔNG TIN TÀI KHOẢN MẪU

## 🎯 Truy cập hệ thống
**URL:** http://127.0.0.1:8000

---

## 👨‍💼 TÀI KHOẢN ADMIN
- **Email:** `admin@dktc.edu.vn`
- **Password:** `admin123`
- **Quyền:** Toàn quyền quản trị hệ thống

---

## 👨‍🏫 TÀI KHOẢN GIẢNG VIÊN

### Giảng viên 1
- **Họ tên:** Nguyễn Văn Giảng
- **Email:** `giang@dktc.edu.vn`
- **Password:** `giang123`
- **Khoa:** Công nghệ Thông tin
- **Học vị:** Tiến sĩ

### Giảng viên 2
- **Họ tên:** Trần Thị Hương
- **Email:** `huong@dktc.edu.vn`
- **Password:** `huong123`
- **Khoa:** Điện tử - Viễn thông
- **Học vị:** Thạc sĩ

---

## 🎓 TÀI KHOẢN SINH VIÊN

### Sinh viên 1
- **Họ tên:** Hoàng Văn Cường
- **Mã SV:** 12345
- **Email:** `cuong@student.dktc.edu.vn`
- **Password:** `cuong123`
- **Khoa:** Công nghệ Thông tin
- **Khóa:** 2024
- **Đã đăng ký:** 2 lớp (IT101, IT201)

### Sinh viên 2
- **Họ tên:** Lê Thị Mai
- **Mã SV:** 12346
- **Email:** `mai@student.dktc.edu.vn`
- **Password:** `mai123`
- **Khoa:** Công nghệ Thông tin
- **Khóa:** 2024
- **Đã đăng ký:** 1 lớp (IT101)

### Sinh viên 3
- **Họ tên:** Phạm Minh Tuấn
- **Mã SV:** 12347
- **Email:** `tuan@student.dktc.edu.vn`
- **Password:** `tuan123`
- **Khoa:** Điện tử - Viễn thông
- **Khóa:** 2024
- **Đã đăng ký:** 1 lớp (EC101)

---

## 📚 DỮ LIỆU MẪU ĐÃ TẠO

### Khoa (2)
1. **CNTT** - Công nghệ Thông tin
2. **DTVT** - Điện tử - Viễn thông

### Học phần (3)
1. **IT101** - Lập trình hướng đối tượng (3 TC)
2. **IT201** - Cấu trúc dữ liệu và Giải thuật (4 TC)
3. **EC101** - Mạch điện tử (3 TC)

### Phòng học (3)
- **A101** - Phòng A101 (Tòa A, 50 chỗ)
- **A102** - Phòng A102 (Tòa A, 45 chỗ)
- **B201** - Phòng B201 (Tòa B, 40 chỗ)

### Ca học (3)
- **Ca 1:** 07:00 - 09:30 (Tiết 1-3)
- **Ca 2:** 09:45 - 12:15 (Tiết 4-6)
- **Ca 3:** 13:00 - 15:30 (Tiết 7-9)

### Lớp học phần (3)
1. **IT101-01** - Thứ 2, Ca 1, Phòng A101 (GV: Nguyễn Văn Giảng)
2. **IT201-01** - Thứ 3, Ca 2, Phòng A102 (GV: Nguyễn Văn Giảng)
3. **EC101-01** - Thứ 4, Ca 1, Phòng B201 (GV: Trần Thị Hương)

### Đợt đăng ký
- **Đợt đăng ký chính HK1 2024-2025**
- Thời gian: Đang mở (từ 5 ngày trước đến 10 ngày sau)

---

## 🔧 THAO TÁC QUẢN TRỊ

### Xem database trong phpMyAdmin
1. Mở: http://localhost/phpmyadmin
2. Click vào database `quanlytkbieu` bên trái
3. Xem các bảng và dữ liệu

### Reset lại dữ liệu mẫu
```bash
php artisan migrate:fresh --seed
```

### Tạo thêm tài khoản mới
Sử dụng chức năng Admin hoặc chạy trong Tinker:
```bash
php artisan tinker
```

---

## 📝 GHI CHÚ
- Tất cả mật khẩu đều theo định dạng: `<tên>123`
- Email admin: `admin@dktc.edu.vn`
- Email giảng viên: `<tên>@dktc.edu.vn`
- Email sinh viên: `<tên>@student.dktc.edu.vn`
