<?php
/**
 * thanhvien/3120224189_yoi/gioithieu.php - Trang giới thiệu cá nhân sinh viên Xaiyasith Yoi
 * Tệp này hiển thị hồ sơ cá nhân, thời khóa biểu và tích hợp 2 chức năng máy chủ:
 * 1. Sổ lưu bút sinh viên (lưu vào storage/3120224189_luubut.jsonl, hiển thị 5 lời nhắn mới nhất, PRG)
 * 2. Giao diện Sáng/Tối ghi nhớ trạng thái bằng Cookie máy chủ (chuyển đổi qua GET ?doitheme=1)
 * Cách thử: Mở trình duyệt truy cập http://localhost:8000/thanhvien/3120224189_yoi/gioithieu.php
 */

$goc = '../../';
require_once __DIR__ . '/../../inc/config.php';

// Chức năng 2: Chuyển đổi giao diện Sáng / Tối ghi nhớ bằng Cookie máy chủ
$themeHienTai = $_COOKIE['yoi_theme'] ?? 'light';
if (isset($_GET['doitheme'])) {
    $themeMoi = ($themeHienTai === 'dark') ? 'light' : 'dark';
    setcookie('yoi_theme', $themeMoi, [
        'expires'  => time() + 30 * 24 * 3600,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    header('Location: gioithieu.php');
    exit;
}

// Chức năng 1: Xử lý gửi Sổ lưu bút sinh viên (Guestbook)
$loiYoi = [];
$duYoi = ['ten' => '', 'loiNhan' => ''];
$tepLuuBut = __DIR__ . '/../../storage/3120224189_luubut.jsonl';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'gui_luubut') {
    $duYoi['ten'] = trim($_POST['ten'] ?? '');
    $duYoi['loiNhan'] = trim($_POST['loiNhan'] ?? '');

    if ($duYoi['ten'] === '') {
        $loiYoi['ten'] = 'Vui lòng nhập họ tên hoặc biệt danh của bạn.';
    } elseif (mb_strlen($duYoi['ten']) < 2) {
        $loiYoi['ten'] = 'Tên phải có độ dài từ 2 ký tự trở lên.';
    }

    if ($duYoi['loiNhan'] === '') {
        $loiYoi['loiNhan'] = 'Vui lòng nhập lời nhắn lưu bút.';
    } elseif (mb_strlen($duYoi['loiNhan']) < 5) {
        $loiYoi['loiNhan'] = 'Lời nhắn phải có ít nhất 5 ký tự.';
    }

    if (empty($loiYoi)) {
        $thuMucStorage = dirname($tepLuuBut);
        if (!is_dir($thuMucStorage)) {
            mkdir($thuMucStorage, 0777, true);
        }

        $banGhi = [
            'thoiGian' => date('d/m/Y H:i'),
            'ten'      => $duYoi['ten'],
            'loiNhan'  => $duYoi['loiNhan'],
            'ip'       => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
        ];
        $dong = json_encode($banGhi, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        file_put_contents($tepLuuBut, $dong . PHP_EOL, FILE_APPEND | LOCK_EX);

        $_SESSION['flash_yoi'] = 'Đã gửi lời nhắn vào Sổ lưu bút thành công!';
        header('Location: gioithieu.php');
        exit;
    }
}

$thongBaoYoi = $_SESSION['flash_yoi'] ?? '';
unset($_SESSION['flash_yoi']);

// Đọc 5 lời nhắn mới nhất từ tệp JSONL
$danhSachLuuBut = [];
if (is_file($tepLuuBut)) {
    $cacDong = file($tepLuuBut, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($cacDong) {
        $ds = array_map(fn($d) => json_decode($d, true), $cacDong);
        $danhSachLuuBut = array_slice(array_reverse(array_filter($ds, 'is_array')), 0, 5);
    }
}

$tieuDe = 'Hồ sơ sinh viên: Xaiyasith Yoi (Nhóm trưởng)';
$trang = 'gioi-thieu';
$moTaTrang = 'Hồ sơ lý lịch, thời khóa biểu và chức năng PHP cá nhân của sinh viên Xaiyasith Yoi.';

require __DIR__ . '/../../inc/header.php';
?>

<!-- Nạp thêm CSS riêng của thành viên -->
<link rel="stylesheet" href="css/style.css">

<style>
  <?php if ($themeHienTai === 'dark'): ?>
    body { background-color: #0f172a !important; color: #f8fafc !important; }
    .noi-dung-chinh, .khung-chua { color: #f8fafc !important; }
    .ho-so-ca-nhan, .khu-vuc-chuc-nang, .hop-luu-but { background: #1e293b !important; color: #f8fafc !important; border-color: #334155 !important; }
    table { background: #1e293b !important; color: #f8fafc !important; border-color: #334155 !important; }
    th { background: #334155 !important; color: #f8fafc !important; }
    td { border-color: #334155 !important; color: #cbd5e1 !important; }
  <?php endif; ?>
</style>

<main class="noi-dung-chinh khung-chua">
  <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 20px;">
    <h1>Hồ sơ sinh viên: Xaiyasith Yoi</h1>
    
    <!-- Chức năng 2: Nút chuyển đổi Theme Sáng / Tối (Cookie) -->
    <a href="gioithieu.php?doitheme=1" class="nut nut--vien" style="text-decoration: none;">
      <?= ($themeHienTai === 'dark') ? '☀️ Chuyển sang Giao diện Sáng' : '🌙 Chuyển sang Giao diện Tối (Cookie)' ?>
    </a>
  </div>

  <!-- 1. Khối thông tin lý lịch cá nhân -->
  <section class="ho-so-ca-nhan" style="background: #ffffff; padding: 25px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 30px;">
    <figure>
      <img src="../../images/yoi-avatar.png" alt="Ảnh chân dung sinh viên Xaiyasith Yoi" width="140" height="140" style="border-radius: 50%; border: 3px solid #0284c7;">
      <figcaption><strong>Xaiyasith Yoi</strong><br>Sinh viên Khoa Toán &ndash; Tin</figcaption>
    </figure>

    <div>
      <h2>Thông tin chi tiết</h2>
      <ul>
        <li><strong>Họ và tên:</strong> Xaiyasith Yoi</li>
        <li><strong>Mã sinh viên:</strong> 3120224189</li>
        <li><strong>Lớp sinh hoạt:</strong> 24CNTT3</li>
        <li><strong>Chuyên ngành:</strong> Công nghệ thông tin</li>
        <li><strong>Đơn vị đào tạo:</strong> Khoa Toán &ndash; Tin, Trường Đại học Sư phạm &ndash; Đại học Đà Nẵng</li>
        <li><strong>Vai trò trong nhóm:</strong> Nhóm trưởng (Quản trị dự án, khung PHP MVC dùng chung, Giỏ hàng Session, Git)</li>
        <li><strong>Email học tập:</strong> <a href="mailto:3120224189@ued.udn.vn">3120224189@ued.udn.vn</a></li>
        <li><strong>Tài khoản GitHub:</strong> <a href="https://github.com/xys-3y7" target="_blank" rel="noopener">xys-3y7</a></li>
      </ul>
    </div>
  </section>

  <!-- 2. Danh sách kỹ năng chuyên môn -->
  <section style="margin-bottom: 30px;">
    <h2>Danh sách kỹ năng chuyên môn</h2>
    <ul class="danh-sach-ky-nang">
      <li>PHP 8 Thuần &amp; Kiến trúc MVC / Hướng đối tượng (OOP)</li>
      <li>Quản lý gói Composer &amp; PSR-4 Autoloading</li>
      <li>Kiến trúc CSS3 BEM &amp; Mobile-First Responsive</li>
      <li>Bố cục CSS Grid &amp; Flexbox chuẩn W3C</li>
      <li>HTML5 Ngữ nghĩa &amp; WCAG AA Accessibility</li>
      <li>Quản lý mã nguồn với Git &amp; GitHub Branching</li>
    </ul>
  </section>

  <!-- 3. HAI CHỨC NĂNG XỬ LÝ PHÍA MÁY CHỦ (PHẦN C) -->
  <section class="khu-vuc-chuc-nang" style="background: #f8fafc; padding: 25px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 35px;">
    <h2>Chức năng máy chủ: Sổ lưu bút sinh viên (Guestbook)</h2>
    <p>Gửi lời chúc, nhắn nhủ tới Yoi &mdash; Dữ liệu được máy chủ kiểm tra và ghi nhận an toàn vào <code>storage/3120224189_luubut.jsonl</code>:</p>

    <?php if ($thongBaoYoi !== ''): ?>
      <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 10px 14px; border-radius: 6px; margin-bottom: 20px;">
        ✓ <?= e($thongBaoYoi) ?>
      </div>
    <?php endif; ?>

    <!-- Biểu mẫu Sổ lưu bút (POST - PRG) -->
    <form method="POST" action="gioithieu.php" style="max-width: 600px; margin-bottom: 30px;" novalidate>
      <input type="hidden" name="action" value="gui_luubut">

      <div style="margin-bottom: 15px;">
        <label for="yoi-ten" style="display: block; font-weight: bold; margin-bottom: 5px;">Họ và tên hoặc Biệt danh <span style="color: #dc2626;">*</span>:</label>
        <input type="text" id="yoi-ten" name="ten" value="<?= e($duYoi['ten']) ?>" placeholder="Ví dụ: Bạn học cùng lớp" style="width: 100%; padding: 8px 12px; border: 1px solid <?= isset($loiYoi['ten']) ? '#dc2626' : '#cbd5e1' ?>; border-radius: 4px;">
        <?php if (isset($loiYoi['ten'])): ?>
          <div style="color: #dc2626; font-size: 0.85rem; margin-top: 4px;"><?= e($loiYoi['ten']) ?></div>
        <?php endif; ?>
      </div>

      <div style="margin-bottom: 15px;">
        <label for="yoi-loinhan" style="display: block; font-weight: bold; margin-bottom: 5px;">Lời nhắn gửi Yoi <span style="color: #dc2626;">*</span>:</label>
        <textarea id="yoi-loinhan" name="loiNhan" rows="3" placeholder="Nhập lời nhắn động viên..." style="width: 100%; padding: 8px 12px; border: 1px solid <?= isset($loiYoi['loiNhan']) ? '#dc2626' : '#cbd5e1' ?>; border-radius: 4px;"><?= e($duYoi['loiNhan']) ?></textarea>
        <?php if (isset($loiYoi['loiNhan'])): ?>
          <div style="color: #dc2626; font-size: 0.85rem; margin-top: 4px;"><?= e($loiYoi['loiNhan']) ?></div>
        <?php endif; ?>
      </div>

      <button type="submit" class="nut nut--chinh">✍️ Gửi lưu bút</button>
    </form>

    <!-- Hiển thị 5 lời nhắn gần nhất -->
    <h3>5 Lời nhắn mới nhất đã nhận:</h3>
    <?php if (!empty($danhSachLuuBut)): ?>
      <div style="display: flex; flex-direction: column; gap: 12px;">
        <?php foreach ($danhSachLuuBut as $lb): ?>
          <div class="hop-luu-but" style="background: #ffffff; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 0.85rem; color: #64748b;">
              <strong><?= e($lb['ten']) ?></strong>
              <span><?= e($lb['thoiGian']) ?></span>
            </div>
            <p style="margin: 0; line-height: 1.5;"><?= nl2br(e($lb['loiNhan'])) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p style="color: #64748b; font-style: italic;">Chưa có lời nhắn nào. Hãy là người đầu tiên viết lưu bút nhé!</p>
    <?php endif; ?>
  </section>

  <!-- 4. Bảng thời khóa biểu bọc trong khung cuộn ngang -->
  <section style="margin-bottom: 30px;">
    <h2>Thời khóa biểu học tập học kỳ 1 (Năm học 2026 &ndash; 2027)</h2>
    <div class="khung-cuon-tkb">
      <table>
        <caption>Bảng 1. Thời khóa biểu học tập trong tuần của sinh viên Xaiyasith Yoi</caption>
        <thead>
          <tr>
            <th scope="col">Buổi học</th>
            <th scope="col">Thứ Hai</th>
            <th scope="col">Thứ Ba</th>
            <th scope="col">Thứ Tư</th>
            <th scope="col">Thứ Năm</th>
            <th scope="col">Thứ Sáu</th>
            <th scope="col">Thứ Bảy</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">Sáng (Tiết 1 - 4)</th>
            <td>Thiết kế Web (Lý thuyết - A3.201)</td>
            <td>Tự học thư viện</td>
            <td>Mạng máy tính (Lý thuyết - A2.102)</td>
            <td>Toán rời rạc &amp; Đồ thị</td>
            <td>Thực hành Web (Phòng máy 02)</td>
            <td>Sinh hoạt CLB Tin học</td>
          </tr>
          <tr>
            <th scope="row">Chiều (Tiết 6 - 9)</th>
            <td>Cơ sở dữ liệu (A4.305)</td>
            <td>Thực hành CSDL</td>
            <td>Tiếng Anh chuyên ngành CNTT</td>
            <td>Nghỉ tự học</td>
            <td>Giáo dục thể chất</td>
            <td>Nghỉ cuối tuần</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</main>

<script type="module" src="js/canhan.js"></script>

<?php require __DIR__ . '/../../inc/footer.php'; ?>
