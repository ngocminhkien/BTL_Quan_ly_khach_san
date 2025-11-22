<?php
// File này chứa TẤT CẢ các hàm CSDL cho chức năng 'Khách hàng'

/**
 * Lấy tất cả khách hàng.
 * @param object $conn Biến kết nối CSDL
 * @return object Kết quả MySQLi
 */
function getAllCustomers($conn) {
    $sql = "SELECT id, full_name, email, phone FROM customers ORDER BY full_name ASC";
    $result = $conn->query($sql);
    return $result;
}

/**
 * Lấy một khách hàng bằng ID.
 * @param object $conn Biến kết nối CSDL
 * @param int $customer_id ID của khách hàng
 * @return array|null Mảng chứa thông tin, hoặc null
 */
function getCustomerById($conn, $customer_id) {
    $stmt = $conn->prepare("SELECT full_name, email, phone FROM customers WHERE id = ?");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        return null;
    }
    return $result->fetch_assoc();
}

/**
 * Tạo khách hàng mới.
 * @param object $conn Biến kết nối CSDL
 * @param array $data Mảng dữ liệu từ $_POST
 * @return bool True nếu thành công
 */
function createCustomer($conn, $data) {
    $stmt = $conn->prepare("INSERT INTO customers (full_name, email, phone) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $data['full_name'], $data['email'], $data['phone']);
    return $stmt->execute();
}

/**
 * Cập nhật khách hàng.
 * @param object $conn Biến kết nối CSDL
 * @param int $customer_id ID của khách hàng
 * @param array $data Mảng dữ liệu từ $_POST
 * @return bool True nếu thành công
 */
function updateCustomer($conn, $customer_id, $data) {
    $stmt = $conn->prepare("UPDATE customers SET full_name = ?, email = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("sssi", $data['full_name'], $data['email'], $data['phone'], $customer_id);
    return $stmt->execute();
}

/**
 * Xóa khách hàng.
 * @param object $conn Biến kết nối CSDL
 * @param int $customer_id ID của khách hàng
 * @return bool True nếu thành công
 */
function deleteCustomer($conn, $customer_id) {
    // (Nên kiểm tra xem khách hàng này có booking nào không trước khi xóa)
    $stmt = $conn->prepare("DELETE FROM customers WHERE id = ?");
    $stmt->bind_param("i", $customer_id);
    return $stmt->execute();
}

?>