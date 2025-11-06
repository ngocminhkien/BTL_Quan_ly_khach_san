<?php
// File này chứa TẤT CẢ các hàm CSDL cho chức năng 'Đặt phòng'

/**
 * Lấy tất cả các đơn đặt phòng (JOIN với các bảng khác).
 * @param object $conn Biến kết nối CSDL
 * @return object Kết quả MySQLi
 */
function getAllBookings($conn) {
    $sql = "SELECT 
                b.id, 
                b.check_in_date, 
                b.check_out_date, 
                b.total_price, 
                b.status,
                c.full_name AS customer_name,
                r.room_number,
                rt.type_name AS room_type
            FROM bookings AS b
            LEFT JOIN customers AS c ON b.customer_id = c.id
            LEFT JOIN rooms AS r ON b.room_id = r.id
            LEFT JOIN room_types AS rt ON r.room_type_id = rt.id
            ORDER BY b.check_in_date DESC";
    $result = $conn->query($sql);
    return $result;
}

/**
 * Lấy một đơn đặt phòng bằng ID.
 * @param object $conn Biến kết nối CSDL
 * @param int $booking_id ID của đơn đặt phòng
 * @return array|null Mảng chứa thông tin, hoặc null
 */
function getBookingById($conn, $booking_id) {
    $stmt = $conn->prepare("SELECT b.customer_id, b.room_id, b.check_in_date, b.check_out_date, r.room_type_id
                              FROM bookings AS b
                              JOIN rooms AS r ON b.room_id = r.id
                              WHERE b.id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        return null;
    }
    return $result->fetch_assoc();
}

/**
 * Hàm logic 1: Kiểm tra phòng trống.
 * @return int Số lượng phòng còn trống
 */
function checkRoomAvailability($conn, $room_type_id, $check_in_date, $check_out_date, $booking_id_to_exclude = 0) {
    // 1. Đếm TỔNG SỐ PHÒNG
    $stmt_total = $conn->prepare("SELECT COUNT(id) AS total_count FROM rooms WHERE room_type_id = ?");
    $stmt_total->bind_param("i", $room_type_id);
    $stmt_total->execute();
    $total_rooms_of_type = $stmt_total->get_result()->fetch_assoc()['total_count'];
    $stmt_total->close();

    // 2. Đếm SỐ PHÒNG ĐÃ BỊ ĐẶT
    $sql_booked = "SELECT COUNT(b.id) AS booked_count
                   FROM bookings AS b
                   JOIN rooms AS r ON b.room_id = r.id
                   WHERE r.room_type_id = ? 
                     AND b.status != 'cancelled'
                     AND (b.check_in_date < ? AND b.check_out_date > ?)";
    
    $params = [$room_type_id, $check_out_date, $check_in_date];
    $types = "iss";

    if ($booking_id_to_exclude > 0) {
        $sql_booked .= " AND b.id != ?"; 
        $params[] = $booking_id_to_exclude;
        $types .= "i";
    }

    $stmt_booked = $conn->prepare($sql_booked);
    $stmt_booked->bind_param($types, ...$params);
    $stmt_booked->execute();
    $booked_rooms_count = $stmt_booked->get_result()->fetch_assoc()['booked_count'];
    $stmt_booked->close();

    // 3. So sánh
    return $total_rooms_of_type - $booked_rooms_count;
}

/**
 * Hàm logic 2: Tìm một phòng cụ thể còn trống.
 * @return int|null ID của phòng trống, hoặc null
 */
function findAvailableRoom($conn, $room_type_id, $check_in_date, $check_out_date, $booking_id_to_exclude = 0) {
    // 1. Lấy danh sách ID các phòng đã bị đặt
    $sql_booked_ids = "SELECT b.room_id
                       FROM bookings AS b
                       JOIN rooms AS r ON b.room_id = r.id
                       WHERE r.room_type_id = ? 
                         AND b.status != 'cancelled'
                         AND (b.check_in_date < ? AND b.check_out_date > ?)";
    
    $params = [$room_type_id, $check_out_date, $check_in_date];
    $types = "iss";

    if ($booking_id_to_exclude > 0) {
        $sql_booked_ids .= " AND b.id != ?";
        $params[] = $booking_id_to_exclude;
        $types .= "i";
    }
    
    $stmt_booked_ids = $conn->prepare($sql_booked_ids);
    $stmt_booked_ids->bind_param($types, ...$params);
    $stmt_booked_ids->execute();
    $result_booked_ids = $stmt_booked_ids->get_result();
    $booked_room_ids = [];
    while ($row = $result_booked_ids->fetch_assoc()) {
        $booked_room_ids[] = $row['room_id'];
    }
    $stmt_booked_ids->close();

    // 2. Tìm một phòng còn trống
    $sql_find = "SELECT id FROM rooms WHERE room_type_id = ?";
    $params_find = [$room_type_id];
    $types_find = "i";
    if (count($booked_room_ids) > 0) {
        $placeholders = implode(',', array_fill(0, count($booked_room_ids), '?'));
        $sql_find .= " AND id NOT IN ($placeholders)";
        $types_find .= str_repeat('i', count($booked_room_ids));
        $params_find = array_merge($params_find, $booked_room_ids);
    }
    $sql_find .= " LIMIT 1";

    $stmt_find = $conn->prepare($sql_find);
    $stmt_find->bind_param($types_find, ...$params_find);
    $stmt_find->execute();
    $result_available_room = $stmt_find->get_result();
    
    if ($result_available_room->num_rows == 0) {
        return null; // Hết phòng
    }
    return $result_available_room->fetch_assoc()['id']; // Trả về ID phòng tìm được
}

/**
 * Hàm logic 3: Tính tổng tiền.
 * @return float Tổng tiền
 */
function calculateTotalPrice($conn, $room_type_id, $check_in_date, $check_out_date) {
    $stmt_price = $conn->prepare("SELECT price_per_night FROM room_types WHERE id = ?");
    $stmt_price->bind_param("i", $room_type_id);
    $stmt_price->execute();
    $price_per_night = $stmt_price->get_result()->fetch_assoc()['price_per_night'];
    $stmt_price->close();

    $date_in = new DateTime($check_in_date);
    $date_out = new DateTime($check_out_date);
    $interval = $date_in->diff($date_out);
    $number_of_nights = ($interval->days == 0) ? 1 : $interval->days;

    return $price_per_night * $number_of_nights;
}

/**
 * Tạo đơn đặt phòng mới.
 * @param object $conn Biến kết nối CSDL
 * @param array $data Dữ liệu (customer_id, room_id, check_in, check_out, total_price)
 * @return bool True nếu thành công
 */
function createBooking($conn, $data) {
    $sql_insert = "INSERT INTO bookings (customer_id, room_id, check_in_date, check_out_date, status, total_price) 
                   VALUES (?, ?, ?, ?, 'confirmed', ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param(
        "iissd", 
        $data['customer_id'], 
        $data['room_id'], 
        $data['check_in_date'], 
        $data['check_out_date'], 
        $data['total_price']
    );
    return $stmt_insert->execute();
}

/**
 * Cập nhật đơn đặt phòng.
 * @param object $conn Biến kết nối CSDL
 * @param int $booking_id ID đơn đặt phòng
 * @param array $data Dữ liệu (customer_id, room_id, check_in, check_out, total_price)
 * @return bool True nếu thành công
 */
function updateBooking($conn, $booking_id, $data) {
    $stmt_update = $conn->prepare("UPDATE bookings SET 
                                       customer_id = ?, 
                                       room_id = ?, 
                                       check_in_date = ?, 
                                       check_out_date = ?, 
                                       total_price = ? 
                                   WHERE id = ?");
    $stmt_update->bind_param(
        "iissdi", 
        $data['customer_id'], 
        $data['room_id'], 
        $data['check_in_date'], 
        $data['check_out_date'], 
        $data['total_price'], 
        $booking_id
    );
    return $stmt_update->execute();
}

/**
 * Cập nhật trạng thái đơn đặt phòng VÀ phòng.
 * @param object $conn Biến kết nối CSDL
 * @param int $booking_id ID đơn đặt phòng
 * @param string $booking_status Trạng thái mới ('checked_in', 'checked_out', 'cancelled')
 * @param string|null $room_status Trạng thái mới cho phòng (nếu có)
 * @return bool True nếu thành công
 */
function updateBookingStatus($conn, $booking_id, $booking_status, $room_status = null) {
    // Lấy room_id từ đơn đặt phòng
    $stmt_room = $conn->prepare("SELECT room_id FROM bookings WHERE id = ?");
    $stmt_room->bind_param("i", $booking_id);
    $stmt_room->execute();
    $room_id = $stmt_room->get_result()->fetch_assoc()['room_id'];
    $stmt_room->close();
    
    // Bắt đầu Transaction
    $conn->begin_transaction();
    
    try {
        // 1. Cập nhật trạng thái booking
        $stmt_booking = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt_booking->bind_param("si", $booking_status, $booking_id);
        $stmt_booking->execute();
        
        // 2. Cập nhật trạng thái phòng (nếu có)
        if ($room_status !== null && $room_id) {
            $stmt_room_update = $conn->prepare("UPDATE rooms SET status = ? WHERE id = ?");
            $stmt_room_update->bind_param("si", $room_status, $room_id);
            $stmt_room_update->execute();
        }
        
        // Commit nếu mọi thứ OK
        $conn->commit();
        return true;
        
    } catch (Exception $e) {
        // Rollback nếu có lỗi
        $conn->rollback();
        die("Lỗi cập nhật trạng thái: " . $e->getMessage());
    }
}

?>