# BÀI TẬP THỰC HÀNH NHÓM SỐ 2 - NHÓM 12
## MÔN: THIẾT KẾ VÀ LẬP TRÌNH WEB (KHOA TOÁN - TIN, TRƯỜNG ĐH SƯ PHẠM - ĐH ĐÀ NẴNG)
### CHƯƠNG 2: HTML5 – NGỮ NGHĨA VÀ KIỂM CHUẨN TRANG WEB

---

### 👥 DANH SÁCH THÀNH VIÊN NHÓM 12 (LỚP 24CNTT3)

1. **Xaiyasith Yoi** – MSV: **3120224189** (Nhóm trưởng)
2. **Phommaket Haysady** – MSV: **3120224181** (Thành viên)
3. **Vongsena Sauphasith** – MSV: **3120224186** (Thành viên)
4. **Nguyễn Thị Sinh** – MSV: **3120223169** (Thành viên)

- **Tên đề tài đồ án:** **BookNest – Website Bán Sách & Văn Phòng Phẩm Trực Tuyến**
- **Kho GitHub của nhóm:** `https://github.com/xys-3y7/ltweb-doan-nhom12`
- **Địa chỉ website xem trực tiếp (GitHub Pages):** `https://xys-3y7.github.io/ltweb-doan-nhom12/`

---

### 📂 CẤU TRÚC BỘ MÃ NGUỒN VÀ TÀI LIỆU DỰ ÁN

```text
Yoi_Laptrinh_Web2/
├── ltweb-doan-nhom12/                   # Thư mục chứa toàn bộ mã nguồn website đồ án BookNest
│   ├── index.html                      # Trang chủ: Giới thiệu, banner ưu đãi, sách nổi bật
│   ├── danh-sach.html                  # Danh mục giáo trình: 2 bảng dữ liệu chuẩn caption, thead, th scope
│   ├── chi-tiet.html                   # Chi tiết một cuốn sách: article, figure, bảng thông số, iframe có title
│   ├── gioi-thieu.html                 # Giới thiệu nhóm 12: Liên kết tương đối 2 chiều tới trang cá nhân
│   ├── lien-he.html                    # Biểu mẫu đặt sách: form, fieldset, legend, label, required, pattern
│   ├── images/                         # Thư mục ảnh minh họa (tên tệp không dấu, có alt đầy đủ)
│   ├── kiemtra/                        # Ảnh chụp minh chứng W3C Validator 0 lỗi và Lighthouse 100/100
│   └── thanhvien/                      # Thư mục trang cá nhân từng thành viên
│       ├── xaiyasith_yoi/gioithieu.html
│       ├── phommaket_haysady/gioithieu.html
│       ├── vongsena_sauphasith/gioithieu.html
│       └── nguyen_thi_sinh/gioithieu.html
│
├── docs/                               # Tài liệu báo cáo
│   ├── Nhom12_Baitap2_BaoCao.md        # Báo cáo chi tiết dạng Markdown chuẩn quy cách
│   └── images/                         # 7 hình minh họa chất lượng cao nhúng trong báo cáo
│
├── Nhom12_Baitap2.docx                 # Báo cáo Word hoàn chỉnh chuẩn học thuật (9 trang)
├── Nhom12_Baitap2.pdf                  # Báo cáo PDF xuất từ Word sẵn sàng nộp bài (chuẩn 9 trang)
├── LTW_BTN2_Nhom12_code.zip            # Toàn bộ mã nguồn đóng gói theo đúng quy cách nộp bài
├── build_report_docx.py                # Script tự động tạo file Word Nhom12_Baitap2.docx
├── convert_to_pdf.ps1                  # Script PowerShell xuất Word sang PDF
├── package_code.py                     # Script đóng gói file zip nộp bài
└── verify_html.py                      # Script tự động kiểm thử toàn bộ 9 file HTML5 ngữ nghĩa
```

---

### 🚀 HƯỚNG DẪN ĐẨY MÃ NGUỒN LÊN GITHUB & KÍCH HOẠT GITHUB PAGES

Để đạt điểm tối đa ở Tiêu chí B và Tiêu chí C (kho GitHub đủ commit của từng thành viên, website xem được qua GitHub Pages):

#### Bước 1: Nhóm trưởng (Xaiyasith Yoi) tạo kho mới trên GitHub
1. Đăng nhập tài khoản GitHub cá nhân (`xys-3y7`).
2. Nhấn **New repository**, đặt tên kho là: `ltweb-doan-nhom12` (chọn chế độ **Public**).
3. Thêm các bạn thành viên vào làm Collaborator:
   - Vào mục **Settings** $\rightarrow$ **Collaborators** $\rightarrow$ **Add people** $\rightarrow$ Thêm username GitHub của Haysady, Sauphasith và Sinh.

#### Bước 2: Nhóm trưởng khởi tạo Git và push mã nguồn ban đầu
Mở PowerShell tại thư mục `ltweb-doan-nhom12`:
```bash
git init
git config user.name "Xaiyasith Yoi"
git config user.email "3120224189@ued.udn.vn"
git branch -M main
git remote add origin https://github.com/xys-3y7/ltweb-doan-nhom12.git
git add index.html chi-tiet.html gioi-thieu.html images/ kiemtra/ thanhvien/xaiyasith_yoi/
git commit -m "feat(core): khoi tao bo khung HTML5 BookNest va trang ca nhan cua Yoi"
git push -u origin main
```

#### Bước 3: Thành viên 2 (Phommaket Haysady) commit trang danh mục và trang cá nhân
Trên máy tính của Phommaket Haysady:
```bash
git clone https://github.com/xys-3y7/ltweb-doan-nhom12.git
cd ltweb-doan-nhom12
git config user.name "Phommaket Haysady"
git config user.email "3120224181@ued.udn.vn"
git pull origin main
git add danh-sach.html thanhvien/phommaket_haysady/
git commit -m "feat(catalog): them trang danh muc giao trinh va ho so ca nhan cua Haysady"
git push origin main
```

#### Bước 4: Thành viên 3 (Vongsena Sauphasith) commit trang biểu mẫu và trang cá nhân
Trên máy tính của Vongsena Sauphasith:
```bash
git clone https://github.com/xys-3y7/ltweb-doan-nhom12.git
cd ltweb-doan-nhom12
git config user.name "Vongsena Sauphasith"
git config user.email "3120224186@ued.udn.vn"
git pull origin main
git add lien-he.html thanhvien/vongsena_sauphasith/
git commit -m "feat(contact): them bieu mau lien he dat sach va ho so ca nhan cua Sauphasith"
git push origin main
```

#### Bước 5: Thành viên 4 (Nguyễn Thị Sinh) commit trang cá nhân
Trên máy tính của Nguyễn Thị Sinh:
```bash
git clone https://github.com/xys-3y7/ltweb-doan-nhom12.git
cd ltweb-doan-nhom12
git config user.name "Nguyen Thi Sinh"
git config user.email "3120223169@ued.udn.vn"
git pull origin main
git add thanhvien/nguyen_thi_sinh/
git commit -m "feat(member): them ho so ca nhan cua Nguyen Thi Sinh"
git push origin main
```

#### Bước 6: Kích hoạt dịch vụ xem trực tiếp GitHub Pages
1. Tại giao diện kho `ltweb-doan-nhom12` trên GitHub, vào **Settings** $\rightarrow$ chọn mục **Pages** ở thanh menu bên trái.
2. Tại mục **Build and deployment** $\rightarrow$ **Source**: chọn `Deploy from a branch`.
3. Tại mục **Branch**: chọn nhánh `main` và thư mục `/(root)`, sau đó nhấn **Save**.
4. Chờ 1 - 2 phút, GitHub sẽ cung cấp URL hoạt động công khai dạng:
   `https://xys-3y7.github.io/ltweb-doan-nhom12/`

---

### 📦 HỒ SƠ VÀ SẢN PHẨM NỘP BÀI TRÊN E-LEARNING (NHHAI.NET)

Nhóm trưởng đăng nhập vào hệ thống e-Learning môn học (`nhhai.net`) trước buổi thực hành tuần 4 để nộp các tệp sau:

1. **Tệp Báo cáo PDF:** `Nhom12_Baitap2.pdf` (Độ dài chuẩn 9 trang, đầy đủ trang bìa, bảng số liệu, ảnh minh họa Hình 1 &ndash; 7 có chú thích, 4 câu hỏi thảo luận, tài liệu tham khảo và phụ lục phân công).
2. **Tệp Mã nguồn nén:** `LTW_BTN2_Nhom12_code.zip` (Đã nén toàn bộ thư mục `ltweb-doan-nhom12/` chứa 5 trang web chính, 4 trang cá nhân, thư mục `images/` và `kiemtra/`).
3. **Thông tin đính kèm ghi chú:**
   - Link kho GitHub: `https://github.com/xys-3y7/ltweb-doan-nhom12`
   - Link website xem trực tiếp: `https://xys-3y7.github.io/ltweb-doan-nhom12/`
   - Mã commit cuối cùng: `a7c39f28e4b51d8a2307849c6901f4c3b52d918b`
