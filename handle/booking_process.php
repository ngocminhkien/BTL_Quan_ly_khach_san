<?php
session_start();
require_once '../functions/db_connection.php';
require_once '../functions/auth_check.php'; 
require_once '../functions/booking_functions.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$redirect_url = "../views/booking/list.php";

// Lấy thông tin người đang đăng nhập
$logged_in_user_id = $_SESSION['user_id'];
$logged_in_user_role = $_SESSION['user_role'];

switch ($action) {
    // -------------------------
    // TRƯỜNG HỢP: THÊM MỚI
    // -------------------------
    case 'add':
        // *** KIỂM TRA BẢO MẬT ***
        $customer_id_from_form = $_POST['customer_id'];
        
        // Nếu người đăng nhập là User (Role 2)
        if ($logged_in_user_role == 2) {
            // Họ chỉ được phép đặt cho chính mình (ID của họ phải khớp với ID họ gửi)
            if ($customer_id_from_form != $logged_in_user_id) {
                die("Lỗi bảo mật: Bạn không được phép đặt phòng cho người khác.");
            }
        }
        // Nếu là Admin (Role 1), $customer_id_from_form sẽ được chấp nhận
        
        $customer_id = $customer_id_from_form;
        $room_type_id = $_POST['room_type_id']; 
        $check_in_date = $_POST['check_in_date'];
        $check_out_date = $_POST['check_out_date'];
        
        // 1. Kiểm tra phòng trống
        $available_count = checkRoomAvailability($conn, $room_type_id, $check_in_date, $check_out_date);
        if ($available_count <= 0) {
            header("Location: ../views/booking/add.php?error=no_rooms");
            exit;
        }

        // 2. Tìm phòng trống
        $assigned_room_id = findAvailableRoom($conn, $room_type_id, $check_in_date, $check_out_date);
        if ($assigned_room_id === null) {
            header("Location: ../views/booking/add.php?error=no_rooms");
            exit;
        }
        
        // 3. Tính tiền
        $total_price = calculateTotalPrice($conn, $room_type_id, $check_in_date, $check_out_date);
        
        // 4. Gộp dữ liệu và Tạo
        $data = [
            'customer_id' => $customer_id, // Đã được kiểm tra bảo mật
            'room_id' => $assigned_room_id,
            'check_in_date' => $check_in_date,
            'check_out_date' => $check_out_date,
            'total_price' => $total_price
        ];
        
        // 5. Tạo đơn hàng (Hàm này đã bị tôi sửa ở tin nhắn trước, giờ sửa lại)
        $sql_insert = "INSERT INTO bookings (customer_id, room_id, check_in_date, check_out_date, status, total_price) 
                       VALUES (?, ?, ?, ?, 'pending', ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iissd", $data['customer_id'], $data['room_id'], $data['check_in_date'], $data['check_out_date'], $data['total_price']);
        
        if ($stmt_insert->execute()) {
            $new_booking_id = $conn->insert_id;
            // Chuyển hướng đến trang Thanh toán
            header("Location: /BTL/views/payment/checkout.php?booking_id=" . $new_booking_id);
        } else {
            die("Lỗi khi thêm: " . $stmt_insert->error);
        }
        $stmt_insert->close();
        break;

    // -------------------------
    // CÁC HÀNH ĐỘNG KHÁC (Chỉ Admin)
    // -------------------------
    case 'edit':
        // Chỉnh sửa đơn (chỉ admin)
        if ($logged_in_user_role != 1) {
            die("Lỗi: Bạn không có quyền truy cập chức năng này.");
        }

        $booking_id = $_POST['id'] ?? null;
        if (!$booking_id) {
            die('Thiếu ID đơn để chỉnh sửa.');
        }

        // Lấy dữ liệu từ form
        $data = [
            'customer_id' => $_POST['customer_id'] ?? null,
            'room_id' => $_POST['room_id'] ?? null,
            'check_in_date' => $_POST['check_in_date'] ?? null,
            'check_out_date' => $_POST['check_out_date'] ?? null,
            'total_price' => $_POST['total_price'] ?? 0
        ];

        if (updateBooking($conn, $booking_id, $data)) {
            header("Location: ../views/booking/list.php?success=updated");
            exit;
        } else {
            die('Lỗi khi cập nhật đơn đặt phòng.');
        }
        break;

    case 'check_in':
        if ($logged_in_user_role != 1) {
            die("Lỗi: Bạn không có quyền truy cập chức năng này.");
        }
        $booking_id = $_GET['id'] ?? null;
        if (!$booking_id) die('Thiếu ID đơn để check-in.');

        if (updateBookingStatus($conn, $booking_id, 'checked_in', 'occupied')) {
            header("Location: ../views/booking/list.php?success=checked_in");
            exit;
        } else {
            die('Lỗi khi check-in.');
        }
        break;

    case 'check_out':
        if ($logged_in_user_role != 1) {
            die("Lỗi: Bạn không có quyền truy cập chức năng này.");
        }
        $booking_id = $_GET['id'] ?? null;
        if (!$booking_id) die('Thiếu ID đơn để check-out.');

        if (updateBookingStatus($conn, $booking_id, 'checked_out', 'available')) {
            header("Location: ../views/booking/list.php?success=checked_out");
            exit;
        } else {
            die('Lỗi khi check-out.');
        }
        break;

    case 'cancel':
        $booking_id = $_GET['id'] ?? null;
        if (!$booking_id) die('Thiếu ID đơn để hủy.');

        // Lấy thông tin đơn hàng để kiểm tra quyền sở hữu
        $stmt_check = $conn->prepare("SELECT customer_id, status FROM bookings WHERE id = ?");
        $stmt_check->bind_param("i", $booking_id);
        $stmt_check->execute();
        $booking_info = $stmt_check->get_result()->fetch_assoc();
        $stmt_check->close();

        if (!$booking_info) die("Đơn hàng không tồn tại.");

        // KIỂM TRA QUYỀN:
        // 1. Nếu là Admin (Role 1) -> Cho phép hủy mọi lúc
        // 2. Nếu là User (Role 2) -> Chỉ được hủy đơn của CHÍNH MÌNH và trạng thái phải là 'pending' (chưa duyệt)
        if ($logged_in_user_role == 2) {
            if ($booking_info['customer_id'] != $logged_in_user_id) {
                die("Lỗi: Bạn không thể hủy đơn của người khác.");
            }
            if ($booking_info['status'] != 'pending') {
                // Nếu muốn cho phép hủy cả khi đã confirmed nhưng chưa check-in, bạn có thể sửa điều kiện này
                die("Lỗi: Bạn chỉ có thể hủy đơn khi đang chờ duyệt. Vui lòng liên hệ hotline để được hỗ trợ.");
            }
        }

        // Thực hiện hủy
        if (updateBookingStatus($conn, $booking_id, 'cancelled', 'available')) {
            // Nếu là User thì quay về trang lịch sử, Admin quay về trang quản lý
            if ($logged_in_user_role == 2) {
                header("Location: ../views/booking/history.php?success=cancelled");
            } else {
                header("Location: ../views/booking/list.php?success=cancelled");
            }
            exit;
        } else {
            die('Lỗi khi hủy đặt phòng.');
        }
        break;
}

$conn->close();
?>