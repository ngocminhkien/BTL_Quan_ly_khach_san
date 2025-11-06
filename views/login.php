<?php 
session_start();
// Nếu đã đăng nhập, đá về dashboard
if (isset($_SESSION['user_logged_in'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Hệ thống</title>
    <link rel="stylesheet" href="../assets/css/Admin_style.css"> 
</head>
<body style="background-color: #f4f7f6;"> <div class="login-container">
        <h2>Đăng nhập</h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="login-error">Tên đăng nhập hoặc mật khẩu không đúng!</div>
        <?php endif; ?>
        <?php if (isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>
            <div class="login-success">Bạn đã đăng xuất thành công.</div>
        <?php endif; ?>
         <?php if (isset($_GET['not_logged_in'])): ?>
            <div class="login-error">Bạn phải đăng nhập để tiếp tục.</div>
        <?php endif; ?>
        <?php if (isset($_GET['register']) && $_GET['register'] == 'success'): ?>
            <div class="login-success">Đăng ký thành công! Vui lòng đăng nhập.</div>
        <?php endif; ?>

        <form action="../handle/login_process.php" method="POST">
            <input type="hidden" name="action" value="login">
            
            <div class="form-group">
                <label for="username">Tên đăng nhập:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-submit">Đăng nhập</button>
        </form>
        
        <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
    </div>

</body>
</html>