<?php
session_start();
require_once '../functions/db_connection.php';
require_once '../functions/admin_check.php'; // Chỉ Admin mới được quản lý người dùng
// *** GỌI FILE FUNCTIONS MỚI ***
require_once '../functions/user_functions.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$redirect_url = "../views/user/list.php";

switch ($action) {
    // -------------------------
    // TRƯỜNG HỢP: THÊM MỚI
    // -------------------------
    case 'add':
        // Gọi hàm từ Model
        if (createUser($conn, $_POST)) {
            header("Location: $redirect_url?success=added");
        } else {
            die("Lỗi khi thêm người dùng: " . $conn->error);
        }
        break;

    // -------------------------
    // TRƯỜNG HỢP: CẬP NHẬT
    // -------------------------
    case 'edit':
        $user_id = $_POST['user_id'];
        $role = $_POST['role'];
        
        // Kiểm tra logic: Không cho Admin tự hạ quyền
        $is_self = ($user_id == $_SESSION['user_id']);
        if ($is_self && $role != 1) { // 1 = Admin
            header("Location: $redirect_url?error=self_demote");
            exit;
        }
        
        // Gọi hàm từ Model
        if (updateUser($conn, $user_id, $_POST)) {
            header("Location: $redirect_url?success=updated");
        } else {
            die("Lỗi khi cập nhật người dùng: " . $conn->error);
        }
        break;

    // -------------------------
    // TRƯỜNG HỢP: XÓA
    // -------------------------
    case 'delete':
        $user_id = $_GET['id'];
        
        // Kiểm tra logic: Không cho tự xóa
        if ($user_id == $_SESSION['user_id']) {
            header("Location: $redirect_url?error=self_delete");
            exit;
        }
        
        // Gọi hàm từ Model
        if (deleteUser($conn, $user_id)) {
            header("Location: $redirect_url?success=deleted");
        } else {
            die("Lỗi khi xóa người dùng: " . $conn->error);
        }
        break;

    default:
        header("Location: ../views/dashboard.php");
        break;
}

$conn->close();
?>