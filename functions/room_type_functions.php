<?php
// File này chứa TẤT CẢ các hàm CSDL cho chức năng 'Loại phòng'

/**
 * Lấy tất cả các loại phòng.
 * @param object $conn Biến kết nối CSDL (MySQLi)
 * @return object Kết quả MySQLi
 */
function getAllRoomTypes($conn) {
    $sql = "SELECT id, type_name, price_per_night, description 
            FROM room_types 
            ORDER BY type_name ASC";
    $result = $conn->query($sql);
    return $result;
}

/**
 * Lấy một loại phòng bằng ID.
 * @param object $conn Biến kết nối CSDL
 * @param int $type_id ID của loại phòng
 * @return array|null Mảng chứa thông tin loại phòng, hoặc null nếu không tìm thấy
 */
function getRoomTypeById($conn, $type_id) {
    $stmt = $conn->prepare("SELECT type_name, price_per_night, description 
                            FROM room_types 
                            WHERE id = ?");
    $stmt->bind_param("i", $type_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        return null;
    }
    return $result->fetch_assoc();
}

/**
 * Tạo một loại phòng mới.
 * @param object $conn Biến kết nối CSDL
 * @param array $data Mảng dữ liệu từ $_POST (['type_name', 'price_per_night', 'description'])
 * @return bool True nếu thành công, False nếu thất bại
 */
function createRoomType($conn, $data) {
    $stmt = $conn->prepare("INSERT INTO room_types (type_name, price_per_night, description) 
                            VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $data['type_name'], $data['price_per_night'], $data['description']); // 'd' for double (giá tiền)
    return $stmt->execute();
}

/**
 * Cập nhật một loại phòng.
 * @param object $conn Biến kết nối CSDL
 * @param int $type_id ID của loại phòng cần cập nhật
 * @param array $data Mảng dữ liệu từ $_POST
 * @return bool True nếu thành công, False nếu thất bại
 */
function updateRoomType($conn, $type_id, $data) {
    $stmt = $conn->prepare("UPDATE room_types 
                            SET type_name = ?, price_per_night = ?, description = ? 
                            WHERE id = ?");
    $stmt->bind_param("sdsi", $data['type_name'], $data['price_per_night'], $data['description'], $type_id);
    return $stmt->execute();
}

/**
 * Xóa một loại phòng.
 * @param object $conn Biến kết nối CSDL
 * @param int $type_id ID của loại phòng cần xóa
 * @return bool True nếu thành công, False nếu thất bại
 */
function deleteRoomType($conn, $type_id) {
    // (Lưu ý: Nên kiểm tra xem có phòng nào đang dùng loại phòng này không trước khi xóa)
    $stmt = $conn->prepare("DELETE FROM room_types WHERE id = ?");
    $stmt->bind_param("i", $type_id);
    return $stmt->execute();
}

?>