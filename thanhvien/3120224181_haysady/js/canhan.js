/**
 * Tệp: thanhvien/3120224181_haysady/js/canhan.js
 * Tác giả: Phommaket Haysady (MSV: 3120224181) - Thành viên Nhóm 12
 * Mô tả: Script tương tác độc lập cho trang cá nhân của Haysady:
 *  1. Tương tác 1: Bộ lọc danh mục kỹ năng chuyên môn (Tất cả, Giao diện Web, Công cụ & Kiểm thử, Kỹ năng mềm).
 *  2. Tương tác 2: Accordion thu gọn / mở rộng chi tiết các đề án môn học và chứng chỉ (hỗ trợ aria-expanded).
 * Cách thử nghiệm:
 *  - Nhấp các nút phân loại kỹ năng để lọc tức thì danh sách kỹ năng tương ứng.
 *  - Nhấp vào tiêu đề các mục trong danh sách Đề án để đóng/mở nội dung chi tiết.
 */

// 1. TƯƠNG TÁC 1: BỘ LỌC DANH MỤC KỸ NĂNG CHUYÊN MÔN
const DU_LIEU_KY_NANG = [
  { ten: 'Thiết kế bố cục lưới CSS Grid & Flexbox', nhom: 'web' },
  { ten: 'Xây dựng giao diện với Bootstrap 5', nhom: 'web' },
  { ten: 'JavaScript ES6+, DOM Scripting & Fetch API', nhom: 'web' },
  { ten: 'Kiểm chuẩn W3C Validator & Chrome DevTools', nhom: 'cong-cu' },
  { ten: 'Quản lý mã nguồn nhóm qua Git & GitHub', nhom: 'cong-cu' },
  { ten: 'Thiết kế đồ họa giao diện Figma & Photoshop', nhom: 'cong-cu' },
  { ten: 'Kỹ năng làm việc nhóm & Thuyết trình kỹ thuật', nhom: 'mem' },
  { ten: 'Giao tiếp tiếng Lào & tiếng Việt thành thạo', nhom: 'mem' }
];

function khoiTaoBoLocKyNang() {
  const ulKyNang = document.querySelector('.danh-sach-ky-nang');
  if (!ulKyNang) return;

  const container = document.createElement('div');
  container.className = 'khu-vuc-loc-ky-nang';
  container.style.margin = '12px 0 16px 0';

  const danhMuc = [
    { ma: 'tat-ca', nhan: 'Tất cả kỹ năng' },
    { ma: 'web', nhan: '🌐 Giao diện Web' },
    { ma: 'cong-cu', nhan: '🛠️ Công cụ & Kiểm thử' },
    { ma: 'mem', nhan: '🤝 Kỹ năng mềm' }
  ];

  danhMuc.forEach((dm, idx) => {
    const nut = document.createElement('button');
    nut.type = 'button';
    nut.className = `nut nut--vien nut-loc-kn ${idx === 0 ? 'active' : ''}`;
    nut.style.marginRight = '8px';
    nut.style.marginBottom = '6px';
    nut.textContent = dm.nhan;
    nut.dataset.nhom = dm.ma;

    nut.addEventListener('click', () => {
      container.querySelectorAll('.nut-loc-kn').forEach((b) => b.classList.remove('active'));
      nut.classList.add('active');
      renderKyNang(dm.ma);
    });

    container.appendChild(nut);
  });

  function renderKyNang(nhom) {
    ulKyNang.innerHTML = '';
    const loc = nhom === 'tat-ca' ? DU_LIEU_KY_NANG : DU_LIEU_KY_NANG.filter((k) => k.nhom === nhom);
    loc.forEach((k) => {
      const li = document.createElement('li');
      li.textContent = k.ten;
      ulKyNang.appendChild(li);
    });
  }

  // Chèn bộ lọc lên trước danh sách kỹ năng
  ulKyNang.parentNode.insertBefore(container, ulKyNang);
  renderKyNang('tat-ca');
}

// 2. TƯƠNG TÁC 2: ACCORDION CHI TIẾT ĐỀ ÁN HỌC TẬP
function khoiTaoAccordionDeAn() {
  const article = document.querySelector('article');
  if (!article) return;

  const section = document.createElement('section');
  section.className = 'khu-vuc-accordion';
  section.innerHTML = `<h3>Danh mục Đề án & Thành tựu học tập</h3>`;

  const danhSachDeAn = [
    {
      tieuDe: '1. Đồ án BookNest – Website Bán Sách Trực Tuyến UED (2026)',
      noiDung: 'Đảm nhận xây dựng danh-sach.html, so sánh hiệu năng giữa CSS tự viết và Bootstrap 5. Tối ưu hóa 0 lỗi W3C Validator và điểm Lighthouse Mobile 95/100.'
    },
    {
      tieuDe: '2. Ứng dụng Quản lý Điểm Sinh viên bằng Java Swing (2025)',
      noiDung: 'Xây dựng phần mềm quản lý hồ sơ và bảng điểm theo kiến trúc MVC, kết nối cơ sở dữ liệu MySQL và xuất báo cáo kết quả học tập.'
    },
    {
      tieuDe: '3. Nghiên cứu UI/UX và Khả năng truy cập Web (WCAG 2.1 AA)',
      noiDung: 'Tìm hiểu các tiêu chuẩn màu sắc tương phản tối thiểu 4.5:1, hỗ trợ phím Tab và điều hướng bằng trình đọc màn hình.'
    }
  ];

  danhSachDeAn.forEach((da, idx) => {
    const item = document.createElement('div');
    item.className = 'accordion-item';
    item.style.border = '1px solid #cbd5e1';
    item.style.borderRadius = '6px';
    item.style.marginBottom = '8px';
    item.style.overflow = 'hidden';

    const header = document.createElement('button');
    header.type = 'button';
    header.className = 'accordion-header';
    header.style.width = '100%';
    header.style.textAlign = 'left';
    header.style.padding = '12px 16px';
    header.style.backgroundColor = '#f1f5f9';
    header.style.border = 'none';
    header.style.cursor = 'pointer';
    header.style.fontWeight = 'bold';
    header.style.color = '#123b6d';
    header.style.display = 'flex';
    header.style.justifyContent = 'space-between';
    header.style.alignItems = 'center';
    header.setAttribute('aria-expanded', 'false');

    const spanText = document.createElement('span');
    spanText.textContent = da.tieuDe;
    const spanIcon = document.createElement('span');
    spanIcon.textContent = '➕';
    header.appendChild(spanText);
    header.appendChild(spanIcon);

    const body = document.createElement('div');
    body.className = 'accordion-body';
    body.style.padding = '12px 16px';
    body.style.display = 'none';
    body.style.backgroundColor = '#ffffff';
    body.style.fontSize = '14px';
    body.textContent = da.noiDung;

    header.addEventListener('click', () => {
      const dangMo = body.style.display === 'block';
      body.style.display = dangMo ? 'none' : 'block';
      header.setAttribute('aria-expanded', String(!dangMo));
      spanIcon.textContent = dangMo ? '➕' : '➖';
      header.style.backgroundColor = dangMo ? '#f1f5f9' : '#e2e8f0';
    });

    item.appendChild(header);
    item.appendChild(body);
    section.appendChild(item);
  });

  article.appendChild(section);
}

// Chèn CSS bổ sung cho nút active
const styleEl = document.createElement('style');
styleEl.textContent = `
  .nut-loc-kn.active {
    background-color: #123b6d !important;
    color: #ffffff !important;
    border-color: #123b6d !important;
  }
`;
document.head.appendChild(styleEl);

document.addEventListener('DOMContentLoaded', () => {
  khoiTaoBoLocKyNang();
  khoiTaoAccordionDeAn();
});
