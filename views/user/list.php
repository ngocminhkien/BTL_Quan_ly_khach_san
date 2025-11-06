<?php
require_once '../../functions/admin_check.php';
require_once '../../functions/db_connection.php';
require_once '../partials/header.php';
// *** GỌI FILE FUNCTIONS MỚI ***
require_once '../../functions/user_functions.php';

// LẤY DỮ LIỆU USERS BẰNG HÀM
$result = getAllUsers($conn);
?>

<div class="page-title">
    <h2>Quản lý Người dùng</h2>
    <a href="add.php" class="btn-add-new">Thêm người dùng</a>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Họ và Tên</th>
            <th>Tên đăng nhập (username)</th>
            <th>Quyền (Role)</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Code lặp while giữ nguyên
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // ... (HTML <tr> echo dữ liệu giữ nguyên) ...
            }
        } else {
            echo "<tr><td colspan='4'>Chưa có người dùng nào.</td></tr>";
        }
        $conn->close();
        ?>
    </tbody>
</table>

<?php
require_once '../partials/footer.php';
?>