<?php
/**
 * src/Data/KhoLienHe.php - Lớp lưu trữ và truy xuất các liên hệ phản hồi vào tệp JSON Lines (.jsonl)
 * Nhóm 12 - Lớp 24CNTT3 - Trường ĐH Sư phạm - ĐH Đà Nẵng
 */

namespace App\Data;

class KhoLienHe
{
    public function __construct(private string $tep)
    {
        $thuMuc = dirname($this->tep);
        if (!is_dir($thuMuc)) {
            mkdir($thuMuc, 0777, true);
        }
    }

    /**
     * Thêm một bản ghi liên hệ mới vào cuối tệp JSON Lines kèm cơ chế khóa tệp an toàn (LOCK_EX)
     */
    public function them(array $lh): void
    {
        $dong = json_encode($lh, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        file_put_contents($this->tep, $dong . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    /**
     * Đọc toàn bộ danh sách liên hệ, trả về mảng với các bản ghi mới nhất xếp ở đầu tiên
     * @return array[]
     */
    public function tatCa(): array
    {
        if (!is_file($this->tep)) {
            return [];
        }
        $dong = file($this->tep, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!$dong) {
            return [];
        }
        $ds = array_map(function ($d) {
            $parsed = json_decode($d, true);
            return is_array($parsed) ? $parsed : null;
        }, $dong);

        return array_reverse(array_filter($ds));
    }
}
