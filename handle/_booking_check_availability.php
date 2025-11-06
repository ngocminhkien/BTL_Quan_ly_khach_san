<?php
// FILE NÀY KHÔNG CHẠY ĐỘC LẬP
// Nó được 'include' bởi booking_process.php
// Nó yêu cầu $conn, $room_type_id, $check_in_date, $check_out_date
// Nó cung cấp $available_rooms_count

// Bước 1: Đếm TỔNG SỐ PHÒNG
$stmt_total = $conn->prepare("SELECT COUNT(id) AS total_count FROM rooms WHERE room_type_id = ?");
$stmt_total->bind_param("i", $room_type_id);
$stmt_total->execute();
$total_rooms_of_type = $stmt_total->get_result()->fetch_assoc()['total_count'];
$stmt_total->close();

// Bước 2: Đếm SỐ PHÒNG ĐÃ BỊ ĐẶT
$sql_booked = "SELECT COUNT(b.id) AS booked_count
               FROM bookings AS b
               JOIN rooms AS r ON b.room_id = r.id
               WHERE r.room_type_id = ? 
                 AND b.status != 'cancelled'
                 AND (b.check_in_date < ? AND b.check_out_date > ?)";
                 
// Nếu đang sửa (edit), phải loại trừ chính đơn đặt phòng này
if ($action == 'edit') {
    $sql_booked .= " AND b.id != ?"; // Thêm điều kiện loại trừ
}

$stmt_booked = $conn->prepare($sql_booked);
if ($stmt_booked === false) die("Lỗi SQL (sql_booked): " . $conn->error);

if ($action == 'edit') {
    $stmt_booked->bind_param("issi", $room_type_id, $check_out_date, $check_in_date, $booking_id);
} else {
    $stmt_booked->bind_param("iss", $room_type_id, $check_out_date, $check_in_date);
}

$stmt_booked->execute();
$booked_rooms_count = $stmt_booked->get_result()->fetch_assoc()['booked_count'];
$stmt_booked->close();

// Bước 3: So sánh
$available_rooms_count = $total_rooms_of_type - $booked_rooms_count;
?>