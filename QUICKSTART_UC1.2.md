# 🚀 UC1.2 Quick Start Guide

## Triển Khai UC1.2 - Thiết Lập Lại Mật Khẩu

### ✅ Đã Hoàn Thành

Tất cả các file và chức năng UC1.2 đã được triển khai xong!

### 📦 Files Mới

```
✅ resources/views/forgot-password.blade.php
✅ resources/views/reset-password.blade.php  
✅ resources/views/emails/reset-password.blade.php
✅ app/Http/Controllers/AuthController.php (đã cập nhật)
✅ routes/web.php (đã thêm 4 routes)
✅ resources/views/login.blade.php (đã cập nhật link)
✅ UC1.2_SUMMARY.md (tài liệu đầy đủ)
✅ MAIL_CONFIGURATION.md (hướng dẫn mail)
```

### ⚡ Bắt Đầu Nhanh (3 bước)

#### 1️⃣ Tạo file .env và APP_KEY

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

#### 2️⃣ Cấu hình Mail trong .env

**Nhanh nhất - Dùng Mailtrap:**

Đăng ký miễn phí: https://mailtrap.io

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@dangkytinchi.edu.vn
MAIL_FROM_NAME="Hệ Thống Đăng Ký Tín Chỉ"
```

**Hoặc - Dùng Log (chỉ test, không gửi email thật):**

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@dangkytinchi.edu.vn
MAIL_FROM_NAME="Hệ Thống Đăng Ký Tín Chỉ"
```

Sau đó:
```powershell
php artisan config:clear
```

#### 3️⃣ Tạo User Test

```powershell
php artisan tinker
```

Trong tinker:
```php
User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => Hash::make('password123')
]);
exit
```

### 🎯 Test UC1.2

```powershell
# Khởi động server
php artisan serve

# Truy cập: http://127.0.0.1:8000/login
# Click "Quên mật khẩu?"
# Nhập email: test@example.com
# Kiểm tra email trong Mailtrap hoặc storage/logs/laravel.log
# Click link reset password
# Đặt mật khẩu mới
# Đăng nhập!
```

### 🔗 Routes UC1.2

| URL | Chức Năng |
|-----|-----------|
| `/forgot-password` | Form nhập email |
| `/reset-password/{token}` | Form đặt mật khẩu mới |

### 📧 Kiểm Tra Email

**Mailtrap:** Vào inbox trên https://mailtrap.io

**Log Mode:** Xem file:
```
storage/logs/laravel.log
```

### ⏰ Specs UC1.2

- ✅ Link có hiệu lực: **60 phút**
- ✅ Token được hash an toàn
- ✅ Validation đầy đủ
- ✅ Email template chuyên nghiệp
- ✅ Responsive mobile/desktop

### 🐛 Lỗi Thường Gặp

**Email không gửi được?**
```powershell
php artisan config:clear
php artisan cache:clear
# Kiểm tra .env
# Xem storage/logs/laravel.log
```

**Link không hoạt động?**
```env
# Trong .env, đảm bảo:
APP_URL=http://127.0.0.1:8000
```

### 📚 Tài Liệu Chi Tiết

Xem file: `UC1.2_SUMMARY.md` (tài liệu đầy đủ)  
Xem file: `MAIL_CONFIGURATION.md` (hướng dẫn mail)

### 🎉 Hoàn Thành!

UC1.2 đã sẵn sàng sử dụng. Chúc bạn test thành công! 🚀

---

**Hỗ trợ:** Xem logs trong `storage/logs/laravel.log`  
**Ngày:** 17/10/2025  
**Status:** ✅ READY TO USE
