<?php
require_once '../../functions/admin_check.php';
require_once '../../functions/db_connection.php';
require_once '../partials/header.php';
// *** GỌI FILE FUNCTIONS MỚI ***
require_once '../../functions/booking_functions.php';

// Lấy dữ liệu BẰNG HÀM
$result = getAllBookings($conn);
?>

<table class="data-table">
    <tbody>
        <?php
        // Code lặp while giữ nguyên
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // ... (HTML <tr> echo dữ liệu và các nút giữ nguyên) ...
            }
        } else {
            echo "<tr><td colspan='7'>Chưa có đơn đặt phòng nào.</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php
$conn->close();
require_once '../partials/footer.php';
?>