/**
 * Tệp: js/trang-chu.js
 * Mô tả: Module phục vụ trang chủ (index.html).
 * Chức năng 5 (Bảng 1): Tích hợp khối dữ liệu thời gian thực từ REST API công khai Open-Meteo
 * (Thời tiết tại Đà Nẵng) kèm lời khuyên chọn sách đọc phù hợp trong ngày.
 * Nhóm 12 - Lớp 24CNTT3 - Người phụ trách: Xaiyasith Yoi (Nhóm trưởng)
 */

import { taiJSON } from './api.js';

const KHUNG_THOI_TIET = document.querySelector('#khoi-thoi-tiet-dong');

/**
 * Ánh xạ mã thời tiết WMO (Weather Interpretation Codes) sang mô tả tiếng Việt
 * @param {number} code 
 * @returns {string} Mô tả thời tiết
 */
function giaiMaThoiTiet(code) {
  if (code === 0) return 'Trời quang đãng, nắng đẹp';
  if ([1, 2, 3].includes(code)) return 'Trời có mây rải rác, mát mẻ';
  if ([45, 48].includes(code)) return 'Có sương mù nhẹ';
  if ([51, 53, 55, 61, 63, 65].includes(code)) return 'Có mưa rào rải rác';
  if ([80, 81, 82].includes(code)) return 'Mưa lớn cục bộ';
  if (code >= 95) return 'Có dông sét';
  return 'Thời tiết ôn hòa';
}

/**
 * Đưa ra gợi ý sách theo điều kiện nhiệt độ và thời tiết
 * @param {number} nhietDo 
 * @param {string} moTa 
 * @returns {string} Lời khuyên đọc sách
 */
function goiYSachTheoThoiTiet(nhietDo, moTa) {
  if (nhietDo < 22 || moTa.includes('mưa')) {
    return 'Thời tiết se mát và có mưa rất thích hợp để nhâm nhi tách trà nóng và nghiên cứu sâu cuốn "Cấu trúc dữ liệu và Giải thuật"!';
  }
  if (nhietDo > 32) {
    return 'Trời nắng nóng, hãy ghé phòng tự học máy lạnh của trường ĐH Sư phạm để ôn luyện môn "Lập trình Web hiện đại" cùng bạn bè nhé!';
  }
  return 'Thời tiết Đà Nẵng hôm nay rất dễ chịu, là thời điểm lý tưởng để đọc sách chuyên ngành và ghi chép kiến thức mới vào sổ tay BookNest.';
}

/**
 * Tải và hiển thị dữ liệu thời tiết
 */
async function taiVaHienThiThoiTiet() {
  if (!KHUNG_THOI_TIET) return;

  // 1. Trạng thái Đang tải (Loading)
  KHUNG_THOI_TIET.innerHTML = '';
  const thongBaoTai = document.createElement('div');
  thongBaoTai.className = 'trang-thai-tai';
  thongBaoTai.textContent = '⏳ Đang nạp dữ liệu thời tiết Đà Nẵng từ Open-Meteo API...';
  KHUNG_THOI_TIET.appendChild(thongBaoTai);

  const thamSo = new URLSearchParams({
    latitude: '16.05',
    longitude: '108.20',
    current: 'temperature_2m,relative_humidity_2m,weather_code',
    timezone: 'auto'
  });

  const urlAPI = `https://api.open-meteo.com/v1/forecast?${thamSo.toString()}`;

  try {
    const duLieu = await taiJSON(urlAPI);

    // Xóa trạng thái đang tải
    KHUNG_THOI_TIET.innerHTML = '';

    const hienTai = duLieu.current;
    const nhietDo = hienTai.temperature_2m;
    const doAm = hienTai.relative_humidity_2m;
    const moTa = giaiMaThoiTiet(hienTai.weather_code);
    const loiKhuyen = goiYSachTheoThoiTiet(nhietDo, moTa);

    // Dựng giao diện an toàn bằng DOM API
    const theThoiTiet = document.createElement('div');
    theThoiTiet.className = 'the-thoi-tiet';

    const tieuDe = document.createElement('h3');
    tieuDe.textContent = '🌤️ Góc bạn đọc & Thời tiết hôm nay tại Đà Nẵng';
    theThoiTiet.appendChild(tieuDe);

    const hangThongTin = document.createElement('div');
    hangThongTin.className = 'hang-thong-so-thoi-tiet';

    const pNhietDo = document.createElement('p');
    pNhietDo.innerHTML = `<strong>Nhiệt độ hiện tại:</strong> <span class="chi-so">${nhietDo} °C</span>`;

    const pDoAm = document.createElement('p');
    pDoAm.innerHTML = `<strong>Độ ẩm:</strong> <span class="chi-so">${doAm}%</span>`;

    const pMoTa = document.createElement('p');
    pMoTa.innerHTML = `<strong>Tình trạng:</strong> <span class="chi-so">${moTa}</span>`;

    hangThongTin.appendChild(pNhietDo);
    hangThongTin.appendChild(pDoAm);
    hangThongTin.appendChild(pMoTa);
    theThoiTiet.appendChild(hangThongTin);

    const pLoiKhuyen = document.createElement('p');
    pLoiKhuyen.className = 'loi-khuyen-doc-sach';
    pLoiKhuyen.textContent = `💡 Lời khuyên BookNest: ${loiKhuyen}`;
    theThoiTiet.appendChild(pLoiKhuyen);

    const pNguon = document.createElement('p');
    pNguon.className = 'nguon-api';
    pNguon.textContent = 'Dữ liệu được cập nhật trực tiếp từ Open-Meteo REST API (Vĩ độ: 16.05°N, Kinh độ: 108.20°E).';
    theThoiTiet.appendChild(pNguon);

    KHUNG_THOI_TIET.appendChild(theThoiTiet);
  } catch (error) {
    // 2. Trạng thái Lỗi (Error)
    console.error('Không thể lấy dữ liệu Open-Meteo:', error);
    KHUNG_THOI_TIET.innerHTML = '';

    const theLoi = document.createElement('div');
    theLoi.className = 'thong-bao-loi';
    theLoi.setAttribute('role', 'alert');

    const tieuDeLoi = document.createElement('strong');
    tieuDeLoi.textContent = '⚠️ Không thể tải dữ liệu thời tiết trực tuyến: ';
    theLoi.appendChild(tieuDeLoi);

    const noiDungLoi = document.createElement('span');
    noiDungLoi.textContent = error.message || 'Mất kết nối mạng hoặc máy chủ API không phản hồi.';
    theLoi.appendChild(noiDungLoi);

    const nutThuLai = document.createElement('button');
    nutThuLai.type = 'button';
    nutThuLai.className = 'nut nut--vien nut-thu-lai';
    nutThuLai.textContent = '🔄 Thử lại';
    nutThuLai.style.marginLeft = '12px';
    nutThuLai.addEventListener('click', taiVaHienThiThoiTiet);
    theLoi.appendChild(nutThuLai);

    KHUNG_THOI_TIET.appendChild(theLoi);
  }
}

// Chạy khi nạp trang
taiVaHienThiThoiTiet();
