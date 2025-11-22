<?php
session_start();
require_once '../functions/db_connection.php';

$action = $_POST['action'] ?? 'login';

switch ($action) {
    
    // -------------------------
    // TRƯỜNG HỢP: ĐĂNG NHẬP
    // -------------------------
    case 'login':
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? ''); 

        if (empty($username) || empty($password)) {
            header("Location: /BTL/views/login.php?error=empty");
            exit;
        }

        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Bây giờ $password (đã trim) sẽ khớp với $user['password']
            if (password_verify($password, $user['password'])) {
                // Đăng nhập thành công
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_username'] = $user['username'];
                $_SESSION['user_role'] = $user['role']; 

                if ($user['role'] == 1) {
                    header("Location: /BTL/views/dashboard.php"); // Admin
                } else {
                    header("Location: /BTL/"); // Người dùng
                }
                exit;

            } else {
                header("Location: /BTL/views/login.php?error=1"); // Sai mật khẩu
                exit;
            }
        } else {
            header("Location: /BTL/views/login.php?error=1"); // Không tìm thấy user
            exit;
        }
        $stmt->close();
        break;

    // -------------------------
    // TRƯỜNG HỢP: ĐĂNG KÝ (ĐÃ CẬP NHẬT)
    // -------------------------
    case 'register':
        $full_name = trim($_POST['full_name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        
        if (empty($full_name) || empty($username) || empty($password)) {
             header("Location: /BTL/views/register.php?error=empty");
             exit;
        }
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $default_role = 2; // Mặc định là Người dùng

        // Bắt đầu Transaction
        $conn->begin_transaction();
        
        try {
            // 1. Tạo tài khoản trong bảng 'users'
            // *** SỬA LỖI THỨ TỰ CỘT Ở ĐÂY ***
            $stmt_user = $conn->prepare("INSERT INTO users (username, password, full_name, role) VALUES (?, ?, ?, ?)");
            $stmt_user->bind_param("sssi", $username, $hashed_password, $full_name, $default_role);
            $stmt_user->execute();
            
            // Lấy ID của user vừa tạo
            $new_user_id = $conn->insert_id;
            
            // 2. TẠO HỒ SƠ KHÁCH HÀNG TƯƠNG ỨNG
            $stmt_customer = $conn->prepare("INSERT INTO customers (id, full_name, email, phone) VALUES (?, ?, ?, ?)");
            $email_default = $username . '@example.com'; 
            $phone_default = '0000000000'; 
            $stmt_customer->bind_param("isss", $new_user_id, $full_name, $email_default, $phone_default);
            $stmt_customer->execute();

            // Nếu cả 2 đều thành công
            $conn->commit();
            header("Location: /BTL/views/login.php?register=success");
            
        } catch (mysqli_sql_exception $exception) {
            // Nếu có lỗi (ví dụ: trùng username), hủy bỏ
            $conn->rollback();
            header("Location: /BTL/views/register.php?error=exists");
        }
        
        $stmt_user->close();
        $stmt_customer->close();
        break;
}

$conn->close();
?>