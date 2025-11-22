<?php
session_start();
require_once '../functions/db_connection.php';
require_once '../functions/admin_check.php'; 
// *** GỌI FILE FUNCTIONS MỚI ***
require_once '../functions/customer_functions.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$redirect_url = "../views/customer/list.php"; 

switch ($action) {
    case 'add':
        if (createCustomer($conn, $_POST)) {
            header("Location: $redirect_url?success=added");
        } else {
            die("Lỗi khi thêm khách hàng: " . $conn->error);
        }
        break;

    case 'edit':
        $customer_id = $_POST['customer_id'];
        if (updateCustomer($conn, $customer_id, $_POST)) {
            header("Location: $redirect_url?success=updated");
        } else {
            die("Lỗi khi cập nhật khách hàng: " . $conn->error);
        }
        break;

    case 'delete':
        $customer_id = $_GET['id'];
        if (deleteCustomer($conn, $customer_id)) {
            header("Location: $redirect_url?success=deleted");
        } else {
            die("Lỗi khi xóa khách hàng: " . $conn->error);
        }
        break;

    default:
        header("Location: ../views/dashboard.php");
        break;
}

$conn->close();
?>