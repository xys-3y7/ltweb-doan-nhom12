<?php
/**
 * thanhvien/3120224186_sauphasith/gioithieu.php - Trang giới thiệu cá nhân Vongsena Sauphasith
 * Tệp này hiển thị lý lịch, thời khóa biểu và tích hợp 2 chức năng máy chủ:
 * 1. Bộ đếm lượt xem trang cá nhân (Session + tệp storage/3120224186_luotxem.txt, mỗi phiên tăng 1 lần)
 * 2. Danh sách kỹ năng chuyên môn từ mảng PHP, lọc theo nhóm qua tham số GET (?nhom=...)
 * Cách thử: Mở trình duyệt tại http://localhost:8000/thanhvien/3120224186_sauphasith/gioithieu.php
 */

$goc = '../../';
require_once __DIR__ . '/../../inc/config.php';

// Chức năng 1: Bộ đếm lượt xem trang cá nhân (mỗi phiên session chỉ tăng 1 lần)
$tepLuotXem = __DIR__ . '/../../storage/3120224186_luotxem.txt';
if (!is_dir(dirname($tepLuotXem))) {
    mkdir(dirname($tepLuotXem), 0777, true);
}

$luotXemHienTai = is_file($tepLuotXem) ? (int)file_get_contents($tepLuotXem) : 100;

if (!isset($_SESSION['da_xem_sauphasith'])) {
    $_SESSION['da_xem_sauphasith'] = true;
    $luotXemHienTai++;
    file_put_contents($tepLuotXem, (string)$luotXemHienTai, LOCK_EX);
}

// Chức năng 2: Danh sách kỹ năng từ mảng PHP lọc bằng tham số GET
$tatCaKyNang = [
    ['ten' => 'HTML5 Ngữ nghĩa & Biểu mẫu bảo mật', 'nhom' => 'frontend', 'tiLe' => 95, 'kinhNghiem' => '2 năm'],
    ['ten' => 'CSS3 Responsive & Kiến trúc BEM', 'nhom' => 'frontend', 'tiLe' => 90, 'kinhNghiem' => '2 năm'],
    ['ten' => 'JavaScript ES6 Modules & Fetch API', 'nhom' => 'frontend', 'tiLe' => 85, 'kinhNghiem' => '1.5 năm'],
    ['ten' => 'Lập trình PHP 8 & Kiểm thử Form', 'nhom' => 'backend', 'tiLe' => 88, 'kinhNghiem' => '1 năm'],
    ['ten' => 'Xử lý Upload tệp an toàn (finfo)', 'nhom' => 'backend', 'tiLe' => 92, 'kinhNghiem' => '1 năm'],
    ['ten' => 'Quản lý kho mã nguồn Git / GitHub', 'nhom' => 'devops', 'tiLe' => 85, 'kinhNghiem' => '2 năm'],
    ['ten' => 'Kiểm chuẩn W3C & Đo lường Lighthouse A11y', 'nhom' => 'devops', 'tiLe' => 90, 'kinhNghiem' => '1.5 năm']
];

$nhomChon = $_GET['nhom'] ?? 'tat-ca';
$danhSachKyNang = $tatCaKyNang;

if ($nhomChon !== 'tat-ca') {
    $danhSachKyNang = array_filter($tatCaKyNang, fn($kn) => $kn['nhom'] === $nhomChon);
}

$tieuDe = 'Hồ sơ sinh viên: Vongsena Sauphasith';
$trang = 'gioi-thieu';
$moTaTrang = 'Hồ sơ lý lịch, bộ đếm lượt xem và kỹ năng chuyên môn của Vongsena Sauphasith.';

require __DIR__ . '/../../inc/header.php';
?>

<link rel="stylesheet" href="css/style.css">

<main class="noi-dung-chinh khung-chua">
  <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 20px;">
    <h1>Hồ sơ sinh viên: Vongsena Sauphasith</h1>
    
    <!-- Chức năng 1: Khối huy hiệu đếm lượt xem trang -->
    <div style="background: #e0f2fe; border: 1px solid #7dd3fc; color: #0369a1; padding: 8px 16px; border-radius: 20px; font-weight: bold; font-size: 0.95rem;">
      👁️ Lượt xem hồ sơ: <span style="font-size: 1.15rem; color: #0284c7;"><?= number_format($luotXemHienTai) ?></span>
    </div>
  </div>

  <!-- Khối lý lịch -->
  <section class="ho-so-ca-nhan" style="background: #ffffff; padding: 25px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 30px;">
    <figure>
      <img src="../../images/sauphasith-avatar.png" alt="Ảnh chân dung sinh viên Vongsena Sauphasith" width="140" height="140" style="border-radius: 50%; border: 3px solid #0284c7;">
      <figcaption><strong>Vongsena Sauphasith</strong><br>Sinh viên Khoa Toán &ndash; Tin</figcaption>
    </figure>

    <div>
      <h2>Thông tin chi tiết</h2>
      <ul>
        <li><strong>Họ và tên:</strong> Vongsena Sauphasith</li>
        <li><strong>Mã sinh viên:</strong> 3120224186</li>
        <li><strong>Lớp sinh hoạt:</strong> 24CNTT3</li>
        <li><strong>Chuyên ngành:</strong> Công nghệ thông tin</li>
        <li><strong>Đơn vị đào tạo:</strong> Khoa Toán &ndash; Tin, Trường Đại học Sư phạm &ndash; Đại học Đà Nẵng</li>
        <li><strong>Vai trò trong nhóm:</strong> Thành viên (Phụ trách trang biểu mẫu <code>lien-he.php</code>, kiểm tra dữ liệu máy chủ, upload ảnh qua finfo, lớp <code>KhoLienHe</code> và các ca kiểm thử Bảng 3)</li>
        <li><strong>Email học tập:</strong> <a href="mailto:3120224186@ued.udn.vn">3120224186@ued.udn.vn</a></li>
      </ul>
    </div>
  </section>

  <!-- HAI CHỨC NĂNG XỬ LÝ PHÍA MÁY CHỦ (PHẦN C) -->
  
  <!-- Chức năng 2: Danh sách kỹ năng từ mảng PHP lọc bằng tham số GET -->
  <section style="background: #ffffff; padding: 25px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 35px;">
    <h2>Chức năng máy chủ: Lọc danh mục kỹ năng chuyên môn (Tham số GET)</h2>
    <p>Dữ liệu kỹ năng được định nghĩa trong mảng PHP máy chủ và lọc động theo URL <code>?nhom=...</code>:</p>

    <!-- Nút lọc GET -->
    <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
      <a href="gioithieu.php?nhom=tat-ca" class="nut <?= $nhomChon === 'tat-ca' ? 'nut--chinh' : 'nut--vien' ?>" style="text-decoration: none;">Tất cả kỹ năng (<?= count($tatCaKyNang) ?>)</a>
      <a href="gioithieu.php?nhom=frontend" class="nut <?= $nhomChon === 'frontend' ? 'nut--chinh' : 'nut--vien' ?>" style="text-decoration: none;">Front-end Web</a>
      <a href="gioithieu.php?nhom=backend" class="nut <?= $nhomChon === 'backend' ? 'nut--chinh' : 'nut--vien' ?>" style="text-decoration: none;">Back-end PHP</a>
      <a href="gioithieu.php?nhom=devops" class="nut <?= $nhomChon === 'devops' ? 'nut--chinh' : 'nut--vien' ?>" style="text-decoration: none;">DevOps &amp; Testing</a>
    </div>

    <div style="display: flex; flex-direction: column; gap: 16px;">
      <?php foreach ($danhSachKyNang as $kn): ?>
        <div style="background: #f8fafc; padding: 15px 20px; border-radius: 6px; border: 1px solid #e2e8f0;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-weight: bold;">
            <span><?= e($kn['ten']) ?></span>
            <span style="color: #0284c7;"><?= $kn['tiLe'] ?>% (<?= e($kn['kinhNghiem']) ?>)</span>
          </div>
          <!-- Thanh tiến độ kỹ năng -->
          <div style="background: #e2e8f0; height: 10px; border-radius: 5px; overflow: hidden;">
            <div style="background: #0284c7; height: 100%; width: <?= $kn['tiLe'] ?>%;"></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Thời khóa biểu -->
  <section style="margin-bottom: 30px;">
    <h2>Thời khóa biểu học tập</h2>
    <div class="khung-cuon-tkb">
      <table>
        <caption>Thời khóa biểu học phần của sinh viên Vongsena Sauphasith</caption>
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
            <td>Tự học thư viện</td>
            <td>Mạng máy tính (A2.102)</td>
            <td>Toán rời rạc</td>
            <td>Thực hành Web (PM02)</td>
          </tr>
          <tr>
            <th scope="row">Chiều</th>
            <td>Cơ sở dữ liệu</td>
            <td>Thực hành CSDL</td>
            <td>Tiếng Anh CNTT</td>
            <td>Nghỉ tự học</td>
            <td>Giáo dục thể chất</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</main>

<script type="module" src="js/canhan.js"></script>

<?php require __DIR__ . '/../../inc/footer.php'; ?>
