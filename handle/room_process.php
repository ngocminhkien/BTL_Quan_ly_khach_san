<?php
session_start();
require_once '../functions/db_connection.php';
require_once '../functions/admin_check.php'; 
// *** GỌI FILE FUNCTIONS MỚI ***
require_once '../functions/room_functions.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$redirect_url = "../views/room/list.php"; // Đường dẫn quay về

switch ($action) {
    // -------------------------
    // TRƯỜNG HỢP: THÊM MỚI
    // -------------------------
    case 'add':
        // Gọi hàm createRoom, truyền $conn và toàn bộ $_POST
        if (createRoom($conn, $_POST)) {
            header("Location: $redirect_url?success=added");
        } else {
            die("Lỗi khi thêm phòng: " . $conn->error);
        }
        break;

    // -------------------------
    // TRƯỜNG HỢP: CẬP NHẬT
    // -------------------------
    case 'edit':
        $room_id = $_POST['room_id'];
        // Gọi hàm updateRoom, truyền $conn, $room_id và $_POST
        if (updateRoom($conn, $room_id, $_POST)) {
            header("Location: $redirect_url?success=updated");
        } else {
            die("Lỗi khi cập nhật phòng: " . $conn->error);
        }
        break;

    // -------------------------
    // TRƯỜNG HỢP: XÓA
    // -------------------------
    case 'delete':
        $room_id = $_GET['id'];
        // Gọi hàm deleteRoom
        if (deleteRoom($conn, $room_id)) {
            header("Location: $redirect_url?success=deleted");
        } else {
            die("Lỗi khi xóa phòng: " . $conn->error);
        }
        break;

    default:
        header("Location: ../views/dashboard.php");
        break;
}

$conn->close();
?>