<?php
// Luôn bắt đầu session
session_start();

// Xóa tất cả các biến session
$_SESSION = array();

// Hủy session
session_destroy();

// Chuyển hướng về trang đăng nhập với thông báo
// (Chúng ta dùng đường dẫn tuyệt đối để chắc chắn)
header("Location: /BTL/index.php?logout=success");
exit;
?>