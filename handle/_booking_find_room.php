<?php
// FILE NÀY KHÔNG CHẠY ĐỘC LẬP
// Nó yêu cầu $conn, $room_type_id, $check_in_date, $check_out_date
// Nó cung cấp $assigned_room_id

// Lấy danh sách ID các phòng đã bị đặt
$sql_booked_ids = "SELECT b.room_id
                   FROM bookings AS b
                   JOIN rooms AS r ON b.room_id = r.id
                   WHERE r.room_type_id = ? 
                     AND b.status != 'cancelled'
                     AND (b.check_in_date < ? AND b.check_out_date > ?)";

if ($action == 'edit') {
    $sql_booked_ids .= " AND b.id != ?"; // Loại trừ đơn hiện tại
}

$stmt_booked_ids = $conn->prepare($sql_booked_ids);

if ($action == 'edit') {
    $stmt_booked_ids->bind_param("issi", $room_type_id, $check_out_date, $check_in_date, $booking_id);
} else {
    $stmt_booked_ids->bind_param("iss", $room_type_id, $check_out_date, $check_in_date);
}

$stmt_booked_ids->execute();
$result_booked_ids = $stmt_booked_ids->get_result();

$booked_room_ids = [];
while ($row = $result_booked_ids->fetch_assoc()) {
    $booked_room_ids[] = $row['room_id'];
}
$stmt_booked_ids->close();

// Tìm một phòng còn trống
$sql_find_available_room = "SELECT id FROM rooms WHERE room_type_id = ?";
$params = [$room_type_id];
$types = "i";
if (count($booked_room_ids) > 0) {
    $placeholders = implode(',', array_fill(0, count($booked_room_ids), '?'));
    $sql_find_available_room .= " AND id NOT IN ($placeholders)";
    $types .= str_repeat('i', count($booked_room_ids));
    $params = array_merge($params, $booked_room_ids);
}
$sql_find_available_room .= " LIMIT 1";

$stmt_find = $conn->prepare($sql_find_available_room);
$stmt_find->bind_param($types, ...$params);
$stmt_find->execute();
$result_available_room = $stmt_find->get_result();

if ($result_available_room->num_rows == 0) {
    // Nếu không tìm thấy phòng (dù check_availability báo có) -> báo lỗi
    // Dùng 'add.php' hay 'edit.php' tùy theo $action
    $redirect_url = ($action == 'edit') ? "../views/booking/edit.php?id=$booking_id" : "../views/booking/add.php";
    header("Location: $redirect_url&error=no_rooms");
    exit;
}

$assigned_room_id = $result_available_room->fetch_assoc()['id'];
$stmt_find->close();
?>