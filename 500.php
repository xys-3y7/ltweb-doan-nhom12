<?php
/**
 * 500.php - Trang thông báo lỗi máy chủ nội bộ thân thiện (Môi trường prod)
 * Nhóm 12 - Lớp 24CNTT3 - Trường ĐH Sư phạm - ĐH Đà Nẵng
 */

require_once __DIR__ . '/inc/config.php';

http_response_code(500);
$tieuDe = '500 - Lỗi hệ thống';
$trang = '500';

require __DIR__ . '/inc/header.php';
?>

<main class="noi-dung-chinh khung-chua">
  <div style="text-align: center; padding: 60px 20px;">
    <h1 style="font-size: 4rem; color: #dc2626; margin-bottom: 10px;">500</h1>
    <h2>Đã xảy ra sự cố phía máy chủ</h2>
    <p style="max-width: 600px; margin: 15px auto; color: #64748b; font-size: 1.1rem;">
      Hệ thống BookNest đang gặp sự cố kỹ thuật trong quá trình xử lý yêu cầu. Sự cố đã được tự động ghi nhận vào nhật ký hệ thống (log). Chúng tôi thành thật xin lỗi vì sự bất tiện này.
    </p>
    <div style="margin-top: 30px; display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
      <a href="index.php" class="nut nut--chinh">&larr; Trở về Trang chủ an toàn</a>
      <a href="lien-he.php" class="nut nut--vien">Báo cáo sự cố với quản trị viên</a>
    </div>
  </div>
</main>

<?php require __DIR__ . '/inc/footer.php'; ?>
