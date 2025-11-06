<?php
// File này chứa TẤT CẢ các hàm CSDL cho chức năng 'Phòng'

/**
 * Lấy tất cả các phòng (JOIN với loại phòng).
 * @param object $conn Biến kết nối CSDL
 * @return object Kết quả MySQLi
 */
function getAllRooms($conn) {
    $sql = "SELECT 
                rooms.id, 
                rooms.room_number, 
                rooms.status, 
                room_types.type_name,
                room_types.price_per_night
            FROM rooms
            JOIN room_types ON rooms.room_type_id = room_types.id
            ORDER BY rooms.room_number ASC";
    $result = $conn->query($sql);
    return $result;
}

/**
 * Lấy một phòng bằng ID.
 * @param object $conn Biến kết nối CSDL
 * @param int $room_id ID của phòng
 * @return array|null Mảng chứa thông tin phòng, hoặc null
 */
function getRoomById($conn, $room_id) {
    $stmt = $conn->prepare("SELECT room_number, room_type_id, status FROM rooms WHERE id = ?");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        return null;
    }
    return $result->fetch_assoc();
}

/**
 * Tạo một phòng mới.
 * @param object $conn Biến kết nối CSDL
 * @param array $data Mảng dữ liệu từ $_POST (['room_number', 'room_type_id', 'status'])
 * @return bool True nếu thành công
 */
function createRoom($conn, $data) {
    $stmt = $conn->prepare("INSERT INTO rooms (room_number, room_type_id, status) VALUES (?, ?, ?)");
    // 's' = string (room_number), 'i' = integer (room_type_id), 's' = string (status)
    $stmt->bind_param("sis", $data['room_number'], $data['room_type_id'], $data['status']);
    return $stmt->execute();
}

/**
 * Cập nhật một phòng.
 * @param object $conn Biến kết nối CSDL
 * @param int $room_id ID của phòng cần cập nhật
 * @param array $data Mảng dữ liệu từ $_POST
 * @return bool True nếu thành công
 */
function updateRoom($conn, $room_id, $data) {
    $stmt = $conn->prepare("UPDATE rooms SET room_number = ?, room_type_id = ?, status = ? WHERE id = ?");
    // 's' (number), 'i' (type_id), 's' (status), 'i' (room_id)
    $stmt->bind_param("sisi", $data['room_number'], $data['room_type_id'], $data['status'], $room_id);
    return $stmt->execute();
}

/**
 * Xóa một phòng.
 * @param object $conn Biến kết nối CSDL
 * @param int $room_id ID của phòng cần xóa
 * @return bool True nếu thành công
 */
function deleteRoom($conn, $room_id) {
    // (Nên thêm kiểm tra: Không cho xóa nếu phòng đang có booking 'confirmed' hoặc 'checked_in')
    $stmt = $conn->prepare("DELETE FROM rooms WHERE id = ?");
    $stmt->bind_param("i", $room_id);
    return $stmt->execute();
}

?>