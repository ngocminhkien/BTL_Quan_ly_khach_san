<?php
session_start();
require_once '../functions/db_connection.php';
require_once '../functions/auth_check.php'; 
require_once '../functions/booking_functions.php'; // Cần để gọi hàm updateBookingStatus

$action = $_POST['action'] ?? '';
$booking_id = $_POST['booking_id'] ?? 0;

if ($booking_id == 0) {
    die("Lỗi: Không tìm thấy mã đơn hàng.");
}

// Lấy thông tin người đang đăng nhập
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// Bảo mật: Lấy customer_id từ CSDL để kiểm tra
$stmt_check = $conn->prepare("SELECT customer_id FROM bookings WHERE id = ?");
$stmt_check->bind_param("i", $booking_id);
$stmt_check->execute();
$booking = $stmt_check->get_result()->fetch_assoc();

// Bảo mật: Đơn hàng phải tồn tại VÀ (là của người dùng HOẶC người xem là Admin)
if (!$booking || ($booking['customer_id'] != $user_id && $user_role != 1)) {
    die("Lỗi bảo mật: Bạn không có quyền thực hiện hành động này.");
}

$payment_method = '';
$booking_status = 'confirmed'; // Đơn hàng được xác nhận sau khi thanh toán

switch ($action) {
    
    // Thanh toán khi nhận phòng (Cash on Delivery)
    case 'cod':
        $payment_method = 'Thanh toán khi nhận phòng';
        
        $stmt_update = $conn->prepare("UPDATE bookings SET status = ?, payment_method = ? WHERE id = ?");
        $stmt_update->bind_param("ssi", $booking_status, $payment_method, $booking_id);
        $stmt_update->execute();
        
        break;

    // Giả lập thanh toán online thành công
    case 'paid_online':
        $payment_method = 'Đã thanh toán Online';
        
        $stmt_update = $conn->prepare("UPDATE bookings SET status = ?, payment_method = ? WHERE id = ?");
        $stmt_update->bind_param("ssi", $booking_status, $payment_method, $booking_id);
        $stmt_update->execute();

        break;
        
    default:
        die("Hành động không hợp lệ.");
}

$conn->close();

// Chuyển hướng đến trang Hóa đơn
header("Location: /BTL/views/booking/invoice.php?id=" . $booking_id);
exit;
?>