<?php
/**
 * 404.php - Trang thông báo lỗi 404 Không tìm thấy tài nguyên
 * Nhóm 12 - Lớp 24CNTT3 - Trường ĐH Sư phạm - ĐH Đà Nẵng
 */

require_once __DIR__ . '/inc/config.php';

http_response_code(404);
$tieuDe = '404 - Không tìm thấy trang';
$trang = '404';

require __DIR__ . '/inc/header.php';
?>

<main class="noi-dung-chinh khung-chua">
  <div style="text-align: center; padding: 60px 20px;">
    <h1 style="font-size: 4rem; color: #d97706; margin-bottom: 10px;">404</h1>
    <h2>Không tìm thấy trang yêu cầu</h2>
    <p style="max-width: 600px; margin: 15px auto; color: #64748b; font-size: 1.1rem;">
      Sản phẩm hoặc đường dẫn bạn đang tìm kiếm có thể đã bị xóa, thay đổi mã định danh hoặc tạm thời không khả dụng trên hệ thống BookNest.
    </p>
    <div style="margin-top: 30px; display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
      <a href="index.php" class="nut nut--chinh">&larr; Quay lại Trang chủ</a>
      <a href="danh-sach.php" class="nut nut--vien">Khám phá Danh mục sách</a>
    </div>
  </div>
</main>

<?php require __DIR__ . '/inc/footer.php'; ?>
