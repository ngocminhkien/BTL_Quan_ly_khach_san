<?php
// File: handle/check_session.php
session_start();
header('Content-Type: application/json');

// Yêu cầu các file functions cần thiết
require_once '../functions/db_connection.php';
require_once '../functions/room_type_functions.php'; // Gọi file model

// 1. Lấy danh sách loại phòng
$room_types = [];
$result_types = getAllRoomTypes($conn); // Dùng hàm ta đã tạo
if ($result_types && $result_types->num_rows > 0) {
    while ($row = $result_types->fetch_assoc()) {
        $room_types[] = $row; // Thêm vào mảng
    }
}

// 2. Chuẩn bị thông tin phản hồi
$response = [
    'loggedIn' => false,
    'username' => '',
    'role' => null,
    'roomTypes' => $room_types // Gửi kèm danh sách loại phòng
];

// 3. Kiểm tra đăng nhập
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    $response['loggedIn'] = true;
    $response['username'] = htmlspecialchars($_SESSION['user_username']);
    $response['role'] = (int)$_SESSION['user_role'];
}

// 4. Trả về JSON
$conn->close();
echo json_encode($response);
exit;
?>