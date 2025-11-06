<?php
// Gác cổng Admin (Bắt buộc)
require_once '../../functions/admin_check.php';
require_once '../../functions/db_connection.php';
require_once '../partials/header.php';
// *** GỌI FILE FUNCTIONS MỚI ***
require_once '../../functions/room_type_functions.php';

// Lấy dữ liệu loại phòng BẰNG HÀM
$result = getAllRoomTypes($conn); 
?>

<div class="page-title">
    <h2>Quản lý Loại phòng</h2>
    <a href="add.php" class="btn-add-new">Thêm Loại phòng</a>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="login-success" style="margin-bottom: 20px;">
        <?php
        if ($_GET['success'] == 'added') echo "Thêm loại phòng mới thành công!";
        if ($_GET['success'] == 'updated') echo "Cập nhật loại phòng thành công!";
        if ($_GET['success'] == 'deleted') echo "Xóa loại phòng thành công!";
        ?>
    </div>
<?php endif; ?>

<table class="data-table">
    <thead>
        <tr>
            <th>Tên loại phòng</th>
            <th>Giá (VND/đêm)</th>
            <th>Mô tả</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['type_name']) . "</td>";
                echo "<td>" . number_format($row['price_per_night']) . "</td>";
                echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                echo "<td>";
                // Link Sửa (trỏ đến edit.php)
                echo "<a href='edit.php?id=" . $row['id'] . "' class='btn-edit'>Sửa</a> ";
                
                // Link Xóa (trỏ đến file process gộp)
                echo "<a href='../../handle/room_type_process.php?action=delete&id=" . $row['id'] . "' 
                           class='btn-delete' 
                           onclick=\"return confirm('Bạn có chắc muốn xóa loại phòng này?');\">Xóa</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Chưa có loại phòng nào.</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php
$conn->close();
require_once '../partials/footer.php';
?>