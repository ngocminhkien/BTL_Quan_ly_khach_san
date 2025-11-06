<?php
require_once '../../functions/admin_check.php';
require_once '../../functions/db_connection.php';
require_once '../partials/header.php';
// *** GỌI FILE FUNCTIONS MỚI ***
require_once '../../functions/room_functions.php';

// Lấy dữ liệu phòng BẰNG HÀM
$result = getAllRooms($conn);
?>

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
        // Code lặp while giữ nguyên
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // ... (Phần HTML <tr> echo dữ liệu giữ nguyên) ...
            }
        } else {
            echo "<tr><td colspan='5'>Chưa có phòng nào.</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php
$conn->close();
require_once '../partials/footer.php';
?>