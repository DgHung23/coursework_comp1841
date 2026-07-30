# COMP1841 Coursework Review

Đánh giá này được viết từ việc kiểm tra:
- mã nguồn PHP/MySQL trong project
- schema và dữ liệu trong database
- phản hồi HTTP của các route chính
- ảnh chụp màn hình desktop và mobile

## Kết luận nhanh

Web của bạn **đủ nền tảng để nộp coursework**, và đã chạm được phần lớn yêu cầu chính của rubric.

Ước lượng:
- **Phần web/system:** khoảng **52-57/70**
- **Tổng điểm cả bài:** khoảng **72-80/100** nếu report làm tốt

## Điểm mạnh

- Dùng **PHP PDO** đúng yêu cầu coursework.
- Database quan hệ rõ ràng, có foreign key:
  - `accounts`
  - `post`
  - `category`
  - `post_category`
- Có **CRUD post**:
  - xem danh sách câu hỏi
  - xem chi tiết
  - thêm
  - sửa
  - xoá
- Có **đăng ký / đăng nhập** với `password_hash()`.
- Có **admin area** để:
  - xem users
  - xoá user
  - đổi role
  - quản lý modules
- Có **upload ảnh** cho post và ảnh hiển thị được.
- Có **filter/query post theo category/module** ở trang Questions.
- Giao diện desktop nhìn khá sạch, đồng bộ, không bị vỡ nặng.

## Điểm bị trừ

- Contact form hiện chỉ **giả lập gửi mail**, chưa thật sự gửi email.
- Phần **module edit** chưa có UI rõ ràng, dù backend có hàm update.
- Phần **user management** chưa đủ đầy:
  - có list
  - có delete
  - có đổi role
  - nhưng chưa có edit username/email rõ ràng trong admin
- Checklist rubric về **assign post to module and user from pre-existing lists** chưa thật sự đầy đủ ở phía user dropdown.
- Mobile layout có **horizontal scroll** và nav bị cắt.
- Project đã có `scratch/schema.sql` cho schema database bản đã gộp.
- Có vài file legacy trong `admin/login/` có thể gây rối nếu marker mở nhầm.

## Đối chiếu rubric

### Core system
- List questions/posts: **Pass**
- Add/edit/delete post: **Pass**
- Image per post: **Pass**
- Contact form to admin: **Partial**
- Add/edit/delete users: **Partial**
- Assign post to module and user: **Partial**
- Add/edit/delete modules: **Partial**

### Extras
- Login system: **Pass**
- Password hashing: **Pass**
- Sign up system: **Pass**
- Admin area: **Pass**
- Front-end design: **Okay**

## Ghi chú cuối

Nếu bạn muốn kéo điểm lên an toàn hơn, các phần đáng sửa nhất là:
1. làm contact form gửi mail thật hoặc ghi rõ prototype trong report
2. thêm edit UI cho modules và users
3. chỉnh responsive mobile
4. nộp kèm `scratch/schema.sql` hoặc export database từ phpMyAdmin
