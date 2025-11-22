<?php
require_once '../../functions/auth_check.php';
require_once '../../functions/db_connection.php';
require_once '../../functions/booking_functions.php'; 
require_once '../partials/header.php'; 

// Lấy ID booking từ URL
if (!isset($_GET['booking_id'])) {
    die("Thiếu mã đặt phòng.");
}
$booking_id = $_GET['booking_id'];
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// Lấy thông tin đơn hàng
$stmt_booking = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
$stmt_booking->bind_param("i", $booking_id);
$stmt_booking->execute();
$booking = $stmt_booking->get_result()->fetch_assoc();

// Bảo mật: Đảm bảo đúng người đang xem (hoặc là Admin)
if (!$booking || ($booking['customer_id'] != $user_id && $user_role != 1)) {
    die("Bạn không có quyền xem đơn đặt phòng này.");
}
?>

<div class="page-title">
    <h2>Xác nhận Thanh toán</h2>
</div>

<div class="form-layout">
    <h3>Chi tiết Đơn hàng (Mã: #<?php echo $booking_id; ?>)</h3>
    <table class="data-table">
        <tr>
            <th>Ngày Check-in:</th>
            <td><?php echo date('d-m-Y', strtotime($booking['check_in_date'])); ?></td>
        </tr>
        <tr>
            <th>Ngày Check-out:</th>
            <td><?php echo date('d-m-Y', strtotime($booking['check_out_date'])); ?></td>
        </tr>
        <tr>
            <th>Trạng thái:</th>
            <td><?php echo htmlspecialchars($booking['status']); ?></td>
        </tr>
        <tr style="font-size: 1.2em; background-color: #f4f7f6;">
            <th>Tổng cộng:</th>
            <td><strong><?php echo number_format($booking['total_price']); ?> VND</strong></td>
        </tr>
    </table>

    <h4 style="margin-top: 30px;">Chọn phương thức thanh toán:</h4>

    <form action="../../handle/payment_process.php" method="POST" style="display:inline-block; margin-right: 15px;">
        <input type="hidden" name="action" value="cod">
        <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
        <button type="submit" class="btn-submit">
            Thanh toán khi nhận phòng
        </button>
    </form>
    
    <form action="../../handle/payment_process.php" method="POST" style="display:inline-block;">
        <input type="hidden" name="action" value="paid_online">
        <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
        <button type="submit" class="btn-submit" style="background-color: #27ae60;">
            Thanh toán ngay
        </button>
    </form>
</div>

<?php
$conn->close();
require_once '../partials/footer.php';
?>