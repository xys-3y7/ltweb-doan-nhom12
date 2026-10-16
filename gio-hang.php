<?php
/**
 * gio-hang.php - Quản lý giỏ hàng mua sách lưu trong Session
 * Nhóm 12 - Lớp 24CNTT3 - Trường ĐH Sư phạm - ĐH Đà Nẵng
 * Phụ trách: Phommaket Hatsady (MSV: 3120224181) & Xaiyasith Yoi (MSV: 3120224189)
 * Kỹ thuật: Session, kiểm tra số lượng min/max, PRG (Post/Redirect/Get), bảo vệ giá từ máy chủ
 */

require_once __DIR__ . '/inc/config.php';

use App\Data\KhoSanPham;
use App\Services\GioHang;

$kho = new KhoSanPham(__DIR__ . '/data/san-pham.json');
$gio = new GioHang();

// Xử lý các thao tác giỏ hàng qua phương thức POST (theo mẫu Post/Redirect/Get)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'them') {
        $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
        $soLuong = filter_var($_POST['soLuong'] ?? 1, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 99]
        ]);

        if ($id === false || $soLuong === false || $kho->timTheoId($id) === null) {
            $_SESSION['flash_loi'] = 'Thao tác không hợp lệ: Mã sách không tồn tại hoặc số lượng không nằm trong khoảng 1 - 99.';
        } else {
            $gio->them($id, $soLuong, $kho);
            $_SESSION['flash'] = 'Đã thêm sách vào giỏ hàng thành công!';
        }
        header('Location: gio-hang.php');
        exit;
    }

    if ($action === 'capnhat') {
        $cacSoLuong = $_POST['so_luong'] ?? [];
        if (is_array($cacSoLuong)) {
            foreach ($cacSoLuong as $id => $sl) {
                $idInt = filter_var($id, FILTER_VALIDATE_INT);
                $slInt = filter_var($sl, FILTER_VALIDATE_INT);
                if ($idInt !== false && $slInt !== false) {
                    $gio->capNhat($idInt, (int)$slInt);
                }
            }
            $_SESSION['flash'] = 'Đã cập nhật số lượng sách trong giỏ!';
        }
        header('Location: gio-hang.php');
        exit;
    }

    if ($action === 'xoa') {
        $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
        if ($id !== false) {
            $gio->xoa($id);
            $_SESSION['flash'] = 'Đã xóa cuốn sách khỏi giỏ hàng.';
        }
        header('Location: gio-hang.php');
        exit;
    }

    if ($action === 'xoahet') {
        $gio->xoaHet();
        $_SESSION['flash'] = 'Đã làm trống toàn bộ giỏ hàng.';
        header('Location: gio-hang.php');
        exit;
    }
}

// Lấy thông báo flash một lần duy nhất
$thongBao = $_SESSION['flash'] ?? '';
$thongBaoLoi = $_SESSION['flash_loi'] ?? '';
unset($_SESSION['flash'], $_SESSION['flash_loi']);

$chiTietGio = $gio->layChiTiet($kho);
$tongTien = $gio->tongTien($kho);
$soMon = $gio->soMon();

$tieuDe = 'Giỏ hàng của bạn';
$trang = 'gio-hang';
$moTaTrang = 'Xem và quản lý danh sách giáo trình đã chọn trong giỏ hàng BookNest.';

require __DIR__ . '/inc/header.php';
?>

<main class="noi-dung-chinh khung-chua">
  <h1>Giỏ hàng sách của bạn</h1>
  <p>Toàn bộ danh mục được lưu giữ trong phiên làm việc an toàn của máy chủ (PHP Session).</p>

  <!-- Thông báo trạng thái Flash Message -->
  <?php if ($thongBao !== ''): ?>
    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px;" aria-live="polite">
      <strong>✓ Thành công:</strong> <?= e($thongBao) ?>
    </div>
  <?php endif; ?>

  <?php if ($thongBaoLoi !== ''): ?>
    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px;" aria-live="assertive">
      <strong>⚠ Lỗi:</strong> <?= e($thongBaoLoi) ?>
    </div>
  <?php endif; ?>

  <?php if ($soMon > 0): ?>
    <form method="POST" action="gio-hang.php">
      <input type="hidden" name="action" value="capnhat">

      <div style="overflow-x: auto; margin-bottom: 25px;">
        <table style="width: 100%; border-collapse: collapse; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px;">
          <thead>
            <tr style="background: #f8fafc; border-bottom: 2px solid #cbd5e1; text-align: left;">
              <th style="padding: 12px 15px;">Hình ảnh</th>
              <th style="padding: 12px 15px;">Tên giáo trình</th>
              <th style="padding: 12px 15px;">Đơn giá</th>
              <th style="padding: 12px 15px; width: 120px;">Số lượng</th>
              <th style="padding: 12px 15px;">Thành tiền</th>
              <th style="padding: 12px 15px; text-align: center;">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($chiTietGio as $item): 
              $sp = $item['sanPham'];
            ?>
              <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 12px 15px; width: 80px;">
                  <img src="<?= e($sp->hinhAnh) ?>" alt="Bìa sách <?= e($sp->ten) ?>" width="60" height="75" style="border-radius: 4px; object-fit: cover;">
                </td>
                <td style="padding: 12px 15px;">
                  <a href="chi-tiet.php?id=<?= $sp->id ?>" style="font-weight: bold; color: #0284c7; text-decoration: none;">
                    <?= e($sp->ten) ?>
                  </a>
                  <div style="color: #64748b; font-size: 0.85rem; margin-top: 3px;">Tác giả: <?= e($sp->tacGia) ?></div>
                </td>
                <td style="padding: 12px 15px; font-weight: 500;">
                  <?= vnd($sp->gia) ?>
                </td>
                <td style="padding: 12px 15px;">
                  <input type="number" name="so_luong[<?= $sp->id ?>]" value="<?= $item['soLuong'] ?>" min="0" max="99" style="width: 70px; padding: 6px 8px; border: 1px solid #cbd5e1; border-radius: 4px; text-align: center;">
                </td>
                <td style="padding: 12px 15px; font-weight: bold; color: #dc2626;">
                  <?= vnd($item['thanhTien']) ?>
                </td>
                <td style="padding: 12px 15px; text-align: center;">
                  <button type="submit" form="form-xoa-<?= $sp->id ?>" class="nut nut--vien" style="padding: 5px 10px; color: #dc2626; border-color: #fca5a5; font-size: 0.85rem;">
                    Xóa
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr style="background: #f8fafc; font-size: 1.1rem; border-top: 2px solid #cbd5e1;">
              <td colspan="3" style="padding: 15px; font-weight: bold; text-align: right;">
                Tổng cộng (<?= $soMon ?> món):
              </td>
              <td colspan="3" style="padding: 15px; font-weight: bold; color: #dc2626; font-size: 1.3rem;">
                <?= vnd($tongTien) ?>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>

      <!-- Các nút điều hướng thao tác -->
      <div style="display: flex; gap: 15px; justify-content: space-between; align-items: center; flex-wrap: wrap;">
        <div style="display: flex; gap: 10px;">
          <a href="danh-sach.php" class="nut nut--vien">&larr; Tiếp tục chọn sách</a>
          <button type="submit" class="nut nut--vien">🔄 Cập nhật giỏ hàng</button>
        </div>
        <div style="display: flex; gap: 10px;">
          <button type="submit" form="form-xoa-het" class="nut nut--vien" style="color: #dc2626; border-color: #fca5a5;">
            🗑️ Xóa toàn bộ
          </button>
          <a href="lien-he.php" class="nut nut--chinh" style="padding: 10px 24px; font-size: 1rem;">
            Tiến hành đặt sách &rarr;
          </a>
        </div>
      </div>
    </form>

    <!-- Các form xóa riêng lẻ ngoài bảng (để tránh lồng form HTML) -->
    <?php foreach ($chiTietGio as $item): ?>
      <form id="form-xoa-<?= $item['sanPham']->id ?>" method="POST" action="gio-hang.php" style="display: none;">
        <input type="hidden" name="action" value="xoa">
        <input type="hidden" name="id" value="<?= $item['sanPham']->id ?>">
      </form>
    <?php endforeach; ?>

    <form id="form-xoa-het" method="POST" action="gio-hang.php" style="display: none;">
      <input type="hidden" name="action" value="xoahet">
    </form>

  <?php else: ?>
    <!-- Giao diện khi giỏ hàng trống -->
    <div style="text-align: center; padding: 50px 20px; background: #ffffff; border-radius: 8px; border: 1px dashed #cbd5e1; margin-top: 20px;">
      <p style="font-size: 3rem; margin: 0 0 10px 0;">🛒</p>
      <h2>Giỏ hàng của bạn đang trống</h2>
      <p style="color: #64748b; max-width: 450px; margin: 10px auto 25px auto;">
        Bạn chưa thêm cuốn giáo trình nào vào giỏ. Hãy ghé thăm danh mục sách để tìm những tài liệu học tập bổ ích nhé!
      </p>
      <a href="danh-sach.php" class="nut nut--chinh" style="padding: 10px 24px;">Khám phá Danh mục sách ngay</a>
    </div>
  <?php endif; ?>
</main>

<?php require __DIR__ . '/inc/footer.php'; ?>
