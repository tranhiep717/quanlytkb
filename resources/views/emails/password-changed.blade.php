<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mật khẩu đã được thay đổi</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0
        }

        .wrap {
            max-width: 640px;
            margin: 24px auto;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .08)
        }

        .header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 24px 28px;
            color: #fff
        }

        .header h1 {
            margin: 0;
            font-size: 22px
        }

        .content {
            padding: 28px;
            color: #333;
            line-height: 1.6
        }

        .content p {
            margin: 10px 0
        }

        .note {
            background: #fef6e0;
            border-left: 4px solid #f7c948;
            padding: 14px;
            border-radius: 6px
        }

        .footer {
            background: #f7f9fb;
            padding: 18px;
            text-align: center;
            color: #6b7280;
            font-size: 13px
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="header">
            <h1>Thông báo bảo mật</h1>
        </div>
        <div class="content">
            <p>Xin chào{{ isset($user->name) ? ' ' . $user->name : '' }},</p>
            <p>Mật khẩu tài khoản của bạn trên Hệ Thống Đăng Ký Tín Chỉ vừa được <strong>thay đổi</strong>.</p>
            <div class="note">
                <p>Nếu không phải bạn thực hiện, vui lòng <strong>đổi lại mật khẩu ngay</strong> và liên hệ bộ phận hỗ trợ.</p>
            </div>
            <p>Thời gian: {{ now()->format('d/m/Y H:i') }}</p>
            <p>Địa chỉ email: <strong>{{ $user->email }}</strong></p>
            <p>Cảm ơn bạn đã sử dụng hệ thống.</p>
        </div>
        <div class="footer">Đây là email tự động, vui lòng không trả lời.</div>
    </div>
</body>

</html>