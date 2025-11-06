<?php
session_start();
require_once '../functions/db_connection.php';
require_once '../functions/admin_check.php'; 
// *** GỌI FILE FUNCTIONS MỚI ***
require_once '../functions/room_type_functions.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$redirect_url = "../views/room_type/list.php"; // Định nghĩa sẵn đường dẫn

switch ($action) {
    // -------------------------
    // TRƯỜNG HỢP: THÊM MỚI
    // -------------------------
    case 'add':
        // Dữ liệu đã được chuẩn bị trong mảng $_POST
        // Gọi hàm từ Model
        if (createRoomType($conn, $_POST)) {
            header("Location: $redirect_url?success=added");
        } else {
            die("Lỗi khi thêm: " . $conn->error);
        }
        break;

    // -------------------------
    // TRƯỜNG HỢP: CẬP NHẬT
    // -------------------------
    case 'edit':
        $type_id = $_POST['type_id'];
        // Gọi hàm từ Model
        if (updateRoomType($conn, $type_id, $_POST)) {
            header("Location: $redirect_url?success=updated");
        } else {
            die("Lỗi khi cập nhật: " . $conn->error);
        }
        break;

    // -------------------------
    // TRƯỜNG HỢP: XÓA
    // -------------------------
    case 'delete':
        $type_id = $_GET['id'];
        // Gọi hàm từ Model
        if (deleteRoomType($conn, $type_id)) {
            header("Location: $redirect_url?success=deleted");
        } else {
            die("Lỗi khi xóa: " . $conn->error);
        }
        break;

    default:
        header("Location: ../views/dashboard.php");
        break;
}

$conn->close();
?>