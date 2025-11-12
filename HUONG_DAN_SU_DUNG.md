# 🎓 HƯỚNG DẪN SỬ DỤNG HỆ THỐNG QUẢN LÝ ĐĂNG KÝ TÍN CHỈ

## 📋 MỤC LỤC
1. [Tài khoản mẫu](#tài-khoản-mẫu)
2. [Chức năng đã hoàn thành](#chức-năng-đã-hoàn-thành)
3. [Cấu hình Email](#cấu-hình-email)
4. [Hướng dẫn sử dụng Admin](#hướng-dẫn-sử-dụng-admin)
5. [Kiểm tra hệ thống](#kiểm-tra-hệ-thống)

---

## 👥 TÀI KHOẢN MẪU

### Tài khoản Admin
- **Email**: `admin@dangkytinchi.edu.vn`
- **Mật khẩu**: `Admin@123`
- **Vai trò**: Super Admin (có quyền truy cập tất cả chức năng)

### Tài khoản Giảng viên
1. **GV001 - Trần Văn A**
   - Email: `giangvien1@dangkytinchi.edu.vn`
   - Mật khẩu: `Gv123456@`
   
2. **GV002 - Nguyễn Thị B**
   - Email: `giangvien2@dangkytinchi.edu.vn`
   - Mật khẩu: `Gv123456@`

### Tài khoản Sinh viên
1. **SV001 - Lê Minh C** (CNTT, K19)
   - Email: `sinhvien1@dangkytinchi.edu.vn`
   - Mật khẩu: `Sv123456@`
   
2. **SV002 - Phạm Thu D** (CNTT, K18)
   - Email: `sinhvien2@dangkytinchi.edu.vn`
   - Mật khẩu: `Sv123456@`
   
3. **SV003 - Hoàng Nam E** (KT, K19)
   - Email: `sinhvien3@dangkytinchi.edu.vn`
   - Mật khẩu: `Sv123456@`
   
4. **SV004 - Đỗ Mai F** (KT, K18)
   - Email: `sinhvien4@dangkytinchi.edu.vn`
   - Mật khẩu: `Sv123456@`

---

## ✅ CHỨC NĂNG ĐÃ HOÀN THÀNH

### 🔐 Xác thực (UC1.1, UC1.2, UC1.3)
- ✅ **Đăng nhập** với thông báo lỗi cụ thể
  - "Email và/hoặc mật khẩu chưa đúng" khi sai thông tin
  - Rate limiting: 5 lần/5 phút
  
- ✅ **Quên mật khẩu** với email reset
  - "Không tìm thấy tài khoản với email này." nếu email không tồn tại
  - Token hết hạn sau 60 phút
  - Gửi email chứa link reset
  
- ✅ **Đổi mật khẩu** với xác thực mật khẩu cũ
  - Chính sách mật khẩu mạnh (8+ ký tự, chữ hoa, chữ thường, số, ký tự đặc biệt)
  - Vô hiệu hóa sessions cũ

### 👨‍💼 Quản lý Người dùng (U-1 đến U-6)
- ✅ **Danh sách người dùng** với bộ lọc
  - Tìm kiếm theo tên, email, mã
  - Lọc theo vai trò, trạng thái, khoa, khóa
  - Phân trang
  
- ✅ **Thêm/Sửa người dùng**
  - Chọn vai trò: Super Admin, Faculty Admin, Lecturer, Student
  - Gán khoa (cho sinh viên/giảng viên)
  - Gán khóa (cho sinh viên)
  
- ✅ **Khóa/Mở khóa tài khoản**
  - Nút khóa/mở trực tiếp trên danh sách
  - Ghi log hành động
  
- ✅ **Reset mật khẩu**
  - Admin có thể reset mật khẩu cho bất kỳ user nào
  - Gửi email chứa link reset

### 🏢 Quản lý Dữ liệu Đào tạo (A-1, A-2, A-3, A-4)

#### Khoa (Faculty)
- ✅ Thêm/Sửa/Xóa khoa
- ✅ Hiển thị số lượng người dùng thuộc khoa
- ✅ Kiểm tra ràng buộc khi xóa

#### Phòng học (Room)
- ✅ Quản lý phòng với mã, tòa nhà, sức chứa
- ✅ Kiểm tra phòng đang được sử dụng khi xóa

#### Ca học (Study Shift)
- ✅ Quản lý ca học theo thứ và tiết
- ✅ Hiển thị thời gian ca học (VD: Thứ 2, Tiết 1-3)

#### Môn học (Course)
- ✅ Thêm/Sửa/Xóa môn học
- ✅ **Quản lý môn tiên quyết** (prerequisites)
- ✅ Multi-select cho môn tiên quyết
- ✅ Hiển thị badge cho môn tiên quyết

#### Lớp học phần (Class Section)
- ✅ Tạo lớp học phần với đầy đủ thông tin
- ✅ **Kiểm tra xung đột (A-4)**:
  - ❌ Mã lớp trùng trong cùng năm học/học kỳ
  - ❌ Giảng viên dạy 2 lớp cùng thứ/ca
  - ❌ Phòng học bị trùng cùng thứ/ca
- ✅ Hiển thị sĩ số hiện tại/tối đa
- ✅ Lọc theo năm học, học kỳ, khoa

### ⏰ Đợt Đăng ký (S-1)
- ✅ Tạo đợt đăng ký với:
  - Năm học, học kỳ
  - Thời gian bắt đầu/kết thúc
  - **Đối tượng được đăng ký**: Chọn nhiều khoa và khóa
- ✅ Hiển thị trạng thái: Sắp diễn ra / Đang mở / Đã kết thúc
- ✅ Badge màu sắc cho đối tượng

### 📊 Báo cáo & Logs (S-2, S-3)
- ✅ **Báo cáo đăng ký**
  - Lọc theo năm học, học kỳ, khoa, khóa
  - Hiển thị chi tiết đăng ký (sinh viên, môn học, giảng viên)
  - Nút xuất file (placeholder)
  
- ✅ **Nhật ký hệ thống**
  - Ghi log tất cả hành động admin
  - Hiển thị timestamp, user, action, metadata
  - Action labels với emoji

### 📧 Email Configuration
- ✅ Cấu hình Gmail SMTP
- ✅ Hướng dẫn tạo App Password
- ✅ Script test email
- ✅ Template email reset password đẹp

---

## 📧 CẤU HÌNH EMAIL (BẮT BUỘC)

### Bước 1: Lấy App Password từ Gmail

1. Đăng nhập Gmail: https://myaccount.google.com
2. Vào **Security** → **2-Step Verification** (bật nếu chưa có)
3. Cuộn xuống **App passwords**
4. Tạo mới:
   - Select app: **Mail**
   - Select device: **Other** (nhập: "Laravel DKTC")
5. Copy mật khẩu 16 ký tự (VD: `abcd efgh ijkl mnop`)

### Bước 2: Cập nhật file `.env`

Mở file `.env` và tìm dòng:
```env
MAIL_PASSWORD=your_app_password_here
```

Thay bằng App Password vừa lấy (bỏ khoảng trắng):
```env
MAIL_PASSWORD=abcdefghijklmnop
```

### Bước 3: Xóa cache và test

```powershell
php artisan config:clear
php test-email.php
```

Nếu thành công, bạn sẽ nhận email test tại `hieptran19102005@gmail.com`

📖 **Chi tiết**: Xem file `HUONG_DAN_CAU_HINH_GMAIL.md`

---

## 🛠️ HƯỚNG DẪN SỬ DỤNG ADMIN

### 1️⃣ Đăng nhập Admin

```
URL: http://localhost/quanlytkbieu/public/login
Email: admin@dangkytinchi.edu.vn
Mật khẩu: Admin@123
```

### 2️⃣ Quản lý Khoa

1. Vào **Admin** → **Khoa**
2. Click **Thêm Khoa**
3. Nhập:
   - Mã khoa (VD: `CNTT`, `KT`)
   - Tên khoa (VD: `Công nghệ Thông tin`)
4. Click **Lưu**

**Dữ liệu mẫu đã có**:
- CNTT - Công nghệ Thông tin
- KT - Kinh tế

### 3️⃣ Quản lý Phòng học

1. Vào **Admin** → **Phòng học**
2. Click **Thêm Phòng học**
3. Nhập:
   - Mã phòng (VD: `A101`)
   - Tòa nhà (VD: `Nhà A`)
   - Sức chứa (VD: `50`)

**Dữ liệu mẫu đã có**:
- A101, A102, A103 (Nhà A)
- B201, B202 (Nhà B)
- C301 (Nhà C)
- LAB01, LAB02 (Phòng Máy)

### 4️⃣ Quản lý Ca học

1. Vào **Admin** → **Ca học**
2. Click **Thêm Ca học**
3. Chọn:
   - Thứ (2-7)
   - Tiết bắt đầu (1-15)
   - Tiết kết thúc (1-15)

**Dữ liệu mẫu đã có**: 15 ca học (Thứ 2-6, mỗi thứ 3 ca: sáng/chiều/tối)

### 5️⃣ Quản lý Môn học

1. Vào **Admin** → **Môn học**
2. Click **Thêm Môn học**
3. Nhập:
   - Mã môn (VD: `IT001`)
   - Tên môn (VD: `Nhập môn Lập trình`)
   - Số tín chỉ (1-10)
   - Chọn khoa
   - **Chọn môn tiên quyết** (giữ Ctrl để chọn nhiều)

**Dữ liệu mẫu đã có**:
- CNTT: IT001, IT002, IT003, IT004, IT005, IT006, IT007, IT008
- KT: EC001, EC002, EC003, EC004, EC005

**Môn tiên quyết đã thiết lập**:
- IT002 (Lập trình OOP) cần IT001 (Nhập môn Lập trình)
- IT003 (CTDL&GT) cần IT001
- IT004 (CSDL) cần IT002

### 6️⃣ Quản lý Lớp học phần

1. Vào **Admin** → **Lớp học phần**
2. Click **Thêm Lớp học phần**
3. Nhập:
   - Năm học (VD: `2024-2025`)
   - Học kỳ (HK1/HK2/HK3)
   - Chọn môn học
   - Mã lớp (VD: `IT001.01`)
   - Chọn giảng viên
   - Chọn thứ
   - Chọn ca học
   - Chọn phòng
   - Sĩ số tối đa

⚠️ **Hệ thống tự động kiểm tra xung đột**:
- Mã lớp trùng
- Giảng viên trùng lịch
- Phòng học trùng lịch

**Dữ liệu mẫu đã có**: 3 lớp học phần (IT001.01, IT002.01, IT003.01)

### 7️⃣ Quản lý Đợt đăng ký

1. Vào **Admin** → **Đợt đăng ký**
2. Click **Thêm Đợt đăng ký**
3. Nhập:
   - Năm học, học kỳ
   - Tên đợt (VD: `Đợt 1 - Ưu tiên Khóa cũ`)
   - Thời gian bắt đầu/kết thúc
   - **Tick chọn các khoa** được phép đăng ký
   - **Tick chọn các khóa** (để trống = tất cả)

**Dữ liệu mẫu đã có**: 2 đợt đăng ký cho HK1 2024-2025

### 8️⃣ Quản lý Người dùng

1. Vào **Admin** → **Người dùng**
2. Click **Thêm Người dùng**
3. Nhập thông tin:
   - Mã, Họ tên, Email
   - Chọn **Vai trò**:
     - `super_admin`: Toàn quyền
     - `faculty_admin`: Quản lý khoa
     - `lecturer`: Giảng viên
     - `student`: Sinh viên
   - Chọn khoa (với lecturer/student)
   - Nhập khóa (với student, VD: `K19`)

**Thao tác**:
- 🔒 **Khóa tài khoản**: Click nút khóa màu đỏ
- 🔓 **Mở khóa**: Click nút xanh
- 🔑 **Reset mật khẩu**: Gửi email reset cho user

### 9️⃣ Xem Báo cáo & Logs

**Báo cáo đăng ký**:
1. Vào **Admin** → **Báo cáo**
2. Lọc theo năm học, học kỳ, khoa, khóa
3. Click **Xuất báo cáo** (chức năng đang phát triển)

**Nhật ký hệ thống**:
1. Vào **Admin** → **Nhật ký**
2. Xem tất cả hành động: Tạo, Sửa, Xóa, Khóa, Reset password...

---

## 🧪 KIỂM TRA HỆ THỐNG

### Test 1: Đăng nhập sai thông tin
```
1. Vào /login
2. Nhập email sai → "Email và/hoặc mật khẩu chưa đúng."
3. Nhập đúng email, sai password → "Email và/hoặc mật khẩu chưa đúng."
4. Thử sai 5 lần → Rate limit block 5 phút
```

### Test 2: Quên mật khẩu
```
1. Vào /forgot-password
2. Nhập email không tồn tại → "Không tìm thấy tài khoản với email này."
3. Nhập email hợp lệ → Nhận email reset (kiểm tra hieptran19102005@gmail.com)
4. Click link trong email → Form reset mật khẩu
5. Nhập mật khẩu mới (phải đủ mạnh)
6. Đăng nhập bằng mật khẩu mới
```

### Test 3: Thêm lớp học phần với xung đột
```
1. Admin → Lớp học phần → Thêm
2. Tạo lớp: IT001.02, Thứ 2, Ca 1-3, Phòng A101, GV: giangvien1
3. → Lỗi: "Giảng viên đã có lịch dạy vào Thứ 2, ca 1-3" (vì đã có IT001.01)
4. Đổi GV thành giangvien2 → Thành công
5. Thử tạo lớp khác cùng Thứ 2, Ca 1-3, Phòng A101
6. → Lỗi: "Phòng A101 đã được sử dụng vào Thứ 2, ca 1-3"
```

### Test 4: Môn tiên quyết
```
1. Admin → Môn học → Sửa IT004 (CSDL)
2. Thêm IT003 vào prerequisites (giữ Ctrl, chọn IT002 và IT003)
3. Lưu → IT004 giờ cần cả IT002 và IT003
4. Xem danh sách → Badge hiển thị IT002, IT003
```

### Test 5: Đợt đăng ký
```
1. Admin → Đợt đăng ký → Thêm
2. Tạo đợt: "Đợt 3 - Tất cả sinh viên"
3. Chọn khoa: CNTT, KT
4. Không chọn khóa nào (= tất cả khóa)
5. Thời gian: Ngày mai 8h → 7 ngày sau 23h59
6. Lưu → Trạng thái "Sắp diễn ra" (badge vàng)
7. Sau khi đến thời gian → "Đang mở" (badge xanh)
```

---

## 📂 CẤU TRÚC DỰ ÁN

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php          # UC1.1, UC1.2, UC1.3
│   │   ├── AdminController.php         # U-1 to U-6, S-2, S-3
│   │   ├── FacultyController.php       # A-1: Quản lý Khoa
│   │   ├── RoomController.php          # A-1: Quản lý Phòng
│   │   ├── ShiftController.php         # A-1: Quản lý Ca học
│   │   ├── CourseController.php        # A-2: Quản lý Môn học + Prerequisites
│   │   ├── ClassSectionController.php  # A-3, A-4: Quản lý Lớp + Kiểm tra xung đột
│   │   └── RegistrationWaveController.php  # S-1: Đợt đăng ký
│   └── Middleware/
│       ├── EnsureAdmin.php             # Middleware admin
│       └── EnsureRole.php              # Middleware role-based
└── Models/
    ├── User.php
    ├── Faculty.php
    ├── Course.php
    ├── Room.php
    ├── StudyShift.php
    ├── ClassSection.php
    ├── RegistrationWave.php
    └── LogEntry.php

resources/views/
├── login.blade.php                     # UC1.1
├── forgot-password.blade.php           # UC1.2
├── reset-password.blade.php            # UC1.2
├── change-password.blade.php           # UC1.3
└── admin/
    ├── layout.blade.php                # Layout chính
    ├── dashboard.blade.php             # Dashboard admin
    ├── users/                          # U-1 to U-6
    ├── faculties/                      # A-1
    ├── rooms/                          # A-1
    ├── shifts/                         # A-1
    ├── courses/                        # A-2
    ├── class-sections/                 # A-3, A-4
    ├── registration-waves/             # S-1
    ├── reports/                        # S-2
    └── logs/                           # S-3

database/
├── migrations/
│   └── [Batch 2] 10 migration files    # Toàn bộ schema
└── seeders/
    ├── UserSeeder.php                  # 7 tài khoản test
    └── TrainingDataSeeder.php          # Dữ liệu mẫu đầy đủ

routes/
└── web.php                             # Tất cả routes
```

---

## 🔥 KHỞI ĐỘNG HỆ THỐNG

### Lần đầu sử dụng:

```powershell
# 1. Chạy migrations (nếu chưa)
php artisan migrate

# 2. Seed dữ liệu mẫu
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=TrainingDataSeeder

# 3. Cấu hình email (xem phần Cấu hình Email ở trên)

# 4. Khởi động server
php artisan serve
```

### Truy cập:

```
🌐 Trang chủ:     http://localhost:8000
🔐 Đăng nhập:     http://localhost:8000/login
👨‍💼 Admin:        http://localhost:8000/admin/dashboard
```

---

## 💡 LƯU Ý QUAN TRỌNG

1. ⚠️ **Email chưa hoạt động** cho đến khi bạn thêm **Gmail App Password** vào `.env`
2. 🔒 Mật khẩu mạnh bắt buộc: 8+ ký tự, chữ hoa, chữ thường, số, ký tự đặc biệt
3. 📧 Reset password token **hết hạn sau 60 phút**
4. 🚫 Rate limiting: **5 lần login** / 5 phút, **10 lần reset** / 60 giây
5. 🎓 Môn tiên quyết: Giữ **Ctrl** (hoặc Cmd trên Mac) để chọn nhiều môn
6. ⚡ Kiểm tra xung đột **tự động** khi tạo/sửa lớp học phần

---

## 🐛 TROUBLESHOOTING

### Lỗi: "Class not found"
```powershell
composer dump-autoload
```

### Lỗi: "SQLSTATE[HY000]: General error"
```powershell
php artisan migrate:fresh --seed
```

### Email không gửi được
1. Kiểm tra `.env` có đúng App Password?
2. Chạy: `php artisan config:clear`
3. Test: `php test-email.php`

### Không thấy dữ liệu mẫu
```powershell
php artisan db:seed --class=TrainingDataSeeder
```

---

## 📞 HỖ TRỢ

- 📧 Email: hieptran19102005@gmail.com
- 📖 Xem thêm: `HUONG_DAN_CAU_HINH_GMAIL.md`

---

**Chúc bạn sử dụng hệ thống thành công! 🎉**
