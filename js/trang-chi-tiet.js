/**
 * Tệp: js/trang-chi-tiet.js
 * Mô tả: Module phục vụ trang chi tiết sản phẩm (chi-tiet.html).
 * Chức năng 3 (Bảng 1): Đọc tham số ?id= trên URL bằng URLSearchParams, tìm kiếm phần tử tương ứng,
 * cập nhật tiêu đề trang (document.title), render an toàn thông tin sản phẩm và xử lý thông báo "Không tìm thấy".
 * Chức năng 6 (Bảng 1): Nút thêm/bỏ yêu thích đồng bộ với localStorage.
 * Nhóm 12 - Lớp 24CNTT3 - Người phụ trách: Vongsena Sauphasith
 */

import { taiJSON } from './api.js';
import { kiemTraYeuThich, chuyenDoiYeuThich } from './yeu-thich.js';

const KHUNG_CHI_TIET = document.querySelector('#chi-tiet-dong');

/**
 * Định dạng tiền tệ VNĐ
 * @param {number} soTien 
 * @returns {string}
 */
function dinhDangTien(soTien) {
  return new Intl.NumberFormat('vi-VN').format(soTien) + ' VNĐ';
}

/**
 * Dựng giao diện chi tiết sản phẩm an toàn bằng DOM API
 * @param {Object} sp 
 */
function renderChiTietSanPham(sp) {
  if (!KHUNG_CHI_TIET) return;
  KHUNG_CHI_TIET.innerHTML = '';

  // Đổi tiêu đề tab trình duyệt theo tên sách
  document.title = `${sp.ten} – Nhà sách sinh viên BookNest`;

  const container = document.createElement('div');
  container.className = 'chi-tiet-container';

  // 1. Cột ảnh
  const cotAnh = document.createElement('div');
  cotAnh.className = 'chi-tiet__cot-anh';

  const figure = document.createElement('figure');
  const img = document.createElement('img');
  img.src = sp.hinhAnh;
  img.alt = `Bìa giáo trình ${sp.ten}`;
  img.width = 320;
  img.height = 420;
  figure.appendChild(img);

  const figcaption = document.createElement('figcaption');
  figcaption.textContent = `Hình ảnh thực tế giáo trình ${sp.ten}`;
  figure.appendChild(figcaption);
  cotAnh.appendChild(figure);
  container.appendChild(cotAnh);

  // 2. Cột thông tin
  const cotThongTin = document.createElement('div');
  cotThongTin.className = 'chi-tiet__cot-thong-tin';

  const h1 = document.createElement('h1');
  h1.className = 'chi-tiet__tieu-de';
  h1.textContent = sp.ten;
  cotThongTin.appendChild(h1);

  // Huy hiệu thể loại
  const pTheLoai = document.createElement('p');
  pTheLoai.className = 'chi-tiet__the-loai';
  const badgeTheLoai = document.createElement('span');
  badgeTheLoai.className = 'huy-hieu huy-hieu--thanh-cong';
  badgeTheLoai.textContent =
    sp.theLoai === 'cntt'
      ? 'Chuyên ngành Công nghệ thông tin'
      : sp.theLoai === 'toanhoc'
      ? 'Chuyên ngành Toán học'
      : 'Văn phòng phẩm & Dụng cụ';
  pTheLoai.appendChild(badgeTheLoai);
  cotThongTin.appendChild(pTheLoai);

  // Giá bán
  const pGia = document.createElement('p');
  pGia.className = 'chi-tiet__gia';
  pGia.textContent = dinhDangTien(sp.gia) + ' ';
  if (sp.giaGoc && sp.giaGoc > sp.gia) {
    const spanGiaGoc = document.createElement('span');
    spanGiaGoc.className = 'the__gia-goc';
    spanGiaGoc.textContent = dinhDangTien(sp.giaGoc);
    pGia.appendChild(spanGiaGoc);
  }
  cotThongTin.appendChild(pGia);

  // Mô tả tóm tắt
  const pMoTa = document.createElement('p');
  pMoTa.className = 'chi-tiet__mo-ta';
  pMoTa.textContent = sp.moTa;
  cotThongTin.appendChild(pMoTa);

  // Bảng thông số chi tiết
  const bang = document.createElement('table');
  bang.className = 'bang-thong-so';

  const thongSo = [
    ['Tác giả:', sp.tacGia],
    ['Nhà xuất bản:', sp.nhaXuatBan],
    ['Năm xuất bản:', String(sp.namXuatBan)],
    ['Số trang:', `${sp.soTrang} trang`],
    ['Số lượng tồn kho:', `${sp.soLuong} cuốn (Sẵn sàng giao trong 24h)`],
    ['Đánh giá bạn đọc:', `${sp.danhGia} / 5.0 ⭐⭐⭐⭐⭐`]
  ];

  const tbody = document.createElement('tbody');
  thongSo.forEach(([nhan, tri]) => {
    const tr = document.createElement('tr');
    const th = document.createElement('th');
    th.textContent = nhan;
    const td = document.createElement('td');
    td.textContent = tri;
    tr.appendChild(th);
    tr.appendChild(td);
    tbody.appendChild(tr);
  });
  bang.appendChild(tbody);
  cotThongTin.appendChild(bang);

  // Nút hành động: Đặt mua và Thêm vào yêu thích
  const hangNut = document.createElement('div');
  hangNut.className = 'chi-tiet__hanh-dong';

  const linkDatHang = document.createElement('a');
  linkDatHang.href = `lien-he.html?sach=${encodeURIComponent(sp.ten)}`;
  linkDatHang.className = 'nut nut--chinh nut--lon';
  linkDatHang.textContent = 'Đặt mua sách này';
  hangNut.appendChild(linkDatHang);

  const nutYeuThich = document.createElement('button');
  nutYeuThich.type = 'button';
  nutYeuThich.className = 'nut nut--vien nut--lon nut-bam-yeu-thich';
  nutYeuThich.dataset.id = String(sp.id);

  const daThich = kiemTraYeuThich(sp.id);
  nutYeuThich.textContent = daThich ? '❤️ Đã thêm vào yêu thích' : '🤍 Thêm vào yêu thích';
  nutYeuThich.setAttribute('aria-pressed', String(daThich));

  nutYeuThich.addEventListener('click', () => {
    const trangThai = chuyenDoiYeuThich(sp.id);
    nutYeuThich.textContent = trangThai ? '❤️ Đã thêm vào yêu thích' : '🤍 Thêm vào yêu thích';
    nutYeuThich.setAttribute('aria-pressed', String(trangThai));
    nutYeuThich.classList.toggle('da-thich', trangThai);
  });

  hangNut.appendChild(nutYeuThich);

  const linkQuayLai = document.createElement('a');
  linkQuayLai.href = 'danh-sach.html';
  linkQuayLai.className = 'nut nut--vien';
  linkQuayLai.textContent = '← Quay lại danh mục';
  hangNut.appendChild(linkQuayLai);

  cotThongTin.appendChild(hangNut);
  container.appendChild(cotThongTin);

  KHUNG_CHI_TIET.appendChild(container);
}

/**
 * Khởi tạo trang chi tiết
 */
async function khoiTaoTrangChiTiet() {
  if (!KHUNG_CHI_TIET) return;

  // Đọc id từ URL: chi-tiet.html?id=5
  const thamSoURL = new URLSearchParams(window.location.search);
  const idRaw = thamSoURL.get('id');

  // Mặc định id = 1 nếu không truyền tham số để trang không bị trống khi mở trực tiếp
  const id = idRaw ? Number(idRaw) : 1;

  // 1. Trạng thái Đang tải (Loading)
  KHUNG_CHI_TIET.innerHTML = '';
  const theTai = document.createElement('div');
  theTai.className = 'trang-thai-tai';
  theTai.textContent = '⏳ Đang nạp thông tin chi tiết giáo trình BookNest...';
  KHUNG_CHI_TIET.appendChild(theTai);

  try {
    const dsSanPham = await taiJSON('data/san-pham.json');
    const sp = dsSanPham.find((item) => item.id === id);

    if (!sp) {
      // Trạng thái Không tìm thấy
      KHUNG_CHI_TIET.innerHTML = '';
      const theRong = document.createElement('div');
      theRong.className = 'thong-bao-rong';
      theRong.setAttribute('role', 'alert');

      const h2 = document.createElement('h2');
      h2.textContent = '⚠️ Không tìm thấy sản phẩm yêu cầu';
      theRong.appendChild(h2);

      const p = document.createElement('p');
      p.textContent = `Mã sản phẩm #${id} không tồn tại trong hệ thống BookNest hoặc đã ngừng phát hành.`;
      theRong.appendChild(p);

      const linkVe = document.createElement('a');
      linkVe.href = 'danh-sach.html';
      linkVe.className = 'nut nut--chinh';
      linkVe.textContent = 'Xem toàn bộ danh mục sách';
      theRong.appendChild(linkVe);

      KHUNG_CHI_TIET.appendChild(theRong);
      document.title = 'Không tìm thấy sản phẩm – BookNest';
      return;
    }

    renderChiTietSanPham(sp);
  } catch (error) {
    // 2. Trạng thái Lỗi (Error)
    console.error('Lỗi khi nạp chi tiết sản phẩm:', error);
    KHUNG_CHI_TIET.innerHTML = '';

    const theLoi = document.createElement('div');
    theLoi.className = 'thong-bao-loi';
    theLoi.setAttribute('role', 'alert');

    const tieuDeLoi = document.createElement('strong');
    tieuDeLoi.textContent = '❌ Không thể tải dữ liệu chi tiết sách: ';
    theLoi.appendChild(tieuDeLoi);

    const chiTiet = document.createElement('span');
    chiTiet.textContent = error.message || 'Mất kết nối mạng.';
    theLoi.appendChild(chiTiet);

    const nutThuLai = document.createElement('button');
    nutThuLai.type = 'button';
    nutThuLai.className = 'nut nut--vien nut-thu-lai';
    nutThuLai.textContent = '🔄 Thử lại';
    nutThuLai.style.marginLeft = '12px';
    nutThuLai.addEventListener('click', khoiTaoTrangChiTiet);
    theLoi.appendChild(nutThuLai);

    KHUNG_CHI_TIET.appendChild(theLoi);
  }
}

// Chạy khởi tạo
khoiTaoTrangChiTiet();
