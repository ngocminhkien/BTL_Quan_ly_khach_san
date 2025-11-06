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
        <button type="submit" class="btn-submit">Lưu thay đổi</button>
        <a href="list.php" class="btn-cancel">Hủy</a>
    </div>

</form>

<?php
require_once '../partials/footer.php';
?>