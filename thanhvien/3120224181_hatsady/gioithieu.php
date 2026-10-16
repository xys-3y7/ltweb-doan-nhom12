<?php
/**
 * thanhvien/3120224181_hatsady/gioithieu.php - Trang giới thiệu cá nhân Phommaket Hatsady
 * Tệp này hiển thị lý lịch, thời khóa biểu và tích hợp 2 chức năng xử lý máy chủ:
 * 1. Máy tính điểm học phần: 0.2*A1 + 0.3*A2 + 0.5*A3, kiểm tra 0 - 10, xếp loại học lực
 * 2. Danh sách dự án cá nhân từ mảng PHP, lọc theo thể loại qua tham số GET (?theloai=...)
 * Cách thử: Mở trình duyệt tại http://localhost:8000/thanhvien/3120224181_hatsady/gioithieu.php
 */

$goc = '../../';
require_once __DIR__ . '/../../inc/config.php';

// Chức năng 1: Máy tính điểm học phần (A1, A2, A3)
$diemA1 = $_GET['a1'] ?? '';
$diemA2 = $_GET['a2'] ?? '';
$diemA3 = $_GET['a3'] ?? '';
$loiDiem = [];
$ketQuaDiem = null;

if (isset($_GET['tinh_diem'])) {
    $v1 = filter_var($diemA1, FILTER_VALIDATE_FLOAT);
    $v2 = filter_var($diemA2, FILTER_VALIDATE_FLOAT);
    $v3 = filter_var($diemA3, FILTER_VALIDATE_FLOAT);

    if ($v1 === false || $v1 < 0.0 || $v1 > 10.0) {
        $loiDiem['a1'] = 'Điểm A1 phải là số thực từ 0.0 đến 10.0';
    }
    if ($v2 === false || $v2 < 0.0 || $v2 > 10.0) {
        $loiDiem['a2'] = 'Điểm A2 phải là số thực từ 0.0 đến 10.0';
    }
    if ($v3 === false || $v3 < 0.0 || $v3 > 10.0) {
        $loiDiem['a3'] = 'Điểm A3 phải là số thực từ 0.0 đến 10.0';
    }

    if (empty($loiDiem)) {
        $tongKet = round(0.2 * $v1 + 0.3 * $v2 + 0.5 * $v3, 2);
        
        // Xác định điểm chữ và xếp loại theo thang điểm Đại học Đà Nẵng
        $xepLoai = match (true) {
            $tongKet >= 9.0 => ['chu' => 'A+', 'loai' => 'Xuất sắc', 'mau' => '#16a34a'],
            $tongKet >= 8.5 => ['chu' => 'A',  'loai' => 'Giỏi',     'mau' => '#16a34a'],
            $tongKet >= 8.0 => ['chu' => 'B+', 'loai' => 'Khá giỏi', 'mau' => '#0284c7'],
            $tongKet >= 7.0 => ['chu' => 'B',  'loai' => 'Khá',      'mau' => '#0284c7'],
            $tongKet >= 6.5 => ['chu' => 'C+', 'loai' => 'Trung bình khá', 'mau' => '#d97706'],
            $tongKet >= 5.5 => ['chu' => 'C',  'loai' => 'Trung bình',     'mau' => '#d97706'],
            $tongKet >= 5.0 => ['chu' => 'D+', 'loai' => 'Trung bình yếu', 'mau' => '#ea580c'],
            $tongKet >= 4.0 => ['chu' => 'D',  'loai' => 'Đạt (Yếu)',      'mau' => '#ea580c'],
            default         => ['chu' => 'F',  'loai' => 'Không đạt (Kém)', 'mau' => '#dc2626'],
        };

        $ketQuaDiem = [
            'tongKet' => $tongKet,
            'chu'     => $xepLoai['chu'],
            'loai'    => $xepLoai['loai'],
            'mau'     => $xepLoai['mau']
        ];
    }
}

// Chức năng 2: Danh sách dự án từ mảng PHP lọc bằng tham số GET
$tatCaDuAn = [
    [
        'ten' => 'BookNest - Nhà sách trực tuyến UED',
        'theLoai' => 'web',
        'congNghe' => 'HTML5, CSS3, JavaScript ES6, PHP 8, Composer',
        'vaiTro' => 'Phát triển trang Danh mục, tìm kiếm và dịch vụ Giỏ hàng',
        'nam' => 2026
    ],
    [
        'ten' => 'Thời tiết Sinh viên UED (Open-Meteo)',
        'theLoai' => 'web',
        'congNghe' => 'JavaScript Fetch API, RESTful JSON API, CSS Grid',
        'vaiTro' => 'Tích hợp widget dự báo thời tiết Đà Nẵng theo tọa độ',
        'nam' => 2026
    ],
    [
        'ten' => 'App Nhắc nhở Lịch học & Deadline',
        'theLoai' => 'di-dong',
        'congNghe' => 'Flutter, Dart, SQLite nội bộ',
        'vaiTro' => 'Thiết kế giao diện người dùng và quản lý cơ sở dữ liệu',
        'nam' => 2025
    ],
    [
        'ten' => 'Hệ thống Quản lý Ký túc xá Sư phạm',
        'theLoai' => 'csdl',
        'congNghe' => 'MySQL, PHP PDO, Bootstrap 5',
        'vaiTro' => 'Thiết kế mô hình thực thể ERD và viết truy vấn SQL',
        'nam' => 2025
    ]
];

$theLoaiChon = $_GET['theloai'] ?? 'tat-ca';
$danhSachDuAn = $tatCaDuAn;

if ($theLoaiChon !== 'tat-ca') {
    $danhSachDuAn = array_filter($tatCaDuAn, fn($item) => $item['theLoai'] === $theLoaiChon);
}

$tieuDe = 'Hồ sơ sinh viên: Phommaket Hatsady';
$trang = 'gioi-thieu';
$moTaTrang = 'Hồ sơ lý lịch, máy tính điểm học phần và danh sách dự án của Phommaket Hatsady.';

require __DIR__ . '/../../inc/header.php';
?>

<link rel="stylesheet" href="css/style.css">

<main class="noi-dung-chinh khung-chua">
  <h1>Hồ sơ sinh viên: Phommaket Hatsady</h1>

  <!-- Khối lý lịch -->
  <section class="ho-so-ca-nhan" style="background: #ffffff; padding: 25px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 30px;">
    <figure>
      <img src="../../images/hatsady-avatar.png" alt="Ảnh chân dung sinh viên Phommaket Hatsady" width="140" height="140" style="border-radius: 50%; border: 3px solid #0284c7;">
      <figcaption><strong>Phommaket Hatsady</strong><br>Sinh viên Khoa Toán &ndash; Tin</figcaption>
    </figure>

    <div>
      <h2>Thông tin chi tiết</h2>
      <ul>
        <li><strong>Họ và tên:</strong> Phommaket Hatsady</li>
        <li><strong>Mã sinh viên:</strong> 3120224181</li>
        <li><strong>Lớp sinh hoạt:</strong> 24CNTT3</li>
        <li><strong>Chuyên ngành:</strong> Công nghệ thông tin</li>
        <li><strong>Đơn vị đào tạo:</strong> Khoa Toán &ndash; Tin, Trường Đại học Sư phạm &ndash; Đại học Đà Nẵng</li>
        <li><strong>Vai trò trong nhóm:</strong> Thành viên (Phụ trách trang danh mục <code>danh-sach.php</code>, bộ lọc tìm kiếm, dịch vụ <code>GioHang.php</code>)</li>
        <li><strong>Email học tập:</strong> <a href="mailto:3120224181@ued.udn.vn">3120224181@ued.udn.vn</a></li>
      </ul>
    </div>
  </section>

  <!-- HAI CHỨC NĂNG XỬ LÝ PHÍA MÁY CHỦ (PHẦN C) -->
  
  <!-- Chức năng 1: Máy tính điểm học phần -->
  <section style="background: #f8fafc; padding: 25px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 35px;">
    <h2>Chức năng máy chủ 1: Máy tính điểm học phần tín chỉ UED</h2>
    <p>Công thức tính điểm tổng kết: <code>Điểm HP = 0.2 × A1 (Chuyên cần) + 0.3 × A2 (Giữa kỳ) + 0.5 × A3 (Cuối kỳ)</code></p>

    <form method="GET" action="gioithieu.php" style="max-width: 600px; margin-bottom: 20px;">
      <input type="hidden" name="tinh_diem" value="1">
      <input type="hidden" name="theloai" value="<?= e($theLoaiChon) ?>">

      <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-bottom: 15px;">
        <div style="flex: 1; min-width: 140px;">
          <label for="a1" style="display: block; font-weight: bold; margin-bottom: 5px;">Điểm A1 (20%):</label>
          <input type="text" id="a1" name="a1" value="<?= e($diemA1) ?>" placeholder="0.0 - 10.0" style="width: 100%; padding: 8px; border: 1px solid <?= isset($loiDiem['a1']) ? '#dc2626' : '#cbd5e1' ?>; border-radius: 4px;">
          <?php if (isset($loiDiem['a1'])): ?>
            <div style="color: #dc2626; font-size: 0.8rem; margin-top: 4px;"><?= e($loiDiem['a1']) ?></div>
          <?php endif; ?>
        </div>

        <div style="flex: 1; min-width: 140px;">
          <label for="a2" style="display: block; font-weight: bold; margin-bottom: 5px;">Điểm A2 (30%):</label>
          <input type="text" id="a2" name="a2" value="<?= e($diemA2) ?>" placeholder="0.0 - 10.0" style="width: 100%; padding: 8px; border: 1px solid <?= isset($loiDiem['a2']) ? '#dc2626' : '#cbd5e1' ?>; border-radius: 4px;">
          <?php if (isset($loiDiem['a2'])): ?>
            <div style="color: #dc2626; font-size: 0.8rem; margin-top: 4px;"><?= e($loiDiem['a2']) ?></div>
          <?php endif; ?>
        </div>

        <div style="flex: 1; min-width: 140px;">
          <label for="a3" style="display: block; font-weight: bold; margin-bottom: 5px;">Điểm A3 (50%):</label>
          <input type="text" id="a3" name="a3" value="<?= e($diemA3) ?>" placeholder="0.0 - 10.0" style="width: 100%; padding: 8px; border: 1px solid <?= isset($loiDiem['a3']) ? '#dc2626' : '#cbd5e1' ?>; border-radius: 4px;">
          <?php if (isset($loiDiem['a3'])): ?>
            <div style="color: #dc2626; font-size: 0.8rem; margin-top: 4px;"><?= e($loiDiem['a3']) ?></div>
          <?php endif; ?>
        </div>
      </div>

      <button type="submit" class="nut nut--chinh">🧮 Tính toán kết quả</button>
      <a href="gioithieu.php" class="nut nut--vien" style="text-decoration: none; margin-left: 10px;">Làm lại</a>
    </form>

    <?php if ($ketQuaDiem !== null): ?>
      <div style="background: #ffffff; padding: 20px; border-radius: 6px; border: 2px solid <?= e($ketQuaDiem['mau']) ?>; max-width: 600px;">
        <h3 style="margin-top: 0; color: <?= e($ketQuaDiem['mau']) ?>;">
          Kết quả học phần: <?= $ketQuaDiem['tongKet'] ?> / 10.0
        </h3>
        <p style="margin: 5px 0;"><strong>Điểm chữ:</strong> <span style="font-size: 1.2rem; font-weight: bold; color: <?= e($ketQuaDiem['mau']) ?>;"><?= e($ketQuaDiem['chu']) ?></span></p>
        <p style="margin: 5px 0;"><strong>Xếp loại học lực:</strong> <strong><?= e($ketQuaDiem['loai']) ?></strong></p>
        <p style="margin: 5px 0;"><strong>Trạng thái:</strong> <?= ($ketQuaDiem['chu'] !== 'F') ? '<span style="color: #16a34a; font-weight: bold;">ĐÃ QUA MÔN ✓</span>' : '<span style="color: #dc2626; font-weight: bold;">HỌC LẠI ✗</span>' ?></p>
      </div>
    <?php endif; ?>
  </section>

  <!-- Chức năng 2: Danh sách đề án & Lọc theo thể loại -->
  <section style="background: #ffffff; padding: 25px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 35px;">
    <h2>Chức năng máy chủ 2: Danh mục đề án cá nhân (Lọc tham số GET)</h2>
    <p>Dữ liệu được khai báo trong mảng PHP máy chủ và lọc động theo URL <code>?theloai=...</code>:</p>

    <!-- Bộ nút lọc GET -->
    <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
      <a href="gioithieu.php?theloai=tat-ca" class="nut <?= $theLoaiChon === 'tat-ca' ? 'nut--chinh' : 'nut--vien' ?>" style="text-decoration: none;">Tất cả (<?= count($tatCaDuAn) ?>)</a>
      <a href="gioithieu.php?theloai=web" class="nut <?= $theLoaiChon === 'web' ? 'nut--chinh' : 'nut--vien' ?>" style="text-decoration: none;">Ứng dụng Web</a>
      <a href="gioithieu.php?theloai=di-dong" class="nut <?= $theLoaiChon === 'di-dong' ? 'nut--chinh' : 'nut--vien' ?>" style="text-decoration: none;">Ứng dụng Di động</a>
      <a href="gioithieu.php?theloai=csdl" class="nut <?= $theLoaiChon === 'csdl' ? 'nut--chinh' : 'nut--vien' ?>" style="text-decoration: none;">Cơ sở dữ liệu</a>
    </div>

    <div style="display: flex; flex-direction: column; gap: 15px;">
      <?php foreach ($danhSachDuAn as $da): ?>
        <article style="background: #f8fafc; padding: 18px; border-radius: 6px; border-left: 4px solid #0284c7;">
          <h3 style="margin-top: 0; color: #0f172a;"><?= e($da['ten']) ?> (<?= $da['nam'] ?>)</h3>
          <p style="margin: 4px 0;"><strong>Công nghệ áp dụng:</strong> <?= e($da['congNghe']) ?></p>
          <p style="margin: 4px 0; color: #475569;"><strong>Đóng góp cá nhân:</strong> <?= e($da['vaiTro']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Thời khóa biểu -->
  <section style="margin-bottom: 30px;">
    <h2>Thời khóa biểu học tập</h2>
    <div class="khung-cuon-tkb">
      <table>
        <caption>Thời khóa biểu học phần của sinh viên Phommaket Hatsady</caption>
        <thead>
          <tr>
            <th scope="col">Buổi</th>
            <th scope="col">Thứ Hai</th>
            <th scope="col">Thứ Ba</th>
            <th scope="col">Thứ Tư</th>
            <th scope="col">Thứ Năm</th>
            <th scope="col">Thứ Sáu</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">Sáng</th>
            <td>Thiết kế Web (A3.201)</td>
            <td>Tự học</td>
            <td>Mạng máy tính</td>
            <td>Toán rời rạc</td>
            <td>Thực hành Web (PM02)</td>
          </tr>
          <tr>
            <th scope="row">Chiều</th>
            <td>Cơ sở dữ liệu</td>
            <td>Thực hành CSDL</td>
            <td>Tiếng Anh chuyên ngành</td>
            <td>Nghỉ</td>
            <td>Giáo dục thể chất</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</main>

<script type="module" src="js/canhan.js"></script>

<?php require __DIR__ . '/../../inc/footer.php'; ?>
