<?php
require_once '../../functions/admin_check.php';
require_once '../../functions/db_connection.php';
require_once '../partials/header.php';
// *** GỌI FILE FUNCTIONS MỚI ***
require_once '../../functions/customer_functions.php';

// Lấy dữ liệu BẰNG HÀM
$result = getAllCustomers($conn);
?>

<div class="page-title">
    <h2>Quản lý Khách hàng</h2>
    <a href="add.php" class="btn-add-new">Thêm Khách hàng</a>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Họ và Tên</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                echo "<td>";
                echo "<a href='edit.php?id=" . $row['id'] . "' class='btn-edit'>Sửa</a> ";
                echo "<a href='../../handle/customer_process.php?action=delete&id=" . $row['id'] . "' 
                           class='btn-delete' 
                           onclick=\"return confirm('Bạn có chắc muốn xóa khách hàng này?');\">Xóa</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Chưa có khách hàng nào.</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php
$conn->close();
require_once '../partials/footer.php';
?>