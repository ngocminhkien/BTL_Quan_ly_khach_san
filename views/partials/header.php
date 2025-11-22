<?php
// Luôn bắt đầu session ở đầu file partials (nếu nó chưa được bắt đầu)
// để lấy thông tin đăng nhập
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Định nghĩa BASE_URL.
 * Nếu bạn đổi tên thư mục "BTL", chỉ cần sửa ở đây.
 * Nếu bạn chạy ở thư mục gốc (root) của domain, hãy đổi nó thành "" (chuỗi rỗng).
 */
define('BASE_URL', '/BTL');

// Lấy đường dẫn của script hiện tại, ví dụ: "/BTL/views/room/list.php"
$current_page_path = $_SERVER['PHP_SELF'];

/**
 * Hàm tiện ích để kiểm tra xem mục menu nào đang 'active'.
 * Nó sẽ kiểm tra xem đường dẫn $current_page_path có chứa $folder_name hay không.
 *
 * @param string $folder_name Tên thư mục cần kiểm tra (ví dụ: 'room', 'customer')
 * @param string $current_page_path Đường dẫn hiện tại
 * @return string Trả về 'active' nếu khớp, ngược lại trả về ''
 */
function is_active($folder_name, $current_page_path) {
    // Kiểm tra trang dashboard (trường hợp đặc biệt)
    if ($folder_name == 'dashboard' && basename($current_page_path) == 'dashboard.php') {
        return 'active';
    }
    
    // Kiểm tra các thư mục con, ví dụ: "/views/room/"
    if (strpos($current_page_path, '/views/' . $folder_name . '/') !== false) {
        return 'active';
    }
    
    return '';
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị Khách sạn</title>
    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin_style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <header class="main-header">
        <h1><span class="logo-icon">🏨</span> 
        <span class="logo-text">Quản lý Khách sạn</span></h1>
        
        <nav class="main-nav">
            <ul>
                <li class="<?php echo is_active('dashboard', $current_page_path); ?>">
                    <a href="<?php echo BASE_URL; ?>/views/dashboard.php">📊 Tổng quan</a>
                </li>
                <li class="<?php echo is_active('booking', $current_page_path); ?>">
                    <a href="<?php echo BASE_URL; ?>/views/booking/list.php">🛎️ Đặt phòng</a>
                </li>
                 <li class="<?php echo is_active('room', $current_page_path); ?>">
                    <a href="<?php echo BASE_URL; ?>/views/room/list.php">🛌 Quản lý Phòng</a>
                </li>
                <li class="<?php echo is_active('room_type', $current_page_path); ?>">
                    <a href="<?php echo BASE_URL; ?>/views/room_type/list.php">🔑 Quản lý Loại phòng</a>
                </li>
                 <li class="<?php echo is_active('customer', $current_page_path); ?>">
                    <a href="<?php echo BASE_URL; ?>/views/customer/list.php">👥 Khách hàng</a>
                </li>
                <li class="<?php echo is_active('user', $current_page_path); ?>">
                    <a href="<?php echo BASE_URL; ?>/views/user/list.php">👤 Người dùng</a>
                </li>
                <li class="nav-homepage">
                    <a href="<?php echo BASE_URL; ?>/index.php" target="_blank">🌐 Xem Trang Chủ</a>
                </li>
            </ul>
        </nav>
        
        <div class="user-info">
            Chào, <strong><?php echo htmlspecialchars($_SESSION['user_username']); ?>!</strong>
            
            <a href="<?php echo BASE_URL; ?>/logout.php" class="btn-logout">Đăng xuất</a>
        </div>
    </header>