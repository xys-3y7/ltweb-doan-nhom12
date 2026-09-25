/**
 * Tệp: js/main.js
 * Mô tả: Module JavaScript chính được nạp trên toàn bộ các trang của website BookNest.
 * Phụ trách:
 *  1. Đánh dấu trạng thái có hỗ trợ JavaScript (Progressive Enhancement).
 *  2. Chức năng 1 (Bảng 1): Menu thu gọn responsive trên điện thoại kèm hỗ trợ bàn phím (Esc, Tab).
 *  3. Khởi tạo và đồng bộ số đếm huy hiệu yêu thích trên header.
 * Nhóm 12 - Lớp 24CNTT3 - Người phụ trách: Xaiyasith Yoi (Nhóm trưởng)
 */

import { capNhatBadgeYeuThich } from './yeu-thich.js';

// Gợi ý 3: Đánh dấu trình duyệt đang bật JavaScript để kích hoạt kiểu dáng nâng cao
document.documentElement.classList.add('js');

/**
 * Khởi tạo menu di động và xử lý tương tác
 */
function khoiTaoMenuDiDong() {
  const nutMenu = document.querySelector('#nut-menu');
  const thanhDieuHuong = document.querySelector('.thanh-dieu-huong');

  if (!nutMenu || !thanhDieuHuong) return;

  // Bật/tắt menu khi click chuột hoặc nhấn Enter/Space trên button
  nutMenu.addEventListener('click', () => {
    const dangMo = thanhDieuHuong.classList.toggle('mo');
    nutMenu.setAttribute('aria-expanded', String(dangMo));
    nutMenu.classList.toggle('da-mo', dangMo);
  });

  // Hỗ trợ trợ năng: Nhấn phím Escape để đóng nhanh menu di động
  window.addEventListener('keydown', (suKien) => {
    if (suKien.key === 'Escape' && thanhDieuHuong.classList.contains('mo')) {
      thanhDieuHuong.classList.remove('mo');
      nutMenu.setAttribute('aria-expanded', 'false');
      nutMenu.classList.remove('da-mo');
      nutMenu.focus(); // Đưa tiêu điểm trở lại nút menu để người dùng khiếm thị tiện thao tác
    }
  });

  // Đóng menu khi người dùng click bên ngoài menu
  document.addEventListener('click', (suKien) => {
    if (
      thanhDieuHuong.classList.contains('mo') &&
      !thanhDieuHuong.contains(suKien.target) &&
      !nutMenu.contains(suKien.target)
    ) {
      thanhDieuHuong.classList.remove('mo');
      nutMenu.setAttribute('aria-expanded', 'false');
      nutMenu.classList.remove('da-mo');
    }
  });
}

// Khởi chạy khi DOM đã sẵn sàng
document.addEventListener('DOMContentLoaded', () => {
  khoiTaoMenuDiDong();
  capNhatBadgeYeuThich();
});
