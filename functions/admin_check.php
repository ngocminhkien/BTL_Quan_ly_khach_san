<?php
// 1. Gọi gác cổng chung (kiểm tra đã login chưa)
// __DIR__ đảm bảo nó luôn tìm thấy file auth_check.php
require_once __DIR__ . '/auth_check.php';

// 2. Kiểm tra vai trò (role)
// *** SỬA TÊN BIẾN SESSION ***
// Nếu session 'user_role' không tồn tại HOẶC role không phải là 1 (Admin)
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    
    // Báo lỗi và dừng
    die("Lỗi: Bạn không có quyền truy cập chức năng này.");
    
    // Hoặc chuyển hướng về dashboard
    // header("Location: /BTL/views/dashboard.php?error=unauthorized");
    // exit;
}

// Nếu là Admin (role == 1) thì cho qua.
?>