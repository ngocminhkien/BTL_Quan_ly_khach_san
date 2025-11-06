<?php
session_start();
require_once '../functions/db_connection.php';
require_once '../functions/auth_check.php'; // Đảm bảo đã đăng nhập

// Xác định hành động (action)
// Ưu tiên $_POST, sau đó mới đến $_GET (cho link Xóa)
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    // -------------------------
    // TRƯỜNG HỢP: THÊM MỚI
    // -------------------------
    case 'add':
        // 1. Lấy dữ liệu từ $_POST
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        // ...
        
        // 2. Chuẩn bị SQL
        $stmt = $conn->prepare("INSERT INTO customers (full_name, email, ...) VALUES (?, ?, ...)");
        $stmt->bind_param("ss", $full_name, $email, ...);
        
        // 3. Thực thi và chuyển hướng
        if ($stmt->execute()) {
            // Chú ý đường dẫn mới: /views/customer/list.php
            header("Location: ../views/customer/list.php?success=added");
        } else {
            die("Lỗi khi thêm khách hàng.");
        }
        $stmt->close();
        break;

    // -------------------------
    // TRƯỜNG HỢP: CẬP NHẬT
    // -------------------------
    case 'edit':
        // 1. Lấy dữ liệu từ $_POST
        $customer_id = $_POST['customer_id'];
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        // ...
        
        // 2. Chuẩn bị SQL
        $stmt = $conn->prepare("UPDATE customers SET full_name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $full_name, $email, $customer_id);
        
        // 3. Thực thi và chuyển hướng
        if ($stmt->execute()) {
            header("Location: ../views/customer/list.php?success=updated");
        } else {
            die("Lỗi khi cập nhật khách hàng.");
        }
        $stmt->close();
        break;

    // -------------------------
    // TRƯỜNG HỢP: XÓA
    // -------------------------
    case 'delete':
        // 1. Lấy dữ liệu từ $_GET
        $customer_id = $_GET['id'];
        
        // 2. Chuẩn bị SQL
        $stmt = $conn->prepare("DELETE FROM customers WHERE id = ?");
        $stmt->bind_param("i", $customer_id);
        
        // 3. Thực thi và chuyển hướng
        if ($stmt->execute()) {
            header("Location: ../views/customer/list.php?success=deleted");
        } else {
            die("Lỗi khi xóa khách hàng.");
        }
        $stmt->close();
        break;

    // -------------------------
    // TRƯỜNG HỢP: MẶC ĐỊNH
    // -------------------------
    default:
        echo "Hành động không hợp lệ.";
        header("Location: ../views/dashboard.php");
        break;
}

$conn->close();
?>