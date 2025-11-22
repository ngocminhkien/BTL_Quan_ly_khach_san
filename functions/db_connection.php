<?php
// ===== THÔNG TIN KẾT NỐI ===== //
$servername = "localhost";
$username = "root";
$dbname = "db_quanlykhachsan";
$password = ""; 

// 1. Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// 2. Kiểm tra kết nối
if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
}

// 3. Thiết lập UTF-8
$conn->set_charset("utf8mb4");
?>