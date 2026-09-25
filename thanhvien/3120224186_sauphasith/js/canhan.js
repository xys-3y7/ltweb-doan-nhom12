/**
 * Tệp: thanhvien/3120224186_sauphasith/js/canhan.js
 * Tác giả: Vongsena Sauphasith (MSV: 3120224186) - Thành viên Nhóm 12
 * Mô tả: Script tương tác độc lập cho trang cá nhân của Sauphasith:
 *  1. Tương tác 1: Đồng hồ đếm ngược thời gian thực (Countdown Timer) đến ngày bảo vệ đồ án Web cuối kỳ.
 *  2. Tương tác 2: Khung đánh giá sao tương tác (5-Star Rating) lưu điểm vào localStorage.
 * Cách thử nghiệm:
 *  - Xem khối đồng hồ đếm ngược tự động nhảy số giây/phút/giờ theo thời gian thực.
 *  - Di chuột hoặc nhấn vào các ngôi sao để đánh giá hồ sơ, trang sẽ lưu điểm và hiển thị lời cảm ơn.
 */

// 1. TƯƠNG TÁC 1: ĐỒNG HỒ ĐẾM NGƯỢC THỜI GIAN THỰC (COUNTDOWN TIMER)
function khoiTaoDongHoDemNguoc() {
  const h1 = document.querySelector('h1');
  if (!h1 || !h1.parentNode) return;

  const khungDongHo = document.createElement('div');
  khungDongHo.className = 'khung-dem-nguoc';
  khungDongHo.setAttribute('role', 'region');
  khungDongHo.setAttribute('aria-label', 'Đồng hồ đếm ngược thi cuối kỳ');
  khungDongHo.style.cssText = `
    background: linear-gradient(135deg, #123b6d 0%, #1e40af 100%);
    color: #ffffff;
    padding: 16px 20px;
    border-radius: 8px;
    margin: 16px 0 24px 0;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  `;

  khungDongHo.innerHTML = `
    <h3 style="margin: 0 0 8px 0; font-size: 15px; color: #f8fafc;">⏱️ Đếm ngược đến Ngày Báo cáo Đồ án Web Cuối kỳ (20/12/2026)</h3>
    <div id="dong-ho-hien-thi" style="font-size: 20px; font-weight: bold; letter-spacing: 1px; color: #fef08a;">
      Đang tính toán thời gian...
    </div>
  `;

  h1.parentNode.insertBefore(khungDongHo, h1.nextSibling);

  const NGAY_MUC_TIEU = new Date('2026-12-20T08:00:00+07:00').getTime();
  const elHienThi = khungDongHo.querySelector('#dong-ho-hien-thi');

  function capNhatDemNguoc() {
    const bayGio = new Date().getTime();
    const khoangCach = NGAY_MUC_TIEU - bayGio;

    if (khoangCach <= 0) {
      elHienThi.textContent = '🎉 Đã đến ngày bảo vệ đồ án! Chúc nhóm đạt kết quả xuất sắc!';
      return;
    }

    const ngay = Math.floor(khoangCach / (1000 * 60 * 60 * 24));
    const gio = Math.floor((khoangCach % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const phut = Math.floor((khoangCach % (1000 * 60 * 60)) / (1000 * 60));
    const giay = Math.floor((khoangCach % (1000 * 60)) / 1000);

    elHienThi.textContent = `${ngay} ngày : ${String(gio).padStart(2, '0')} giờ : ${String(phut).padStart(2, '0')} phút : ${String(giay).padStart(2, '0')} giây`;
  }

  capNhatDemNguoc();
  setInterval(capNhatDemNguoc, 1000);
}

// 2. TƯƠNG TÁC 2: ĐÁNH GIÁ 5 SAO LƯU LOCALSTORAGE
function khoiTaoDanhGiaSao() {
  const KHOA_DANH_GIA = 'sauphasith_rating_score';
  const article = document.querySelector('article');
  if (!article) return;

  const khungDanhGia = document.createElement('section');
  khungDanhGia.className = 'khung-danh-gia-sao';
  khungDanhGia.style.cssText = `
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 16px 20px;
    border-radius: 8px;
    margin-top: 20px;
    text-align: center;
  `;

  khungDanhGia.innerHTML = `
    <h3 style="margin-top: 0; color: #123b6d;">⭐ Đánh giá chất lượng hồ sơ cá nhân</h3>
    <p style="font-size: 14px; margin-bottom: 8px;">Bạn cảm thấy phần trình bày và các kỹ năng của Sauphasith như thế nào?</p>
    <div id="cac-ngoi-sao" style="font-size: 28px; cursor: pointer; user-select: none;">
      <span class="sao" data-diem="1" title="1 sao">☆</span>
      <span class="sao" data-diem="2" title="2 sao">☆</span>
      <span class="sao" data-diem="3" title="3 sao">☆</span>
      <span class="sao" data-diem="4" title="4 sao">☆</span>
      <span class="sao" data-diem="5" title="5 sao">☆</span>
    </div>
    <p id="thong-bao-danh-gia" style="margin: 8px 0 0 0; font-size: 13px; font-weight: bold; color: #047857;" aria-live="polite"></p>
  `;

  article.appendChild(khungDanhGia);

  const cacSao = khungDanhGia.querySelectorAll('.sao');
  const thongBao = khungDanhGia.querySelector('#thong-bao-danh-gia');

  function toMauSao(diem) {
    cacSao.forEach((sao) => {
      const diemSao = Number(sao.dataset.diem);
      if (diemSao <= diem) {
        sao.textContent = '★';
        sao.style.color = '#f59e0b';
      } else {
        sao.textContent = '☆';
        sao.style.color = '#cbd5e1';
      }
    });
  }

  // Đọc điểm đã lưu trước đó
  const diemDaLuu = Number(localStorage.getItem(KHOA_DANH_GIA)) || 0;
  if (diemDaLuu > 0) {
    toMauSao(diemDaLuu);
    thongBao.textContent = `Bạn đã đánh giá ${diemDaLuu}/5 sao. Xin chân thành cảm ơn ý kiến đóng góp của bạn!`;
  }

  cacSao.forEach((sao) => {
    sao.addEventListener('click', () => {
      const diem = Number(sao.dataset.diem);
      localStorage.setItem(KHOA_DANH_GIA, String(diem));
      toMauSao(diem);
      thongBao.textContent = `Cảm ơn bạn đã gửi đánh giá ${diem}/5 sao cho Sauphasith!`;
    });

    sao.addEventListener('mouseenter', () => {
      const diem = Number(sao.dataset.diem);
      toMauSao(diem);
    });
  });

  const vungSao = khungDanhGia.querySelector('#cac-ngoi-sao');
  vungSao.addEventListener('mouseleave', () => {
    const diemHienTai = Number(localStorage.getItem(KHOA_DANH_GIA)) || 0;
    toMauSao(diemHienTai);
  });
}

document.addEventListener('DOMContentLoaded', () => {
  khoiTaoDongHoDemNguoc();
  khoiTaoDanhGiaSao();
});
