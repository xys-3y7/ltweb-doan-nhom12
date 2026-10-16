<?php
/**
 * src/Data/KhoSanPham.php - Lớp truy xuất dữ liệu sản phẩm từ tệp JSON
 * Nhóm 12 - Lớp 24CNTT3 - Trường ĐH Sư phạm - ĐH Đà Nẵng
 */

namespace App\Data;

use App\Models\SanPham;
use RuntimeException;

class KhoSanPham
{
    private ?array $ds = null;

    public function __construct(private string $tepJson) {}

    /**
     * Lấy toàn bộ danh sách sản phẩm. Đọc tệp đúng một lần trong mỗi vòng đời request.
     * @return SanPham[]
     */
    public function tatCa(): array
    {
        if ($this->ds === null) {
            if (!is_file($this->tepJson)) {
                throw new RuntimeException("Không tìm thấy tệp dữ liệu sản phẩm: {$this->tepJson}");
            }
            $json = file_get_contents($this->tepJson);
            if ($json === false) {
                throw new RuntimeException("Không thể đọc tệp dữ liệu sản phẩm: {$this->tepJson}");
            }
            $mang = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            $this->ds = array_map(fn($d) => SanPham::tuMang($d), $mang);
        }
        return $this->ds;
    }

    /**
     * Tìm sản phẩm theo mã ID số nguyên duy nhất
     */
    public function timTheoId(int $id): ?SanPham
    {
        foreach ($this->tatCa() as $sp) {
            if ($sp->id === $id) {
                return $sp;
            }
        }
        return null;
    }

    /**
     * Tìm kiếm, lọc theo thể loại và sắp xếp sản phẩm
     * @return SanPham[]
     */
    public function timKiem(string $tuKhoa = '', string $theLoai = '', string $sapXep = ''): array
    {
        $kq = $this->tatCa();

        // 1. Lọc theo từ khóa tìm kiếm (không phân biệt chữ hoa thường tiếng Việt)
        if ($tuKhoa !== '') {
            $kq = array_filter($kq, function (SanPham $sp) use ($tuKhoa) {
                return mb_stripos($sp->ten, $tuKhoa) !== false
                    || mb_stripos($sp->tacGia, $tuKhoa) !== false
                    || mb_stripos($sp->moTa, $tuKhoa) !== false;
            });
        }

        // 2. Lọc theo thể loại
        if ($theLoai !== '' && $theLoai !== 'tat-ca') {
            $kq = array_filter($kq, fn(SanPham $sp) => $sp->theLoai === $theLoai);
        }

        $kq = array_values($kq);

        // 3. Sắp xếp danh sách
        match ($sapXep) {
            'gia-tang' => usort($kq, fn(SanPham $a, SanPham $b) => $a->gia <=> $b->gia),
            'gia-giam' => usort($kq, fn(SanPham $a, SanPham $b) => $b->gia <=> $a->gia),
            'ten-az'   => usort($kq, fn(SanPham $a, SanPham $b) => strcasecmp($a->ten, $b->ten)),
            'ten-za'   => usort($kq, fn(SanPham $a, SanPham $b) => strcasecmp($b->ten, $a->ten)),
            default    => null, // Giữ nguyên thứ tự mặc định trong JSON
        };

        return $kq;
    }

    /**
     * Lấy các sản phẩm liên quan cùng danh mục
     * @return SanPham[]
     */
    public function sanPhamLienQuan(SanPham $spHienTai, int $gioiHan = 3): array
    {
        $lienQuan = array_filter($this->tatCa(), function (SanPham $item) use ($spHienTai) {
            return $item->theLoai === $spHienTai->theLoai && $item->id !== $spHienTai->id;
        });
        return array_slice(array_values($lienQuan), 0, $gioiHan);
    }
}
