<?php
require_once '../../functions/admin_check.php';
require_once '../../functions/db_connection.php';
require_once '../partials/header.php';
// *** GỌI CÁC FILE FUNCTIONS LIÊN QUAN ***
require_once '../../functions/booking_functions.php';
require_once '../../functions/room_type_functions.php';
require_once '../../functions/customer_functions.php'; // (Giả sử bạn đã tạo file này)

// Lấy ID đơn đặt phòng
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID đặt phòng không hợp lệ.");
}
$booking_id = $_GET['id'];

// Lấy thông tin cũ BẰNG HÀM
$booking = getBookingById($conn, $booking_id);
if ($booking === null) {
    die("Không tìm thấy đơn đặt phòng.");
}

// Lấy TẤT CẢ khách hàng và loại phòng BẰNG HÀM
$result_customers = getAllCustomers($conn); // (Giả sử)
$result_room_types = getAllRoomTypes($conn);

?>

<form class="form-layout" action="../../handle/booking_process.php" method="POST">
    
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">

    </form>

<?php
$conn->close();
require_once '../partials/footer.php';
?>