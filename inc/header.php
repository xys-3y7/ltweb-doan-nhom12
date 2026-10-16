<?php
/**
 * inc/header.php - Phần đầu trang dùng chung cho mọi trang website BookNest
 * Nhóm 12 - Lớp 24CNTT3 - Trường ĐH Sư phạm - ĐH Đà Nẵng
 */

$goc   ??= '';
$trang ??= '';
$tieuDe ??= 'Trang chủ';
$moTaTrang ??= 'BookNest - Nhà sách trực tuyến chuyên cung cấp giáo trình đại học, sách chuyên ngành CNTT, Toán học cho sinh viên Trường ĐH Sư phạm - ĐH Đà Nẵng.';

$gio = new \App\Services\GioHang();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= e($moTaTrang) ?>">
  <title><?= e($tieuDe) ?> | BookNest - Nhà sách sinh viên UED</title>
  <link rel="stylesheet" href="<?= $goc ?>css/style.css">
</head>
<body class="trang-grid">

  <header class="phan-dau">
    <div class="khung-chua phan-dau__khung">
      <div class="phan-dau__logo">
        <a href="<?= $goc ?>index.php">
          <img src="<?= $goc ?>images/logo-booknest.png" alt="Logo BookNest - Nhà sách trực tuyến UED" width="250" height="80">
        </a>
      </div>
      <p class="phan-dau__khau-hieu"><strong>BookNest</strong> &mdash; Tri thức nâng bước tương lai, người bạn đồng hành của sinh viên Sư phạm</p>
    </div>
  </header>

  <nav class="thanh-dieu-huong" aria-label="Điều hướng chính">
    <div class="khung-chua">
      <button class="nut-menu" id="nut-menu" aria-label="Mở menu điều hướng" aria-expanded="false">&#9776; Menu</button>
      <ul>
        <li><a href="<?= $goc ?>index.php" <?= $trang === 'index' ? 'class="hien-tai" aria-current="page"' : '' ?>>Trang chủ</a></li>
        <li><a href="<?= $goc ?>danh-sach.php" <?= $trang === 'danh-sach' ? 'class="hien-tai" aria-current="page"' : '' ?>>Danh mục sách</a></li>
        <li><a href="<?= $goc ?>gioi-thieu.php" <?= $trang === 'gioi-thieu' ? 'class="hien-tai" aria-current="page"' : '' ?>>Giới thiệu nhóm</a></li>
        <li><a href="<?= $goc ?>lien-he.php" <?= $trang === 'lien-he' ? 'class="hien-tai" aria-current="page"' : '' ?>>Liên hệ đặt sách</a></li>
        <li><a href="<?= $goc ?>gio-hang.php" <?= $trang === 'gio-hang' ? 'class="hien-tai" aria-current="page"' : '' ?>>🛒 Giỏ hàng (<span class="so-luong-gio"><?= $gio->soMon() ?></span>)</a></li>
        <?php if (isset($_SESSION['user'])): ?>
          <li><a href="<?= $goc ?>quan-tri.php" <?= $trang === 'quan-tri' ? 'class="hien-tai" aria-current="page"' : '' ?>>👤 Quản trị (<?= e($_SESSION['user']) ?>)</a></li>
          <li><a href="<?= $goc ?>dang-xuat.php">Đăng xuất</a></li>
        <?php else: ?>
          <li><a href="<?= $goc ?>dang-nhap.php" <?= $trang === 'dang-nhap' ? 'class="hien-tai" aria-current="page"' : '' ?>>Đăng nhập</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>
