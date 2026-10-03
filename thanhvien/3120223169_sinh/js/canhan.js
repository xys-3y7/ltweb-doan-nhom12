/**
 * Tệp: thanhvien/3120223169_sinh/js/canhan.js
 * Tác giả: Nguyễn Thị Sinh (MSV: 3120223169) - Thành viên Nhóm 12
 * Mô tả: Script tương tác độc lập cho trang cá nhân của Sinh:
 *  1. Tương tác 1: Ô tìm kiếm & lọc nhanh môn học trong bảng Thời khóa biểu tuần.
 *  2. Tương tác 2: Công cụ tính điểm trung bình học phần & quy đổi GPA (hệ 10 sang hệ 4 và hệ chữ).
 * Cách thử nghiệm:
 *  - Gõ tên môn (ví dụ "Web", "Toán", "Mạng") vào ô tìm kiếm trên thời khóa biểu để xem highlight ô môn học.
 *  - Nhập điểm chuyên cần, điểm giữa kỳ, điểm thi cuối kỳ vào bảng tính GPA để xem điểm tổng kết và xếp loại.
 */

// 1. TƯƠNG TÁC 1: TÌM KIẾM NHANH MÔN HỌC TRONG BẢNG THỜI KHÓA BIỂU
function khoiTaoTimKiemTKB() {
  const bangTKB = document.querySelector('.khung-cuon-tkb table');
  if (!bangTKB || !bangTKB.parentNode) return;

  const khungTimKiem = document.createElement('div');
  khungTimKiem.className = 'khung-tim-tkb';
  khungTimKiem.style.cssText = 'margin: 12px 0; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;';

  const nhan = document.createElement('label');
  nhan.htmlFor = 'o-loc-tkb';
  nhan.textContent = '🔍 Lọc nhanh môn học:';
  nhan.style.fontWeight = 'bold';

  const input = document.createElement('input');
  input.type = 'search';
  input.id = 'o-loc-tkb';
  input.placeholder = 'Nhập tên môn (Web, Toán, Cơ sở...)...';
  input.style.cssText = 'padding: 6px 12px; border: 1px solid #cbd5e1; border-radius: 4px; min-width: 220px;';

  const thongBao = document.createElement('span');
  thongBao.style.cssText = 'font-size: 13px; color: #1e40af;';

  khungTimKiem.appendChild(nhan);
  khungTimKiem.appendChild(input);
  khungTimKiem.appendChild(thongBao);

  bangTKB.parentNode.insertBefore(khungTimKiem, bangTKB);

  const cacO = bangTKB.querySelectorAll('tbody td');

  input.addEventListener('input', () => {
    const tuKhoa = input.value.trim().toLowerCase();
    let soKetQua = 0;

    cacO.forEach((o) => {
      const noiDung = o.textContent.trim().toLowerCase();
      // Khôi phục kiểu mặc định
      o.style.backgroundColor = '';
      o.style.outline = '';

      if (tuKhoa && noiDung.includes(tuKhoa) && noiDung !== '-' && !noiDung.includes('nghỉ')) {
        o.style.backgroundColor = '#fef08a';
        o.style.outline = '2px solid #ca8a04';
        soKetQua++;
      }
    });

    if (tuKhoa) {
      thongBao.textContent = `Tìm thấy ${soKetQua} buổi học phù hợp.`;
    } else {
      thongBao.textContent = '';
    }
  });
}

// 2. TƯƠNG TÁC 2: MÁY TÍNH ĐIỂM HỌC PHẦN & GPA HỆ 4
function khoiTaoMayTinhGPA() {
  const article = document.querySelector('article');
  if (!article) return;

  const section = document.createElement('section');
  section.className = 'may-tinh-gpa';
  section.style.cssText = `
    background-color: #f8fafc;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 18px 20px;
    margin-top: 24px;
  `;

  section.innerHTML = `
    <h3 style="margin-top: 0; color: #123b6d;">🧮 Máy tính điểm học phần & Quy đổi thang điểm 4</h3>
    <p style="font-size: 13.5px; color: #475569; margin-bottom: 14px;">
      Công cụ hỗ trợ sinh viên Sư phạm Đà Nẵng dự tính kết quả học phần (Điểm quá trình 40% + Điểm thi 60%):
    </p>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 12px;">
      <div>
        <label for="diem-qt" style="display:block; font-weight: bold; font-size: 13px; margin-bottom: 4px;">Điểm Quá trình (hệ 10):</label>
        <input type="number" id="diem-qt" min="0" max="10" step="0.1" value="8.5" style="width: 100%; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 4px;">
      </div>
      <div>
        <label for="diem-thi" style="display:block; font-weight: bold; font-size: 13px; margin-bottom: 4px;">Điểm Thi cuối kỳ (hệ 10):</label>
        <input type="number" id="diem-thi" min="0" max="10" step="0.1" value="8.0" style="width: 100%; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 4px;">
      </div>
    </div>
    <button type="button" id="nut-tinh-diem" class="nut nut--chinh" style="padding: 6px 16px; cursor: pointer;">Tính kết quả tổng kết</button>
    <div id="ket-qua-gpa" style="margin-top: 14px; padding: 12px; background-color: #ffffff; border-radius: 6px; border: 1px dashed #94a3b8;" aria-live="polite">
      <strong>Kết quả:</strong> Nhấp "Tính kết quả tổng kết" để xem chi tiết.
    </div>
  `;

  article.appendChild(section);

  const inputQT = section.querySelector('#diem-qt');
  const inputThi = section.querySelector('#diem-thi');
  const nutTinh = section.querySelector('#nut-tinh-diem');
  const elKetQua = section.querySelector('#ket-qua-gpa');

  function tinhKetQua() {
    const qt = parseFloat(inputQT.value);
    const thi = parseFloat(inputThi.value);

    if (isNaN(qt) || isNaN(thi) || qt < 0 || qt > 10 || thi < 0 || thi > 10) {
      elKetQua.innerHTML = `<span style="color: #dc2626;">⚠️ Vui lòng nhập điểm hợp lệ từ 0 đến 10!</span>`;
      return;
    }

    const tongKet10 = (qt * 0.4 + thi * 0.6).toFixed(2);
    let diemChu = 'F';
    let thang4 = 0.0;
    let xepLoai = 'Kém (Thi lại)';

    if (tongKet10 >= 8.5) {
      diemChu = 'A';
      thang4 = 4.0;
      xepLoai = 'Xuất sắc';
    } else if (tongKet10 >= 8.0) {
      diemChu = 'B+';
      thang4 = 3.5;
      xepLoai = 'Giỏi';
    } else if (tongKet10 >= 7.0) {
      diemChu = 'B';
      thang4 = 3.0;
      xepLoai = 'Khá';
    } else if (tongKet10 >= 6.5) {
      diemChu = 'C+';
      thang4 = 2.5;
      xepLoai = 'Trung bình khá';
    } else if (tongKet10 >= 5.5) {
      diemChu = 'C';
      thang4 = 2.0;
      xepLoai = 'Trung bình';
    } else if (tongKet10 >= 5.0) {
      diemChu = 'D+';
      thang4 = 1.5;
      xepLoai = 'Trung bình yếu';
    } else if (tongKet10 >= 4.0) {
      diemChu = 'D';
      thang4 = 1.0;
      xepLoai = 'Đạt';
    }

    elKetQua.innerHTML = `
      <div style="font-size: 14px; line-height: 1.6;">
        📊 Điểm tổng kết hệ 10: <strong style="color: #1e40af; font-size: 16px;">${tongKet10}</strong><br>
        🎯 Điểm hệ chữ: <strong style="color: #047857; font-size: 16px;">${diemChu}</strong> &nbsp;|&nbsp; 
        Thang điểm 4: <strong style="color: #b45309; font-size: 16px;">${thang4.toFixed(1)}</strong><br>
        🏆 Xếp loại học phần: <strong>${xepLoai}</strong>
      </div>
    `;
  }

  nutTinh.addEventListener('click', tinhKetQua);
  tinhKetQua(); // Khởi tạo ban đầu
}

document.addEventListener('DOMContentLoaded', () => {
  khoiTaoTimKiemTKB();
  khoiTaoMayTinhGPA();
});
