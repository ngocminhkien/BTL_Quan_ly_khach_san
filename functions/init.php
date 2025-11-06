<?php
// Bật báo cáo lỗi để dễ dàng debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Bắt đầu session (để lưu đăng nhập)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>












