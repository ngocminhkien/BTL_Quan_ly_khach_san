<?php
require_once '../../functions/auth_check.php';
require_once '../../functions/db_connection.php';
require_once '../../functions/booking_functions.php'; // Cần để lấy thông tin
require_once '../partials/header.php'; // Dùng header của admin

// Lấy ID booking từ URL
if (!isset($_GET['id'])) {
    die("Thiếu mã đặt phòng.");
}
$booking_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// ==========================================================
// *** SỬA LỖI Ở ĐÂY: Thêm "b.customer_id" vào câu SELECT ***
// ==========================================================
$sql = "SELECT 
            b.id, b.customer_id, b.check_in_date, b.check_out_date, b.total_price, b.status, b.payment_method,
            c.full_name AS customer_name, c.email AS customer_email, c.phone AS customer_phone,
            r.room_number,
            rt.type_name AS room_type
        FROM bookings AS b
        LEFT JOIN customers AS c ON b.customer_id = c.id
        LEFT JOIN rooms AS r ON b.room_id = r.id
        LEFT JOIN room_types AS rt ON r.room_type_id = rt.id
        WHERE b.id = ?";
        
$stmt_invoice = $conn->prepare($sql);
$stmt_invoice->bind_param("i", $booking_id);
$stmt_invoice->execute();
$invoice = $stmt_invoice->get_result()->fetch_assoc();

// Bảo mật (Dòng 32)
// Bây giờ $invoice['customer_id'] đã tồn tại và code này sẽ chạy đúng
if (!$invoice || ($invoice['customer_id'] != $user_id && $user_role != 1)) {
    die("Bạn không có quyền xem hóa đơn này.");
}

// Lấy mã phòng (ID phòng cụ thể)
$room_id_assigned = $invoice['room_number'];
?>

<div class="page-title">
    <h2>Hóa đơn Đặt phòng</h2>
</div>

<div class="invoice-box">
    <table class="invoice-table">
        <tr>
            <td colspan="2">
                <h1 style="margin: 0;">Hotel Mellow</h1>
                (Cảm ơn bạn đã đặt phòng)
            </td>
            <td class="right">
                <strong>Hóa đơn #: <?php echo $invoice['id']; ?></strong><br>
                Ngày tạo: <?php echo date('d-m-Y'); ?><br>
                Trạng thái: 
                <span style="color: green; font-weight: bold;">
                    <?php echo ($invoice['status'] == 'confirmed') ? 'ĐÃ XÁC NHẬN' : htmlspecialchars($invoice['status']); ?>
                </span>
            </td>
        </tr>
        
        <tr class="information">
            <td colspan="3"><hr></td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Khách hàng:</strong><br>
                <?php echo htmlspecialchars($invoice['customer_name']); ?><br>
                <?php echo htmlspecialchars($invoice['customer_email']); ?><br>
                <?php echo htmlspecialchars($invoice['customer_phone']); ?>
            </td>
            <td class="right">
                <strong>Phương thức:</strong><br>
                <?php echo htmlspecialchars($invoice['payment_method']); ?>
            </td>
        </tr>
        
        <tr class="heading">
            <td colspan="3"><hr><strong>Chi tiết Dịch vụ</strong><hr></td>
        </tr>
        <tr>
            <td><strong>Dịch vụ</strong></td>
            <td class="right"><strong>Ngày</strong></td>
            <td class="right"><strong>Mã Phòng</strong></td>
        </tr>
         <tr>
            <td>Thuê phòng (<?php echo htmlspecialchars($invoice['room_type']); ?>)</td>
            <td class="right">
                <?php echo date('d/m/Y', strtotime($invoice['check_in_date'])); ?> 
                - <?php echo date('d/m/Y', strtotime($invoice['check_out_date'])); ?>
            </td>
            <td class="right">
                <strong><?php echo htmlspecialchars($room_id_assigned); ?></strong>
            </td>
        </tr>
        
        <tr class="total">
            <td colspan="2" class="right"><strong>Tổng cộng:</strong></td>
            <td class="right">
               <strong><?php echo number_format($invoice['total_price']); ?> VND</strong>
            </td>
        </tr>
    </table>
    
    <div class="invoice-actions">
        <button onclick="window.print();" class="btn-submit">
            In Hóa đơn
        </button>
        <a href="/BTL/views/dashboard.php" class="btn-cancel" style="background-color: #007bff;">
            Quay về Trang chủ
        </a>
    </div>
</div>

<?php
$conn->close();
require_once '../partials/footer.php';
?>