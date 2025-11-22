<?php
require_once '../../functions/admin_check.php';
require_once '../../functions/db_connection.php';
require_once '../partials/header.php';
require_once '../../functions/room_functions.php';

// Lấy dữ liệu phòng BẰNG HÀM
$result = getAllRooms($conn);
?>

<div class="page-title">
    <h2>Quản lý Phòng</h2>
    <a href="add.php" class="btn-add-new">Thêm Phòng mới</a>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Số phòng</th>
            <th>Loại phòng</th>
            <th>Giá (VND/đêm)</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // SỬA LẠI LOGIC KIỂM TRA
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['room_number']) . "</td>";
                // Kiểm tra nếu type_name là NULL (do LEFT JOIN)
                echo "<td>" . htmlspecialchars($row['type_name'] ?? 'N/A') . "</td>";
                echo "<td>" . number_format($row['price_per_night'] ?? 0) . "</td>";
                
                // Hiển thị trạng thái
                $status_text = '';
                $status_class = '';
                switch ($row['status']) {
                    case 'available':
                        $status_text = 'Có sẵn';
                        $status_class = 'status-available';
                        break;
                    case 'occupied':
                        $status_text = 'Đang có khách';
                        $status_class = 'status-occupied';
                        break;
                    case 'cleaning':
                        $status_text = 'Đang dọn';
                        $status_class = 'status-cleaning';
                        break;
                    default:
                         $status_text = htmlspecialchars($row['status']);
                }
                echo "<td class='{$status_class}'>" . $status_text . "</td>";
                
                echo "<td>";
                echo "<a href='edit.php?id=" . $row['id'] . "' class='btn-edit'>Sửa</a> ";
                echo "<a href='../../handle/room_process.php?action=delete&id=" . $row['id'] . "' 
                           class='btn-delete' 
                           onclick=\"return confirm('Bạn có chắc muốn xóa Phòng " . htmlspecialchars($row['room_number']) . "?');\">Xóa</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            // Đảm bảo hiển thị thông báo này
            echo "<tr><td colspan='5'>Chưa có phòng nào.</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php
$conn->close();
require_once '../partials/footer.php';
?>