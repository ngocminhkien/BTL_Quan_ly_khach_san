<?php
// Yêu cầu Gác cổng, Kết nối, Header
require_once '../../functions/admin_check.php';
require_once '../../functions/db_connection.php';
require_once '../partials/header.php';

// Gọi file Model (logic CSDL) của Booking
require_once '../../functions/booking_functions.php';

// Lấy tất cả dữ liệu đặt phòng BẰNG HÀM
$result = getAllBookings($conn);
?>

<div class="page-title">
    <h2>Quản lý Đặt phòng</h2>
    <a href="add.php" class="btn-add-new">Tạo Đơn đặt phòng</a>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="login-success" style="margin-bottom: 20px;">
        <?php
        if ($_GET['success'] == 'added') echo "Tạo đặt phòng mới thành công!";
        if ($_GET['success'] == 'updated') echo "Cập nhật đặt phòng thành công!";
        if ($_GET['success'] == 'deleted') echo "Xóa đặt phòng thành công!";
        if ($_GET['success'] == 'checked_in') echo "Check-in khách thành công!";
        if ($_GET['success'] == 'checked_out') echo "Check-out khách thành công!";
        if ($_GET['success'] == 'cancelled') echo "Hủy đặt phòng thành công!";
        ?>
    </div>
<?php endif; ?>
<?php if (isset($_GET['error'])): ?>
    <div class="login-error" style="margin-bottom: 20px;">
        <?php
        if ($_GET['error'] == 'no_rooms') echo "Lỗi: Không còn phòng trống cho loại phòng và ngày đã chọn!";
        ?>
    </div>
<?php endif; ?>

<table class="data-table">
    <thead>
        <tr>
            <th>Khách hàng</th>
            <th>Phòng (Loại)</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Thêm class CSS dựa trên trạng thái
                $status_class = 'status-' . htmlspecialchars($row['status']);
                echo "<tr class='{$status_class}'>";
                echo "<td>" . htmlspecialchars($row['customer_name'] ?? 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row['room_number'] ?? 'N/A') . " (" . htmlspecialchars($row['room_type'] ?? 'N/A') . ")</td>";
                echo "<td>" . date('d-m-Y', strtotime($row['check_in_date'])) . "</td>";
                echo "<td>" . date('d-m-Y', strtotime($row['check_out_date'])) . "</td>";
                echo "<td>" . number_format($row['total_price']) . " VND</td>";
                
                // Hiển thị text trạng thái
                $status_text = '';
                switch ($row['status']) {
                    case 'confirmed': $status_text = 'Đã xác nhận'; break;
                    case 'checked_in': $status_text = 'Đã check-in'; break;
                    case 'checked_out': $status_text = 'Đã check-out'; break;
                    case 'cancelled': $status_text = 'Đã hủy'; break;
                    default: $status_text = htmlspecialchars($row['status']);
                }
                echo "<td><strong>" . $status_text . "</strong></td>";
                
                // Hiển thị các nút hành động
                echo "<td>";
                
                // Chỉ hiển thị Check-in nếu status là 'confirmed'
                if ($row['status'] == 'confirmed') {
                    echo "<a href='../../handle/booking_process.php?action=check_in&id=" . $row['id'] . "' 
                             class='btn-action btn-checkin'
                             onclick=\"return confirm('Xác nhận CHECK-IN cho khách " . htmlspecialchars($row['customer_name']) . "?');\">Check-in</a> ";
                }
                
                // Chỉ hiển thị Check-out nếu status là 'checked_in'
                if ($row['status'] == 'checked_in') {
                    echo "<a href='../../handle/booking_process.php?action=check_out&id=" . $row['id'] . "' 
                             class='btn-action btn-checkout'
                             onclick=\"return confirm('Xác nhận CHECK-OUT cho khách " . htmlspecialchars($row['customer_name']) . "?');\">Check-out</a> ";
                }
                
                // Cho phép sửa nếu chưa check-out hoặc chưa hủy
                if ($row['status'] != 'checked_out' && $row['status'] != 'cancelled') {
                    echo "<a href='edit.php?id=" . $row['id'] . "' class='btn-edit'>Sửa</a> ";
                }
                
                // Cho phép hủy nếu chưa check-out
                if ($row['status'] != 'checked_out' && $row['status'] != 'cancelled') {
                    echo "<a href='../../handle/booking_process.php?action=cancel&id=" . $row['id'] . "' 
                             class='btn-delete'
                             onclick=\"return confirm('Bạn có chắc muốn HỦY đơn đặt phòng này?');\">Hủy</a>";
                }

                echo "</td>";
                echo "</tr>";
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