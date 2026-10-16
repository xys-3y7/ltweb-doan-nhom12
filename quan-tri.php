<?php
/**
 * quan-tri.php - Trang quản trị hệ thống: Xem danh sách liên hệ và đơn đặt sách
 * Nhóm 12 - Lớp 24CNTT3 - Trường ĐH Sư phạm - ĐH Đà Nẵng
 * Phụ trách: Xaiyasith Yoi (MSV: 3120224189) & Nguyễn Thị Sinh (MSV: 3120223169)
 * Kỹ thuật: Bảo vệ bằng inc/bao-ve.php, đọc tệp JSONL mới nhất lên đầu, in qua e() chống XSS
 */

require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/bao-ve.php'; // Kiểm tra phiên làm việc đăng nhập

use App\Data\KhoLienHe;

$khoLh = new KhoLienHe(__DIR__ . '/storage/lien-he.jsonl');
$danhSachLh = $khoLh->tatCa();
$tongSo = count($danhSachLh);

$tieuDe = 'Bảng điều khiển Quản trị';
$trang = 'quan-tri';
$moTaTrang = 'Quản lý danh sách liên hệ phản hồi và đặt in giáo trình tại BookNest.';

require __DIR__ . '/inc/header.php';
?>

<main class="noi-dung-chinh khung-chua">
  <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 25px; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px;">
    <div>
      <h1 style="margin: 0; font-size: 1.8rem; color: #0f172a;">Bảng điều khiển Quản trị viên</h1>
      <p style="margin: 5px 0 0 0; color: #64748b;">
        Xin chào, <strong><?= e($_SESSION['hoTen'] ?? $_SESSION['user']) ?></strong> (Tài khoản: <code><?= e($_SESSION['user']) ?></code>)
      </p>
    </div>
    <div style="display: flex; gap: 10px;">
      <a href="index.php" class="nut nut--vien">&larr; Về trang chủ</a>
      <a href="dang-xuat.php" class="nut nut--vien" style="color: #dc2626; border-color: #fca5a5;">Đăng xuất</a>
    </div>
  </div>

  <section>
    <h2>Danh sách thông tin liên hệ &amp; Đặt giáo trình (Tổng số: <?= $tongSo ?>)</h2>
    <p>Dữ liệu được đọc trực tiếp từ tệp nhật ký máy chủ <code>storage/lien-he.jsonl</code> (xếp theo thứ tự mới nhất đứng trước):</p>

    <?php if ($tongSo > 0): ?>
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px;">
          <thead>
            <tr style="background: #f8fafc; border-bottom: 2px solid #cbd5e1; text-align: left; font-size: 0.95rem;">
              <th style="padding: 12px 15px; width: 150px;">Thời gian</th>
              <th style="padding: 12px 15px; width: 160px;">Người gửi</th>
              <th style="padding: 12px 15px; width: 180px;">Liên lạc</th>
              <th style="padding: 12px 15px;">Nội dung liên hệ</th>
              <th style="padding: 12px 15px; width: 110px; text-align: center;">Ảnh đính kèm</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($danhSachLh as $lh): ?>
              <tr style="border-bottom: 1px solid #e2e8f0; vertical-align: top;">
                <td style="padding: 12px 15px; font-size: 0.85rem; color: #64748b;">
                  <?= e($lh['thoiGian'] ?? 'N/A') ?><br>
                  <small>IP: <?= e($lh['ip'] ?? '127.0.0.1') ?></small>
                </td>
                <td style="padding: 12px 15px; font-weight: bold; color: #0f172a;">
                  <?= e($lh['hoTen'] ?? 'N/A') ?>
                </td>
                <td style="padding: 12px 15px; font-size: 0.9rem;">
                  <div>📧 <a href="mailto:<?= e($lh['email'] ?? '') ?>"><?= e($lh['email'] ?? '') ?></a></div>
                  <div style="margin-top: 4px;">📞 <?= e($lh['sdt'] ?? '') ?></div>
                </td>
                <td style="padding: 12px 15px; line-height: 1.5; color: #334155;">
                  <!-- In an toàn chống XSS qua hàm e(), bảo vệ quản trị viên khỏi mã độc script -->
                  <?= nl2br(e($lh['noiDung'] ?? '')) ?>
                </td>
                <td style="padding: 12px 15px; text-align: center;">
                  <?php if (!empty($lh['anh']) && is_file(__DIR__ . '/uploads/' . $lh['anh'])): ?>
                    <a href="uploads/<?= e($lh['anh']) ?>" target="_blank" rel="noopener">
                      <img src="uploads/<?= e($lh['anh']) ?>" alt="Minh chứng" width="60" height="60" style="object-fit: cover; border-radius: 4px; border: 1px solid #cbd5e1;">
                    </a>
                  <?php else: ?>
                    <span style="color: #94a3b8; font-size: 0.85rem;">Không có</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div style="padding: 40px 20px; text-align: center; background: #ffffff; border-radius: 8px; border: 1px dashed #cbd5e1;">
        <p style="font-size: 2.5rem; margin: 0 0 10px 0;">📬</p>
        <h3>Hộp thư hiện đang trống</h3>
        <p style="color: #64748b;">Chưa có phản hồi hoặc đơn đặt sách nào được gửi tới hệ thống.</p>
      </div>
    <?php endif; ?>
  </section>
</main>

<?php require __DIR__ . '/inc/footer.php'; ?>
