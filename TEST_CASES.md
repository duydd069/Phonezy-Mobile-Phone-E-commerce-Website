# Bảng Test Case — Phonezy Mobile Phone E-commerce

Hướng dẫn: Tài liệu này liệt kê các test case cần thực hiện cho các chức năng chính của hệ thống. Mỗi test case gồm: **Tiêu đề**, **Tiền điều kiện**, **Các bước**, **Kết quả mong đợi**, **Loại** (Unit / Integration / E2E), và **Ưu tiên** (High / Medium / Low).

---

## 1. Authentication (Đăng ký / Đăng nhập / Logout / Email verification)

| ID | Test Case | Tiền điều kiện | Các bước | Kết quả mong đợi | Loại | Ưu tiên |
|---|---|---|---|---|---:|---:|
| A01 | Đăng ký thành công | Không có tài khoản với email này | 1. Mở form đăng ký
2. Điền tên, email hợp lệ, password hợp lệ, confirm
3. Gửi form | Tài khoản được tạo, user được redirect/đăng nhập hoặc nhận email xác thực tùy config | Integration/E2E | High |
| A02 | Đăng ký với email đã tồn tại | Email đã tồn tại | Gửi form đăng ký dùng email trùng | Hiển thị lỗi validation: email đã được sử dụng | Integration | High |
| A03 | Đăng ký với mật khẩu yếu | - | Gửi form với password quá ngắn/không đủ yêu cầu | Hiển thị lỗi validation password | Integration | Medium |
| A04 | Đăng nhập thành công | User đã tồn tại, đã verify (nếu yêu cầu) | 1. Mở form login
2. Điền email & password đúng
3. Gửi form | User authenticated, session tồn tại, redirect tới `client.index` | Integration/E2E | High |
| A05 | Đăng nhập sai mật khẩu | User đã tồn tại | Gửi form với mật khẩu sai | Hiển thị lỗi xác thực, không tạo session | Integration | High |
| A06 | Logout | User đang login | Gửi POST tới `/logout` | Session bị huỷ, redirect tới trang public | Integration | Medium |
| A07 | Xác thực email với token hợp lệ | User có token verify | Truy cập `/email/verify/{token}` | Trạng thái email=verified, thông báo thành công | Integration | Medium |
| A08 | Xác thực email với token sai/hết hạn | Token sai/expired | Truy cập link verify | Hiển thị lỗi hoặc hướng dẫn gửi lại | Integration | Medium |

---

## 2. Client - Product / Category / Promotions

| ID | Test Case | Tiền điều kiện | Các bước | Kết quả mong đợi | Loại | Ưu tiên |
|---|---|---|---|---|---:|---:|
| P01 | Danh sách sản phẩm - trang chủ | Có nhiều sản phẩm trong DB | Truy cập `client/` | Hiển thị danh sách, pagination hoạt động | E2E | High |
| P02 | Trang chi tiết sản phẩm hợp lệ | Product tồn tại với variants/images | Truy cập `client/p/{product}` | Hiển thị tên, giá, variants, gallery | E2E | High |
| P03 | Truy cập sản phẩm không tồn tại | product id/slug không hợp lệ | Truy cập URL | 404 hoặc redirect về danh sách với message | E2E | Medium |
| P04 | Lọc theo category | Category có sản phẩm | Truy cập `client/category/{slug}` | Chỉ hiển thị sản phẩm thuộc category | E2E | Medium |
| P05 | Trang promotions | Một số sản phẩm có promotion | Truy cập `client/promotions` | Hiển thị sản phẩm có promotion, giá hiển thị giảm | E2E | Low |

---

## 3. Cart (Thêm, Cập nhật, Xóa, Clear)

| ID | Test Case | Tiền điều kiện | Các bước | Kết quả mong đợi | Loại | Ưu tiên |
|---|---|---|---|---|---:|---:|
| C01 | Thêm item vào giỏ thành công | Product variant tồn tại, đủ stock | POST `/cart/add` với variant_id, qty | Item xuất hiện trong session/cart, subtotal cập nhật | Integration | High |
| C02 | Thêm vượt quá tồn kho | Qty > stock | POST add | Trả lỗi (validation) hoặc giới hạn số lượng | Integration | High |
| C03 | Cập nhật số lượng | Item đã có trong cart | POST `/cart/update` với qty mới | Số lượng cập nhật, subtotal thay đổi tương ứng; qty=0 -> item bị xóa | Integration | High |
| C04 | Xóa 1 item | Item có trong cart | POST `/cart/remove` | Item bị loại khỏi cart | Integration | Medium |
| C05 | Clear cart | Cart có items | POST `/cart/clear` | Cart trống | Integration | Medium |

---

## 4. Checkout & Payment (Coupon, VNPAY)

| ID | Test Case | Tiền điều kiện | Các bước | Kết quả mong đợi | Loại | Ưu tiên |
|---|---|---|---|---|---:|---:|
| CH01 | Checkout thành công (khách hoặc user) | Cart có items, địa chỉ & payment hợp lệ | Truy cập checkout, điền thông tin, chọn payment, submit | Tạo order, giảm stock tương ứng, chuyển tới payment gateway hoặc trang success | Integration/E2E | High |
| CH02 | Checkout với coupon fixed amount | Coupon hợp lệ type=fixed | Áp coupon trước khi submit | Tổng tiền giảm đúng giá trị coupon | Integration | High |
| CH03 | Checkout với coupon percentage | Coupon type=percentage | Áp coupon | Tổng tiền giảm % chính xác (các giới hạn được áp dụng) | Integration | High |
| CH04 | Coupon expired / usage limit | Coupon hết hạn hoặc đã dùng vượt limit | Áp coupon | Coupon bị từ chối, message lỗi rõ ràng | Integration | High |
| CH05 | VNPAY return success | Order chờ thanh toán, VNPAY trả về | Thực hiện flow return với signature hợp lệ | Order marked as paid, redirect success | Integration | High |
| CH06 | VNPAY IPN invalid signature | IPN có signature sai | POST ipn | Bác bỏ request, không mark paid | Integration | High |

---

## 5. Comments (Client) & Admin Comment Management

- Client side

| ID | Test Case | Tiền điều kiện | Các bước | Kết quả mong đợi | Loại | Ưu tiên |
|---|---|---|---|---|---:|---:|
| CM01 | Lấy danh sách bình luận sản phẩm | Có bình luận | GET `/comments/{product}` | Trả về list comment với pagination | Integration | Medium |
| CM02 | Gửi bình luận khi đã login | User đã auth | POST `/comments/{product}` với nội dung | Bình luận được lưu, hiển thị; nếu có bad words -> xử lý theo policy | Integration | Medium |
| CM03 | Gửi bình luận khi chưa login | Chưa auth | POST | Redirect tới login / 401 | Integration | Medium |

- Admin side

| ID | Test Case | Test Steps | Expected | Loại | Ưu tiên |
|---|---|---|---|---:|---:|
| CM04 | Admin xem & trả lời comment | Admin auth, có comment | GET admin/comments, POST reply | Reply lưu, liên kết admin, hiển thị | Integration | Medium |
| CM05 | Admin xóa comment | Admin auth | DELETE comment | Comment bị xóa | Integration | Medium |

---

## 6. Wishlist

| ID | Test Case | Tiền điều kiện | Các bước | Kết quả mong đợi | Loại | Ưu tiên |
|---|---|---|---|---|---:|---:|
| W01 | Thêm vào wishlist | User auth, product tồn tại | POST `/wishlist/add` | Item thêm vào wishlist | Integration | Medium |
| W02 | Xóa wishlist | Item có trong wishlist | POST `/wishlist/remove` | Item bị xóa | Integration | Medium |
| W03 | Toggle wishlist | - | POST `/wishlist/toggle` | Nếu chưa có -> thêm, có -> xóa | Integration | Medium |
| W04 | Không auth khi thao tác wishlist | Chưa login | POST | Redirect/401 | Integration | Medium |

---

## 7. Client Account & Orders

| ID | Test Case | Tiền điều kiện | Các bước | Kết quả mong đợi | Loại | Ưu tiên |
|---|---|---|---|---|---:|---:|
| AC01 | Xem trang account | User auth | GET `/client/account` | Hiển thị thông tin user | Integration | Medium |
| AC02 | Cập nhật thông tin hợp lệ | User auth | POST update với data hợp lệ | Thông tin lưu thành công, phản hồi success | Integration | Medium |
| AC03 | Update email trùng | Email đã dùng bởi người khác | POST update | Validation error | Integration | Medium |
| OR01 | Xem danh sách đơn hàng | User auth, có orders | GET `/client/orders` | Trả orders của user | Integration | High |
| OR02 | Xem chi tiết order | User là owner của order | GET `/client/orders/{order}` | Hiển thị order + items + trạng thái | Integration | High |
| OR03 | Truy cập order của người khác | User auth, order của user khác | GET | 403/404 | Integration | High |

---

## 8. Order Return (Client) & Admin Return Management

| ID | Test Case | Tiền điều kiện | Các bước | Kết quả mong đợi | Loại | Ưu tiên |
|---|---|---|---|---|---:|---:|
| R01 | Tạo yêu cầu trả hàng hợp lệ | Order đã hoàn thành/đủ điều kiện return | GET tạo form, POST với lý do + hình ảnh | Yêu cầu tạo, trạng thái 'pending', ảnh lưu | Integration | Medium |
| R02 | Tạo return bởi user không phải owner | - | POST | Bị từ chối (403) | Integration | High |
| R03 | Admin approve return | Admin auth, return pending | POST approve | Return trạng thái approve, notify user | Integration | High |
| R04 | Admin reject return | Admin auth | POST reject | Trạng thái rejected, có lý do | Integration | High |
| R05 | Confirm received & process refund | Admin auth, return approved & shipped | POST confirm-received, process-refund | Trạng thái updated, refund transaction ghi nhận | Integration | High |

---

## 9. Admin — CRUD Resources (Brands, Categories, Colors, Storages, Versions, Coupons)

| ID | Test Case | Tiền điều kiện | Các bước | Kết quả mong đợi | Loại | Ưu tiên |
|---|---|---|---|---|---:|---:|
| AD01 | CRUD resource cơ bản | Admin auth | Create, Read, Update, Delete resource | Các thao tác thành công, validation hoạt động | Integration | High |
| AD02 | Non-admin truy cập admin route | Auth nhưng không phải admin | Gọi route admin | 403 | Integration | High |

---

## 10. Admin — Products & Variants

| ID | Test Case | Tiền điều kiện | Các bước | Kết quả mong đợi | Loại | Ưu tiên |
|---|---|---|---|---|---:|---:|
| PR01 | Tạo product với variants và images | Admin auth, data hợp lệ | POST product + variants + images | Product + variants + images lưu, hiển thị ở client | Integration | High |
| PR02 | Update product/variant | Admin auth | PUT update | Thay đổi được áp dụng | Integration | High |
| PR03 | Xóa product | Admin auth | DELETE product | Xóa hoặc mark inactive theo policy | Integration | High |

---

## 11. Admin — Users (Ban / Unban)

| ID | Test Case | Các bước | Kết quả mong đợi | Loại | Ưu tiên |
|---|---|---|---|---:|---:|
| U01 | Ban user | Admin auth, target user exists | PUT `users/{user}/ban` | User bị mark banned, không thể login | Integration | High |
| U02 | Unban user | Admin auth | PUT `users/{user}/unban` | User có thể login lại | Integration | High |

---

## 12. Admin — Orders Management

| ID | Test Case | Tiền điều kiện | Các bước | Kết quả mong đợi | Loại | Ưu tiên |
|---|---|---|---|---|---:|---:|
| OAD01 | Xem danh sách order | Admin auth, có orders | GET `/admin/orders` | Trả danh sách, có filters | Integration | High |
| OAD02 | Update order status | Admin auth | PUT `/orders/{order}/status` | Trạng thái thay đổi và log/notify | Integration | High |
| OAD03 | Confirm payment | Admin auth | POST confirm-payment | Order marked paid, payment record lưu | Integration | High |

---

## 13. Revenue Report / Export

| ID | Test Case | Các bước | Kết quả mong đợi | Loại | Ưu tiên |
|---|---|---|---|---:|
| REP01 | Xem báo cáo doanh thu | Admin auth, có orders data | GET `/admin/revenue` với filter date | Trả báo cáo đúng với sum, group, các chỉ số | Integration | Medium |
| REP02 | Export CSV | Admin auth | GET `/admin/revenue/export` | Trả file CSV có header và dữ liệu khớp | Integration | Medium |

---

## 14. Inventory / WarehouseStock

| ID | Test Case | Tiền điều kiện | Các bước | Kết quả mong đợi | Loại | Ưu tiên |
|---|---|---|---|---|---:|---:|
| INV01 | Giảm tồn kho khi tạo order | Order successful | Thực hiện checkout | Stock của variants giảm tương ứng | Integration | High |
| INV02 | Restock khi return processed | Return processed & accepted | Process refund/confirm-received | Stock tăng lại (theo policy) | Integration | High |
| INV03 | Kiểm tra race condition (đa request cùng lúc) | Stock giới hạn nhỏ | Gửi nhiều request order song song | Không cho phép oversell; xử lý transaction/locking | Integration/Stress | High |

---

## 15. Notifications (Email Order Confirmation)

| ID | Test Case | Các bước | Kết quả mong đợi | Loại | Ưu tiên |
|---|---|---|---|---:|
| N01 | Gửi email khi order đặt thành công | Order created | Trigger notification | Email queued/sent với nội dung order đúng | Integration | Medium |

---

## 16. API Chatbot

| ID | Test Case | Các bước | Kết quả mong đợi | Loại | Ưu tiên |
|---|---|---|---|---:|
| BOT01 | Yêu cầu hợp lệ | Gửi POST `/api/chatbot` với payload hợp lệ | Trả JSON đáp ứng, status 200 | Integration | Low |
| BOT02 | Payload không hợp lệ | Gửi POST thiếu field bắt buộc | Trả 400 với message lỗi | Integration | Low |

---

## Ghi chú chung & đề xuất thực hiện

- Thực hiện test theo thứ tự ưu tiên: High trước (Authentication, Cart, Checkout, Payment, Admin permissions, Inventory).
- Với từng test case Integration/E2E, ghi rõ data seed cần thiết (users, products, variants, coupons) trước khi chạy test.
- Những bài kiểm thử liên quan tới thanh toán (VNPAY) cần mô phỏng response và kiểm tra signature; không gửi giao dịch thật.
- Đối với concurrency/race condition, nên có test stress hoặc sử dụng transaction locking unit test.

---

Nếu bạn muốn, tôi có thể:

- Xuất file CSV tương ứng.
- Sinh skeleton test Laravel (PHPUnit/Feature) cho các test case High-priority.
- Mở rộng mỗi test case thành form chi tiết (Pre-condition, Test data, Steps, Expected, Cleanup).

Hãy cho biết bạn muốn tôi tiếp tục theo hướng nào (xuất CSV / tạo skeleton tests / mở rộng chi tiết từng test case).
