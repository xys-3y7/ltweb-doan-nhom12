<?php
/**
 * src/Models/SanPham.php - Thực thể Sách / Giáo trình trong hệ thống BookNest
 * Nhóm 12 - Lớp 24CNTT3 - Trường ĐH Sư phạm - ĐH Đà Nẵng
 */

namespace App\Models;

class SanPham
{
    public function __construct(
        public readonly int $id,
        public readonly string $ten,
        public readonly int $gia,
        public readonly int $giaGoc,
        public readonly string $theLoai,
        public readonly string $tacGia,
        public readonly string $nhaXuatBan,
        public readonly int $namXuatBan,
        public readonly int $soTrang,
        public readonly int $soLuong,
        public readonly string $hinhAnh,
        public readonly string $moTa,
        public readonly float $danhGia
    ) {}

    /**
     * Khởi tạo đối tượng SanPham từ một mục của mảng tệp JSON
     */
    public static function tuMang(array $d): self
    {
        return new self(
            id: (int)($d['id'] ?? 0),
            ten: (string)($d['ten'] ?? ''),
            gia: (int)($d['gia'] ?? 0),
            giaGoc: (int)($d['giaGoc'] ?? 0),
            theLoai: (string)($d['theLoai'] ?? ''),
            tacGia: (string)($d['tacGia'] ?? ''),
            nhaXuatBan: (string)($d['nhaXuatBan'] ?? ''),
            namXuatBan: (int)($d['namXuatBan'] ?? 0),
            soTrang: (int)($d['soTrang'] ?? 0),
            soLuong: (int)($d['soLuong'] ?? 0),
            hinhAnh: (string)($d['hinhAnh'] ?? ''),
            moTa: (string)($d['moTa'] ?? ''),
            danhGia: (float)($d['danhGia'] ?? 0.0)
        );
    }
}
