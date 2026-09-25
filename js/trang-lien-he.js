/**
 * Tệp: js/trang-lien-he.js
 * Mô tả: Module phục vụ trang liên hệ và đặt sách trực tuyến (lien-he.html).
 * Chức năng 4 (Bảng 1): Kiểm tra hợp lệ dữ liệu biểu mẫu phía client (Client-side Form Validation)
 * với novalidate và Constraint Validation API (validity / setCustomValidity), hiển thị thông báo lỗi
 * dưới từng trường khi blur/input, gửi dữ liệu ngầm bằng Fetch API (POST) tới JSONPlaceholder,
 * khóa nút submit khi đang chờ và phản hồi kết quả trực quan có thuộc tính aria-live="polite".
 * Nhóm 12 - Lớp 24CNTT3 - Người phụ trách: Nguyễn Thị Sinh
 */

const FORM_LIEN_HE = document.querySelector('#form-lien-he');
const KHUNG_THONG_BAO = document.querySelector('#thong-bao-gui-form');
const NUT_SUBMIT = document.querySelector('#nut-gui-form');

const API_LIEN_HE = 'https://jsonplaceholder.typicode.com/posts';

/**
 * Kiểm tra tính hợp lệ của từng trường nhập liệu cụ thể
 * @param {HTMLInputElement|HTMLTextAreaElement|HTMLSelectElement} field 
 * @returns {boolean} True nếu hợp lệ, False nếu có lỗi
 */
function kiemTraTruong(field) {
  const tenTruong = field.name || field.id;
  const thongBaoLoi = document.querySelector(`#loi-${tenTruong}`);
  let thongDiep = '';

  field.setCustomValidity('');

  // 1. Kiểm tra trường bắt buộc (required)
  if (field.validity.valueMissing) {
    thongDiep = 'Vui lòng không bỏ trống trường thông tin này.';
  }
  // 2. Kiểm tra định dạng Email
  else if (field.type === 'email' && field.validity.typeMismatch) {
    thongDiep = 'Địa chỉ email không đúng định dạng (Ví dụ: sinhvien@ued.udn.vn).';
  }
  // 3. Kiểm tra số điện thoại (10 chữ số VN)
  else if (field.id === 'so-dien-thoai') {
    const val = field.value.trim();
    const regexSDT = /^0[0-9]{9}$/;
    if (!regexSDT.test(val)) {
      thongDiep = 'Số điện thoại phải gồm đúng 10 chữ số và bắt đầu bằng số 0.';
    }
  }
  // 4. Kiểm tra độ dài tối thiểu họ tên
  else if (field.id === 'ho-ten' && field.value.trim().length < 3) {
    thongDiep = 'Họ và tên sinh viên cần có ít nhất 3 ký tự.';
  }
  // 5. Kiểm tra nội dung tin nhắn
  else if (field.id === 'loi-nhan' && field.value.trim().length < 10) {
    thongDiep = 'Nội dung ghi chú đặt sách cần tối thiểu 10 ký tự.';
  }

  // Cập nhật trạng thái lỗi
  if (thongDiep) {
    field.setCustomValidity(thongDiep);
    field.classList.add('truong-loi');
    field.setAttribute('aria-invalid', 'true');
    if (thongBaoLoi) {
      thongBaoLoi.textContent = thongDiep;
      thongBaoLoi.style.display = 'block';
    }
    return false;
  } else {
    field.classList.remove('truong-loi');
    field.removeAttribute('aria-invalid');
    if (thongBaoLoi) {
      thongBaoLoi.textContent = '';
      thongBaoLoi.style.display = 'none';
    }
    return true;
  }
}

/**
 * Khởi tạo trình lắng nghe biểu mẫu
 */
function khoiTaoFormLienHe() {
  if (!FORM_LIEN_HE) return;

  // Điền tự động tên sách nếu có tham số ?sach= trên URL
  const params = new URLSearchParams(window.location.search);
  const sachYeuCau = params.get('sach');
  if (sachYeuCau) {
    const ghiChu = document.querySelector('#loi-nhan');
    if (ghiChu && !ghiChu.value) {
      ghiChu.value = `Xin chào BookNest, tôi muốn đặt mua cuốn sách: "${decodeURIComponent(sachYeuCau)}".`;
    }
  }

  // Lắng nghe sự kiện rời ô (blur) và nhập liệu (input) trên các trường
  const cacTruong = FORM_LIEN_HE.querySelectorAll('input, select, textarea');
  cacTruong.forEach((field) => {
    field.addEventListener('blur', () => kiemTraTruong(field));
    field.addEventListener('input', () => {
      if (field.classList.contains('truong-loi')) {
        kiemTraTruong(field);
      }
    });
  });

  // Lắng nghe sự kiện gửi (submit)
  FORM_LIEN_HE.addEventListener('submit', async (suKien) => {
    suKien.preventDefault(); // Chặn tải lại trang truyền thống

    // Kiểm tra toàn bộ các trường
    let formHopLe = true;
    cacTruong.forEach((field) => {
      const hopLe = kiemTraTruong(field);
      if (!hopLe && formHopLe) {
        field.focus(); // Đưa tiêu điểm tới ô lỗi đầu tiên
        formHopLe = false;
      }
    });

    if (!formHopLe) {
      if (KHUNG_THONG_BAO) {
        KHUNG_THONG_BAO.className = 'thong-bao-loi';
        KHUNG_THONG_BAO.textContent = '⚠️ Vui lòng hoàn thiện đúng các mục có báo lỗi trước khi gửi.';
        KHUNG_THONG_BAO.style.display = 'block';
      }
      return;
    }

    // Thu thập dữ liệu form an toàn
    const formData = new FormData(FORM_LIEN_HE);
    const duLieuGui = {
      hoTen: formData.get('ho-ten') || formData.get('hoTen') || formData.get('ho_ten') || '',
      email: formData.get('email') || formData.get('email_lien_he') || '',
      soDienThoai: formData.get('so-dien-thoai') || formData.get('soDienThoai') || formData.get('so_dien_thoai') || '',
      theLoai: formData.get('muc-dich') || formData.get('muc_dich') || formData.get('theLoai') || '',
      chonGiaoTrinh: formData.get('chon-giao-trinh') || formData.get('chon_giao_trinh') || '',
      soLuong: formData.get('so-luong') || formData.get('so_luong') || '1',
      loiNhan: formData.get('loi-nhan') || formData.get('ghi-chu') || formData.get('ghi_chu') || '',
      ngayGui: new Date().toISOString()
    };

    // Khóa nút gửi trong lúc chờ phản hồi (Loading State)
    if (NUT_SUBMIT) {
      NUT_SUBMIT.disabled = true;
      NUT_SUBMIT.dataset.banDau = NUT_SUBMIT.textContent;
      NUT_SUBMIT.textContent = '⏳ Đang truyền dữ liệu đặt sách...';
    }

    if (KHUNG_THONG_BAO) {
      KHUNG_THONG_BAO.className = 'trang-thai-tai';
      KHUNG_THONG_BAO.textContent = 'Đang kết nối tới máy chủ tiếp nhận...';
      KHUNG_THONG_BAO.style.display = 'block';
    }

    try {
      // Gửi request POST tới JSONPlaceholder
      const phanHoi = await fetch(API_LIEN_HE, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json; charset=UTF-8'
        },
        body: JSON.stringify(duLieuGui)
      });

      if (!phanHoi.ok) {
        throw new Error(`Máy chủ phản hồi mã lỗi HTTP ${phanHoi.status}`);
      }

      const ketQua = await phanHoi.json();
      console.log('[BookNest Form Success] Gửi thành công tới JSONPlaceholder:', ketQua);

      // Hiển thị thông báo thành công
      if (KHUNG_THONG_BAO) {
        KHUNG_THONG_BAO.className = 'thong-bao-thanh-cong';
        KHUNG_THONG_BAO.setAttribute('role', 'alert');
        KHUNG_THONG_BAO.innerHTML = `
          <strong>🎉 Đặt sách thành công!</strong> Mã xác nhận đơn hàng của bạn là <code>#BN-${ketQua.id || 101}</code>.<br>
          Bộ phận hỗ trợ sinh viên BookNest sẽ liên hệ qua số điện thoại <strong>${escapeHTML(duLieuGui.soDienThoai)}</strong> trong vòng 24h.
        `;
        KHUNG_THONG_BAO.style.display = 'block';
      }

      FORM_LIEN_HE.reset();
    } catch (error) {
      console.error('[BookNest Form Error] Lỗi khi gửi biểu mẫu:', error);
      if (KHUNG_THONG_BAO) {
        KHUNG_THONG_BAO.className = 'thong-bao-loi';
        KHUNG_THONG_BAO.setAttribute('role', 'alert');
        KHUNG_THONG_BAO.textContent = `❌ Không thể gửi đơn đặt sách: ${error.message || 'Lỗi mạng'}. Vui lòng thử lại sau ít phút.`;
        KHUNG_THONG_BAO.style.display = 'block';
      }
    } finally {
      // Mở khóa nút gửi
      if (NUT_SUBMIT) {
        NUT_SUBMIT.disabled = false;
        NUT_SUBMIT.textContent = NUT_SUBMIT.dataset.banDau || 'Gửi yêu cầu đặt sách';
      }
    }
  });
}

/**
 * Hàm lọc ký tự đặc biệt HTML đơn giản để chống XSS
 * @param {string} str 
 * @returns {string}
 */
function escapeHTML(str) {
  if (!str) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

khoiTaoFormLienHe();
