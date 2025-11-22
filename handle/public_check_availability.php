<?php
// File này chỉ để kiểm tra, không yêu cầu đăng nhập
session_start();
require_once '../functions/db_connection.php';
require_once '../functions/booking_functions.php'; // Gọi Model Booking

header('Content-Type: application/json');

// 1. Kiểm tra vai trò (Role)
$role = $_SESSION['user_role'] ?? 0;
if ($role == 1) { // 1 = Admin/Nhân viên
    echo json_encode(['available' => false, 'message' => 'Tài khoản Quản trị/Nhân viên không thể đặt phòng.']);
    exit;
}

// 2. Lấy dữ liệu
$room_type_id = $_GET['room_type_id'] ?? 0;
$check_in_date = $_GET['check_in'] ?? '';
$check_out_date = $_GET['check_out'] ?? '';

if (empty($room_type_id) || empty($check_in_date) || empty($check_out_date)) {
    echo json_encode(['available' => false, 'message' => 'Vui lòng chọn đủ thông tin.']);
    exit;
}

// 3. Gọi hàm kiểm tra từ Model
$available_count = checkRoomAvailability($conn, $room_type_id, $check_in_date, $check_out_date);

// Nếu còn phòng, trả về tối đa 5 phòng khả dụng để hiển thị trên trang chủ
if ($available_count > 0) {
    $rooms = [];
    $sql_rooms = "SELECT r.id, r.room_number
                  FROM rooms AS r
                  WHERE r.room_type_id = ?
                    AND r.id NOT IN (
                        SELECT b.room_id
                        FROM bookings AS b
                        JOIN rooms AS rr ON b.room_id = rr.id
                        WHERE rr.room_type_id = ?
                          AND b.status != 'cancelled'
                          AND (b.check_in_date < ? AND b.check_out_date > ?)
                    )
                  LIMIT 5";

    $stmt_rooms = $conn->prepare($sql_rooms);
    if ($stmt_rooms) {
        $stmt_rooms->bind_param('iiss', $room_type_id, $room_type_id, $check_out_date, $check_in_date);
        $stmt_rooms->execute();
        $res_rooms = $stmt_rooms->get_result();
        while ($r = $res_rooms->fetch_assoc()) {
            $rooms[] = $r;
        }
        $stmt_rooms->close();
    }

    echo json_encode([
        'available' => true,
        'available_count' => $available_count,
        'rooms' => $rooms
    ]);
} else {
    // HẾT PHÒNG
    echo json_encode(['available' => false, 'message' => 'Đã hết phòng cho loại phòng và ngày bạn chọn.']);
}

$conn->close();
exit;
?>