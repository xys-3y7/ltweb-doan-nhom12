<?php
/**
 * inc/bao-ve.php - Middleware bảo vệ các trang yêu cầu quyền quản trị
 * Nhóm 12 - Lớp 24CNTT3 - Trường ĐH Sư phạm - ĐH Đà Nẵng
 */

if (!isset($_SESSION['user'])) {
    $_SESSION['flash_loi'] = 'Vui lòng đăng nhập để truy cập trang quản trị.';
    $goc = $goc ?? '';
    header('Location: ' . $goc . 'dang-nhap.php');
    exit;
}
