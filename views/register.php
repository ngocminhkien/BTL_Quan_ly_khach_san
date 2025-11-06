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
    <title>Đăng ký tài khoản</title>
    <link rel="stylesheet" href="../assets/css/Admin_style.css">
</head>
<body style="background-color: #f4f7f6;">

    <div class="login-container">
        <h2>Đăng ký tài khoản</h2>
        
        <?php if (isset($_GET['error']) && $_GET['error'] == 'exists'): ?>
            <div class="login-error">Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.</div>
        <?php endif; ?>
         <?php if (isset($_GET['error']) && $_GET['error'] == 'empty'): ?>
            <div class="login-error">Vui lòng điền đầy đủ thông tin.</div>
        <?php endif; ?>

        <form action="../handle/login_process.php" method="POST">
            <input type="hidden" name="action" value="register">
            
            <div class="form-group">
                <label for="full_name">Họ và Tên:</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            <div class="form-group">
                <label for="username">Tên đăng nhập:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-submit">Đăng ký</button>
        </form>

        <p>Đã có tài khoản? <a href="login.php">Quay lại Đăng nhập</a></p>
    </div>

</body>
</html>