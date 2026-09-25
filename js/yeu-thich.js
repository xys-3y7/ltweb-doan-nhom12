/**
 * Tệp: js/yeu-thich.js
 * Mô tả: Module quản lý danh sách sản phẩm yêu thích (hoặc giỏ hàng tạm) của người dùng.
 * Sử dụng localStorage để duy trì trạng thái dữ liệu qua các lần tải lại trang.
 * Nhóm 12 - Lớp 24CNTT3 - Người phụ trách: Phommaket Haysady & Vongsena Sauphasith
 */

const KHOA_LUU_TRU = 'booknest_danh_sach_yeu_thich';

/**
 * Đọc danh sách ID yêu thích từ localStorage
 * @returns {Array<number>} Danh sách các ID sản phẩm
 */
export function layDanhSachYeuThich() {
  try {
    const raw = localStorage.getItem(KHOA_LUU_TRU);
    if (!raw) return [];
    const ds = JSON.parse(raw);
    return Array.isArray(ds) ? ds : [];
  } catch (e) {
    console.warn('[BookNest Storage] Không thể đọc localStorage, khởi tạo mảng rỗng:', e);
    return [];
  }
}

/**
 * Lưu danh sách ID yêu thích vào localStorage
 * @param {Array<number>} ds 
 */
export function luuDanhSachYeuThich(ds) {
  try {
    localStorage.setItem(KHOA_LUU_TRU, JSON.stringify(ds));
  } catch (e) {
    console.error('[BookNest Storage] Lỗi khi ghi vào localStorage:', e);
  }
}

/**
 * Kiểm tra xem một sản phẩm đã có trong danh sách yêu thích chưa
 * @param {number} id 
 * @returns {boolean}
 */
export function kiemTraYeuThich(id) {
  const ds = layDanhSachYeuThich();
  return ds.includes(Number(id));
}

/**
 * Thêm hoặc bỏ một sản phẩm khỏi danh sách yêu thích
 * @param {number} id 
 * @returns {boolean} Trạng thái mới: true nếu vừa thêm, false nếu vừa bỏ
 */
export function chuyenDoiYeuThich(id) {
  const maSo = Number(id);
  let ds = layDanhSachYeuThich();
  let daThem = false;

  if (ds.includes(maSo)) {
    ds = ds.filter((item) => item !== maSo);
    daThem = false;
  } else {
    ds.push(maSo);
    daThem = true;
  }

  luuDanhSachYeuThich(ds);
  capNhatBadgeYeuThich();
  window.dispatchEvent(new CustomEvent('booknest:yeuthich-thaydoi', { detail: { id: maSo, daThem } }));
  return daThem;
}

/**
 * Cập nhật số đếm trên thanh điều hướng header ở mọi trang
 */
export function capNhatBadgeYeuThich() {
  const phanTuBadge = document.querySelectorAll('.so-luong-yeu-thich');
  const ds = layDanhSachYeuThich();
  const soLuong = ds.length;

  phanTuBadge.forEach((badge) => {
    badge.textContent = String(soLuong);
    badge.setAttribute('aria-label', `Có ${soLuong} sản phẩm trong danh sách yêu thích`);
  });
}
