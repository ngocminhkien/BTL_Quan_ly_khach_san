<?php
session_start();

// Xóa tất cả các biến session
$_SESSION = array();

// Hủy session
session_destroy();

// *** SỬA LỖI Ở ĐÂY ***
// Chuyển hướng về trang chủ (phải là .html)
header("Location: /BTL/index.php");
exit;
?>