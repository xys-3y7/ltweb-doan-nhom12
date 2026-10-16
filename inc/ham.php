<?php
/**
 * inc/ham.php - Các hàm trợ giúp dùng chung cho website BookNest
 * Nhóm 12 - Lớp 24CNTT3 - Trường ĐH Sư phạm - ĐH Đà Nẵng
 */

/**
 * Thoát ký tự đặc biệt trong chuỗi để in an toàn ra HTML chống XSS
 * Sử dụng htmlspecialchars với ENT_QUOTES và UTF-8
 */
function e(?string $chuoi): string
{
    return htmlspecialchars((string)$chuoi, ENT_QUOTES, 'UTF-8');
}

/**
 * Định dạng tiền tệ Việt Nam (VNĐ)
 */
function vnd(int|float $so): string
{
    return number_format($so, 0, ',', '.') . ' VNĐ';
}
