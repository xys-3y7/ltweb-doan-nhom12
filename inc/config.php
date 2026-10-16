<?php
/**
 * inc/config.php - Cấu hình hệ thống, môi trường chạy, log lỗi và nạp thư viện
 * Nhóm 12 - Lớp 24CNTT3 - Trường ĐH Sư phạm - ĐH Đà Nẵng
 */

// Định nghĩa môi trường: 'dev' khi phát triển/kiểm thử, 'prod' khi triển khai máy chủ thật
const MOI_TRUONG = 'dev';

// Bật báo toàn bộ lỗi theo chuẩn PHP 8
error_reporting(E_ALL);

// Cấu hình ghi log lỗi ra tệp chuyên biệt
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../logs/php-error.log');

if (MOI_TRUONG === 'dev') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
}

// Bắt ngoại lệ chưa được xử lý (uncaught exceptions)
set_exception_handler(function (Throwable $e) {
    error_log(sprintf(
        "[%s] Uncaught Exception: %s in %s on line %d\nStack trace:\n%s",
        date('Y-m-d H:i:s'),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
        $e->getTraceAsString()
    ));

    if (MOI_TRUONG === 'prod') {
        http_response_code(500);
        require __DIR__ . '/../500.php';
        exit;
    } else {
        http_response_code(500);
        echo "<!DOCTYPE html><html lang='vi'><head><meta charset='utf-8'><title>Lỗi hệ thống (500)</title>";
        echo "<style>body{font-family:sans-serif;padding:30px;line-height:1.6;background:#f8fafc;color:#0f172a;}";
        echo "pre{background:#1e293b;color:#f8fafc;padding:15px;border-radius:6px;overflow-x:auto;}</style></head><body>";
        echo "<h1>Đã xảy ra ngoại lệ (Chế độ DEV)</h1>";
        echo "<p><strong>Thông điệp:</strong> " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p><strong>Vị trí:</strong> " . htmlspecialchars($e->getFile(), ENT_QUOTES, 'UTF-8') . " dòng " . $e->getLine() . "</p>";
        echo "<h3>Chi tiết Stack Trace:</h3>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString(), ENT_QUOTES, 'UTF-8') . "</pre>";
        echo "<p><a href='index.php'>&larr; Về trang chủ</a></p>";
        echo "</body></html>";
        exit;
    }
});

// Cấu hình cookie phiên bảo mật trước khi khởi động phiên
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// Nạp tự động các lớp (Composer PSR-4)
require_once __DIR__ . '/../vendor/autoload.php';

// Nạp các hàm tiện ích
require_once __DIR__ . '/ham.php';
