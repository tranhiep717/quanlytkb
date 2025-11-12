<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Mã OTP đặt lại mật khẩu</title>
</head>

<body style="font-family:Arial,Helvetica,sans-serif;background:#f6f7fb;padding:20px;">
    <div style="max-width:600px;margin:0 auto;background:#fff;border-radius:8px;padding:24px;border:1px solid #eee;">
        <h2>Xin chào {{ $user->name }},</h2>
        <p>Bạn vừa yêu cầu đặt lại mật khẩu cho tài khoản MSSV <strong>{{ $user->code }}</strong>.</p>
        <p>Mã OTP của bạn là:</p>
        <div style="font-size:28px;font-weight:bold;letter-spacing:4px;background:#eef2ff;color:#4338ca;padding:12px 16px;border-radius:8px;display:inline-block;">
            {{ $otp }}
        </div>
        <p style="margin-top:16px;color:#555;">Mã OTP có hiệu lực trong <strong>10 phút</strong>. Tuyệt đối không chia sẻ mã này cho bất kỳ ai.</p>
        <hr style="border:none;border-top:1px solid #eee;margin:20px 0;">
        <p>Nếu bạn không yêu cầu, vui lòng bỏ qua email này.</p>
        <p>Trân trọng,<br>Hệ Thống Đăng Ký Tín Chỉ</p>
    </div>
</body>

</html>