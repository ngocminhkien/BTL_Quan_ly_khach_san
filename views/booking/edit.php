<?php
require_once '../../functions/admin_check.php';
require_once '../../functions/db_connection.php';
require_once '../partials/header.php';

// *** GỌI CÁC FILE FUNCTIONS LIÊN QUAN ***
require_once '../../functions/booking_functions.php';
require_once '../../functions/customer_functions.php';
require_once '../../functions/room_type_functions.php';

// 1. Lấy ID đơn đặt phòng
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID đặt phòng không hợp lệ.");
}
$booking_id = $_GET['id'];

// 2. Lấy thông tin cũ của đơn đặt phòng BẰNG HÀM
$booking = getBookingById($conn, $booking_id);
if ($booking === null) {
    die("Không tìm thấy đơn đặt phòng.");
}

// 3. Lấy TẤT CẢ khách hàng và loại phòng BẰNG HÀM (cho dropdown)
$result_customers = getAllCustomers($conn);
$result_room_types = getAllRoomTypes($conn);

?>

<div class="page-title">
    <h2>Sửa Đơn đặt phòng (ID: <?php echo $booking_id; ?>)</h2>
</div>

<?php if (isset($_GET['error']) && $_GET['error'] == 'no_rooms'): ?>
    <div class="login-error" style="padding: 15px; margin-bottom: 20px;">
        <strong>Lỗi:</strong> Loại phòng này đã hết phòng trống trong khoảng thời gian bạn chọn.
    </div>
<?php endif; ?>

<form class="form-layout" action="../../handle/booking_process.php" method="POST">
    
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">

    <div class="form-group">
        <label for="customer_id">Khách hàng:</label>
        <select id="customer_id" name="customer_id" required>
            <option value="">-- Chọn khách hàng --</option>
            <?php
            if ($result_customers && $result_customers->num_rows > 0) {
                while ($row = $result_customers->fetch_assoc()) {
                    // Thêm 'selected' nếu ID khớp
                    $selected = ($row['id'] == $booking['customer_id']) ? 'selected' : '';
                    echo "<option value='" . $row['id'] . "' $selected>" . htmlspecialchars($row['full_name']) . "</option>";
                }
            }
            ?>
        </select>
    </div>
    
    <div class="form-group">
        <label for="room_type_id">Loại phòng:</label>
        <select id="room_type_id" name="room_type_id" required> 
            <option value="">-- Chọn loại phòng --</option>
            <?php
            if ($result_room_types && $result_room_types->num_rows > 0) {
                while ($row = $result_room_types->fetch_assoc()) {
                    // Thêm 'selected' nếu ID khớp
                    $selected = ($row['id'] == $booking['room_type_id']) ? 'selected' : '';
                    echo "<option value='" . $row['id'] . "' $selected>" . htmlspecialchars($row['type_name']) . "</option>";
                }
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="check_in_date">Ngày Check-in:</label>
        <input type="date" id="check_in_date" name="check_in_date" 
               value="<?php echo date('Y-m-d', strtotime($booking['check_in_date'])); ?>" required>
    </div>

    <div class="form-group">
        <label for="check_out_date">Ngày Check-out:</label>
        <input type="date" id="check_out_date" name="check_out_date" 
               value="<?php echo date('Y-m-d', strtotime($booking['check_out_date'])); ?>" required>
    </div>

    <div class="form-group">
        <button type="submit" class="btn-submit">Lưu thay đổi</button>
        <a href="list.php" class="btn-cancel">Hủy</a>
    </div>

</form>

<?php
$conn->close();
require_once '../partials/footer.php';
?>