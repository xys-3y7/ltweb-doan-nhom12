<?php
/**
 * lien-he.php - Biểu mẫu liên hệ, tư vấn giáo trình và đính kèm tệp ảnh minh chứng
 * Nhóm 12 - Lớp 24CNTT3 - Trường ĐH Sư phạm - ĐH Đà Nẵng
 * Phụ trách: Vongsena Sauphasith (MSV: 3120224186)
 * Kỹ thuật: Kiểm tra máy chủ nghiêm ngặt, upload ảnh finfo chống giả mạo đuôi, PRG + Flash message
 */

require_once __DIR__ . '/inc/config.php';

use App\Data\KhoLienHe;

$du = [
    'hoten'   => '',
    'email'   => '',
    'sdt'     => '',
    'noidung' => ''
];
$loi = [];
$tenAnhLuu = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Thu nhận và làm sạch dữ liệu đầu vào
    $du['hoten']   = trim($_POST['hoten'] ?? '');
    $du['email']   = trim($_POST['email'] ?? '');
    $du['sdt']     = trim($_POST['sdt'] ?? '');
    $du['noidung'] = trim($_POST['noidung'] ?? '');

    // 2. Kiểm tra tính hợp lệ của từng trường dữ liệu phía máy chủ (Server-side validation)
    if ($du['hoten'] === '') {
        $loi['hoten'] = 'Vui lòng nhập họ và tên của bạn.';
    } elseif (mb_strlen($du['hoten']) < 2 || mb_strlen($du['hoten']) > 100) {
        $loi['hoten'] = 'Họ và tên phải có độ dài từ 2 đến 100 ký tự.';
    }

    if ($du['email'] === '') {
        $loi['email'] = 'Vui lòng cung cấp địa chỉ email.';
    } elseif (!filter_var($du['email'], FILTER_VALIDATE_EMAIL)) {
        $loi['email'] = 'Địa chỉ email không đúng định dạng (ví dụ: sinhvien@ued.udn.vn).';
    }

    if ($du['sdt'] === '') {
        $loi['sdt'] = 'Vui lòng nhập số điện thoại liên lạc.';
    } elseif (!preg_match('/^(0|\+84)[0-9]{9,10}$/', $du['sdt'])) {
        $loi['sdt'] = 'Số điện thoại không hợp lệ (gồm 10-11 chữ số, bắt đầu bằng 0 hoặc +84).';
    }

    if ($du['noidung'] === '') {
        $loi['noidung'] = 'Vui lòng nhập nội dung liên hệ hoặc yêu cầu đặt sách.';
    } elseif (mb_strlen($du['noidung']) < 10) {
        $loi['noidung'] = 'Nội dung liên hệ phải có ít nhất 10 ký tự.';
    }

    // 3. Xử lý tệp đính kèm (nếu người dùng có tải lên)
    if (isset($_FILES['anh']) && $_FILES['anh']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['anh'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $loi['anh'] = 'Lỗi trong quá trình tải tệp lên máy chủ (Mã lỗi: ' . $file['error'] . ').';
        } elseif ($file['size'] > 2 * 1024 * 1024) { // 2 Megabytes
            $loi['anh'] = 'Dung lượng tệp đính kèm vượt quá giới hạn 2 MB cho phép.';
        } else {
            // Kiểm tra MIME Type thực tế của nội dung tệp bằng finfo (chống giả mạo đuôi tệp .jpg)
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($file['tmp_name']);

            $duoiHopLe = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp'
            ];

            if (!array_key_exists($mime, $duoiHopLe)) {
                $loi['anh'] = 'Tệp đính kèm không phải là ảnh hợp lệ! Chỉ chấp nhận ảnh JPG, PNG hoặc WebP.';
            } else {
                // Tạo tên tệp ngẫu nhiên an toàn bằng chuỗi ngẫu nhiên cryptographically secure
                $ext = $duoiHopLe[$mime];
                $tenNgauNhien = 'lh_' . bin2hex(random_bytes(8)) . '.' . $ext;
                $duongDanDich = __DIR__ . '/uploads/' . $tenNgauNhien;

                if (!is_dir(__DIR__ . '/uploads')) {
                    mkdir(__DIR__ . '/uploads', 0777, true);
                }

                if (move_uploaded_file($file['tmp_name'], $duongDanDich)) {
                    $tenAnhLuu = $tenNgauNhien;
                } else {
                    $loi['anh'] = 'Không thể lưu tệp ảnh tải lên vào thư mục máy chủ.';
                }
            }
        }
    }

    // 4. Nếu toàn bộ dữ liệu hợp lệ -> Lưu vào tệp storage/lien-he.jsonl và chuyển hướng PRG
    if (empty($loi)) {
        $khoLh = new KhoLienHe(__DIR__ . '/storage/lien-he.jsonl');
        $khoLh->them([
            'thoiGian' => date('Y-m-d H:i:s'),
            'hoTen'    => $du['hoten'],
            'email'    => $du['email'],
            'sdt'      => $du['sdt'],
            'noiDung'  => $du['noidung'],
            'anh'      => $tenAnhLuu,
            'ip'       => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
        ]);

        $_SESSION['flash'] = 'Cảm ơn bạn! Thông tin liên hệ và đặt giáo trình đã được gửi thành công đến BookNest.';
        header('Location: lien-he.php'); // Mẫu thiết kế PRG chống gửi trùng khi bấm F5
        exit;
    }
}

// Lấy thông báo flash thành công (hiển thị đúng 1 lần)
$thongBao = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

$tieuDe = 'Liên hệ & Đặt sách trực tuyến';
$trang = 'lien-he';
$moTaTrang = 'Gửi phản hồi, yêu cầu đặt in giáo trình chuyên ngành và hỗ trợ sinh viên tại BookNest UED.';

require __DIR__ . '/inc/header.php';
?>

<main class="noi-dung-chinh khung-chua">
  <h1>Liên hệ &amp; Hỗ trợ đặt giáo trình</h1>
  <p>Bạn cần đặt in giáo trình số lượng lớn hoặc có thắc mắc cần giải đáp? Hãy gửi thông tin cho đội ngũ quản trị BookNest.</p>

  <!-- Thông báo trạng thái gửi thành công (Flash message) -->
  <?php if ($thongBao !== ''): ?>
    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 15px 20px; border-radius: 6px; margin-bottom: 25px;" aria-live="polite">
      <h3 style="margin-top: 0; color: #047857;">✓ Gửi thành công!</h3>
      <p style="margin-bottom: 0;"><?= e($thongBao) ?></p>
    </div>
  <?php endif; ?>

  <div style="display: flex; gap: 40px; flex-wrap: wrap;">
    <!-- Form liên hệ (phía máy chủ PHP) -->
    <section style="flex: 2; min-width: 320px; background: #ffffff; padding: 25px; border-radius: 8px; border: 1px solid #e2e8f0;">
      <h2 style="margin-top: 0;">Gửi biểu mẫu trực tuyến</h2>
      <p style="font-size: 0.9rem; color: #64748b; margin-bottom: 20px;">
        Các trường có dấu sao đỏ (<span style="color: #dc2626;">*</span>) là bắt buộc nhập.
      </p>

      <form method="POST" action="lien-he.php" enctype="multipart/form-data" novalidate>
        <!-- Họ và tên -->
        <div style="margin-bottom: 18px;">
          <label for="hoten" style="display: block; font-weight: bold; margin-bottom: 6px;">
            Họ và tên sinh viên / giảng viên <span style="color: #dc2626;">*</span>:
          </label>
          <input type="text" id="hoten" name="hoten" value="<?= e($du['hoten']) ?>" placeholder="Ví dụ: Nguyễn Văn An" style="width: 100%; padding: 10px; border: 1px solid <?= isset($loi['hoten']) ? '#dc2626' : '#cbd5e1' ?>; border-radius: 4px;">
          <?php if (isset($loi['hoten'])): ?>
            <div style="color: #dc2626; font-size: 0.85rem; margin-top: 5px;" role="alert">
              <?= e($loi['hoten']) ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Email -->
        <div style="margin-bottom: 18px;">
          <label for="email" style="display: block; font-weight: bold; margin-bottom: 6px;">
            Địa chỉ email <span style="color: #dc2626;">*</span>:
          </label>
          <input type="email" id="email" name="email" value="<?= e($du['email']) ?>" placeholder="name@ued.udn.vn" style="width: 100%; padding: 10px; border: 1px solid <?= isset($loi['email']) ? '#dc2626' : '#cbd5e1' ?>; border-radius: 4px;">
          <?php if (isset($loi['email'])): ?>
            <div style="color: #dc2626; font-size: 0.85rem; margin-top: 5px;" role="alert">
              <?= e($loi['email']) ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Số điện thoại -->
        <div style="margin-bottom: 18px;">
          <label for="sdt" style="display: block; font-weight: bold; margin-bottom: 6px;">
            Số điện thoại liên lạc <span style="color: #dc2626;">*</span>:
          </label>
          <input type="tel" id="sdt" name="sdt" value="<?= e($du['sdt']) ?>" placeholder="Ví dụ: 0912345678" style="width: 100%; padding: 10px; border: 1px solid <?= isset($loi['sdt']) ? '#dc2626' : '#cbd5e1' ?>; border-radius: 4px;">
          <?php if (isset($loi['sdt'])): ?>
            <div style="color: #dc2626; font-size: 0.85rem; margin-top: 5px;" role="alert">
              <?= e($loi['sdt']) ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Nội dung -->
        <div style="margin-bottom: 18px;">
          <label for="noidung" style="display: block; font-weight: bold; margin-bottom: 6px;">
            Nội dung chi tiết / Yêu cầu đặt sách <span style="color: #dc2626;">*</span>:
          </label>
          <textarea id="noidung" name="noidung" rows="5" placeholder="Ghi rõ tên giáo trình, số lượng hoặc câu hỏi của bạn..." style="width: 100%; padding: 10px; border: 1px solid <?= isset($loi['noidung']) ? '#dc2626' : '#cbd5e1' ?>; border-radius: 4px;"><?= e($du['noidung']) ?></textarea>
          <?php if (isset($loi['noidung'])): ?>
            <div style="color: #dc2626; font-size: 0.85rem; margin-top: 5px;" role="alert">
              <?= e($loi['noidung']) ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Tệp đính kèm -->
        <div style="margin-bottom: 25px;">
          <label for="anh" style="display: block; font-weight: bold; margin-bottom: 6px;">
            Hình ảnh minh chứng hoặc bìa sách cần tìm (Tùy chọn, tối đa 2 MB):
          </label>
          <input type="file" id="anh" name="anh" accept="image/jpeg,image/png,image/webp" style="display: block; width: 100%; padding: 8px 0;">
          <div style="color: #64748b; font-size: 0.85rem; margin-top: 3px;">
            Hệ thống chỉ chấp nhận định dạng ảnh JPG, PNG hoặc WebP.
          </div>
          <?php if (isset($loi['anh'])): ?>
            <div style="color: #dc2626; font-size: 0.85rem; margin-top: 5px;" role="alert">
              <?= e($loi['anh']) ?>
            </div>
          <?php endif; ?>
        </div>

        <button type="submit" class="nut nut--chinh" style="padding: 12px 28px; font-size: 1rem;">
          📤 Gửi thông tin liên hệ
        </button>
      </form>
    </section>

    <!-- Thông tin văn phòng hỗ trợ -->
    <aside style="flex: 1; min-width: 260px;">
      <div style="background: #f8fafc; padding: 25px; border-radius: 8px; border: 1px solid #e2e8f0;">
        <h2 style="margin-top: 0; font-size: 1.25rem;">Văn phòng Nhà sách BookNest</h2>
        <p><strong>Địa chỉ:</strong> Sảnh Tầng 1, Nhà A1, Khoa Toán &ndash; Tin, Trường Đại học Sư phạm &ndash; Đại học Đà Nẵng.</p>
        <p><strong>Số 459:</strong> Đường Tôn Đức Thắng, Phường Hòa Khánh Nam, Quận Liên Chiểu, TP. Đà Nẵng.</p>
        <p><strong>Điện thoại:</strong> (0236) 3841323 &ndash; Máy lẻ 102</p>
        <p><strong>Email:</strong> <a href="mailto:booknest.ued@gmail.com">booknest.ued@gmail.com</a></p>
        <p><strong>Giờ làm việc:</strong> Thứ Hai &ndash; Thứ Bảy (7h30 &ndash; 17h00)</p>
      </div>
    </aside>
  </div>
</main>

<?php require __DIR__ . '/inc/footer.php'; ?>
