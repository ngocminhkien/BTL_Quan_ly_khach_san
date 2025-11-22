<?php
require_once '../../functions/admin_check.php';
require_once '../../functions/db_connection.php';
// *** GỌI FILE FUNCTIONS MỚI ***
require_once '../../functions/user_functions.php';

// Lấy ID từ URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID không hợp lệ.");
}
$user_id = $_GET['id'];

// Lấy thông tin cũ của user BẰNG HÀM
$user = getUserById($conn, $user_id);
if ($user === null) {
    die("Người dùng không tồn tại.");
}
$conn->close();

require_once '../partials/header.php';
?>

<div class="page-title">
    <h2>Sửa thông tin: <?php echo htmlspecialchars($user['full_name']); ?></h2>
</div>

<form class="form-layout" action="../../handle/user_process.php" method="POST">
    
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

    <div class="form-group">
        <label for="full_name">Họ và Tên:</label>
        <input type="text" id="full_name" name="full_name" 
               value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="username">Tên đăng nhập (username):</label>
        <input type="text" id="username" name="username" 
               value="<?php echo htmlspecialchars($user['username']); ?>" required>
    </div>

    <div class="form-group">
        <label for="password">Mật khẩu mới (Bỏ trống nếu không muốn đổi):</label>
        <input type="password" id="password" name="password">
    </div>

    <div class="form-group">
        <label for="role">Quyền (Role):</label>
        <select id="role" name="role" required>
            <?php
            // Kiểm tra xem có phải đang sửa chính mình không
            $is_self = ($user['id'] == $_SESSION['user_id']);
            ?>
            <option value="2" <?php if ($user['role'] == 2) echo 'selected'; ?> 
                                <?php if ($is_self) echo 'disabled'; ?>> Người dùng
            </option>
            <option value="1" <?php if ($user['role'] == 1) echo 'selected'; ?>>
                Admin (Quản trị viên)
            </option>
        </select>
        <?php if ($is_self && $user['role'] == 1): // Chỉ hiển thị nếu là Admin tự sửa mình ?>
            <small style="color:red;">Bạn không thể tự hạ quyền của chính mình.</small>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <button type="submit" class="btn-submit">Lưu thay đổi</button>
        <a href="list.php" class="btn-cancel">Hủy</a>
    </div>

</form>

<?php
require_once '../partials/footer.php';
?>