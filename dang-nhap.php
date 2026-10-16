<?php
/**
 * dang-nhap.php - Đăng nhập tài khoản quản trị hệ thống BookNest
 * Nhóm 12 - Lớp 24CNTT3 - Trường ĐH Sư phạm - ĐH Đà Nẵng
 * Phụ trách: Xaiyasith Yoi (MSV: 3120224189) & Nguyễn Thị Sinh (MSV: 3120223169)
 * Kỹ thuật: password_verify an toàn, cấp lại ID phiên session_regenerate_id(true), ghi log lỗi
 */

require_once __DIR__ . '/inc/config.php';

// Nếu người dùng đã đăng nhập từ trước -> Chuyển thẳng tới trang quản trị
if (isset($_SESSION['user'])) {
    header('Location: quan-tri.php');
    exit;
}

$loi = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $taiKhoanHeThong = require __DIR__ . '/inc/tai-khoan.php';

    // Xác thực tài khoản với mật khẩu mã hóa BCRYPT
    if (
        $username !== '' &&
        isset($taiKhoanHeThong[$username]) &&
        password_verify($password, $taiKhoanHeThong[$username]['matKhau'])
    ) {
        // Cấp lại Session ID mới để ngăn chặn tấn công cố định phiên (Session Fixation)
        session_regenerate_id(true);

        $_SESSION['user'] = $username;
        $_SESSION['hoTen'] = $taiKhoanHeThong[$username]['hoTen'];
        $_SESSION['quyen'] = $taiKhoanHeThong[$username]['quyen'];

        header('Location: quan-tri.php');
        exit;
    } else {
        // Ghi nhật ký vào logs/php-error.log khi có nỗ lực đăng nhập thất bại
        $ipNguoiDung = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        error_log(sprintf(
            "[%s] CẢNH BÁO: Đăng nhập thất bại với tài khoản '%s' từ IP %s\n",
            date('Y-m-d H:i:s'),
            $username,
            $ipNguoiDung
        ));

        // Thông báo lỗi chung bảo mật (không phân biệt sai tên hay sai mật khẩu)
        $loi = 'Sai tên đăng nhập hoặc mật khẩu.';
    }
}

$thongBaoChuyenTrang = $_SESSION['flash_loi'] ?? '';
unset($_SESSION['flash_loi']);

$tieuDe = 'Đăng nhập Quản trị viên';
$trang = 'dang-nhap';
$moTaTrang = 'Cổng đăng nhập quản trị hệ thống BookNest UED.';

require __DIR__ . '/inc/header.php';
?>

<main class="noi-dung-chinh khung-chua">
  <div style="max-width: 450px; margin: 40px auto; background: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
    <h1 style="text-align: center; margin-top: 0; font-size: 1.6rem; color: #0f172a;">Đăng nhập Quản trị</h1>
    <p style="text-align: center; color: #64748b; font-size: 0.95rem; margin-bottom: 25px;">
      Khu vực quản lý hộp thư liên hệ và đơn đặt giáo trình
    </p>

    <!-- Thông báo điều hướng bảo vệ -->
    <?php if ($thongBaoChuyenTrang !== ''): ?>
      <div style="background: #fffbeb; border: 1px solid #fef3c7; color: #b45309; padding: 10px 14px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem;">
        <?= e($thongBaoChuyenTrang) ?>
      </div>
    <?php endif; ?>

    <!-- Thông báo lỗi đăng nhập -->
    <?php if ($loi !== ''): ?>
      <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 10px 14px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem;" role="alert">
        <strong>⚠ Lỗi:</strong> <?= e($loi) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="dang-nhap.php">
      <div style="margin-bottom: 18px;">
        <label for="username" style="display: block; font-weight: bold; margin-bottom: 6px;">Tên đăng nhập:</label>
        <input type="text" id="username" name="username" value="<?= e($username) ?>" required autofocus style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px;">
      </div>

      <div style="margin-bottom: 22px;">
        <label for="password" style="display: block; font-weight: bold; margin-bottom: 6px;">Mật khẩu:</label>
        <input type="password" id="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px;">
      </div>

      <button type="submit" class="nut nut--chinh" style="width: 100%; padding: 11px; font-size: 1rem;">
        🔐 Đăng nhập
      </button>
    </form>

    <!-- Ghi chú tài khoản thử nghiệm của nhóm -->
    <div style="margin-top: 25px; padding-top: 20px; border-top: 1px dashed #cbd5e1; font-size: 0.85rem; color: #64748b; background: #f8fafc; padding: 15px; border-radius: 6px;">
      <strong>Tài khoản thử nghiệm phục vụ chấm bài:</strong>
      <ul style="margin: 6px 0 0 18px; padding: 0;">
        <li>Quản trị: <code>admin</code> | Mật khẩu: <code>admin123</code></li>
        <li>Giảng viên: <code>giaovien</code> | Mật khẩu: <code>ued2026</code></li>
      </ul>
    </div>
  </div>
</main>

<?php require __DIR__ . '/inc/footer.php'; ?>
