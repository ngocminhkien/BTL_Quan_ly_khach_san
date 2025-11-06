<?php
session_start();
require_once '../functions/db_connection.php';
require_once '../functions/admin_check.php'; 
// *** GỌI FILE FUNCTIONS MỚI ***
require_once '../functions/booking_functions.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$redirect_url = "../views/booking/list.php";

switch ($action) {
    // -------------------------
    // TRƯỜNG HỢP: THÊM MỚI
    // -------------------------
    case 'add':
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
        $data = $_POST;
        $data['room_id'] = $assigned_room_id;
        $data['total_price'] = $total_price;
        
        if (createBooking($conn, $data)) {
            header("Location: $redirect_url?success=added");
        } else {
            die("Lỗi khi thêm: " . $conn->error);
        }
        break;

    // -------------------------
    // TRƯỜNG HỢP: CẬP NHẬT
    // -------------------------
    case 'edit':
        $booking_id = $_POST['booking_id'];
        $room_type_id = $_POST['room_type_id'];
        $check_in_date = $_POST['check_in_date'];
        $check_out_date = $_POST['check_out_date'];

        // 1. Kiểm tra phòng trống (loại trừ đơn này)
        $available_count = checkRoomAvailability($conn, $room_type_id, $check_in_date, $check_out_date, $booking_id);
        if ($available_count <= 0) {
            header("Location: ../views/booking/edit.php?id=$booking_id&error=no_rooms");
            exit;
        }

        // 2. Tìm phòng trống (loại trừ đơn này)
        $assigned_room_id = findAvailableRoom($conn, $room_type_id, $check_in_date, $check_out_date, $booking_id);
        if ($assigned_room_id === null) {
            header("Location: ../views/booking/edit.php?id=$booking_id&error=no_rooms");
            exit;
        }
        
        // 3. Tính tiền
        $total_price = calculateTotalPrice($conn, $room_type_id, $check_in_date, $check_out_date);
        
        // 4. Gộp dữ liệu và Cập nhật
        $data = $_POST;
        $data['room_id'] = $assigned_room_id;
        $data['total_price'] = $total_price;
        
        if (updateBooking($conn, $booking_id, $data)) {
            header("Location: $redirect_url?success=updated");
        } else {
            die("Lỗi khi cập nhật: " . $conn->error);
        }
        break;

    // -------------------------
    // TRƯỜNG HỢP: CẬP NHẬT TRẠNG THÁI
    // -------------------------
    case 'check_in':
        updateBookingStatus($conn, $_GET['id'], 'checked_in', 'occupied');
        header("Location: $redirect_url?success=checked_in");
        break;

    case 'check_out':
        updateBookingStatus($conn, $_GET['id'], 'checked_out', 'cleaning');
        header("Location: $redirect_url?success=checked_out");
        break;

    case 'cancel':
        updateBookingStatus($conn, $_GET['id'], 'cancelled');
        header("Location: $redirect_url?success=cancelled");
        break;

    default:
        header("Location: ../views/dashboard.php");
        break;
}

$conn->close();
?>