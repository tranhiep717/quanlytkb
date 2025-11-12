<?php

/**
 * Script test gửi email nhanh
 * Chạy: php test-email.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;

echo "🧪 Test gửi email...\n\n";

$testEmail = 'hieptran19102005@gmail.com';
$testMessage = 'Đây là email test từ Hệ Thống Đăng Ký Tín Chỉ. Nếu bạn nhận được email này, cấu hình Gmail đã thành công!';

try {
    Mail::raw($testMessage, function ($message) use ($testEmail) {
        $message->to($testEmail)
            ->subject('✅ Test Email - Hệ Thống Đăng Ký Tín Chỉ');
    });

    echo "✅ Email đã được gửi thành công!\n";
    echo "📧 Kiểm tra hộp thư: {$testEmail}\n";
    echo "💡 Lưu ý: Nếu không thấy email, kiểm tra thư mục Spam\n\n";
} catch (Exception $e) {
    echo "❌ Lỗi khi gửi email:\n";
    echo $e->getMessage() . "\n\n";
    echo "🔧 Kiểm tra lại:\n";
    echo "  1. App Password đã đúng chưa? (16 ký tự)\n";
    echo "  2. Đã bật xác thực 2 bước (2FA) chưa?\n";
    echo "  3. Kết nối Internet có ổn không?\n\n";
}
