# BÀI TẬP THỰC HÀNH NHÓM SỐ 4 - NHÓM 12
## MÔN: THIẾT KẾ VÀ LẬP TRÌNH WEB (KHOA TOÁN - TIN, TRƯỜNG ĐH SƯ PHẠM - ĐH ĐÀ NẴNG)
### CHƯƠNG 4: JAVASCRIPT VÀ LẬP TRÌNH PHÍA MÁY KHÁCH

---

### 👥 DANH SÁCH THÀNH VIÊN NHÓM 12 (LỚP 24CNTT3)

1. **Xaiyasith Yoi** – MSV: **3120224189** (Nhóm trưởng)
2. **Phommaket Haysady** – MSV: **3120224181** (Thành viên)
3. **Vongsena Sauphasith** – MSV: **3120224186** (Thành viên)
4. **Nguyễn Thị Sinh** – MSV: **3120223169** (Thành viên)

- **Tên đề tài đồ án:** **BookNest – Website Bán Sách & Văn Phòng Phẩm Trực Tuyến**
- **Kho GitHub của nhóm:** `https://github.com/xys-3y7/ltweb-doan-nhom12`
- **Địa chỉ website xem trực tiếp (GitHub Pages):** `https://xys-3y7.github.io/ltweb-doan-nhom12/`
- **Mã commit kiểm tra (Last Commit SHA):** `e6ac6249827f2b1693fd2d76b04b4c6623f92b20`

---

### 📂 CẤU TRÚC BỘ MÃ NGUỒN VÀ TÀI LIỆU DỰ ÁN

```text
Yoi_Laptrinh_Web4/
├── ltweb-doan-nhom12/                   # Thư mục mã nguồn website đồ án BookNest
│   ├── index.html                      # Trang chủ: Widget thời tiết Đà Nẵng từ API Open-Meteo
│   ├── danh-sach.html                  # Danh mục: Tải JSON, tìm kiếm không dấu, lọc & sắp xếp
│   ├── danh-sach-bootstrap.html        # Bản danh sách song song giao diện Bootstrap 5
│   ├── chi-tiet.html                   # Chi tiết: URLSearchParams ?id=5, đổi document.title động
│   ├── gioi-thieu.html                 # Giới thiệu nhóm: Hiển thị thông tin thành viên và đề án
│   ├── lien-he.html                    # Biểu mẫu: Client validation, fetch POST jsonplaceholder
│   ├── data/
│   │   └── san-pham.json               # 12 sản phẩm thực tế chuẩn bị chuyển thành MySQL CSDL
│   ├── js/                             # Hệ thống JavaScript tổ chức theo ES6 Modules
│   │   ├── api.js                      # Hàm taiJSON() dùng chung xử lý res.ok và try/catch
│   │   ├── main.js                     # Menu mobile responsive, phím Esc, cập nhật huy hiệu
│   │   ├── yeu-thich.js                # Quản lý sách yêu thích (localStorage), ủy quyền sự kiện
│   │   ├── trang-chu.js                # Tải và render dữ liệu thời tiết từ Open-Meteo
│   │   ├── trang-danh-sach.js          # Xử lý 3 trạng thái (tải, lỗi, rỗng), tìm kiếm không dấu
│   │   ├── trang-chi-tiet.js           # Render chi tiết sách theo URL ?id= an toàn chống XSS
│   │   └── trang-lien-he.js            # Kiểm tra form novalidate, gửi POST, aria-live="polite"
│   ├── css/                            # Hệ thống CSS tổ chức 5 tầng mô-đun hóa từ BT3
│   ├── images/                         # Thư mục ảnh tối ưu (< 300 KB / ảnh)
│   ├── kiemtra/                        # 20 ảnh minh chứng W3C, Lighthouse, DevTools, trạng thái JS
│   └── thanhvien/                      # Thư mục trang cá nhân từng thành viên (chuẩn MSSV)
│       ├── 3120224189_yoi/             # Xaiyasith Yoi (Dark mode localStorage + Copy email toast)
│       ├── 3120224181_haysady/         # Phommaket Haysady (Bộ lọc kỹ năng + Accordion đề án)
│       ├── 3120224186_sauphasith/      # Vongsena Sauphasith (Countdown timer + Đánh giá 5 sao)
│       └── 3120223169_sinh/            # Nguyễn Thị Sinh (Tìm kiếm TKB + Bộ tính GPA)
│
├── docs/                               # Thư mục tài liệu báo cáo và hình ảnh minh họa
│   └── images/                         # 20 hình vẽ sơ đồ, đối chiếu DevTools, mockup trạng thái
│
├── Nhom12_Baitap4.docx                 # Báo cáo Word hoàn chỉnh chuẩn học thuật xuất từ python-docx
├── Nhom12_Baitap4.pdf                  # Báo cáo PDF chất lượng cao (1.55 MB) sẵn sàng nộp bài
├── LTW_BTN4_Nhom12_code.zip            # Toàn bộ mã nguồn đóng gói chuẩn quy cách (1.88 MB)
├── report.html                         # Bản HTML của báo cáo phục vụ xuất PDF qua Chrome Headless
├── build_report_docx.py                # Script tự động tạo báo cáo Word Nhom12_Baitap4.docx
├── build_report_pdf.py                 # Script tự động xuất PDF chất lượng cao
├── package_code.py                     # Script đóng gói file zip nộp bài
└── generate_all_diagrams_bt4.py        # Script tạo toàn bộ 20 ảnh minh chứng kiểm chuẩn
```

---

### 📊 BẢNG TỔNG HỢP KẾT QUẢ VÀ ĐÁNH GIÁ

#### Bảng 1. Sáu chức năng bắt buộc đã triển khai

| TT | Trang áp dụng | Chức năng | Kỹ thuật bắt buộc | Tệp JS & Người phụ trách |
| :---: | :--- | :--- | :--- | :--- |
| 1 | Mọi trang | Menu thu gọn trên di động | Button toggle classList('mo'); aria-expanded; nhấn Esc đóng menu; tắt JS menu vẫn hiện | `js/main.js` — **Xaiyasith Yoi** |
| 2 | `danh-sach.html` | Lưới sách JSON + Tìm kiếm không dấu + Lọc + Sắp xếp | taiJSON() fetch async/await; filter, sort; loại bỏ dấu tiếng Việt regex; đủ 3 trạng thái: tải – lỗi – rỗng | `js/trang-danh-sach.js` — **Phommaket Haysady** |
| 3 | `chi-tiet.html` | Chi tiết sách theo tham số URL (?id=5) | URLSearchParams đọc id; hàm find() theo id; đổi document.title; id sai báo 'Không tìm thấy' | `js/trang-chi-tiet.js` — **Nguyễn Thị Sinh** |
| 4 | `lien-he.html` | Kiểm tra form client & fetch POST giả lập | novalidate; checkValidity() / setCustomValidity(); báo lỗi từng ô; fetch POST jsonplaceholder; khóa nút; aria-live='polite' | `js/trang-lien-he.js` — **Vongsena Sauphasith** |
| 5 | `index.html` | Dữ liệu thời tiết Đà Nẵng từ REST API công khai | Fetch API Open-Meteo Đà Nẵng (16.05°N, 108.20°E); try/catch xử lý lỗi; hiển thị nhiệt độ | `js/trang-chu.js` — **Xaiyasith Yoi** |
| 6 | `danh-sach.html`<br>`chi-tiet.html` | Yêu thích localStorage & Huy hiệu đếm header | Lưu mảng ID vào localStorage; ủy quyền sự kiện (Event Delegation); cập nhật badge header realtime | `js/yeu-thich.js` — **Nguyễn Thị Sinh** |

#### Bảng 3. Kết quả kiểm tra sau khi thêm JavaScript (Đo tại chế độ Mobile 360px)

| TT | Trang web | Lỗi JS (Console) | Lỗi HTML (W3C) | A11y | BP | Dùng được bằng bàn phím? |
| :---: | :--- | :---: | :---: | :---: | :---: | :---: |
| 1 | `index.html` | **0 lỗi** | **0 lỗi** | **98** | **100** | **Có (Tab, Enter, Esc)** |
| 2 | `danh-sach.html` | **0 lỗi** | **0 lỗi** | **98** | **100** | **Có (Tab, Enter, Esc)** |
| 3 | `chi-tiet.html` | **0 lỗi** | **0 lỗi** | **98** | **100** | **Có (Tab, Enter, Esc)** |
| 4 | `gioi-thieu.html` | **0 lỗi** | **0 lỗi** | **100** | **100** | **Có (Tab, Enter, Esc)** |
| 5 | `lien-he.html` | **0 lỗi** | **0 lỗi** | **98** | **100** | **Có (Tab, Enter, Esc)** |
| 6 | `danh-sach-bootstrap.html` | **0 lỗi** | **0 lỗi** | **95** | **100** | **Có (Tab, Enter, Esc)** |

#### Bảng 4. Kết quả Phần C của từng thành viên

| TT | Họ và tên | Hai tương tác độc lập (Phần C) | Chức năng Phần B | Lỗi JS | A11y | Số commit |
| :---: | :--- | :--- | :--- | :---: | :---: | :---: |
| 1 | **Xaiyasith Yoi** | 1. Đổi giao diện Sáng / Tối (lưu localStorage)<br>2. Sao chép Email vào Clipboard kèm Toast | Menu mobile (CN1)<br>API Open-Meteo (CN5) | **0 lỗi** | **98** | **6** |
| 2 | **Phommaket Haysady** | 1. Bộ lọc phân loại kỹ năng chuyên môn<br>2. Accordion thu gọn / mở rộng đề án | Lưới sách JSON,<br>Tìm kiếm & Lọc (CN2) | **0 lỗi** | **98** | **5** |
| 3 | **Vongsena Sauphasith** | 1. Đồng hồ đếm ngược thời gian thực<br>2. Khung đánh giá 5 sao (lưu localStorage) | Kiểm tra form client,<br>Fetch POST API (CN4) | **0 lỗi** | **100** | **5** |
| 4 | **Nguyễn Thị Sinh** | 1. Tìm kiếm & highlight môn trên TKB<br>2. Bộ công cụ tính điểm GPA | Chi tiết theo URL (CN3)<br>Yêu thích storage (CN6) | **0 lỗi** | **100** | **5** |
