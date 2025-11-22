<?php
// File này chứa TẤT CẢ các hàm CSDL cho chức năng 'Người dùng'

/**
 * Lấy tất cả người dùng (admin và nhân viên).
 * @param object $conn Biến kết nối CSDL
 * @return object|false Kết quả MySQLi hoặc false nếu lỗi
 */
function getAllUsers($conn) {
    $sql = "SELECT id, username, full_name, role FROM users ORDER BY full_name ASC";
    $result = $conn->query($sql);
    if ($result === false) {
        die("Lỗi SQL (getAllUsers): " . $conn->error);
    }
    return $result;
}

/**
 * Lấy một người dùng bằng ID.
 * @param object $conn Biến kết nối CSDL
 * @param int $user_id ID của người dùng
 * @return array|null Mảng chứa thông tin người dùng, hoặc null
 */
function getUserById($conn, $user_id) {
    $stmt = $conn->prepare("SELECT id, username, full_name, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        return null;
    }
    return $result->fetch_assoc();
}

/**
 * Tạo người dùng mới.
 * @param object $conn Biến kết nối CSDL
 * @param array $data Mảng dữ liệu từ $_POST
 * @return bool True nếu thành công
 */
function createUser($conn, $data) {
    $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (full_name, username, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $data['full_name'], $data['username'], $hashed_password, $data['role']);
    return $stmt->execute();
}

/**
 * Cập nhật thông tin người dùng.
 * @param object $conn Biến kết nối CSDL
 * @param int $user_id ID của người dùng cần cập nhật
 * @param array $data Mảng dữ liệu từ $_POST
 * @return bool True nếu thành công
 */
function updateUser($conn, $user_id, $data) {
    $password = $data['password'];
    
    // Kiểm tra xem có cần cập nhật mật khẩu không
    if (!empty($password)) {
        // Cập nhật CẢ mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, username = ?, password = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssii", $data['full_name'], $data['username'], $hashed_password, $data['role'], $user_id);
    } else {
        // KHÔNG cập nhật mật khẩu
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, username = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssii", $data['full_name'], $data['username'], $data['role'], $user_id);
    }
    return $stmt->execute();
}

/**
 * Xóa một người dùng.
 * @param object $conn Biến kết nối CSDL
 * @param int $user_id ID của người dùng cần xóa
 * @return bool True nếu thành công
 */
function deleteUser($conn, $user_id) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    return $stmt->execute();
}

?>