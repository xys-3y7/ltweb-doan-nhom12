<?php
/**
 * inc/tai-khoan.php - Danh sách tài khoản thử nghiệm của quản trị viên
 * Nhóm 12 - Lớp 24CNTT3 - Trường ĐH Sư phạm - ĐH Đà Nẵng
 * Mật khẩu được mã hóa an toàn bằng thuật toán BCRYPT (password_hash)
 */

return [
    'admin' => [
        'hoTen'    => 'Quản trị viên Hệ thống',
        'matKhau'  => '$2y$12$hZVq28eHEb3bIHCBg3zc.u.YhEM1T6e3zuk/eL1ji9Zool05Zithe', // Mật khẩu gốc: admin123
        'quyen'    => 'admin'
    ],
    'giaovien' => [
        'hoTen'    => 'Giảng viên Đánh giá',
        'matKhau'  => '$2y$12$twZO31NvvKDCN0mAIDW8aucHnzWCYqHjcJmb.7K9pwkcwBzs95Mr.', // Mật khẩu gốc: ued2026
        'quyen'    => 'giangvien'
    ]
];
