<?php
// Luôn bắt đầu session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// *** SỬA TÊN BIẾN SESSION ***
// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    
    // Nếu CHƯA, đuổi về trang login
    // Chúng ta dùng đường dẫn tuyệt đối (từ gốc /BTL/) để chắc chắn
    header("Location: /BTL/views/login.php?error=not_logged_in");
    exit;
}
?>