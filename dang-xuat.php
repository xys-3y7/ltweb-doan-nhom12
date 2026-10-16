<?php
/**
 * dang-xuat.php - Hủy phiên đăng nhập và đăng xuất an toàn khỏi hệ thống BookNest
 * Nhóm 12 - Lớp 24CNTT3 - Trường ĐH Sư phạm - ĐH Đà Nẵng
 */

require_once __DIR__ . '/inc/config.php';

// Xóa thông tin xác thực của tài khoản trong phiên
unset($_SESSION['user'], $_SESSION['hoTen'], $_SESSION['quyen']);

// Cấp lại Session ID mới để hủy bỏ hoàn toàn phiên đăng nhập cũ
session_regenerate_id(true);

// Gán thông báo xác nhận đăng xuất thành công
$_SESSION['flash_loi'] = 'Bạn đã đăng xuất khỏi hệ thống thành công.';

header('Location: dang-nhap.php');
exit;
