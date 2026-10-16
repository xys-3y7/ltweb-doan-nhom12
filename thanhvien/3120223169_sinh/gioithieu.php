<?php
/**
 * thanhvien/3120223169_sinh/gioithieu.php - Trang giới thiệu cá nhân Nguyễn Thị Sinh
 * Tệp này hiển thị lý lịch, thời khóa biểu và tích hợp 2 chức năng xử lý máy chủ:
 * 1. Tải lên và đổi ảnh đại diện an toàn (finfo MIME type, <= 2MB, đổi tên ngẫu nhiên)
 * 2. Biểu mẫu gửi góp ý & đánh giá cá nhân (lưu vào storage/3120223169_danhgia.jsonl, PRG)
 * Cách thử: Mở trình duyệt tại http://localhost:8000/thanhvien/3120223169_sinh/gioithieu.php
 */

$goc = '../../';
require_once __DIR__ . '/../../inc/config.php';

$thuMucStorage = __DIR__ . '/../../storage';
if (!is_dir($thuMucStorage)) {
    mkdir($thuMucStorage, 0777, true);
}
$tepAvatarSinh = $thuMucStorage . '/3120223169_avatar.txt';
$tepDanhGia = $thuMucStorage . '/3120223169_danhgia.jsonl';

// Chức năng 1: Xử lý tải ảnh đại diện an toàn
$loiAvatar = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'doi_avatar') {
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['avatar'];

        if ($file['size'] > 2 * 1024 * 1024) {
            $loiAvatar = 'Dung lượng ảnh vượt quá 2 MB cho phép.';
        } else {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($file['tmp_name']);
            $duoiHopLe = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp'
            ];

            if (!array_key_exists($mime, $duoiHopLe)) {
                $loiAvatar = 'Chỉ chấp nhận định dạng ảnh JPG, PNG hoặc WebP.';
            } else {
                $thuMucAvatar = __DIR__ . '/../../uploads/avatars';
                if (!is_dir($thuMucAvatar)) {
                    mkdir($thuMucAvatar, 0777, true);
                }
                $tenMoi = 'sinh_' . bin2hex(random_bytes(6)) . '.' . $duoiHopLe[$mime];
                if (move_uploaded_file($file['tmp_name'], $thuMucAvatar . '/' . $tenMoi)) {
                    file_put_contents($tepAvatarSinh, $tenMoi, LOCK_EX);
                    $_SESSION['flash_sinh'] = 'Đã cập nhật ảnh đại diện mới thành công!';
                    header('Location: gioithieu.php');
                    exit;
                } else {
                    $loiAvatar = 'Không thể lưu ảnh vào máy chủ.';
                }
            }
        }
    } else {
        $loiAvatar = 'Vui lòng chọn một tệp ảnh để tải lên.';
    }
}

// Chức năng 2: Xử lý gửi góp ý cá nhân
$loiDanhGia = [];
$duDanhGia = ['ten' => '', 'sao' => '5', 'noiDung' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'gui_danhgia') {
    $duDanhGia['ten'] = trim($_POST['ten'] ?? '');
    $duDanhGia['sao'] = trim($_POST['sao'] ?? '5');
    $duDanhGia['noiDung'] = trim($_POST['noiDung'] ?? '');

    if ($duDanhGia['ten'] === '') {
        $loiDanhGia['ten'] = 'Vui lòng nhập tên của bạn.';
    } elseif (mb_strlen($duDanhGia['ten']) < 2) {
        $loiDanhGia['ten'] = 'Tên phải có ít nhất 2 ký tự.';
    }

    $saoInt = filter_var($duDanhGia['sao'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 5]]);
    if ($saoInt === false) {
        $loiDanhGia['sao'] = 'Đánh giá phải từ 1 đến 5 sao.';
    }

    if ($duDanhGia['noiDung'] === '') {
        $loiDanhGia['noiDung'] = 'Vui lòng nhập nội dung góp ý.';
    } elseif (mb_strlen($duDanhGia['noiDung']) < 5) {
        $loiDanhGia['noiDung'] = 'Nội dung góp ý phải có ít nhất 5 ký tự.';
    }

    if (empty($loiDanhGia)) {
        $banGhi = [
            'thoiGian' => date('d/m/Y H:i'),
            'ten'      => $duDanhGia['ten'],
            'sao'      => $saoInt,
            'noiDung'  => $duDanhGia['noiDung']
        ];
        file_put_contents($tepDanhGia, json_encode($banGhi, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND | LOCK_EX);

        $_SESSION['flash_sinh'] = 'Cảm ơn bạn đã gửi đánh giá & góp ý quý báu!';
        header('Location: gioithieu.php');
        exit;
    }
}

$thongBaoSinh = $_SESSION['flash_sinh'] ?? '';
unset($_SESSION['flash_sinh']);

// Đọc ảnh đại diện hiện tại
$anhAvatarHienTai = '../../images/sinh-avatar.png';
if (is_file($tepAvatarSinh)) {
    $tenTep = trim(file_get_contents($tepAvatarSinh));
    if ($tenTep !== '' && is_file(__DIR__ . '/../../uploads/avatars/' . $tenTep)) {
        $anhAvatarHienTai = '../../uploads/avatars/' . $tenTep;
    }
}

// Đọc danh sách đánh giá
$danhSachDanhGia = [];
if (is_file($tepDanhGia)) {
    $dong = file($tepDanhGia, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($dong) {
        $ds = array_map(fn($d) => json_decode($d, true), $dong);
        $danhSachDanhGia = array_slice(array_reverse(array_filter($ds, 'is_array')), 0, 5);
    }
}

$tieuDe = 'Hồ sơ sinh viên: Nguyễn Thị Sinh';
$trang = 'gioi-thieu';
$moTaTrang = 'Hồ sơ lý lịch, thời khóa biểu và chức năng PHP cá nhân của Nguyễn Thị Sinh.';

require __DIR__ . '/../../inc/header.php';
?>

<link rel="stylesheet" href="css/style.css">

<main class="noi-dung-chinh khung-chua">
  <h1>Hồ sơ sinh viên: Nguyễn Thị Sinh</h1>

  <?php if ($thongBaoSinh !== ''): ?>
    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px;">
      ✓ <?= e($thongBaoSinh) ?>
    </div>
  <?php endif; ?>

  <!-- Khối lý lịch -->
  <section class="ho-so-ca-nhan" style="background: #ffffff; padding: 25px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 30px;">
    <figure style="text-align: center;">
      <img src="<?= e($anhAvatarHienTai) ?>" alt="Ảnh chân dung sinh viên Nguyễn Thị Sinh" width="140" height="140" style="border-radius: 50%; border: 3px solid #0284c7; object-fit: cover;">
      <figcaption><strong>Nguyễn Thị Sinh</strong><br>Sinh viên Khoa Toán &ndash; Tin</figcaption>
      
      <!-- Chức năng 1: Form đổi ảnh đại diện an toàn -->
      <form method="POST" action="gioithieu.php" enctype="multipart/form-data" style="margin-top: 15px;">
        <input type="hidden" name="action" value="doi_avatar">
        <label for="sinh-avatar" style="display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 4px;">Đổi ảnh đại diện (≤ 2MB):</label>
        <input type="file" id="sinh-avatar" name="avatar" accept="image/jpeg,image/png,image/webp" style="font-size: 0.8rem; width: 100%; margin-bottom: 6px;">
        <button type="submit" class="nut nut--vien" style="padding: 4px 10px; font-size: 0.8rem;">Cập nhật ảnh</button>
        <?php if ($loiAvatar !== ''): ?>
          <div style="color: #dc2626; font-size: 0.8rem; margin-top: 4px;"><?= e($loiAvatar) ?></div>
        <?php endif; ?>
      </form>
    </figure>

    <div>
      <h2>Thông tin chi tiết</h2>
      <ul>
        <li><strong>Họ và tên:</strong> Nguyễn Thị Sinh</li>
        <li><strong>Mã sinh viên:</strong> 3120223169</li>
        <li><strong>Lớp sinh hoạt:</strong> 23CNTT1</li>
        <li><strong>Chuyên ngành:</strong> Công nghệ thông tin</li>
        <li><strong>Đơn vị đào tạo:</strong> Khoa Toán &ndash; Tin, Trường Đại học Sư phạm &ndash; Đại học Đà Nẵng</li>
        <li><strong>Vai trò trong nhóm:</strong> Thành viên (Phụ trách trang chi tiết <code>chi-tiet.php</code>, cookie <code>da_xem</code>, bảo vệ đăng nhập và trang quản trị)</li>
        <li><strong>Email học tập:</strong> <a href="mailto:3120223169@ued.udn.vn">3120223169@ued.udn.vn</a></li>
      </ul>
    </div>
  </section>

  <!-- HAI CHỨC NĂNG XỬ LÝ PHÍA MÁY CHỦ (PHẦN C) -->
  
  <!-- Chức năng 2: Sổ góp ý & Đánh giá cá nhân -->
  <section style="background: #f8fafc; padding: 25px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 35px;">
    <h2>Chức năng máy chủ: Sổ đánh giá &amp; Góp ý cá nhân</h2>
    <p>Gửi ý kiến đóng góp cho Sinh &mdash; Dữ liệu được kiểm tra nghiêm ngặt và lưu vào <code>storage/3120223169_danhgia.jsonl</code>:</p>

    <form method="POST" action="gioithieu.php" style="max-width: 600px; margin-bottom: 25px;" novalidate>
      <input type="hidden" name="action" value="gui_danhgia">

      <div style="margin-bottom: 15px;">
        <label for="sinh-ten" style="display: block; font-weight: bold; margin-bottom: 5px;">Họ tên của bạn <span style="color: #dc2626;">*</span>:</label>
        <input type="text" id="sinh-ten" name="ten" value="<?= e($duDanhGia['ten']) ?>" placeholder="Ví dụ: Bạn học cùng nhóm" style="width: 100%; padding: 8px 12px; border: 1px solid <?= isset($loiDanhGia['ten']) ? '#dc2626' : '#cbd5e1' ?>; border-radius: 4px;">
        <?php if (isset($loiDanhGia['ten'])): ?>
          <div style="color: #dc2626; font-size: 0.85rem; margin-top: 4px;"><?= e($loiDanhGia['ten']) ?></div>
        <?php endif; ?>
      </div>

      <div style="margin-bottom: 15px;">
        <label for="sinh-sao" style="display: block; font-weight: bold; margin-bottom: 5px;">Mức độ hài lòng hợp tác:</label>
        <select id="sinh-sao" name="sao" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 4px;">
          <option value="5" <?= $duDanhGia['sao'] === '5' ? 'selected' : '' ?>>⭐⭐⭐⭐⭐ Xuất sắc (5 sao)</option>
          <option value="4" <?= $duDanhGia['sao'] === '4' ? 'selected' : '' ?>>⭐⭐⭐⭐ Rất tốt (4 sao)</option>
          <option value="3" <?= $duDanhGia['sao'] === '3' ? 'selected' : '' ?>>⭐⭐⭐ Khá tốt (3 sao)</option>
          <option value="2" <?= $duDanhGia['sao'] === '2' ? 'selected' : '' ?>>⭐⭐ Cần cải thiện (2 sao)</option>
          <option value="1" <?= $duDanhGia['sao'] === '1' ? 'selected' : '' ?>>⭐ Kém (1 sao)</option>
        </select>
      </div>

      <div style="margin-bottom: 15px;">
        <label for="sinh-noidung" style="display: block; font-weight: bold; margin-bottom: 5px;">Nội dung nhận xét <span style="color: #dc2626;">*</span>:</label>
        <textarea id="sinh-noidung" name="noiDung" rows="3" placeholder="Góp ý về kỹ năng làm việc nhóm, đồ án..." style="width: 100%; padding: 8px 12px; border: 1px solid <?= isset($loiDanhGia['noiDung']) ? '#dc2626' : '#cbd5e1' ?>; border-radius: 4px;"><?= e($duDanhGia['noiDung']) ?></textarea>
        <?php if (isset($loiDanhGia['noiDung'])): ?>
          <div style="color: #dc2626; font-size: 0.85rem; margin-top: 4px;"><?= e($loiDanhGia['noiDung']) ?></div>
        <?php endif; ?>
      </div>

      <button type="submit" class="nut nut--chinh">📝 Gửi đánh giá</button>
    </form>

    <!-- Hiển thị các đánh giá mới nhất -->
    <h3>Đánh giá gần đây từ bạn bè:</h3>
    <?php if (!empty($danhSachDanhGia)): ?>
      <div style="display: flex; flex-direction: column; gap: 12px;">
        <?php foreach ($danhSachDanhGia as $dg): ?>
          <div style="background: #ffffff; padding: 15px; border-radius: 6px; border: 1px solid #e2e8f0;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
              <strong><?= e($dg['ten']) ?></strong>
              <span style="color: #d97706;"><?= str_repeat('⭐', (int)($dg['sao'] ?? 5)) ?> (<?= e($dg['thoiGian']) ?>)</span>
            </div>
            <p style="margin: 0; color: #334155;"><?= nl2br(e($dg['noiDung'])) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p style="color: #64748b; font-style: italic;">Chưa có nhận xét nào. Hãy gửi đánh giá đầu tiên nhé!</p>
    <?php endif; ?>
  </section>

  <!-- Thời khóa biểu -->
  <section style="margin-bottom: 30px;">
    <h2>Thời khóa biểu học tập</h2>
    <div class="khung-cuon-tkb">
      <table>
        <caption>Thời khóa biểu học phần của sinh viên Nguyễn Thị Sinh</caption>
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
            <td>Tiếng Anh chuyên ngành</td>
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
