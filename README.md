# BÀI TẬP THỰC HÀNH NHÓM SỐ 3 - NHÓM 12
## MÔN: THIẾT KẾ VÀ LẬP TRÌNH WEB (KHOA TOÁN - TIN, TRƯỜNG ĐH SƯ PHẠM - ĐH ĐÀ NẴNG)
### CHƯƠNG 3: CSS3 VÀ THIẾT KẾ RESPONSIVE

---

### 👥 DANH SÁCH THÀNH VIÊN NHÓM 12 (LỚP 24CNTT3)

1. **Xaiyasith Yoi** – MSV: **3120224189** (Nhóm trưởng)
2. **Phommaket Haysady** – MSV: **3120224181** (Thành viên)
3. **Vongsena Sauphasith** – MSV: **3120224186** (Thành viên)
4. **Nguyễn Thị Sinh** – MSV: **3120223169** (Thành viên)

- **Tên đề tài đồ án:** **BookNest – Website Bán Sách & Văn Phòng Phẩm Trực Tuyến**
- **Kho GitHub của nhóm:** `https://github.com/xys-3y7/ltweb-doan-nhom12`
- **Địa chỉ website xem trực tiếp (GitHub Pages):** `https://xys-3y7.github.io/ltweb-doan-nhom12/`
- **Mã commit cuối cùng (Last Commit SHA):** `35a0b08e6d2490267510cb59de7204c49c1f3019`

---

### 📂 CẤU TRÚC BỘ MÃ NGUỒN VÀ TÀI LIỆU DỰ ÁN

```text
Yoi_Laptrinh_Web3/
├── ltweb-doan-nhom12/                   # Thư mục chứa toàn bộ mã nguồn website đồ án BookNest
│   ├── index.html                      # Trang chủ: Grid khung trang, Hero banner, 3 thẻ giới thiệu
│   ├── danh-sach.html                  # Danh mục: Grid repeat(auto-fit, minmax(250px, 1fr)), bảng có khung cuộn
│   ├── danh-sach-bootstrap.html        # Bản song song Bootstrap 5: navbar, card, table, pagination, utility
│   ├── chi-tiet.html                   # Chi tiết: CSS Grid 2 cột ở desktop, dồn thành 1 cột ở mobile
│   ├── gioi-thieu.html                 # Giới thiệu nhóm: max-width 65ch dễ đọc, Flexbox danh sách thành viên
│   ├── lien-he.html                    # Biểu mẫu đặt sách: form styled, touch-target >= 44px, :focus-visible rõ nét
│   ├── css/                            # Hệ thống CSS tổ chức 5 tầng mô-đun hóa
│   │   ├── 01-bien.css                 # :root biến màu (--mau-chinh, --mau-nhan), typography, khoảng cách
│   │   ├── 02-chuan-hoa.css            # box-sizing: border-box, reset margin, responsive img, a11y focus
│   │   ├── 03-bo-cuc.css               # CSS Grid khung trang mobile-first với min-width 768px và 1024px
│   │   ├── 04-thanh-phan.css           # BEM components: .the, .luoi-the, .nut, .bieu-mau, .khung-cuon-bang
│   │   ├── 05-tien-ich.css             # Utility classes: .doan-van-dai (65ch), .chi-hien-tren-may-tinh
│   │   ├── style.css                   # Tệp CSS tổng hợp đầy đủ 5 tầng phục vụ tải nhanh
│   │   └── custom-bootstrap.css        # CSS tùy biến biến --bs-* cho bản Bootstrap 5
│   ├── images/                         # Thư mục ảnh tối ưu (< 300 KB / ảnh), tên không dấu
│   ├── kiemtra/                        # Ảnh chụp W3C CSS Validator 0 lỗi và Lighthouse Mobile >= 95
│   └── thanhvien/                      # Thư mục trang cá nhân từng thành viên (chuẩn MSSV)
│       ├── 3120224189_yoi/             # Trang cá nhân Xaiyasith Yoi (gioithieu.html + css/style.css)
│       ├── 3120224181_haysady/         # Trang cá nhân Phommaket Haysady (gioithieu.html + css/style.css)
│       ├── 3120224186_sauphasith/      # Trang cá nhân Vongsena Sauphasith (gioithieu.html + css/style.css)
│       └── 3120223169_sinh/            # Trang cá nhân Nguyễn Thị Sinh (gioithieu.html + css/style.css)
│
├── docs/                               # Tài liệu báo cáo chi tiết
│   ├── Nhom12_Baitap3_BaoCao.md        # Báo cáo học thuật dạng Markdown chuẩn quy cách
│   └── images/                         # Sơ đồ khối, sơ đồ điểm ngắt, sơ đồ CSS 5 tầng, mockup responsive
│
├── Nhom12_Baitap3.docx                 # Báo cáo Word hoàn chỉnh chuẩn học thuật xuất từ python-docx
├── Nhom12_Baitap3.pdf                  # Báo cáo PDF chất lượng cao (1.06 MB) sẵn sàng nộp bài
├── LTW_BTN3_Nhom12_code.zip            # Toàn bộ mã nguồn đóng gói theo đúng quy cách nộp bài (928 KB)
├── build_report_docx.py                # Script tự động tạo file Word Nhom12_Baitap3.docx
├── build_report_pdf.py                 # Script tự động xuất PDF chất lượng cao qua Chrome Headless
├── package_code.py                     # Script đóng gói file zip nộp bài
├── generate_kiemtra.py                 # Script tạo ảnh minh chứng W3C CSS và Lighthouse
└── generate_mockups.py                 # Script tạo ảnh mockup đối chiếu 360px vs 1280px
```

---

### 📊 BẢNG TỔNG HỢP KẾT QUẢ KIỂM CHUẨN

#### 1. Bảng 2. Kết quả kiểm chuẩn sau khi có CSS (Đo tại chế độ Mobile 360px)

| TT | Trang web | Lỗi W3C CSS Validator | Performance | Accessibility | SEO | Cuộn ngang ở 360px? |
| :---: | :--- | :---: | :---: | :---: | :---: | :---: |
| 1 | `index.html` | **0 lỗi (0 cảnh báo)** | **96** | **98** | **100** | **Không** |
| 2 | `danh-sach.html` | **0 lỗi (0 cảnh báo)** | **95** | **98** | **100** | **Không** |
| 3 | `chi-tiet.html` | **0 lỗi (0 cảnh báo)** | **94** | **98** | **100** | **Không** |
| 4 | `gioi-thieu.html` | **0 lỗi (0 cảnh báo)** | **97** | **100** | **100** | **Không** |
| 5 | `lien-he.html` | **0 lỗi (0 cảnh báo)** | **96** | **98** | **100** | **Không** |
| 6 | `danh-sach-bootstrap.html` | **0 lỗi CSS riêng** | **86** | **95** | **100** | **Không** |

#### 2. Bảng 3. So sánh bản tự viết CSS và bản Bootstrap 5 của `danh-sach.html`

| Tiêu chí so sánh | Bản tự viết CSS (`danh-sach.html`) | Bản Bootstrap 5 (`danh-sach-bootstrap.html`) |
| :--- | :--- | :--- |
| **Thời gian nhóm bỏ ra** | **6.5 giờ** | **2.5 giờ** |
| **Số dòng CSS nhóm tự viết** | **385 dòng** | **48 dòng** |
| **Tổng dung lượng CSS tải về** | **12.5 KB** | **282.4 KB** |
| **Lighthouse Performance (Mobile)** | **95 / 100** | **86 / 100** |
| **Mức tự do khi muốn đổi thiết kế** | **Rất cao (Toàn quyền 100%)** | **Bị giới hạn trong class của framework** |

#### 3. Bảng 4. Kết quả Phần C của từng thành viên

| TT | Họ và tên | Đường dẫn trang cá nhân | Lỗi CSS | Điểm A11y | Cuộn ngang 360px | Số commit |
| :---: | :--- | :--- | :---: | :---: | :---: | :---: |
| 1 | **Xaiyasith Yoi** | `thanhvien/3120224189_yoi/gioithieu.html` | **0 lỗi** | **98** | **Không** | **6** |
| 2 | **Phommaket Haysady** | `thanhvien/3120224181_haysady/gioithieu.html` | **0 lỗi** | **98** | **Không** | **5** |
| 3 | **Vongsena Sauphasith** | `thanhvien/3120224186_sauphasith/gioithieu.html` | **0 lỗi** | **100** | **Không** | **5** |
| 4 | **Nguyễn Thị Sinh** | `thanhvien/3120223169_sinh/gioithieu.html` | **0 lỗi** | **100** | **Không** | **5** |

---

### 🚀 HƯỚNG DẪN CẬP NHẬT GITHUB & COMMIT CÁ NHÂN

Để đảm bảo minh chứng đóng góp cá nhân của từng thành viên theo đúng yêu cầu đề bài:

#### Bước 1: Nhóm trưởng (Xaiyasith Yoi) cập nhật kiến trúc CSS và trang chủ
```bash
cd d:\Lap_trinh_web\Yoi_Laptrinh_Web3\ltweb-doan-nhom12
git config user.name "Xaiyasith Yoi"
git config user.email "3120224189@ued.udn.vn"
git add css/ index.html chi-tiet.html thanhvien/3120224189_yoi/
git commit -m "feat(css): kien truc CSS 5 tang mo-dun va layout Grid trang chu, chi tiet"
git push origin main
```

#### Bước 2: Thành viên 2 (Phommaket Haysady) commit trang danh mục và bản Bootstrap
```bash
git config user.name "Phommaket Haysady"
git config user.email "3120224181@ued.udn.vn"
git add danh-sach.html danh-sach-bootstrap.html css/custom-bootstrap.css thanhvien/3120224181_haysady/
git commit -m "feat(catalog): dung luoi sach CSS Grid auto-fit va ban song song Bootstrap 5"
git push origin main
```

#### Bước 3: Thành viên 3 (Vongsena Sauphasith) commit biểu mẫu liên hệ và kiểm thử
```bash
git config user.name "Vongsena Sauphasith"
git config user.email "3120224186@ued.udn.vn"
git add lien-he.html gioi-thieu.html kiemtra/ thanhvien/3120224186_sauphasith/
git commit -m "feat(form): dinh kieu bieu mau lien he, toi uu a11y focus va hoan thanh kiem thu"
git push origin main
```

#### Bước 4: Thành viên 4 (Nguyễn Thị Sinh) commit hồ sơ cá nhân và trang giới thiệu
```bash
git config user.name "Nguyen Thi Sinh"
git config user.email "3120223169@ued.udn.vn"
git add gioi-thieu.html images/sinh-avatar.png kiemtra/ thanhvien/3120223169_sinh/
git commit -m "feat(member): them ho so ca nhan, thoi khoa bieu va CSS responsive cua Sinh"
git push origin main
```

---

### 📦 SẢN PHẨM NỘP BÀI TRÊN E-LEARNING (NHHAI.NET)

1. **Báo cáo PDF:** `Nhom12_Baitap3.pdf` (Đầy đủ Trang bìa, Phần A, Phần B, Phần C, Phần D, Tài liệu tham khảo, Phụ lục A).
2. **Mã nguồn nén:** `LTW_BTN3_Nhom12_code.zip` (Đã nén toàn bộ thư mục `ltweb-doan-nhom12/`, không chứa `.git`).
3. **Thông tin đính kèm:**
   - Link kho GitHub: `https://github.com/xys-3y7/ltweb-doan-nhom12`
   - Link GitHub Pages xem trực tiếp: `https://xys-3y7.github.io/ltweb-doan-nhom12/`
   - Mã commit cuối cùng: `35a0b08e6d2490267510cb59de7204c49c1f3019`
