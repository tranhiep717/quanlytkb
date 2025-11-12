<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thiết Lập Lại Mật Khẩu</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
        }

        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
        }

        .email-body {
            padding: 40px 30px;
            color: #333333;
            line-height: 1.6;
        }

        .email-body p {
            margin: 15px 0;
            font-size: 16px;
        }

        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #333333;
            margin-bottom: 20px;
        }

        .reset-button {
            display: inline-block;
            margin: 30px 0;
            padding: 15px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
        }

        .button-container {
            text-align: center;
        }

        .expiry-notice {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .expiry-notice p {
            margin: 0;
            color: #856404;
            font-size: 14px;
        }

        .alternative-link {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            word-break: break-all;
        }

        .alternative-link p {
            margin: 5px 0;
            font-size: 13px;
            color: #666;
        }

        .alternative-link a {
            color: #667eea;
            text-decoration: none;
        }

        .email-footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            color: #666666;
            font-size: 14px;
        }

        .email-footer p {
            margin: 10px 0;
        }

        .security-notice {
            background-color: #e7f3ff;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .security-notice p {
            margin: 0;
            color: #0d47a1;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1>🔐 Thiết Lập Lại Mật Khẩu</h1>
        </div>

        <div class="email-body">
            <p class="greeting">Xin chào{{ isset($user->name) ? ' ' . $user->name : '' }},</p>

            <p>Chúng tôi nhận được yêu cầu thiết lập lại mật khẩu cho tài khoản của bạn trên <strong>Hệ Thống Đăng Ký Tín Chỉ</strong>.</p>

            <p>Để tiếp tục, vui lòng nhấp vào nút bên dưới để thiết lập mật khẩu mới:</p>

            <div class="button-container">
                <a href="{{ $resetLink }}" class="reset-button">Thiết Lập Lại Mật Khẩu</a>
            </div>

            <div class="expiry-notice">
                <p><strong>⏰ Lưu ý:</strong> Liên kết này chỉ có hiệu lực trong vòng <strong>60 phút</strong> kể từ thời điểm nhận email.</p>
            </div>

            <div class="alternative-link">
                <p><strong>Nếu nút bên trên không hoạt động, vui lòng sao chép và dán liên kết sau vào trình duyệt:</strong></p>
                <p><a href="{{ $resetLink }}">{{ $resetLink }}</a></p>
            </div>

            <div class="security-notice">
                <p><strong>🛡️ Bảo mật:</strong> Nếu bạn không yêu cầu thiết lập lại mật khẩu, vui lòng bỏ qua email này. Tài khoản của bạn vẫn an toàn và không có thay đổi nào được thực hiện.</p>
            </div>

            <p>Nếu bạn cần hỗ trợ, vui lòng liên hệ với chúng tôi.</p>

            <p>Trân trọng,<br><strong>Đội ngũ Hệ Thống Đăng Ký Tín Chỉ</strong></p>
        </div>

        <div class="email-footer">
            <p>Email này được gửi tự động. Vui lòng không trả lời email này.</p>
            <p>&copy; {{ date('Y') }} Hệ Thống Đăng Ký Tín Chỉ. All rights reserved.</p>
        </div>
    </div>
</body>

</html>