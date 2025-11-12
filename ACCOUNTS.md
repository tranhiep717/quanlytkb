# 🔐 Tài Khoản & Mật Khẩu Hệ Thống Đăng Ký Tín Chỉ

## 📋 Danh Sách Tài Khoản

### 👨‍💼 Quản Trị Viên (Admin)

| Tên | Email | Mật Khẩu |
|-----|-------|----------|
| Quản Trị Viên | `admin@dangkytinchi.edu.vn` | `admin123456` |

**Quyền:** Quản lý toàn bộ hệ thống

---

### 👨‍🏫 Giảng Viên (Lecturer)

| STT | Tên | Email | Mật Khẩu |
|-----|-----|-------|----------|
| 1 | Nguyễn Văn Giảng | `giangvien1@dangkytinchi.edu.vn` | `giang123456` |
| 2 | Trần Thị Hương | `giangvien2@dangkytinchi.edu.vn` | `giang123456` |

**Quyền:** Quản lý môn học, xem danh sách học viên

---

### 🎓 Học Viên (Student)

| STT | Tên | Email | Mật Khẩu |
|-----|-----|-------|----------|
| 1 | Lê Văn An | `hocvien1@dangkytinchi.edu.vn` | `hocvien123` |
| 2 | Phạm Thị Bình | `hocvien2@dangkytinchi.edu.vn` | `hocvien123` |
| 3 | Hoàng Văn Cường | `hocvien3@dangkytinchi.edu.vn` | `hocvien123` |

**Quyền:** Đăng ký môn học, xem thời khóa biểu

---

### 🧪 Tài Khoản Test

| Tên | Email | Mật Khẩu |
|-----|-------|----------|
| Test User | `test@example.com` | `password123` |

**Mục đích:** Testing, Demo

---

## 🚀 Cách Tạo Tài Khoản

### Phương Án 1: Sử Dụng Seeder (Khuyến Nghị)

```powershell
# Chạy seeder để tạo tất cả tài khoản
php artisan db:seed --class=UserSeeder
```

**Kết quả:** 7 tài khoản được tạo tự động!

### Phương Án 2: Tạo Thủ Công Qua Tinker

```powershell
php artisan tinker
```

Sau đó nhập:

```php
// Tạo Admin
User::create([
    'name' => 'Quản Trị Viên',
    'email' => 'admin@dangkytinchi.edu.vn',
    'password' => Hash::make('admin123456'),
    'email_verified_at' => now()
]);

// Tạo Giảng viên
User::create([
    'name' => 'Nguyễn Văn Giảng',
    'email' => 'giangvien1@dangkytinchi.edu.vn',
    'password' => Hash::make('giang123456'),
    'email_verified_at' => now()
]);

// Tạo Học viên
User::create([
    'name' => 'Lê Văn An',
    'email' => 'hocvien1@dangkytinchi.edu.vn',
    'password' => Hash::make('hocvien123'),
    'email_verified_at' => now()
]);

exit
```

---

## 🧪 Test Đăng Nhập

### Bước 1: Khởi động server
```powershell
php artisan serve
```

### Bước 2: Truy cập
```
http://127.0.0.1:8000/login
```

### Bước 3: Đăng nhập thử

**Test Admin:**
- Email: `admin@dangkytinchi.edu.vn`
- Password: `admin123456`

**Test Học viên:**
- Email: `hocvien1@dangkytinchi.edu.vn`
- Password: `hocvien123`

**Test Giảng viên:**
- Email: `giangvien1@dangkytinchi.edu.vn`
- Password: `giang123456`

---

## 🔄 Test Chức Năng Quên Mật Khẩu (UC1.2)

### 1. Vào trang đăng nhập
```
http://127.0.0.1:8000/login
```

### 2. Click "Quên mật khẩu?"

### 3. Nhập email
Ví dụ: `hocvien1@dangkytinchi.edu.vn`

### 4. Kiểm tra email
- **Nếu dùng Mailtrap:** Xem trong inbox Mailtrap
- **Nếu dùng Log:** Xem file `storage/logs/laravel.log`

### 5. Click link reset trong email

### 6. Đặt mật khẩu mới
- Mật khẩu mới: `newpassword123`
- Xác nhận: `newpassword123`

### 7. Đăng nhập với mật khẩu mới!

---

## 📊 Tổng Hợp

| Vai Trò | Số Lượng | Email Domain |
|---------|----------|--------------|
| Quản Trị Viên | 1 | `admin@dangkytinchi.edu.vn` |
| Giảng Viên | 2 | `giangvien[1-2]@dangkytinchi.edu.vn` |
| Học Viên | 3 | `hocvien[1-3]@dangkytinchi.edu.vn` |
| Test | 1 | `test@example.com` |
| **Tổng** | **7** | |

---

## 🔐 Lưu Ý Bảo Mật

### Trong Môi Trường Production:

1. ⚠️ **ĐỔI TẤT CẢ MẬT KHẨU** trước khi deploy
2. 🔒 Sử dụng mật khẩu mạnh hơn (tối thiểu 12 ký tự)
3. 🛡️ Bật 2FA cho tài khoản Admin
4. 📝 Không commit file này lên Git public
5. 🔄 Thay đổi mật khẩu định kỳ

### Yêu Cầu Mật Khẩu Mạnh:
```
✅ Tối thiểu 8-12 ký tự
✅ Chữ hoa + chữ thường
✅ Số và ký tự đặc biệt
✅ Không dùng thông tin cá nhân
✅ Khác nhau cho mỗi tài khoản
```

---

## 🆘 Khắc Phục Sự Cố

### Lỗi: "These credentials do not match our records"

**Nguyên nhân:** Tài khoản chưa được tạo

**Giải pháp:**
```powershell
# Chạy seeder
php artisan db:seed --class=UserSeeder
```

### Lỗi: "Class UserSeeder does not exist"

**Giải pháp:**
```powershell
# Tự động load lại các class
composer dump-autoload
```

### Kiểm tra tài khoản đã tồn tại chưa:

```powershell
php artisan tinker
```

```php
User::all()->pluck('email', 'name');
exit
```

---

## 📞 Hỗ Trợ

- 📧 Email: support@dangkytinchi.edu.vn
- 📱 Hotline: 1900 xxxx
- 🌐 Website: https://dangkytinchi.edu.vn

---

**Ngày tạo:** 17/10/2025  
**Phiên bản:** 1.0  
**Status:** ✅ READY TO USE

---

## 🎯 Checklist Triển Khai

- [ ] Chạy `php artisan db:seed --class=UserSeeder`
- [ ] Test đăng nhập với mỗi vai trò
- [ ] Test chức năng "Quên mật khẩu"
- [ ] Kiểm tra email reset password
- [ ] Đổi mật khẩu thành công
- [ ] Đăng nhập với mật khẩu mới
- [ ] Chuẩn bị mật khẩu mạnh cho production
- [ ] Backup danh sách tài khoản

**Hoàn thành:** 🎉 Hệ thống sẵn sàng!
