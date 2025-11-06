<?php
// Luôn bắt đầu session ở file xử lý
session_start();
require_once '../functions/db_connection.php'; // Gọi kết nối CSDL

// Xác định hành động (login hoặc register)
$action = $_POST['action'] ?? 'login';

switch ($action) {
    
    // -------------------------
    // TRƯỜNG HỢP: ĐĂNG NHẬP
    // -------------------------
    case 'login':
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            header("Location: ../views/login.php?error=empty");
            exit;
        }

        // Lấy thông tin user VÀ QUYỀN (role)
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Xác thực mật khẩu
            if (password_verify($password, $user['password'])) {
                // Đăng nhập thành công, lưu session
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_username'] = $user['username'];
                $_SESSION['user_role'] = $user['role']; // Rất quan trọng

                // *** PHÂN LUỒNG QUAN TRỌNG ***
                if ($user['role'] == 1) {
                    // Nếu là Admin (role=1), chuyển đến trang Dashboard
                    header("Location: ../views/dashboard.php");
                } else {
                    // Nếu là người dùng (role=2), chuyển về trang chủ
                    header("Location: ../index.html"); // Hoặc trang cá nhân của người dùng
                }
                exit;

            } else {
                // Sai mật khẩu
                header("Location: ../views/login.php?error=1");
                exit;
            }
        } else {
            // Không tìm thấy user
            header("Location: ../views/login.php?error=1");
            exit;
        }
        $stmt->close();
        break;

    // -------------------------
    // TRƯỜNG HỢP: ĐĂNG KÝ
    // -------------------------
    case 'register':
        $full_name = $_POST['full_name'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // (Bạn nên thêm kiểm tra xem username/email đã tồn tại chưa)
        
        if (empty($full_name) || empty($username) || empty($password)) {
             header("Location: ../views/register.php?error=empty");
             exit;
        }

        // Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // *** MẶC ĐỊNH ROLE = 2 (Người dùng) ***
        $default_role = 2; 

        $stmt = $conn->prepare("INSERT INTO users (full_name, username, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $full_name, $username, $hashed_password, $default_role);
        
        if ($stmt->execute()) {
            // Đăng ký thành công, chuyển về trang login
            header("Location: ../views/login.php?register=success");
        } else {
            // Lỗi (ví dụ: trùng username)
            header("Location: ../views/register.php?error=exists");
        }
        $stmt->close();
        break;
}

$conn->close();
?>