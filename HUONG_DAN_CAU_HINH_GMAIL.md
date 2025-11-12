# Hướng dẫn cấu hình Gmail để gửi email reset password

## ⚠️ QUAN TRỌNG: Bạn cần lấy App Password từ Gmail, KHÔNG dùng mật khẩu Gmail thông thường!

## Bước 1: Bật xác thực 2 bước (2FA) cho Gmail

1. Truy cập: https://myaccount.google.com/security
2. Đăng nhập bằng tài khoản: **hieptran19102005@gmail.com**
3. Tìm mục **"Xác minh 2 bước"** (hoặc "2-Step Verification")
4. Nhấn **"Bật"** và làm theo hướng dẫn (cần số điện thoại)

## Bước 2: Tạo App Password (Mật khẩu ứng dụng)

1. Sau khi bật 2FA, quay lại: https://myaccount.google.com/security
2. Tìm mục **"Mật khẩu ứng dụng"** (hoặc "App passwords")
   - Nếu không thấy, truy cập trực tiếp: https://myaccount.google.com/apppasswords
3. Chọn **"Ứng dụng khác"** (Select app → Other)
4. Nhập tên: `Laravel Dang Ky Tin Chi`
5. Nhấn **"Tạo"** (Generate)
6. Google sẽ hiển thị mật khẩu 16 ký tự (VD: `abcd efgh ijkl mnop`)
7. **SAO CHÉP** mật khẩu này (bỏ dấu cách)

## Bước 3: Cập nhật file .env

Mở file `.env` trong thư mục dự án và tìm dòng:

```
MAIL_PASSWORD=your_app_password_here
```

Thay `your_app_password_here` bằng App Password vừa lấy (16 ký tự, bỏ dấu cách):

```
MAIL_PASSWORD=abcdefghijklmnop
```

## Bước 4: Clear cache và test

Chạy lệnh sau trong terminal:

```bash
php artisan config:clear
php artisan cache:clear
```

## Bước 5: Test gửi email

1. Truy cập: http://localhost:8000/forgot-password
2. Nhập email: hieptran19102005@gmail.com
3. Nhấn "Gửi Liên Kết Thiết Lập Lại"
4. Kiểm tra hộp thư Gmail của bạn (bao gồm cả thư mục Spam)

## ✅ Kết quả mong đợi

- Bạn sẽ nhận được email có tiêu đề: "Thiết Lập Lại Mật Khẩu - Hệ Thống Đăng Ký Tín Chỉ"
- Email chứa nút "Thiết Lập Lại Mật Khẩu" với link có hiệu lực 60 phút

## ⚠️ Lưu ý bảo mật

- **KHÔNG BAO GIỜ** chia sẻ App Password với ai
- **KHÔNG** commit file `.env` lên Git (đã có trong `.gitignore`)
- Nếu lộ App Password, hãy xóa và tạo lại ngay

## 🔧 Troubleshooting (Xử lý lỗi)

### Lỗi: "Failed to authenticate on SMTP server"
→ Kiểm tra lại App Password đã nhập đúng chưa (16 ký tự, không có dấu cách)

### Lỗi: "Connection could not be established"
→ Kiểm tra kết nối Internet và port 587 không bị chặn bởi firewall

### Không nhận được email
→ Kiểm tra thư mục Spam/Junk trong Gmail

### Lỗi: "App passwords is not available"
→ Cần bật xác thực 2 bước (2FA) trước

## 📧 Cấu hình hiện tại

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=hieptran19102005@gmail.com
MAIL_PASSWORD=[App Password 16 ký tự]
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hieptran19102005@gmail.com
MAIL_FROM_NAME="Hệ Thống Đăng Ký Tín Chỉ"
```

## 🎯 Link nhanh

- Bật 2FA: https://myaccount.google.com/security
- Tạo App Password: https://myaccount.google.com/apppasswords
- Quản lý tài khoản Google: https://myaccount.google.com

---

**Sau khi hoàn tất, bạn có thể test chức năng "Quên mật khẩu" và nhận email thật!**
