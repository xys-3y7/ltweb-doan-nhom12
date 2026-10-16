<?php
/**
 * index.php - Trang chủ website Nhà sách trực tuyến BookNest
 * Nhóm 12 - Lớp 24CNTT3 - Trường ĐH Sư phạm - ĐH Đà Nẵng
 * Chức năng: Hiển thị sách nổi bật, khối sách đã xem gần đây từ Cookie, thời tiết động
 */

require_once __DIR__ . '/inc/config.php';

use App\Data\KhoSanPham;

$kho = new KhoSanPham(__DIR__ . '/data/san-pham.json');

// Lấy 3 sản phẩm nổi bật làm sách tiêu biểu trang chủ
$sachNoiBat = array_slice($kho->tatCa(), 0, 3);

// Đọc và kiểm tra cookie "da_xem" (Đã xem gần đây)
// Bảo mật: Không tin tưởng giá trị cookie, lọc từng ID bằng FILTER_VALIDATE_INT
$daXem = [];
if (!empty($_COOKIE['da_xem'])) {
    foreach (explode(',', $_COOKIE['da_xem']) as $x) {
        $id = filter_var($x, FILTER_VALIDATE_INT);
        $sp = $id ? $kho->timTheoId($id) : null;
        if ($sp !== null && !in_array($sp, $daXem, true)) {
            $daXem[] = $sp;
        }
    }
}

$tieuDe = 'Trang chủ';
$trang = 'index';
$moTaTrang = 'BookNest - Nhà sách trực tuyến chuyên cung cấp giáo trình đại học, sách chuyên ngành CNTT, Toán học cho sinh viên Trường ĐH Sư phạm - ĐH Đà Nẵng.';

require __DIR__ . '/inc/header.php';
?>

<main class="noi-dung-chinh khung-chua">
  <h1>Chào mừng bạn đến với Nhà sách trực tuyến BookNest</h1>

  <!-- Khối thời tiết động từ Open-Meteo REST API (Kế thừa từ BT4) -->
  <section id="khoi-thoi-tiet-dong" class="khoi-thoi-tiet-container" aria-live="polite"></section>

  <!-- 1. Khu vực nổi bật Hero Banner -->
  <section class="khu-vuc-hero">
    <h2>Chương trình khuyến mãi giáo trình học kỳ mới</h2>
    <p>Nhân dịp khai giảng năm học mới 2026 - 2027, BookNest trân trọng gửi tới các bạn sinh viên chương trình trợ giá giáo trình đặc biệt:</p>
    <figure>
      <img src="images/banner-khuyenmai.png" alt="Banner chương trình trợ giá 20% giáo trình CNTT và Toán học cho sinh viên" width="800" height="250">
      <figcaption>Chương trình ưu đãi giảm 20% cho toàn bộ giáo trình chuyên ngành Toán và CNTT</figcaption>
    </figure>
    <p>Ưu đãi áp dụng từ ngày 15/09/2026 đến hết ngày 15/10/2026 tại khuôn viên Trường Đại học Sư phạm - Đại học Đà Nẵng.</p>
  </section>

  <!-- 2. Giới thiệu dự án và Hàng ba thẻ cam kết nổi bật -->
  <section>
    <h2>Về dự án BookNest</h2>
    <p>BookNest là dự án học phần <em>Thiết kế và Lập trình web</em> được phát triển bởi <strong>Nhóm 12 (Lớp 24CNTT3)</strong>, Khoa Toán - Tin, Trường Đại học Sư phạm - Đại học Đà Nẵng.</p>
    <p>Hệ thống hướng đến mục tiêu giải quyết khó khăn của sinh viên trong việc tìm kiếm giáo trình chuẩn, tài liệu học tập chuyên ngành đáng tin cậy với mức giá hợp lý và phương thức nhận sách tiện lợi ngay tại trường.</p>
    
    <h3>Ba cam kết cốt lõi của BookNest</h3>
    <div class="hang-ba-the">
      <article class="the-tinh-nang">
        <h4>Giáo trình chuẩn hóa</h4>
        <p>100% sách và tài liệu được biên soạn hoặc thẩm định bởi các giảng viên uy tín thuộc các trường đại học hàng đầu.</p>
      </article>
      <article class="the-tinh-nang">
        <h4>Giá cả trợ giá</h4>
        <p>Trợ giá sinh viên từ 10% đến 25% so với giá bìa thị trường kèm nhiều ưu đãi trong suốt học kỳ.</p>
      </article>
      <article class="the-tinh-nang">
        <h4>Giao nhận nhanh chóng</h4>
        <p>Nhận sách trực tiếp tại sảnh Khoa Toán - Tin hoặc giao tận phòng ký túc xá trong vòng 24 giờ.</p>
      </article>
    </div>
  </section>

  <!-- 3. Danh mục sách và giáo trình nổi bật (Sinh động từ PHP và KhoSanPham) -->
  <section>
    <h2>Danh mục sách và giáo trình nổi bật trong tuần</h2>
    <p>Dưới đây là các đầu sách chuyên ngành được các bạn sinh viên ngành Công nghệ thông tin và Sư phạm Tin học quan tâm nhiều nhất:</p>
    
    <div class="luoi-the">
      <?php foreach ($sachNoiBat as $sp): ?>
        <article class="the">
          <figure class="the__hinh-anh">
            <img src="<?= e($sp->hinhAnh) ?>" alt="Bìa sách <?= e($sp->ten) ?>" width="175" height="225" loading="lazy">
          </figure>
          <div class="the__than">
            <h3 class="the__tieu-de"><?= e($sp->ten) ?></h3>
            <p class="the__mo-ta">Tác giả: <?= e($sp->tacGia) ?> | <?= e($sp->nhaXuatBan) ?></p>
            <p class="the__gia"><?= vnd($sp->gia) ?> <span class="the__gia-goc"><?= vnd($sp->giaGoc) ?></span></p>
            <div class="the__chan">
              <a href="chi-tiet.php?id=<?= $sp->id ?>" class="nut nut--chinh">Xem chi tiết &rarr;</a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- 4. Khối sách đã xem gần đây (Lưu và đọc từ Cookie da_xem) -->
  <?php if (!empty($daXem)): ?>
  <section class="khu-vuc-da-xem" style="margin-top: 40px; background: #f8fafc; padding: 25px; border-radius: 8px; border: 1px solid #e2e8f0;">
    <h2>Sách bạn vừa xem gần đây (Cookie máy khách)</h2>
    <p>Ghi nhớ tối đa 4 cuốn sách bạn đã quan tâm truy cập:</p>
    <div class="luoi-the">
      <?php foreach ($daXem as $sp): ?>
        <article class="the">
          <figure class="the__hinh-anh">
            <img src="<?= e($sp->hinhAnh) ?>" alt="Bìa sách <?= e($sp->ten) ?>" width="175" height="225" loading="lazy">
          </figure>
          <div class="the__than">
            <h3 class="the__tieu-de"><a href="chi-tiet.php?id=<?= $sp->id ?>"><?= e($sp->ten) ?></a></h3>
            <p class="the__gia"><?= vnd($sp->gia) ?></p>
            <div class="the__chan">
              <a href="chi-tiet.php?id=<?= $sp->id ?>" class="nut nut--vien">Xem lại</a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- 5. Dịch vụ hỗ trợ người học -->
  <section>
    <h2>Dịch vụ hỗ trợ người học</h2>
    <ul>
      <li>Hỗ trợ tìm và đặt in giáo trình hiếm phục vụ nghiên cứu khoa học.</li>
      <li>Tư vấn lộ trình chọn sách học theo đề cương môn học từng học kỳ.</li>
      <li>Thu đổi và ký gửi giáo trình đã qua sử dụng giúp tiết kiệm chi phí học tập.</li>
    </ul>
    <p>Quý thầy cô và các bạn sinh viên có thể liên hệ đặt sách số lượng lớn hoặc gửi yêu cầu hỗ trợ qua <a href="lien-he.php" class="nut nut--vien">Trang liên hệ trực tuyến</a>.</p>
  </section>
</main>

<script type="module" src="js/trang-chu.js"></script>

<?php require __DIR__ . '/inc/footer.php'; ?>
