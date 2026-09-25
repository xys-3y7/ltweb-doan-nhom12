/**
 * Tệp: js/trang-danh-sach.js
 * Mô tả: Module phục vụ trang danh mục sách (danh-sach.html).
 * Chức năng 2 (Bảng 1): Tải danh sách từ data/san-pham.json, tìm kiếm tức thời không dấu,
 * lọc theo danh mục, sắp xếp theo giá / tên, render an toàn, xử lý 3 trạng thái (đang tải - lỗi - rỗng).
 * Chức năng 6 (Bảng 1): Tích hợp ủy quyền sự kiện (Event Delegation) để bấm nút Thêm/Bỏ yêu thích.
 * Nhóm 12 - Lớp 24CNTT3 - Người phụ trách: Phommaket Haysady
 */

import { taiJSON } from './api.js';
import { kiemTraYeuThich, chuyenDoiYeuThich } from './yeu-thich.js';

// Các phần tử giao diện
const KHUNG_CHUA_LUOI = document.querySelector('#danh-sach-san-pham-dong');
const O_TIM_KIEM = document.querySelector('#o-tim-kiem');
const SELECT_DANH_MUC = document.querySelector('#bo-loc-danh-muc');
const SELECT_SAP_XEP = document.querySelector('#bo-loc-sap-xep');
const THONG_BAO_KET_QUA = document.querySelector('#thong-bao-ket-qua-loc');

// Biến lưu trạng thái danh sách gốc
let danhSachGoc = [];

/**
 * Hàm chuẩn hóa chuỗi bỏ dấu tiếng Việt để tìm kiếm không dấu
 * @param {string} str 
 * @returns {string}
 */
function boDauTiengViet(str) {
  if (!str) return '';
  return str
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/đ/g, 'd')
    .replace(/Đ/g, 'D')
    .toLowerCase()
    .trim();
}

/**
 * Định dạng tiền tệ VNĐ
 * @param {number} soTien 
 * @returns {string}
 */
function dinhDangTien(soTien) {
  return new Intl.NumberFormat('vi-VN').format(soTien) + ' VNĐ';
}

/**
 * Tạo một thẻ sách (DOM Element) an toàn tuyệt đối, không innerHTML dữ liệu động
 * @param {Object} sp 
 * @returns {HTMLElement}
 */
function taoTheSanPham(sp) {
  const the = document.createElement('article');
  the.className = 'the';
  the.dataset.id = String(sp.id);

  // 1. Hình ảnh
  const figure = document.createElement('figure');
  figure.className = 'the__hinh-anh';

  const img = document.createElement('img');
  img.src = sp.hinhAnh;
  img.alt = `Bìa sách ${sp.ten}`;
  img.width = 220;
  img.height = 280;
  img.loading = 'lazy';
  figure.appendChild(img);
  the.appendChild(figure);

  // 2. Thân thẻ
  const than = document.createElement('div');
  than.className = 'the__than';

  const tieuDe = document.createElement('h3');
  tieuDe.className = 'the__tieu-de';
  tieuDe.textContent = sp.ten;
  than.appendChild(tieuDe);

  const moTa = document.createElement('p');
  moTa.className = 'the__mo-ta';
  moTa.textContent = `${sp.tacGia} — ${sp.nhaXuatBan}`;
  than.appendChild(moTa);

  const pGia = document.createElement('p');
  pGia.className = 'the__gia';
  pGia.textContent = dinhDangTien(sp.gia) + ' ';

  if (sp.giaGoc && sp.giaGoc > sp.gia) {
    const giaGoc = document.createElement('span');
    giaGoc.className = 'the__gia-goc';
    giaGoc.textContent = dinhDangTien(sp.giaGoc);
    pGia.appendChild(giaGoc);
  }
  than.appendChild(pGia);

  // 3. Chân thẻ: nút Chi tiết và nút Yêu thích
  const chan = document.createElement('div');
  chan.className = 'the__chan';

  const linkChiTiet = document.createElement('a');
  linkChiTiet.href = `chi-tiet.html?id=${sp.id}`;
  linkChiTiet.className = 'nut nut--chinh';
  linkChiTiet.textContent = 'Chi tiết';
  chan.appendChild(linkChiTiet);

  const nutYeuThich = document.createElement('button');
  nutYeuThich.type = 'button';
  nutYeuThich.className = 'nut nut--vien nut-bam-yeu-thich';
  nutYeuThich.dataset.id = String(sp.id);

  const daThich = kiemTraYeuThich(sp.id);
  nutYeuThich.textContent = daThich ? '❤️ Đã thích' : '🤍 Yêu thích';
  nutYeuThich.setAttribute('aria-pressed', String(daThich));
  nutYeuThich.setAttribute('aria-label', `${daThich ? 'Bỏ thích' : 'Thêm vào yêu thích'} ${sp.ten}`);
  if (daThich) nutYeuThich.classList.add('da-thich');

  chan.appendChild(nutYeuThich);
  than.appendChild(chan);
  the.appendChild(than);

  return the;
}

/**
 * Hiển thị danh sách sản phẩm ra màn hình
 * @param {Array} danhSach 
 */
function renderDanhSach(danhSach) {
  if (!KHUNG_CHUA_LUOI) return;
  KHUNG_CHUA_LUOI.innerHTML = '';

  // Trạng thái Rỗng (Empty)
  if (!danhSach || danhSach.length === 0) {
    const theRong = document.createElement('div');
    theRong.className = 'thong-bao-rong';
    theRong.setAttribute('role', 'status');

    const h4 = document.createElement('h3');
    h4.textContent = '🔍 Không tìm thấy sản phẩm phù hợp';
    theRong.appendChild(h4);

    const p = document.createElement('p');
    p.textContent = 'Vui lòng thử thay đổi từ khóa tìm kiếm hoặc chọn lại danh mục ngành học khác.';
    theRong.appendChild(p);

    KHUNG_CHUA_LUOI.appendChild(theRong);
    if (THONG_BAO_KET_QUA) THONG_BAO_KET_QUA.textContent = 'Tìm thấy 0 sản phẩm.';
    return;
  }

  // Render các thẻ sản phẩm
  danhSach.forEach((sp) => {
    KHUNG_CHUA_LUOI.appendChild(taoTheSanPham(sp));
  });

  if (THONG_BAO_KET_QUA) {
    THONG_BAO_KET_QUA.textContent = `Đang hiển thị ${danhSach.length} sản phẩm phù hợp.`;
  }
}

/**
 * Xử lý lọc, tìm kiếm và sắp xếp kết hợp
 */
function xuLyLocVaSapXep() {
  const tuKhoa = boDauTiengViet(O_TIM_KIEM ? O_TIM_KIEM.value : '');
  const danhMuc = SELECT_DANH_MUC ? SELECT_DANH_MUC.value : 'tat-ca';
  const sapXep = SELECT_SAP_XEP ? SELECT_SAP_XEP.value : 'mac-dinh';

  // 1. Lọc theo từ khóa tìm kiếm (tên sách, tác giả, nhà xuất bản)
  let ketQua = danhSachGoc.filter((sp) => {
    const matchTuKhoa =
      !tuKhoa ||
      boDauTiengViet(sp.ten).includes(tuKhoa) ||
      boDauTiengViet(sp.tacGia).includes(tuKhoa) ||
      boDauTiengViet(sp.nhaXuatBan).includes(tuKhoa);

    const matchDanhMuc = danhMuc === 'tat-ca' || sp.theLoai === danhMuc;

    return matchTuKhoa && matchDanhMuc;
  });

  // 2. Sắp xếp
  if (sapXep === 'gia-tang') {
    ketQua.sort((a, b) => a.gia - b.gia);
  } else if (sapXep === 'gia-giam') {
    ketQua.sort((a, b) => b.gia - a.gia);
  } else if (sapXep === 'ten-az') {
    ketQua.sort((a, b) => a.ten.localeCompare(b.ten, 'vi'));
  } else if (sapXep === 'ten-za') {
    ketQua.sort((a, b) => b.ten.localeCompare(a.ten, 'vi'));
  }

  renderDanhSach(ketQua);
}

/**
 * Tải danh sách sách từ tệp JSON
 */
async function khoiTaoTrangDanhSach() {
  if (!KHUNG_CHUA_LUOI) return;

  // 1. Trạng thái Đang tải (Loading)
  KHUNG_CHUA_LUOI.innerHTML = '';
  const theTai = document.createElement('div');
  theTai.className = 'trang-thai-tai';
  theTai.textContent = '⏳ Đang nạp danh mục giáo trình BookNest từ tệp dữ liệu...';
  KHUNG_CHUA_LUOI.appendChild(theTai);

  try {
    danhSachGoc = await taiJSON('data/san-pham.json');
    xuLyLocVaSapXep();
  } catch (error) {
    // 2. Trạng thái Lỗi (Error)
    console.error('Lỗi nạp danh sách sản phẩm:', error);
    KHUNG_CHUA_LUOI.innerHTML = '';

    const theLoi = document.createElement('div');
    theLoi.className = 'thong-bao-loi';
    theLoi.setAttribute('role', 'alert');

    const tieuDeLoi = document.createElement('strong');
    tieuDeLoi.textContent = '❌ Không thể tải danh sách sản phẩm: ';
    theLoi.appendChild(tieuDeLoi);

    const chiTietLoi = document.createElement('span');
    chiTietLoi.textContent = error.message || 'Lỗi đường truyền hoặc định dạng tệp JSON không hợp lệ.';
    theLoi.appendChild(chiTietLoi);

    const nutThuLai = document.createElement('button');
    nutThuLai.type = 'button';
    nutThuLai.className = 'nut nut--vien nut-thu-lai';
    nutThuLai.textContent = '🔄 Thử lại';
    nutThuLai.style.marginLeft = '12px';
    nutThuLai.addEventListener('click', khoiTaoTrangDanhSach);
    theLoi.appendChild(nutThuLai);

    KHUNG_CHUA_LUOI.appendChild(theLoi);
  }
}

/**
 * Thiết lập các sự kiện lắng nghe
 */
function dangKySuKien() {
  if (O_TIM_KIEM) {
    O_TIM_KIEM.addEventListener('input', xuLyLocVaSapXep);
  }
  if (SELECT_DANH_MUC) {
    SELECT_DANH_MUC.addEventListener('change', xuLyLocVaSapXep);
  }
  if (SELECT_SAP_XEP) {
    SELECT_SAP_XEP.addEventListener('change', xuLyLocVaSapXep);
  }

  // Chức năng 6: Ủy quyền sự kiện (Event Delegation) ở phần tử cha KHUNG_CHUA_LUOI
  if (KHUNG_CHUA_LUOI) {
    KHUNG_CHUA_LUOI.addEventListener('click', (suKien) => {
      const nutYeuThich = suKien.target.closest('.nut-bam-yeu-thich');
      if (!nutYeuThich) return;

      const id = Number(nutYeuThich.dataset.id);
      const daThich = chuyenDoiYeuThich(id);

      nutYeuThich.textContent = daThich ? '❤️ Đã thích' : '🤍 Yêu thích';
      nutYeuThich.setAttribute('aria-pressed', String(daThich));
      nutYeuThich.classList.toggle('da-thich', daThich);
    });
  }

  // Đồng bộ giao diện khi có thay đổi yêu thích từ trang khác
  window.addEventListener('booknest:yeuthich-thaydoi', (e) => {
    const { id, daThem } = e.detail;
    const nut = KHUNG_CHUA_LUOI?.querySelector(`.nut-bam-yeu-thich[data-id="${id}"]`);
    if (nut) {
      nut.textContent = daThem ? '❤️ Đã thích' : '🤍 Yêu thích';
      nut.setAttribute('aria-pressed', String(daThem));
      nut.classList.toggle('da-thich', daThem);
    }
  });
}

// Khởi chạy khi nạp module
khoiTaoTrangDanhSach();
dangKySuKien();
