# Hướng Dẫn Cấu Hình Mail cho UC1.2 - Thiết Lập Lại Mật Khẩu

## Tổng Quan
UC1.2 yêu cầu gửi email chứa liên kết thiết lập lại mật khẩu. Để chức năng hoạt động, bạn cần cấu hình mail trong file `.env`.

## Bước 1: Tạo file .env (nếu chưa có)

Nếu chưa có file `.env`, copy từ `.env.example`:

```powershell
Copy-Item .env.example .env
```

## Bước 2: Tạo APP_KEY

```powershell
php artisan key:generate
```

## Bước 3: Cấu Hình Mail

### Tùy Chọn 1: Sử dụng Mailtrap (Cho Testing - Khuyến Nghị)

Mailtrap là dịch vụ giúp test email mà không gửi email thật. Rất phù hợp cho môi trường phát triển.

1. Đăng ký tài khoản tại: https://mailtrap.io (miễn phí)
2. Vào Inbox > Credentials
3. Cập nhật trong file `.env`:

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

### Tùy Chọn 2: Sử dụng Gmail (Cho Production)

**Lưu ý:** Cần bật "2-Step Verification" và tạo "App Password" trong Google Account.

1. Vào Google Account: https://myaccount.google.com/
2. Security > 2-Step Verification (bật nếu chưa có)
3. Security > App passwords > Tạo mật khẩu ứng dụng mới
4. Cập nhật trong file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_16_character_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="Hệ Thống Đăng Ký Tín Chỉ"
```

### Tùy Chọn 3: Sử dụng SendGrid (Cho Production)

SendGrid cung cấp 100 email/ngày miễn phí.

1. Đăng ký tại: https://sendgrid.com/
2. Tạo API Key trong Settings > API Keys
3. Cập nhật trong file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Hệ Thống Đăng Ký Tín Chỉ"
```

### Tùy Chọn 4: Log Driver (Chỉ Cho Testing - Không Gửi Email Thật)

Email sẽ được ghi vào log thay vì gửi đi. Phù hợp cho test nhanh.

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@dangkytinchi.edu.vn
MAIL_FROM_NAME="Hệ Thống Đăng Ký Tín Chỉ"
```

Email sẽ được lưu trong file: `storage/logs/laravel.log`

## Bước 4: Xóa Cache

Sau khi cập nhật `.env`, chạy:

```powershell
php artisan config:clear
php artisan cache:clear
```

## Bước 5: Test Chức Năng

1. Chạy server: `php artisan serve`
2. Truy cập: http://127.0.0.1:8000/login
3. Click "Quên mật khẩu?"
4. Nhập email và submit
5. Kiểm tra:
   - **Mailtrap**: Inbox trong Mailtrap
   - **Gmail/SendGrid**: Hộp thư email của bạn
   - **Log**: File `storage/logs/laravel.log`

## Các Thông Số Quan Trọng

| Thông Số | Mô Tả |
|----------|-------|
| `MAIL_MAILER` | Driver: smtp, log, sendmail, mailgun |
| `MAIL_HOST` | SMTP host server |
| `MAIL_PORT` | Port (587 cho TLS, 465 cho SSL) |
| `MAIL_USERNAME` | Username SMTP |
| `MAIL_PASSWORD` | Password hoặc API key |
| `MAIL_ENCRYPTION` | tls hoặc ssl |
| `MAIL_FROM_ADDRESS` | Email người gửi |
| `MAIL_FROM_NAME` | Tên người gửi |

## Lưu Ý Bảo Mật

1. **KHÔNG commit file `.env`** lên Git
2. Sử dụng App Password thay vì mật khẩu Gmail thật
3. Giữ bí mật API keys
4. Sử dụng HTTPS trong production

## Khắc Phục Sự Cố

### Lỗi: Connection refused

**Nguyên nhân:** Port bị chặn hoặc cấu hình sai

**Giải pháp:**
- Kiểm tra firewall
- Thử đổi port: 587 → 465 hoặc ngược lại
- Đổi encryption: tls → ssl hoặc ngược lại

### Lỗi: Authentication failed

**Nguyên nhân:** Username/password sai

**Giải pháp:**
- Kiểm tra lại username, password
- Gmail: Đảm bảo dùng App Password, không phải mật khẩu thường
- Kiểm tra 2-Step Verification đã bật chưa

### Lỗi: Email không được gửi

**Giải pháp:**
- Chạy `php artisan config:clear`
- Kiểm tra queue: `php artisan queue:work` (nếu dùng queue)
- Kiểm tra log: `storage/logs/laravel.log`

## Kiểm Tra Cấu Hình

Chạy lệnh sau để test gửi email:

```powershell
php artisan tinker
```

Trong tinker, chạy:

```php
Mail::raw('Test email', function ($message) {
    $message->to('your_email@example.com')->subject('Test');
});
```

## UC1.2 Specifications

- ✅ Link reset password có hiệu lực **60 phút**
- ✅ Email template chuyên nghiệp với responsive design
- ✅ Validation email format
- ✅ Kiểm tra tài khoản tồn tại
- ✅ Bảo mật token với Hash
- ✅ Thông báo lỗi rõ ràng
- ✅ Log hoạt động reset password

## Liên Hệ Support

Nếu gặp vấn đề, kiểm tra:
1. File log: `storage/logs/laravel.log`
2. Network tab trong browser (F12)
3. SMTP server status

## Tài Liệu Tham Khảo

- Laravel Mail: https://laravel.com/docs/mail
- Mailtrap: https://mailtrap.io/
- SendGrid: https://sendgrid.com/
- Gmail App Passwords: https://support.google.com/accounts/answer/185833
