<?php
// Script tạo database MySQL
try {
    $host = '127.0.0.1';
    $username = 'root';
    $password = '';

    // Kết nối MySQL không chọn database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Tạo database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS quanlytkbieu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    echo "✅ Database 'quanlytkbieu' đã được tạo thành công!\n";
    echo "Bây giờ bạn có thể chạy: php artisan migrate:fresh --seed\n";
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
    echo "\nHướng dẫn:\n";
    echo "1. Mở XAMPP Control Panel\n";
    echo "2. Start MySQL (nếu chưa chạy)\n";
    echo "3. Hoặc mở phpMyAdmin tại: http://localhost/phpmyadmin\n";
    echo "4. Tạo database tên 'quanlytkbieu' thủ công\n";
}
