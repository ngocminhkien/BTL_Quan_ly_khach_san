<?php 
session_start();
// Nếu đã đăng nhập, chuyển hướng đi
if (isset($_SESSION['user_logged_in'])) {
    // Dùng đường dẫn tuyệt đối
    $redirect_url = ($_SESSION['user_role'] == 1) ? '/BTL/views/dashboard.php' : '/BTL/index.html';
    header('Location: ' . $redirect_url);
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Mellow Hotel</title>
    
    <link rel="stylesheet" href="/BTL/assets/css/auth_style.css"> 
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Upright:wght@700&family=Open+Sans:wght@400;600&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="login-body"> <div class="login-split-container">
        
        <div class="login-split-image">
            <div class="login-image-content">
                <h1>Mellow</h1>
                <p>Cửa ngõ đến sự an yên. Đăng nhập để bắt đầu quản lý hệ thống của bạn.</p>
            </div>
        </div>

        <div class="login-split-form">
            <div class="login-form-box">
                
                <h2>Chào mừng trở lại!</h2>
                <p class="form-subtitle">Đăng nhập để tiếp tục.</p>

                <?php if (isset($_GET['error']) && $_GET['error'] == '1'): ?>
                    <div class="form-alert alert-danger">Tên đăng nhập hoặc mật khẩu không đúng!</div>
                <?php endif; ?>
                 <?php if (isset($_GET['error']) && $_GET['error'] == 'empty'): ?>
                    <div class="form-alert alert-danger">Vui lòng điền đầy đủ thông tin.</div>
                <?php endif; ?>
                <?php if (isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>
                    <div class="form-alert alert-success">Bạn đã đăng xuất thành công.</div>
                <?php endif; ?>
                <?php if (isset($_GET['error']) && $_GET['error'] == 'not_logged_in'): ?>
                    <div class="form-alert alert-danger">Bạn phải đăng nhập để tiếp tục.</div>
                <?php endif; ?>
                <?php if (isset($_GET['register']) && $_GET['register'] == 'success'): ?>
                    <div class="form-alert alert-success">Đăng ký thành công! Vui lòng đăng nhập.</div>
                <?php endif; ?>

                <form action="../handle/login_process.php" method="POST">
                    <input type="hidden" name="action" value="login">
                    
                    <div class="form-group">
                        <label for="username">Tên đăng nhập:</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn-submit btn-login">Đăng nhập</button>
                </form>
                
                <p class="form-switch-link">
                    Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>