/**
 * Tệp: thanhvien/3120224189_yoi/js/canhan.js
 * Tác giả: Xaiyasith Yoi (MSV: 3120224189) - Nhóm trưởng Nhóm 12
 * Mô tả: Script tương tác độc lập cho trang cá nhân của Yoi:
 *  1. Tương tác 1: Chuyển đổi giao diện Sáng / Tối (Dark / Light Theme) và ghi nhớ vào localStorage.
 *  2. Tương tác 2: Nút sao chép địa chỉ Email sinh viên vào bộ nhớ tạm (Clipboard API) kèm Toast thông báo.
 * Cách thử nghiệm:
 *  - Nhấp nút "🌙 Chế độ tối / ☀️ Chế độ sáng" ở đầu trang để xem đổi màu nền/chữ, tải lại trang vẫn giữ giao diện.
 *  - Nhấp nút "📋 Sao chép Email" cạnh thông tin email để copy vào clipboard và xem thông báo nổi.
 */

// 1. TƯƠNG TÁC 1: CHUYỂN ĐỔI CHẾ ĐỘ SÁNG / TỐI (DARK MODE)
function khoiTaoCheDoToi() {
  const KHOA_THEME = 'yoi_theme_preference';
  const body = document.body;

  // Tạo nút chuyển đổi giao diện và chèn vào đầu phần thông tin cá nhân
  const khuVucNut = document.createElement('div');
  khuVucNut.className = 'khu-vuc-tuy-chon-ca-nhan';
  khuVucNut.style.margin = '16px 0';

  const nutToggle = document.createElement('button');
  nutToggle.type = 'button';
  nutToggle.id = 'nut-doi-theme';
  nutToggle.className = 'nut nut--vien';
  nutToggle.setAttribute('aria-label', 'Chuyển đổi giao diện sáng tối');

  // Đọc thiết lập đã lưu
  const themeDaLuu = localStorage.getItem(KHOA_THEME);
  if (themeDaLuu === 'dark') {
    body.classList.add('che-do-toi');
    nutToggle.textContent = '☀️ Giao diện sáng';
  } else {
    nutToggle.textContent = '🌙 Giao diện tối';
  }

  nutToggle.addEventListener('click', () => {
    const laToi = body.classList.toggle('che-do-toi');
    if (laToi) {
      localStorage.setItem(KHOA_THEME, 'dark');
      nutToggle.textContent = '☀️ Giao diện sáng';
    } else {
      localStorage.setItem(KHOA_THEME, 'light');
      nutToggle.textContent = '🌙 Giao diện tối';
    }
  });

  khuVucNut.appendChild(nutToggle);

  // Chèn vào trước thẻ main hoặc sau h1
  const h1 = document.querySelector('h1');
  if (h1 && h1.parentNode) {
    h1.parentNode.insertBefore(khuVucNut, h1.nextSibling);
  }
}

// 2. TƯƠNG TÁC 2: SAO CHÉP EMAIL VÀO CLIPBOARD KÈM TOAST
function khoiTaoSaoChepEmail() {
  const EMAIL_SINH_VIEN = '3120224189@ued.udn.vn';
  const emailLink = document.querySelector('a[href^="mailto:3120224189"]');
  if (!emailLink) return;

  // Tạo nút sao chép nhỏ cạnh email
  const nutSaoChep = document.createElement('button');
  nutSaoChep.type = 'button';
  nutSaoChep.className = 'nut nut--phu nut--nho';
  nutSaoChep.style.marginLeft = '8px';
  nutSaoChep.textContent = '📋 Sao chép';
  nutSaoChep.setAttribute('aria-label', 'Sao chép địa chỉ email vào bộ nhớ tạm');

  // Khung Toast thông báo
  const toast = document.createElement('div');
  toast.id = 'toast-thong-bao';
  toast.setAttribute('role', 'status');
  toast.setAttribute('aria-live', 'polite');
  toast.style.cssText = `
    display: none;
    position: fixed;
    bottom: 24px;
    right: 24px;
    background-color: #123b6d;
    color: #ffffff;
    padding: 12px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 9999;
    font-size: 14px;
    transition: opacity 0.3s ease;
  `;
  document.body.appendChild(toast);

  nutSaoChep.addEventListener('click', async () => {
    try {
      if (navigator.clipboard && navigator.clipboard.writeText) {
        await navigator.clipboard.writeText(EMAIL_SINH_VIEN);
      } else {
        // Fallback cho trình duyệt cũ
        const oNhap = document.createElement('textarea');
        oNhap.value = EMAIL_SINH_VIEN;
        document.body.appendChild(oNhap);
        oNhap.select();
        document.execCommand('copy');
        document.body.removeChild(oNhap);
      }

      // Hiện thông báo toast
      toast.textContent = `✅ Đã sao chép email: ${EMAIL_SINH_VIEN} vào bộ nhớ tạm!`;
      toast.style.display = 'block';
      toast.style.opacity = '1';

      setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => { toast.style.display = 'none'; }, 300);
      }, 2500);
    } catch (err) {
      console.error('Không thể sao chép email:', err);
      toast.textContent = '❌ Không thể truy cập bộ nhớ tạm!';
      toast.style.display = 'block';
      setTimeout(() => { toast.style.display = 'none'; }, 2000);
    }
  });

  emailLink.parentNode.appendChild(nutSaoChep);
}

// Thêm CSS Dark Mode động cho trang cá nhân Yoi
const styleEl = document.createElement('style');
styleEl.textContent = `
  body.che-do-toi {
    background-color: #0f172a !important;
    color: #e2e8f0 !important;
  }
  body.che-do-toi header, body.che-do-toi nav, body.che-do-toi footer {
    background-color: #1e293b !important;
    color: #f8fafc !important;
  }
  body.che-do-toi a {
    color: #60a5fa !important;
  }
  body.che-do-toi .ho-so-ca-nhan, body.che-do-toi article, body.che-do-toi section {
    background-color: #1e293b !important;
    border-color: #334155 !important;
    color: #e2e8f0 !important;
  }
  body.che-do-toi th {
    background-color: #334155 !important;
    color: #f8fafc !important;
  }
  body.che-do-toi td {
    background-color: #1e293b !important;
    border-color: #334155 !important;
    color: #cbd5e1 !important;
  }
  body.che-do-toi .danh-sach-ky-nang li {
    background-color: #334155 !important;
    color: #f1f5f9 !important;
  }
  .nut--nho {
    padding: 2px 8px;
    font-size: 12px;
    border-radius: 4px;
    cursor: pointer;
  }
`;
document.head.appendChild(styleEl);

document.addEventListener('DOMContentLoaded', () => {
  khoiTaoCheDoToi();
  khoiTaoSaoChepEmail();
});
