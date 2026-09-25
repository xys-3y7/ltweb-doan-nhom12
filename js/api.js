/**
 * Tệp: js/api.js
 * Mô tả: Module dùng chung cung cấp hàm tải dữ liệu bất đồng bộ qua Fetch API.
 * Đảm bảo kiểm tra res.ok và xử lý lỗi mạng thống nhất cho toàn bộ website BookNest.
 * Nhóm 12 - Lớp 24CNTT3 - Trường ĐH Sư phạm - ĐH Đà Nẵng
 */

export async function taiJSON(url) {
  try {
    const res = await fetch(url);
    if (!res.ok) {
      throw new Error(`Lỗi máy chủ HTTP ${res.status}: Không thể tải tài nguyên từ ${url}`);
    }
    return await res.json();
  } catch (error) {
    console.error(`[BookNest API Error] Lỗi khi tải ${url}:`, error);
    throw error;
  }
}
