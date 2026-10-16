<?php
/**
 * gioi-thieu.php - Giới thiệu đề tài BookNest và danh sách thành viên Nhóm 12
 * Nhóm 12 - Lớp 24CNTT3 - Trường ĐH Sư phạm - ĐH Đà Nẵng
 */

require_once __DIR__ . '/inc/config.php';

$tieuDe = 'Giới thiệu Nhóm 12 & Đồ án BookNest';
$trang = 'gioi-thieu';
$moTaTrang = 'Giới thiệu về Nhóm 12 lớp 24CNTT3 và dự án đồ án website BookNest - Nhà sách trực tuyến phục vụ sinh viên UED.';

require __DIR__ . '/inc/header.php';
?>

<main class="noi-dung-chinh khung-chua">
  <h1>Giới thiệu Nhóm phát triển 12 và Đồ án BookNest</h1>

  <!-- 1. Văn bản dài giới thiệu đồ án -->
  <section>
    <h2>Tổng quan về đồ án môn học</h2>
    <p class="doan-van-dai">Dự án website <strong>BookNest</strong> được xây dựng trong khuôn khổ học phần <em>Thiết kế và Lập trình web</em> (Web Design and Programming), Học kỳ 1 năm học 2026 - 2027 tại Khoa Toán - Tin, Trường Đại học Sư phạm - Đại học Đà Nẵng.</p>
    <p class="doan-van-dai">Ở Chương 5, nhóm đã chuyển đổi toàn bộ kiến trúc từ website tĩnh (HTML/CSS/JS) sang hệ thống web động hoàn chỉnh bằng ngôn ngữ <strong>PHP 8</strong> theo mô hình hướng đối tượng, quản lý gói bằng Composer (PSR-4 autoloading), xử lý biểu mẫu an toàn phía máy chủ, giỏ hàng bằng PHP Session, cookie ghi nhớ sách đã xem và bảng điều khiển quản trị viên.</p>
    
    <h3>Tầm nhìn và sứ mệnh</h3>
    <ul class="doan-van-dai">
      <li><strong>Tầm nhìn:</strong> Trở thành cổng thông tin tra cứu và phân phối giáo trình, văn phòng phẩm trực tuyến số một cho sinh viên Trường Đại học Sư phạm - Đại học Đà Nẵng.</li>
      <li><strong>Sứ mệnh:</strong> Số hóa quy trình mượn, mua và chia sẻ tài liệu học tập, góp phần giảm thiểu chi phí và nâng cao chất lượng học tập của sinh viên sư phạm và công nghệ.</li>
    </ul>
  </section>

  <!-- 2. Danh sách thành viên Nhóm 12 -->
  <section>
    <h2>Danh sách thành viên Nhóm 12 (Lớp 24CNTT3)</h2>
    <p>Nhóm 12 gồm 4 sinh viên chuyên ngành Công nghệ thông tin cùng hợp tác và phân chia nhiệm vụ chuyên trách:</p>

    <div class="danh-sach-thanh-vien">
      
      <!-- Thành viên 1 -->
      <article class="the-thanh-vien">
        <figure>
          <img src="images/yoi-avatar.png" alt="Ảnh chân dung sinh viên Xaiyasith Yoi" width="120" height="120" loading="lazy">
          <figcaption><strong>Xaiyasith Yoi</strong><br><small>MSV: 3120224189</small></figcaption>
        </figure>
        <p><span class="huy-hieu huy-hieu--thanh-cong">Nhóm trưởng</span></p>
        <p class="the__mo-ta">Quản lý dự án, khung trang PHP chung (header/footer/config), trang chủ index.php, giỏ hàng và quản trị Git.</p>
        <a href="thanhvien/3120224189_yoi/gioithieu.php" class="nut nut--chinh">Xem hồ sơ cá nhân &rarr;</a>
      </article>

      <!-- Thành viên 2 -->
      <article class="the-thanh-vien">
        <figure>
          <img src="images/hatsady-avatar.png" alt="Ảnh chân dung sinh viên Phommaket Hatsady" width="120" height="120" loading="lazy">
          <figcaption><strong>Phommaket Hatsady</strong><br><small>MSV: 3120224181</small></figcaption>
        </figure>
        <p><span class="huy-hieu huy-hieu--thanh-cong">Thành viên</span></p>
        <p class="the__mo-ta">Xây dựng trang danh mục sách <code>danh-sach.php</code> (tìm kiếm, lọc danh mục, sắp xếp) và dịch vụ <code>GioHang.php</code>.</p>
        <a href="thanhvien/3120224181_hatsady/gioithieu.php" class="nut nut--chinh">Xem hồ sơ cá nhân &rarr;</a>
      </article>

      <!-- Thành viên 3 -->
      <article class="the-thanh-vien">
        <figure>
          <img src="images/sauphasith-avatar.png" alt="Ảnh chân dung sinh viên Vongsena Sauphasith" width="120" height="120" loading="lazy">
          <figcaption><strong>Vongsena Sauphasith</strong><br><small>MSV: 3120224186</small></figcaption>
        </figure>
        <p><span class="huy-hieu huy-hieu--thanh-cong">Thành viên</span></p>
        <p class="the__mo-ta">Phát triển biểu mẫu liên hệ <code>lien-he.php</code>, kiểm tra máy chủ, upload ảnh an toàn qua finfo và lớp <code>KhoLienHe</code>.</p>
        <a href="thanhvien/3120224186_sauphasith/gioithieu.php" class="nut nut--chinh">Xem hồ sơ cá nhân &rarr;</a>
      </article>

      <!-- Thành viên 4 -->
      <article class="the-thanh-vien">
        <figure>
          <img src="images/sinh-avatar.png" alt="Ảnh chân dung sinh viên Nguyễn Thị Sinh" width="120" height="120" loading="lazy">
          <figcaption><strong>Nguyễn Thị Sinh</strong><br><small>MSV: 3120223169</small></figcaption>
        </figure>
        <p><span class="huy-hieu huy-hieu--thanh-cong">Thành viên</span></p>
        <p class="the__mo-ta">Phát triển trang chi tiết <code>chi-tiet.php</code>, xử lý Cookie <code>da_xem</code>, bảo vệ đăng nhập và trang quản trị.</p>
        <a href="thanhvien/3120223169_sinh/gioithieu.php" class="nut nut--chinh">Xem hồ sơ cá nhân &rarr;</a>
      </article>

    </div>
  </section>
</main>

<?php require __DIR__ . '/inc/footer.php'; ?>
