<?php
// Bắt đầu session nếu nó chưa được bắt đầu (để lấy tên người dùng)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Lấy đường dẫn của script hiện tại, ví dụ: "/BTL/views/room/list.php"
$current_page_path = $_SERVER['PHP_SELF'];

// Hàm tiện ích để kiểm tra xem menu có 'active' không
// Nó sẽ kiểm tra xem đường dẫn $current_page_path có chứa $folder_name hay không
function is_active($folder_name, $current_page_path) {
    // Ví dụ: kiểm tra xem "/BTL/views/room/list.php" có chứa "/room/" không
    if (strpos($current_page_path, '/views/' . $folder_name . '/') !== false) {
        return 'active';
    }
    // Kiểm tra riêng cho dashboard
    if ($folder_name == 'dashboard' && basename($current_page_path) == 'dashboard.php') {
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
    <link rel="stylesheet" href="/BTL/assets/css/Admin_style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <header class="main-header">
        <h1>Quản lý Khách sạn</h1>
        
        <nav class="main-nav">
            <ul>
                <li class="<?php echo is_active('dashboard', $current_page_path); ?>">
                    <a href="/BTL/views/dashboard.php">Tổng quan</a>
                </li>
                <li class="<?php echo is_active('room', $current_page_path); ?>">
                    <a href="/BTL/views/room/list.php">Quản lý Phòng</a>
                </li>
                <li class="<?php echo is_active('room_type', $current_page_path); ?>">
                    <a href="/BTL/views/room_type/list.php">Quản lý Loại phòng</a>
                </li>
                <li class="<?php echo is_active('booking', $current_page_path); ?>">
                    <a href="/BTL/views/booking/list.php">Quản lý Đặt phòng</a>
                </li>
                 <li class="<?php echo is_active('customer', $current_page_path); ?>">
                    <a href="/BTL/views/customer/list.php">Quản lý Khách hàng</a>
                </li>
                <li class="<?php echo is_active('user', $current_page_path); ?>">
                    <a href="/BTL/views/user/list.php">Quản lý Người dùng</a>
                </li>
            </ul>
        </nav>
        
        <div class="user-info">
            Chào, <strong><?php echo htmlspecialchars($_SESSION['user_username']); ?>!</strong>
            <a href="/BTL/logout.php">Đăng xuất</a>
        </div>
    </header>

    <div class="main-content">
    
    </div> <footer class="main-footer">
        <p>© 2025 - Dự án BTL Quản lý Khách sạn</p>
    </footer>

</body>
</html>