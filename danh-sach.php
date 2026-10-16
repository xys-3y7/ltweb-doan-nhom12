<?php
/**
 * danh-sach.php - Danh mục sách và tìm kiếm, lọc theo thể loại, sắp xếp
 * Nhóm 12 - Lớp 24CNTT3 - Trường ĐH Sư phạm - ĐH Đà Nẵng
 * Phụ trách: Phommaket Hatsady (MSV: 3120224181)
 */

require_once __DIR__ . '/inc/config.php';

use App\Data\KhoSanPham;

$kho = new KhoSanPham(__DIR__ . '/data/san-pham.json');

// Lấy và chuẩn hóa tham số tìm kiếm, lọc, sắp xếp từ URL (phương thức GET)
$q  = trim($_GET['q'] ?? '');
$dm = trim($_GET['dm'] ?? 'tat-ca');
$sx = trim($_GET['sx'] ?? 'mac-dinh');

// Danh sách danh mục và kiểu sắp xếp hợp lệ (Whitelist Validation)
$danhMucHopLe = [
    'tat-ca'        => 'Tất cả danh mục',
    'cntt'          => 'Công nghệ thông tin',
    'toanhoc'       => 'Toán học & Ứng dụng',
    'vanphongpham'  => 'Văn phòng phẩm & Dụng cụ'
];

$sapXepHopLe = [
    'mac-dinh' => 'Mặc định (Mới nhất)',
    'gia-tang' => 'Giá: Thấp đến Cao',
    'gia-giam' => 'Giá: Cao đến Thấp',
    'ten-az'   => 'Tên sách: A - Z',
    'ten-za'   => 'Tên sách: Z - A'
];

if (!array_key_exists($dm, $danhMucHopLe)) {
    $dm = 'tat-ca';
}

if (!array_key_exists($sx, $sapXepHopLe)) {
    $sx = 'mac-dinh';
}

// Thực hiện truy vấn danh sách qua lớp dữ liệu KhoSanPham
$danhSach = $kho->timKiem($q, $dm, $sx);
$tongSo = count($danhSach);

$tieuDe = 'Danh mục giáo trình & Sách chuyên ngành';
$trang = 'danh-sach';
$moTaTrang = 'Khám phá hơn 12 đầu giáo trình CNTT, Toán học và văn phòng phẩm trợ giá cho sinh viên UED.';

require __DIR__ . '/inc/header.php';
?>

<main class="noi-dung-chinh khung-chua">
  <h1>Danh mục giáo trình &amp; Tài liệu học tập</h1>
  <p>Toàn bộ sách và giáo trình được trợ giá sinh viên bởi Trường Đại học Sư phạm &ndash; Đại học Đà Nẵng.</p>

  <!-- Biểu mẫu tìm kiếm, lọc và sắp xếp (GET - Chia sẻ được liên kết) -->
  <section class="bo-loc-container" style="background: #f1f5f9; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
    <form method="GET" action="danh-sach.php" class="form-bo-loc" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
      
      <!-- Ô tìm kiếm từ khóa -->
      <div style="flex: 2; min-width: 220px;">
        <label for="o-tim-kiem" style="display: block; font-weight: bold; margin-bottom: 5px;">Từ khóa tìm kiếm:</label>
        <input type="text" id="o-tim-kiem" name="q" value="<?= e($q) ?>" placeholder="Nhập tên sách, tác giả..." style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 4px;">
      </div>

      <!-- Lọc theo danh mục -->
      <div style="flex: 1; min-width: 180px;">
        <label for="loc-danh-muc" style="display: block; font-weight: bold; margin-bottom: 5px;">Chuyên ngành:</label>
        <select id="loc-danh-muc" name="dm" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 4px;">
          <?php foreach ($danhMucHopLe as $maDm => $tenDm): ?>
            <option value="<?= e($maDm) ?>" <?= $dm === $maDm ? 'selected' : '' ?>>
              <?= e($tenDm) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Sắp xếp theo giá / tên -->
      <div style="flex: 1; min-width: 180px;">
        <label for="sap-xep" style="display: block; font-weight: bold; margin-bottom: 5px;">Sắp xếp theo:</label>
        <select id="sap-xep" name="sx" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 4px;">
          <?php foreach ($sapXepHopLe as $maSx => $tenSx): ?>
            <option value="<?= e($maSx) ?>" <?= $sx === $maSx ? 'selected' : '' ?>>
              <?= e($tenSx) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Nút submit và reset -->
      <div style="display: flex; gap: 8px;">
        <button type="submit" class="nut nut--chinh" style="padding: 9px 18px;">Lọc sách</button>
        <?php if ($q !== '' || $dm !== 'tat-ca' || $sx !== 'mac-dinh'): ?>
          <a href="danh-sach.php" class="nut nut--vien" style="padding: 9px 15px; text-decoration: none;">Đặt lại</a>
        <?php endif; ?>
      </div>
    </form>
  </section>

  <!-- Thông báo số lượng kết quả -->
  <div style="margin-bottom: 20px; font-weight: 500; color: #475569;" aria-live="polite">
    Tìm thấy <strong><?= $tongSo ?></strong> đầu sách phù hợp
    <?php if ($q !== ''): ?> với từ khóa &ldquo;<em><?= e($q) ?></em>&rdquo;<?php endif; ?>
    <?php if ($dm !== 'tat-ca'): ?> thuộc danh mục <strong><?= e($danhMucHopLe[$dm]) ?></strong><?php endif; ?>.
  </div>

  <!-- Danh sách sách dạng lưới -->
  <?php if ($tongSo > 0): ?>
    <div class="luoi-the">
      <?php foreach ($danhSach as $sp): ?>
        <article class="the">
          <figure class="the__hinh-anh">
            <img src="<?= e($sp->hinhAnh) ?>" alt="Bìa sách <?= e($sp->ten) ?>" width="175" height="225" loading="lazy">
          </figure>
          <div class="the__than">
            <h2 class="the__tieu-de" style="font-size: 1.15rem; margin-top: 0;">
              <a href="chi-tiet.php?id=<?= $sp->id ?>"><?= e($sp->ten) ?></a>
            </h2>
            <p class="the__mo-ta"><strong>Tác giả:</strong> <?= e($sp->tacGia) ?></p>
            <p class="the__mo-ta"><strong>NXB:</strong> <?= e($sp->nhaXuatBan) ?> (<?= $sp->namXuatBan ?>)</p>
            <p class="the__gia"><?= vnd($sp->gia) ?> <span class="the__gia-goc"><?= vnd($sp->giaGoc) ?></span></p>
            <div class="the__chan" style="display: flex; gap: 8px; flex-wrap: wrap;">
              <a href="chi-tiet.php?id=<?= $sp->id ?>" class="nut nut--vien">Chi tiết</a>
              
              <!-- Biểu mẫu Thêm vào giỏ hàng an toàn (POST tới gio-hang.php) -->
              <form method="POST" action="gio-hang.php" style="display: inline-block;">
                <input type="hidden" name="action" value="them">
                <input type="hidden" name="id" value="<?= $sp->id ?>">
                <input type="hidden" name="soLuong" value="1">
                <button type="submit" class="nut nut--chinh">🛒 Thêm vào giỏ</button>
              </form>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <!-- Trạng thái không tìm thấy kết quả -->
    <div style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: 8px; padding: 40px 20px; text-align: center;">
      <h2 style="color: #b45309; margin-top: 0;">Không tìm thấy sách phù hợp</h2>
      <p style="color: #78350f; max-width: 500px; margin: 10px auto;">
        Rất tiếc, không có đầu sách nào khớp với từ khóa tìm kiếm hoặc bộ lọc hiện tại. Bạn vui lòng thử lại với từ khóa khác hoặc bấm nút đặt lại bộ lọc.
      </p>
      <div style="margin-top: 20px;">
        <a href="danh-sach.php" class="nut nut--chinh">Xem toàn bộ sách</a>
      </div>
    </div>
  <?php endif; ?>
</main>

<?php require __DIR__ . '/inc/footer.php'; ?>
