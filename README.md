# BÀI TẬP THỰC HÀNH NHÓM SỐ 5 - NHÓM 12
## MÔN: THIẾT KẾ VÀ LẬP TRÌNH WEB (KHOA TOÁN - TIN, TRƯỜNG ĐH SƯ PHẠM - ĐH ĐÀ NẴNG)
### CHƯƠNG 5: LẬP TRÌNH PHP PHÍA MÁY CHỦ (SERVER-SIDE SCRIPTING)

---

### 👥 DANH SÁCH THÀNH VIÊN NHÓM 12 (LỚP 24CNTT3)

1. **Xaiyasith Yoi** – MSV: **3120224189** (Nhóm trưởng)
2. **Phommaket Hatsady** – MSV: **3120224181** (Thành viên)
3. **Vongsena Sauphasith** – MSV: **3120224186** (Thành viên)
4. **Nguyễn Thị Sinh** – MSV: **3120223169** (Thành viên)

- **Tên đề tài đồ án:** **BookNest – Website Bán Sách & Văn Phòng Phẩm Trực Tuyến**
- **Kho GitHub của nhóm:** `https://github.com/xys-3y7/ltweb-doan-nhom12`
- **Thẻ nộp bài (Git Tag):** `btn4` (Chương 4) và nhánh `main` (Chương 5)
- **Mã commit kiểm tra (Last Commit SHA):** `c58a12e9b41a7d890cf231aef71891bca72891d0`

---

### 💻 YÊU CẦU MÔI TRƯỜNG & HƯỚNG DẪN CÀI ĐẶT CHẠY WEBSITE

#### 1. Yêu cầu môi trường
- **PHP:** Phiên bản ≥ 8.1 (Khuyến nghị PHP 8.2 trở lên, hỗ trợ đầy đủ readonly properties, union types, match expression, finfo, session, cURL).
- **Composer:** Quản lý tự động nạp lớp PSR-4 (getcomposer.org).
- **Trình duyệt:** Google Chrome, Mozilla Firefox hoặc Microsoft Edge (hỗ trợ kiểm chuẩn DevTools & Lighthouse).

#### 2. Các bước khởi chạy dự án
Mở cửa sổ dòng lệnh (Terminal / PowerShell) tại thư mục `ltweb-doan-nhom12`:

```bash
# Bước 1: Nạp bộ nạp tự động PSR-4 của Composer
composer install
# (hoặc: composer dump-autoload)

# Bước 2: Khởi động máy chủ phát triển tích hợp sẵn của PHP
php -S localhost:8000
```

Truy cập website trên trình duyệt tại: **`http://localhost:8000/`**

---

### 🔐 TÀI KHOẢN THỬ NGHIỆM TRANG QUẢN TRỊ (QUAN-TRI.PHP)

Hệ thống sử dụng hàm `password_verify()` để đối chiếu mật khẩu đã được băm an toàn bằng thuật toán BCRYPT (`password_hash`) trong tệp `inc/tai-khoan.php`:

| Tên đăng nhập (Username) | Mật khẩu (Password) | Vai trò | Quyền hạn |
| :--- | :--- | :--- | :--- |
| **`admin`** | **`admin123`** | Quản trị viên hệ thống | Quản lý toàn bộ hộp thư liên hệ, tải ảnh đính kèm |
| **`giaovien`** | **`ued2026`** | Giảng viên đánh giá | Xem danh sách liên hệ, kiểm thử chức năng |

---

### 🌐 BẢNG ĐIỀU HƯỚNG: CHỨC NĂNG – URL – TỆP PHP MÁY CHỦ

| TT | Tên chức năng chính | Đường dẫn URL truy cập | Tệp PHP xử lý | Người phụ trách |
| :---: | :--- | :--- | :--- | :--- |
| **1** | Khung trang dùng chung, Menu active, Trang 404, 500, Config & Exception | `http://localhost:8000/`<br>`http://localhost:8000/404.php`<br>`http://localhost:8000/500.php` | `inc/config.php`<br>`inc/header.php`<br>`inc/footer.php`<br>`404.php`, `500.php` | **Xaiyasith Yoi**<br>(Nhóm trưởng) |
| **2** | Danh mục sách, tìm kiếm từ khóa, lọc theo ngành, sắp xếp giá/tên (GET) | `http://localhost:8000/danh-sach.php`<br>`?q=web&dm=cntt&sx=gia-tang` | `danh-sach.php`<br>`src/Data/KhoSanPham.php` | **Phommaket Hatsady** |
| **3** | Chi tiết giáo trình, Cookie `da_xem` (tối đa 4 ID), khối đã xem trang chủ | `http://localhost:8000/chi-tiet.php?id=1`<br>`http://localhost:8000/index.php` | `chi-tiet.php`<br>`index.php` | **Nguyễn Thị Sinh** |
| **4** | Biểu mẫu liên hệ, Server validation, upload ảnh an toàn qua finfo, JSONL | `http://localhost:8000/lien-he.php` | `lien-he.php`<br>`src/Data/KhoLienHe.php` | **Vongsena Sauphasith** |
| **5** | Giỏ hàng mua sách trong Session, tính tổng tiền, bảo vệ giá máy chủ (PRG) | `http://localhost:8000/gio-hang.php` | `gio-hang.php`<br>`src/Services/GioHang.php` | **Phommaket Hatsady** &<br>**Xaiyasith Yoi** |
| **6** | Đăng nhập password_hash, bảo vệ phiên `inc/bao-ve.php`, Bảng quản trị | `http://localhost:8000/dang-nhap.php`<br>`http://localhost:8000/quan-tri.php`<br>`http://localhost:8000/dang-xuat.php` | `dang-nhap.php`<br>`quan-tri.php`<br>`dang-xuat.php`<br>`inc/bao-ve.php` | **Xaiyasith Yoi** &<br>**Nguyễn Thị Sinh** |

---

### 👤 TRANG CÁ NHÂN VÀ 2 CHỨC NĂNG MÁY CHỦ CỦA TỪNG THÀNH VIÊN (PHẦN C)

1. **Xaiyasith Yoi (Nhóm trưởng):**
   - **URL:** `http://localhost:8000/thanhvien/3120224189_yoi/gioithieu.php`
   - **Chức năng máy chủ 1:** Sổ lưu bút sinh viên (lưu vào `storage/3120224189_luubut.jsonl`, hiển thị 5 lời nhắn mới nhất, PRG).
   - **Chức năng máy chủ 2:** Giao diện Sáng/Tối ghi nhớ trạng thái bằng Cookie máy chủ (`$_COOKIE['yoi_theme']`), không chớp giật layout.

2. **Phommaket Hatsady:**
   - **URL:** `http://localhost:8000/thanhvien/3120224181_hatsady/gioithieu.php`
   - **Chức năng máy chủ 1:** Máy tính điểm học phần theo tín chỉ UED (`0.2*A1 + 0.3*A2 + 0.5*A3`), kiểm tra 0 - 10, xếp loại học lực và điểm chữ.
   - **Chức năng máy chủ 2:** Danh mục dự án cá nhân từ mảng PHP, lọc theo thể loại qua tham số GET (`?theloai=web|di-dong|csdl`).

3. **Vongsena Sauphasith:**
   - **URL:** `http://localhost:8000/thanhvien/3120224186_sauphasith/gioithieu.php`
   - **Chức năng máy chủ 1:** Bộ đếm lượt xem hồ sơ cá nhân (Session + tệp `storage/3120224186_luotxem.txt`, mỗi phiên chỉ tăng 1 lần kèm LOCK_EX).
   - **Chức năng máy chủ 2:** Danh mục kỹ năng chuyên môn từ mảng PHP, lọc theo nhóm qua tham số GET (`?nhom=frontend|backend|devops`).

4. **Nguyễn Thị Sinh:**
   - **URL:** `http://localhost:8000/thanhvien/3120223169_sinh/gioithieu.php`
   - **Chức năng máy chủ 1:** Tải lên và thay đổi ảnh đại diện cá nhân an toàn (kiểm tra MIME type qua finfo, dung lượng ≤ 2 MB, lưu `uploads/avatars/`).
   - **Chức năng máy chủ 2:** Sổ góp ý & đánh giá cá nhân (lưu vào `storage/3120223169_danhgia.jsonl`, hiển thị danh sách nhận xét, PRG).

---

### 📂 CẤU TRÚC THƯ MỤC DỰ ÁN

```text
ltweb-doan-nhom12/
├── inc/                                 # Thư mục mã nguồn dùng chung cho toàn bộ website
│   ├── config.php                       # Cấu hình hằng môi trường MOI_TRUONG='dev', E_ALL, log_errors, exception_handler
│   ├── ham.php                          # Hàm tiện ích dùng chung: e($str) chống XSS, vnd($tien) định dạng tiền Việt Nam
│   ├── tai-khoan.php                    # Danh sách tài khoản thử nghiệm với mật khẩu băm BCRYPT (password_hash)
│   ├── bao-ve.php                       # Middleware bảo vệ các trang yêu cầu quyền quản trị viên
│   ├── header.php                       # Khung đầu trang dùng chung: Logo, điều hướng ngữ nghĩa, giỏ hàng, thông tin phiên
│   └── footer.php                       # Khung chân trang dùng chung: Bản quyền, địa chỉ, liên kết môn học
├── src/                                 # Mã nguồn tổ chức theo chuẩn Hướng đối tượng (OOP) & Composer PSR-4
│   ├── Models/
│   │   └── SanPham.php                  # Thực thể Sách với các thuộc tính readonly và phương thức tĩnh tuMang()
│   ├── Data/
│   │   ├── KhoSanPham.php               # Lớp truy xuất tệp JSON, tìm kiếm, lọc theo thể loại và sắp xếp
│   │   └── KhoLienHe.php                # Lớp lưu trữ và truy xuất tệp JSON Lines (.jsonl) kèm cơ chế khóa tệp LOCK_EX
│   └── Services/
│       └── GioHang.php                  # Dịch vụ quản lý giỏ hàng lưu trong Session: thêm, sửa, xóa, tính tổng tiền
├── storage/                             # Thư mục lưu trữ dữ liệu nhật ký dạng văn bản phía máy chủ
│   ├── lien-he.jsonl                    # Tệp lưu trữ danh sách biểu mẫu liên hệ và đặt giáo trình
│   ├── 3120224189_luubut.jsonl          # Sổ lưu bút của sinh viên Xaiyasith Yoi
│   ├── 3120224186_luotxem.txt           # Bộ đếm lượt xem trang cá nhân của Vongsena Sauphasith
│   ├── 3120223169_avatar.txt            # Tên ảnh đại diện tùy chỉnh của Nguyễn Thị Sinh
│   └── 3120223169_danhgia.jsonl         # Sổ góp ý cá nhân của Nguyễn Thị Sinh
├── uploads/                             # Thư mục lưu trữ các tệp ảnh tải lên an toàn từ người dùng
│   └── avatars/                         # Thư mục lưu ảnh đại diện của thành viên
├── logs/                                # Nhật ký lỗi phía máy chủ
│   └── php-error.log                    # Nhật ký ghi toàn bộ lỗi Warning, Notice, Exception và nỗ lực đăng nhập sai
├── data/
│   └── san-pham.json                    # CSDL 12 giáo trình có cấu trúc JSON chuẩn hóa
├── thanhvien/                           # Thư mục trang cá nhân của 4 thành viên
│   ├── 3120224189_yoi/gioithieu.php     # Hồ sơ Xaiyasith Yoi (Sổ lưu bút + Dark/Light Theme Cookie)
│   ├── 3120224181_hatsady/gioithieu.php # Hồ sơ Phommaket Hatsady (Máy tính điểm học phần + Lọc dự án)
│   ├── 3120224186_sauphasith/gioithieu.php # Hồ sơ Vongsena Sauphasith (Bộ đếm lượt xem + Lọc kỹ năng)
│   └── 3120223169_sinh/gioithieu.php    # Hồ sơ Nguyễn Thị Sinh (Upload avatar finfo + Sổ đánh giá)
├── index.php                            # Trang chủ: Sách nổi bật, khối sách đã xem từ Cookie, thời tiết API
├── danh-sach.php                        # Danh mục: Bộ lọc GET, tìm kiếm từ khóa, sắp xếp, giữ nguyên lựa chọn
├── chi-tiet.php                         # Chi tiết: URL ?id=, ghi Cookie da_xem an toàn, form thêm giỏ, sách liên quan
├── gio-hang.php                         # Giỏ hàng: Lưu trong PHP Session, cập nhật số lượng, xóa mục, PRG
├── lien-he.php                          # Biểu mẫu: Server validation, upload ảnh finfo, lưu JSONL, flash message
├── dang-nhap.php                        # Đăng nhập: password_verify, cấp ID phiên mới, ghi log sai pass
├── dang-xuat.php                        # Đăng xuất: Hủy phiên làm việc an toàn, chuyển hướng về đăng nhập
├── quan-tri.php                         # Bảng quản trị: Xem danh sách liên hệ & ảnh đính kèm, bảo vệ bởi inc/bao-ve.php
├── 404.php                              # Trang báo lỗi 404 thân thiện kèm HTTP Status 404 Not Found
├── 500.php                              # Trang báo lỗi 500 thân thiện (khi MOI_TRUONG='prod')
├── composer.json                        # Cấu hình gói Composer và cơ chế tự động nạp PSR-4: "App\\": "src/"
└── kiemtra/                             # Thư mục lưu 18 ảnh minh chứng phân tích web thực tế, kiểm thử và chất lượng
```
