<?php
/**
 * src/Services/GioHang.php - Dịch vụ quản lý giỏ hàng lưu trong Session
 * Nhóm 12 - Lớp 24CNTT3 - Trường ĐH Sư phạm - ĐH Đà Nẵng
 */

namespace App\Services;

use App\Data\KhoSanPham;
use App\Models\SanPham;

class GioHang
{
    public function __construct()
    {
        if (!isset($_SESSION['gio']) || !is_array($_SESSION['gio'])) {
            $_SESSION['gio'] = [];
        }
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function them(int $id, int $soLuong, KhoSanPham $kho): bool
    {
        // Kiểm tra số lượng hợp lệ (1 - 99)
        if ($soLuong < 1 || $soLuong > 99) {
            return false;
        }

        // Kiểm tra ID sản phẩm có thực sự tồn tại trong CSDL/JSON không
        $sp = $kho->timTheoId($id);
        if ($sp === null) {
            return false;
        }

        $hienCo = $_SESSION['gio'][$id] ?? 0;
        $tongMoi = min(99, $hienCo + $soLuong);
        $_SESSION['gio'][$id] = $tongMoi;

        return true;
    }

    /**
     * Cập nhật số lượng của một sản phẩm trong giỏ
     */
    public function capNhat(int $id, int $soLuong): bool
    {
        if ($soLuong <= 0) {
            $this->xoa($id);
            return true;
        }

        if ($soLuong > 99) {
            $soLuong = 99;
        }

        if (isset($_SESSION['gio'][$id])) {
            $_SESSION['gio'][$id] = $soLuong;
            return true;
        }

        return false;
    }

    /**
     * Xóa một sản phẩm khỏi giỏ
     */
    public function xoa(int $id): void
    {
        unset($_SESSION['gio'][$id]);
    }

    /**
     * Xóa toàn bộ sản phẩm trong giỏ
     */
    public function xoaHet(): void
    {
        $_SESSION['gio'] = [];
    }

    /**
     * Tổng số lượng mặt hàng (items) có trong giỏ
     */
    public function soMon(): int
    {
        return array_sum($_SESSION['gio'] ?? []);
    }

    /**
     * Lấy danh sách thô [id => soLuong]
     */
    public function danhSach(): array
    {
        return $_SESSION['gio'] ?? [];
    }

    /**
     * Lấy danh sách chi tiết các mặt hàng kèm đối tượng SanPham và thành tiền
     * Giá luôn lấy từ KhoSanPham máy chủ, không nhận từ client
     * @return array<int, array{sanPham: SanPham, soLuong: int, thanhTien: int}>
     */
    public function layChiTiet(KhoSanPham $kho): array
    {
        $ketQua = [];
        foreach ($this->danhSach() as $id => $soLuong) {
            $sp = $kho->timTheoId((int)$id);
            if ($sp !== null && $soLuong > 0) {
                $ketQua[] = [
                    'sanPham'   => $sp,
                    'soLuong'   => $soLuong,
                    'thanhTien' => $sp->gia * $soLuong
                ];
            }
        }
        return $ketQua;
    }

    /**
     * Tính tổng tiền cả giỏ hàng dựa trên giá máy chủ
     */
    public function tongTien(KhoSanPham $kho): int
    {
        $tong = 0;
        foreach ($this->layChiTiet($kho) as $item) {
            $tong += $item['thanhTien'];
        }
        return $tong;
    }
}
